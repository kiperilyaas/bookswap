<?php
if(!defined('APP')) die('Accesso negato');

require_once 'config/dbconnect.php';

class OrderModel 
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = DB::connect();
  }
  public function insertRecord(array $param): bool
  {
    // Ordine parametri: id_listing, id_customer, id_seller, final_price, date_order, state, time_meet, place_meet, description_meet
    $dml = "INSERT INTO orders (id_listing, id_customer, id_seller, final_price, date_order, state, time_meet, place_meet, description_meet)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    return $stm->rowCount() !== 0;
  }
  public function deleteRecord(array $param): bool
  {
    $dml = "DELETE FROM orders WHERE id_order = ?";
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    return $stm->rowCount() !== 0;
  }

  public function changeOrderStateSeller($orderId, $newState){
    $sql = "SELECT O.state_seller FROM orders O where id_order = ?";
    $stm = $this->pdo->prepare($sql);
    $stm->execute([$orderId]);
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

    if($result[0] != $newState){
      $sql = "UPDATE orders set `state_seller` = ? where id_order = ?";
      $stm = $this->pdo->prepare($sql);
      $stm->execute([$newState, $orderId]);
    }else return 1;

    return $stm->rowCount() !== 0;
  }

  public function changeOrderStateCustomer($orderId, $newState){
    $sql = "SELECT O.state_customer FROM orders O where id_order = ?";
    $stm = $this->pdo->prepare($sql);
    $stm->execute([$orderId]);
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

    if($result[0] != $newState){
      $sql = "UPDATE orders set `state_customer` = ? where id_order = ?";
      $stm = $this->pdo->prepare($sql);
      $stm->execute([$newState, $orderId]);
    }else return 1;

    return $stm->rowCount() !== 0;
  }

  public function findMyOrders($param = []){
    $sql = "SELECT * from orders O 
      join listings L using(id_listing) 
      join books B using(id_book)
      join users U on O.id_seller = U.id_user
      where id_customer = ?";
    $stm = $this->pdo->prepare($sql);
    $stm->execute([$param]);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }
}