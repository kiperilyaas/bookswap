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
  
  // Metodo DQL per estrarre una tabella
  public function selectAll(): array
  {
    // Ho aggiunto una JOIN per permetterti di recuperare anche il titolo del libro associato all'annuncio
    $dql = "SELECT o.id_order AS id_order,
                   o.date_order AS date_order,
                   o.status AS state, -- ho usato 'status' come definito nel nuovo DB
                   o.time_meet AS time_meet,
                   o.place_meet AS place_meet,
                   o.description_meet AS description_meet,
                   o.id_customer AS id_customer,
                   o.id_seller AS id_seller,
                   o.id_listing AS id_listing,
                   o.final_price AS final_price,
                   b.title AS book_title -- Info utile dal catalogo
            FROM orders o
            JOIN listings l ON o.id_listing = l.id_listing
            JOIN books b ON l.id_book = b.id_book";
    
    $param = [];
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  public function selectListings($param = []){
    $dql = "SELECT * from listings";
    $stm = $this->pdo->prepare($dql);
    $stm->execute();

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per estrarre una colonna
  public function selectIds(): array
  {
    $dql = "SELECT id_order FROM orders ORDER BY id_order ASC";
    $param = [];
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    return $stm->fetchAll(PDO::FETCH_COLUMN);
  }

  // Metodo DQL per controllare l'esistenza di un valore
  public function find($key, $value): bool
  {
    $dql = "SELECT 1 
            FROM orders 
            WHERE $key = ?
            LIMIT 1";
    $stm = $this->pdo->prepare($dql);
    $stm->execute([$value]);
    return $stm->fetchColumn() !== false;
  }

  // Metodo DML per inserire un record
  public function insertRecord(array $param): bool
  {
    // Aggiunto final_price e id_listing (al posto di id_book)
    // L'ordine dei parametri deve essere: state, time_meet, place_meet, description_meet, id_customer, id_seller, id_listing, final_price
    $dml = "INSERT INTO orders (id_order, id_listing, id_customer, id_seller, final_price, date_order, status, time_meet, place_meet, description_meet, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    return $stm->rowCount() !== 0;
  }

  // Metodo DML per cancellare un record
  public function deleteRecord(array $param): bool
  {
    $dml = "DELETE FROM orders WHERE id_order = ?";
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    return $stm->rowCount() !== 0;
  }

  // Metodo DML per modificare un record
  /* public function updateRecord(array $param): bool
  {
    // Aggiornato con i nuovi nomi dei campi
    // Parametri attesi: status, time_meet, place_meet, description_meet, id_customer, id_seller, id_listing, final_price, id_order
    $dml = "UPDATE orders 
              SET `status` = ?, `time_meet` = ?, `place_meet` = ?, `description_meet` = ?, `id_customer` = ?, `id_seller` = ?, `id_listing` = ?, `final_price` = ?
              WHERE id_order = ?";
    
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    return $stm->rowCount() !== 0;
  } */
}