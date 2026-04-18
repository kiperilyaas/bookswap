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
        $allowedFilters = ['title', 'author', 'isbn', 'class'];
        if (!in_array($filter, $allowedFilters)) {
            $filter = 'title';
        }
        $sql = "SELECT MIN(b.id_book) AS id_book, b.title, b.author, b.isbn, c.class AS class_name, b.price
                FROM books b
                LEFT JOIN class c ON b.id_class = c.id_class ";

        if ($filter === 'class') {           
            $sql .= "WHERE c.class LIKE :query "; 
        } else {
            $sql .= "WHERE b.$filter LIKE :query ";
        }
        $sql .= "GROUP BY b.isbn, b.title, b.author, c.class
                LIMIT 10";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['query' => '%' . $query . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteListing($param = []){
        $sql = "DELETE FROM listings  where id_listing = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute($param);

        return $stm->rowCount() !== 0; 
    }
}

?>