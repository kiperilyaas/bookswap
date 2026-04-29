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

    public function selectAllFromClass($param = []){
    $dql = "SELECT * from class";
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  public function selectAllFromSubject($param = []){
    $dql = "SELECT * from subject";
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  public function selectAllFromPublishHouse($param = []){
    $dql = "SELECT * from publishing_house";
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  public function selectAllFromFaculty($param = []){
    $dql = "SELECT * from faculty";
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  public function selectStateCustomerSellerFromOrder($param){
    $sql = "SELECT O.state_seller, O.state_customer from orders O where O.id_order = ? limit 1";
    $stm = $this->pdo->prepare($sql);
    $stm->execute($param);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  public function changeGlobalStateOrder($param = []){
    $sql = "UPDATE orders set `state` = 'cancelled' where orders.id_order = ?";
    $stm = $this->pdo->prepare($sql);
    $stm->execute($param);

    return $stm->rowCount() !== 0;
  }


}


?>