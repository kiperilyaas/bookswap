<?php
if(!defined('APP')) die('Accesso negato');

require_once 'config/dbconnect.php';

// Sostituisci **** con il nome della page
class BookModel // Iniziale maiuscola
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
    $dql = "SELECT id_book AS id_book,
                   b.title AS title,
                   b.isbn AS isbn,
                   b.vol AS volume,
                   b.author AS author,
                   b.school_year AS school_year,
                   b.id_class AS id_class,
                   b.id_subject AS id_subject,
                   b.id_publish_house AS id_publish_house,
                   b.id_faculty AS id_faculty,
                   b.id_order AS id_order,
                   b.price AS price
            FROM books b";
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
    $dql = "SELECT id_book FROM books ORDER BY id_book ASC";
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
            FROM books 
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
    $dml = "INSERT INTO books (title, isbn, vol, author, school_year, id_class, id_subject, id_publish_house, id_faculty, id_order, price) VALUES (?, ?)";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;
  }

  // Metodo DML per cancellare un record
  public function deleteRecord(array $param): bool
  {
    $dml = "DELETE FROM users WHERE id_book = ?";
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
              SET `title` = ?, `isbn` = ?, `vol` = ?, `author` = ?, `school_year` = ?, `id_class` = ?, `id_subject` = ?, `id_publish_house` = ?, `id_faculty` = ?, `id_order` = ?, `price` = ?
              WHERE id_book = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;
  }
}
