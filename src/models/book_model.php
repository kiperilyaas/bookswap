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
  public function selectAll($param = []): array
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
    
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
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

  // Metodo DQL per estrarre una colonna
  public function selectIds(): array
  {
    $dql = "SELECT id_book FROM books ORDER BY id_book ASC";
    $param = [];
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
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
    $dml = "INSERT INTO books (title, isbn, vol, author, school_year, id_class, id_subject, id_publish_house, id_faculty, id_order, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;
  }

  // Metodo DML per cancellare un record
  public function deleteRecord(array $param): bool
  {
    $dml = "DELETE FROM books WHERE id_book = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;
  }

  // Metodo DML per modificare un record
  public function updateRecord(array $param): bool
  {
    $dml = "UPDATE books 
              SET `title` = ?, `isbn` = ?, `vol` = ?, `author` = ?, `school_year` = ?, `id_class` = ?, `id_subject` = ?, `id_publish_house` = ?, `id_faculty` = ?, `id_order` = ?, `price` = ?
              WHERE id_book = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //-----------------------------------
    return $stm->rowCount() !== 0;
  }

  // Metodo DQL per trovare un ID
  public function findById($param = []): array
  {
    $dql = "SELECT * 
            FROM books 
            WHERE id_book = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare(query: $dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per trovare un Titolo
  public function findByTitle($param = []): array
  {
    $dql = "SELECT * 
            FROM books 
            WHERE title LIKE ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

    // Metodo DQL per trovare un ISBN
  public function findByIsbn($param = []): array
  {
    $dql = "SELECT * 
            FROM books 
            WHERE isbn LIKE ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

    // Metodo DQL per trovare un Volume
  public function findByVol($param = []): array
  {
    $dql = "SELECT * 
            FROM books 
            WHERE title = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per trovare un Autore
  public function findByAuthor($param = []): array
  {
    $dql = "SELECT * 
            FROM books 
            WHERE author LIKE ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per la ricerca in base all'Anno
  public function findByYear($param = []): array
  {
    $dql = "SELECT * 
            FROM books 
            WHERE school_year = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per la ricerca in base alla Classe
  public function findByClass($param = []): array
  {
    $dql = "SELECT * 
            FROM books 
            WHERE id_class = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per la ricerca in base alla Materia
  public function findBySubject($param = []): array 
  {
    $dql = "SELECT * 
            FROM books 
            WHERE id_subject = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per la ricerca in base alla Casa Produttrice
  public function findByPublishHouse($param = []): array 
  {
    $dql = "SELECT * 
            FROM books 
            WHERE id_publish_house LIKE ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per la ricerca in base alla Facoltà
  public function findByFaculty($param = []): array 
  {
    $dql = "SELECT * 
            FROM books 
            WHERE id_faculty LIKE ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per la ricerca in base alla Casa Produttrice
  public function findByOrder($param = []): array 
  {
    $dql = "SELECT * 
            FROM books 
            WHERE id_order LIKE ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per la ricerca in base all Prezzo (Singolo)
  public function findByPriceFirst($param = []): array   
  {
    $dql = "SELECT * 
            FROM books 
            WHERE price = ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  // Metodo DQL per la ricerca in base all Prezzo (Tra Minimo e Massimo Specificati)
  public function findByPriceSecond(int $min, int $max): array   
  {
    $dql = "SELECT * 
            FROM books 
            WHERE price BETWEEN ? AND ?";
    //-----------------------------------
    $stm = $this->pdo->prepare($dql);
    $stm->execute([$min, $max]);
    //-----------------------------------
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

}
