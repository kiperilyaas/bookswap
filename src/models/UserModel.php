<?php
if(!defined('APP')) die('Accesso negato');

require_once "config/dbconnect.php";

// Sostituisci **** con il nome della page
class UserModel // Iniziale maiuscola
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
    $dql = "SELECT id_user AS id,
                   u.name AS name,
                   u.surname AS surname,
                   u.class AS class,
                   u.email AS email,
                   u.password AS password
            FROM users u";
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
    $dql = "SELECT id_user FROM users ORDER BY id_user ASC";
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
    $dql = "SELECT * 
            FROM users 
            WHERE $key = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute([$value]);
    //-----------------------------------
    return $stm->fetchColumn() !== false;
  }

  // Metodo DML per inserire un record
  public function insertRecord(array $param): bool
  {
    $dml = "INSERT INTO users (name, surname, class, email, password) VALUES (?, ?, ?, ?, ?)";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;    
  }

  // Metodo DML per cancellare un record
  public function deleteRecord(array $param): bool
  {
    $dml = "DELETE FROM users WHERE id_user = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;
  }

  // Metodo DML per modificare un record
  public function updateRecord(array $param): bool
  {
    $dml = "UPDATE users 
              SET `name` = ?, `surname` = ?, `class` = ?, `email` = ?, `password` = ?
              WHERE id_user = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;
  }

  //ricerca di un user per la mail
  public function findUserByMail($param = []) {
    $dml = "SELECT * FROM users 
    where email = ?
    limit 1";
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getListingsOfUser($param = []){
    $sql = "SELECT L.id_listing, L.price, L.is_available, B.title, B.isbn from listings L
            join books B using(id_book)
            where L.id_seller = ?";
    $stm = $this->pdo->prepare($sql);
    $stm->execute($param);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getOrdersOfUser($param = []){
    $sql = "SELECT * from orders O where O.id_seller = ? ";
    $stm = $this->pdo->prepare($sql);
    $stm->execute($param);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }





}
