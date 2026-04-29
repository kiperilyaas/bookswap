<?php
defined("APP") or die("ACCESSO NEGATO");
require_once "config/dbconnect.php";

class UtilsModel{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    public function getNameFromSubject($param = []){
        $sql = "SELECT `name` from subjects";
        $stm = $this->pdo->prepare($sql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNameFromClass($param = []){
        $sql = "SELECT `class` from class";
        $stm = $this->pdo->prepare($sql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNameFromFaculty($param = []){
        $sql = "SELECT `name` from faculty";
        $stm = $this->pdo->prepare($sql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAllEmail($param = []){
        $sql = "SELECT `email` from users";
        $stm = $this->pdo->prepare($sql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>