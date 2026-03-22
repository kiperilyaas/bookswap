<?php 

require_once "/src/config/dbconnect.php";
class libriModel{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    public function insertRecord($param = []){
        $dql = "insert into bs_libri(titolo, materia, isbn, condizioni, prezzo, stato, id_venditore, id_ordine)
        values(?,?,?,?,?,?,?,?)";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function deleteRecord($param = []){
        $dql = "delete from bs_libri 
        where id_utente = ?";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function updateRecord($param = []){
        $dql = "update bs_libri set titolo = ?, materia = ?, isbn = ?, condizioni = ?, prezzo = ?,
        stato = ?, id_venditore = ?, id_ordine = ?
        where id_utente = ?";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function selectAll($param = []){
        $dql = "select * from bs_libri";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>