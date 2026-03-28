<?php
if(!defined('APP')) die('Accesso negato');

require_once 'config/dbconnect.php';

// Sostituisci **** con il nome della page
class OrderModel // Iniziale maiuscola
{
  // Oggetto PDO
  private $pdo;

  // Metodo costruttore
  public function __construct()
  {
    $this->pdo = DB::connect();
  }
  
 // Metodo DQL per estrarre una tabella
  public function selectAll(): array
  {
    $dql = "SELECT id_order AS id_order,
                   o.date_order AS date_order,
                   o.state AS state,
                   o.time_meet AS time_meet,
                   o.place_meet AS place_meet,
                   o.description_meet AS description_meet,
                   o.id_customer AS id_customer,
                   o.id_seller AS id_seller
            FROM orders o";
    $param = [];
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per estrarre una colonna
  public function selectIds(): array
  {
    $dql = "SELECT id_order FROM orders ORDER BY id_order ASC";
    $param = [];
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_COLUMN);
  }

  // Metodo DQL per controllare l'esistenza di un valore di una colonna
  public function find($key, $value): bool
  {
    $dql = "SELECT 1 
            FROM orders 
            WHERE $key = ?
            LIMIT 1";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute([$value]);
    //-----------------------------------
    return $stm->fetchColumn() !== false;
  }

  // Metodo DML per inserire un record
  public function insertRecord(array $param): bool
  {
    $dml = "INSERT INTO orders (state, time_meet, place_meet, description_meet, id_customer, id_seller) VALUES (?, ?)";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;
  }

  // Metodo DML per cancellare un record
  public function deleteRecord(array $param): bool
  {
    $dml = "DELETE FROM orders WHERE id_order = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;
  }

  // Metodo DML per modificare un record
  public function updateRecord(array $param): bool
  {
    $dml = "UPDATE orders 
              SET `state` = ?, `time_meet` = ?, `place_meet` = ?, `description_meet` = ?, `id_customer` = ?, `id_seller` = ?
              WHERE id_order = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;
  }
}
