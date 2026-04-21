<?php 
defined("APP") or die("ACESSO NEGATO");

require_once("config/dbconnect.php");

class ListingsModel{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    public function insertRecord($param = []){
        $dml = "INSERT INTO listings(id_book, id_seller, price, book_condition, description, is_available)
        values(?, ?, ?, ?, ?, ?);";
        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function selectListings($param = []){
        $dql = "SELECT *, B.title, L.price as priceOffer from listings L
        join books B using(id_book)
        join users U on L.id_seller = U.id_user";
        $stm = $this->pdo->prepare($dql);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchOnlyBooks($query, $filter) {
    try {
        $allowedFilters = ['title', 'author', 'isbn', 'class'];
        if (!in_array($filter, $allowedFilters)) {
            $filter = 'title';
        }
        
        // Cerchiamo SOLO nei libri
        $sql = "SELECT B.*, C.class AS class_name 
                FROM books B
                LEFT JOIN class C ON B.id_class = C.id_class ";

        if ($filter === 'class') {          
            $sql .= "WHERE C.class LIKE :query "; 
        } else {
            $sql .= "WHERE B.$filter LIKE :query ";
        }
        $sql .= " LIMIT 8"; // Spazio iniziale fondamentale

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['query' => '%' . $query . '%']);

        $risultati = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($risultati === false) return [];
        return $risultati;

    } catch (PDOException $e) {
        // Se c'è un errore, lo spariamo nel JSON così lo vedi subito a video!
        return [['title' => 'ERRORE DATABASE: ' . $e->getMessage()]];
    }
}

    public function selectAll($param = []){
        $dql = "SELECT * FROM listings";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchBook($query, $filter) {
        $allowedFilters = ['title', 'author', 'isbn', 'class'];
        if (!in_array($filter, $allowedFilters)) {
            $filter = 'title';
        }
        
        // 1. Definisco tutte le tabelle (Aggiunta la JOIN per le classi)
        // 2. Nota lo SPAZIO VUOTO alla fine della stringa prima delle virgolette!
        $sql = "SELECT *, B.title, L.price as priceOffer FROM listings L
                JOIN books B USING(id_book)
                JOIN users U ON L.id_seller = U.id_user
                LEFT JOIN class C ON B.id_class = C.id_class "; // <--- SPAZIO FONDAMENTALE

        if ($filter === 'class') {          
            // Uso C.name (la colonna corretta della tabella class)
            $sql .= "WHERE C.class LIKE :query "; 
        } else {
            // Uso la B maiuscola come definita nella query sopra
            $sql .= "WHERE B.$filter LIKE :query ";
        }
        
        // Anche qui uso le lettere maiuscole e C.name
        $sql .= "GROUP BY B.isbn, B.title, B.author, C.class
                LIMIT 10";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['query' => '%' . $query . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteListing($param = []){
        $sql = "DELETE FROM listings where id_listing = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute($param);

        return $stm->rowCount() !== 0; 
    }
}

?>