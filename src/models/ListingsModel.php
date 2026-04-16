<?php 
defined("APP") or die("ACESSO NEGATO");

require_once 'config/dbconnect.php';

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

    public function selectAll($param = []){
        $dql = "SELECT * FROM listings";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAllBooks($param = []){
        $dql = "SELECT * from books";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchBooks($query, $filter) {
        // 1. Aggiungiamo 'class' ai filtri permessi
        $allowedFilters = ['title', 'author', 'isbn', 'class'];
        if (!in_array($filter, $allowedFilters)) {
            $filter = 'title';
        }

        // 2. Prepariamo la query base con la JOIN (uniamo libri e classi)
        // Selezioniamo anche il nome della classe per poterlo stampare!
        $sql = "SELECT MIN(b.id_book) AS id_book, b.title, b.author, b.isbn, c.class AS class_name 
                FROM books b
                LEFT JOIN class c ON b.id_class = c.id_class ";

        // 3. Applichiamo il filtro corretto
        if ($filter === 'class') {
            // Se cerco per classe, cerco nella tabella class (c)
            $sql .= "WHERE c.class LIKE :query "; 
        } else {
            // Altrimenti cerco nella tabella books (b)
            $sql .= "WHERE b.$filter LIKE :query ";
        }

        // 4. Raggruppiamo per evitare i doppioni
        $sql .= "GROUP BY b.isbn, b.title, b.author, c.class
                LIMIT 10";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['query' => '%' . $query . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>