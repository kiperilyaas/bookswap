<?php 

require_once "/src/config/dbconnect.php";
class utentiModel{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    public function insertRecord($param = []){
        $dql = "insert into bs_utenti(nome, cognome, classe, email, password)
        values(?,?,?,?)";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function deleteRecord($param = []){
        $dql = "delete from bs_utenti 
        where id_utente = ?";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function updateRecord($param = []){
        $dql = "update bs_utenti set nome = ?, cognome = ?, classe = ?, email = ?, password = ?
        where id_utente = ?";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function selectAll($param = []){
        $dql = "select * from bs_utenti";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>