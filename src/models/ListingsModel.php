<?php 
defined("APP") or die("ACESSO NEGATO");

require_once("config/dbconnect.php");

class ListingsModel{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    /**
     * Recupero dal DB di tutti annunci
     * @param mixed $param
     * @return array
     */
    public function selectAll($param = []){
        $dql = "SELECT * FROM listings";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recupero di id del annucio di un ordine
     * @param mixed $id
     * @return int
     */
    public function getListingByOrderId($id){
        $sql = "SELECT L.id_listing from listings L
        join orders O using(id_listing)
        where O.id_order = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id]);

        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id_listing'] : null;
    }

    /**
     * Inserimento di un ordine
     * param  -> $idbook, idseller, price, book_condition, desciprtion, is_available
     * @return bool
     */
    public function insertRecord($param = []){
        $dml = "INSERT INTO listings(id_book, id_seller, price, book_condition, description, is_available)
        values(?, ?, ?, ?, ?, ?);";
        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    /**
     * Recupera l'ID dell'ultimo record inserito
     * @return int
     */
    public function getLastInsertedId() {
        return $this->pdo->lastInsertId();
    }


    /**
     * Funzione viene usata per mostrare la lista completa di annunci nella home page 
     * con tutti questi campi 
     *          L.*, B.title, B.author, B.isbn,
     *          L.price as priceOffer, U.name, U.surname, C.class as classe,
     *          PH.name as publisher
     * @param mixed $param
     * @return array
     */
    public function selectListings($param = []){
        $dql = "SELECT L.*, B.title, B.author, B.isbn,
                L.price as priceOffer, U.name, U.surname, C.class as classe,
                PH.name as publisher,
                (SELECT image_path FROM listing_images
                 WHERE id_listing = L.id_listing AND is_primary = 1
                 LIMIT 1) as main_image
                FROM listings L
                JOIN books B USING(id_book)
                JOIN users U ON L.id_seller = U.id_user
                LEFT JOIN class C ON B.id_class = C.id_class
                LEFT JOIN publishing_house PH ON B.id_publish_house = PH.id_publish_house";
        $stm = $this->pdo->prepare($dql);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ricerca del annuncio nella HomePage
     * @param mixed $query
     * @param mixed $filter
     * @return array
     */
    public function searchBooksListings($query, $filter) {
        try{
            //imposto il filtro
            $allowedFilters = ['title', 'author', 'isbn', 'class'];
            if (!in_array($filter, $allowedFilters)) {
                $filter = 'title';
            }

            $sql = "SELECT B.*, C.class AS class_name
                    FROM books B
                    LEFT JOIN class C ON B.id_class = C.id_class ";

            //in base al filtro si scelgi per cosa cercare
            if ($filter === 'class') {
                $sql .= "WHERE C.class LIKE :query ";
            } else {
                $sql .= "WHERE B.$filter LIKE :query ";
            }
            $sql .= "GROUP BY B.isbn, B.title, B.author
                     LIMIT 8";

            $stm = $this->pdo->prepare($sql);
            $stm->execute(['query' => '%' . $query . '%']);

            $results = $stm->fetchAll(PDO::FETCH_ASSOC);

            if ($results === false);
            return $results;

        } catch (PDOException) {
            $_SESSION['error'][] = "ERRORE DATABASE";
            header("location: index.php");
            exit;
        }
    }
    /**
     * Funzione di ricerca dei libri dal catalogo con informazioni complete
     * @param mixed $query
     * @param mixed $filter
     * @return array
     */
    public function searchBookOnly($query, $filter) {
        $allowedFilters = ['title', 'author', 'isbn', 'class'];
        if (!in_array($filter, $allowedFilters)) {
            $filter = 'title';
        }
        $sql = "SELECT L.id_listing, L.id_book, L.id_seller, L.price as priceOffer,
                L.book_condition, L.description, L.is_available, L.created_at,
                B.title, B.author, B.isbn, B.vol,
                U.name, U.surname, PH.name as publisher,
                GROUP_CONCAT(DISTINCT C.class ORDER BY C.class SEPARATOR ', ') as class_name,
                (SELECT image_path FROM listing_images
                 WHERE id_listing = L.id_listing AND is_primary = 1
                 LIMIT 1) as main_image
                FROM listings L
                JOIN books B USING(id_book)
                JOIN users U ON L.id_seller = U.id_user
                LEFT JOIN class C ON B.id_class = C.id_class
                LEFT JOIN publishing_house PH ON B.id_publish_house = PH.id_publish_house ";

        if ($filter === 'class') {
            $sql .= "WHERE C.class LIKE :query ";
        } else {
            $sql .= "WHERE B.$filter LIKE :query ";
        }
        $sql .= " AND L.is_available = 1
                GROUP BY L.id_listing, B.isbn, B.title, B.author, B.vol
                LIMIT 10";

        $stm = $this->pdo->prepare($sql);
        $stm->execute(['query' => '%' . $query . '%']);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteListing($param = []){
        $sql = "DELETE FROM listings where id_listing = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    /**
     * Recupero del id dell'annuncio di un ordine 
     * @param mixed $ids
     * @return array
     */
    public function getListingsById($ids = []){
        if(empty($ids)){
            return [];
        }

        // Crea placeholders per la query (?, ?, ?)
        // server per essere flessibile con il numero di id
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "SELECT *, B.title, L.price as priceOffer, L.id_listing
                FROM listings L
                JOIN books B USING(id_book)
                JOIN users U ON L.id_seller = U.id_user
                WHERE L.id_listing IN ($placeholders)";

        $stm = $this->pdo->prepare($sql);
        $stm->execute($ids);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAvailability($id_listing, $is_available){
        $sql = "UPDATE listings SET is_available = ? WHERE id_listing = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$is_available, $id_listing]);
        return $stm->rowCount() !== 0;
    }
}

?>