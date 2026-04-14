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
}

?>