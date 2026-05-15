<?php
if(!defined('APP')) die('Accesso negato');

require_once 'config/dbconnect.php';

/**
 * Summary of BookModel
 * Il model dell tabella books
 */
class BookModel 
{
  public $pdo;
 
  public function __construct()
  {
    $this->pdo = DB::connect();
  }

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
                   b.price AS price
            FROM books b";
    
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Summary of getOrCreateBook
   * Funzione crea passo a passo il libro con i suio FK come classe, subject, faculty, publishHouse
   * @param mixed $title
   * @param mixed $isbn
   * @param mixed $vol
   * @param mixed $author
   * @param mixed $class
   * @param mixed $subject
   * @param mixed $publish
   * @param mixed $faculty
   * @param mixed $price
   * @return void
   */
  public function getOrCreateBook($title, $isbn, $vol, $author, $class, $subject, $publish, $faculty, $price){
    
  //prelievo dell id della classe
    try{
      $this->pdo->beginTransaction();

      #per classe
      $sql = "SELECT id_class from class where class = ? limit 1";
      $stm = $this->pdo->prepare($sql);
      $stm->execute([$class]);
      $result = $stm->fetchAll(PDO::FETCH_ASSOC);

      if($result){
        $id_class = $result[0]['id_class'];
      }
    }
    catch (Exception $e) {
      if ($this->pdo->inTransaction()) {
          $this->pdo->rollBack();
      }         
      $_SESSION['error'][] = "Errore durante il salvataggio";
      header("location: index.php");
      exit;
    
    }

    //prelievo del id della materia
    try{
      $sql = "SELECT id_subject from subjects where `name` = ? limit 1";
      $stm = $this->pdo->prepare($sql);
      $stm->execute([$subject]);
      $result = $stm->fetchAll(PDO::FETCH_ASSOC);

      if($result){
        $id_subject = $result[0]['id_subject'];
      }
      else{
        $sql = "INSERT INTO subjects(name) values(?)";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$subject]);
        $id_subject = $this->pdo->lastInsertId();
      }
    }
    catch (Exception $e) {
      if ($this->pdo->inTransaction()) {
          $this->pdo->rollBack();
      }    
      $_SESSION['error'][] = "Errore durante il salvataggio";
      header("location: index.php");
      exit;
            
    }

    //prelievo del id della casa edittrice
    try{
      $sql = "SELECT id_publish_house from publishing_house where name = ? limit 1";
      $stm = $this->pdo->prepare($sql);
      $stm->execute([$publish]);
      $result = $stm->fetchAll(PDO::FETCH_ASSOC);

      if($result){
        $id_publish = $result[0]['id_publish_house'];
      }
      else{
        $sql = "INSERT INTO publishing_house(name) values(?)";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$publish]);
        $id_publish = $this->pdo->lastInsertId();
      }
    }catch (Exception $e) {
      if ($this->pdo->inTransaction()) {
          $this->pdo->rollBack();
      }         
      $_SESSION['error'][] = "Errore durante il salvataggio";
      header("location: index.php");
      exit;
    }

    //prelievo del id del indirizzo
    try{
      $sql = "SELECT id_faculty from faculty where name = ? limit 1";
      $stm = $this->pdo->prepare($sql);
      $stm->execute([$faculty]);
      $result = $stm->fetchAll(PDO::FETCH_ASSOC);

      if($result){
        $id_faculty = $result[0]['id_faculty'];
      }
      else{
        $sql = "INSERT INTO faculty(name) values(?)";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$faculty]);
        $id_faculty  = $this->pdo->lastInsertId();
      }
    }catch (Exception $e) {
      if ($this->pdo->inTransaction()) {
          $this->pdo->rollBack();
      }         
      $_SESSION['error'][] = "Errore durante il salvataggio";
      header("location: index.php");
      exit;
    }

    //creazione del libro.
    try{
      $param = [$title, $isbn, $vol, $author, $id_class, $id_subject, $id_publish, $id_faculty, $price];
      $this->insertRecord($param);
      $this->pdo->commit();
    }catch (Exception $e) {
      if ($this->pdo->inTransaction()) {
          $this->pdo->rollBack();
      }         
      $_SESSION['error'][] = "Errore durante il salvataggio";
      header("location: index.php");
      exit;
    }
    
  }
  public function insertRecord(array $param): bool
  {
    $dml = "INSERT INTO books (title, isbn, vol, author, id_class, id_subject, id_publish_house, id_faculty, price) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);

    return $stm->rowCount() !== 0;
  }
  /**
   * Summary of findByPriceSecond
   * Ricerca del libro tra un prezzzo minimo e massimo
   * @param int $min
   * @param int $max
   * @todo implementare il filtro per prezzo
   */
  public function findByPriceSecond(int $min, int $max): array   
  {
    $dql = "SELECT * 
            FROM books 
            WHERE price BETWEEN ? AND ?";
    $stm = $this->pdo->prepare($dql);
    $stm->execute([$min, $max]);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera tutte le case editrici
   * @return array
   */
  public function getAllPublishingHouses(): array {
    $sql = "SELECT id_publish_house, name FROM publishing_house ORDER BY name ASC";
    $stm = $this->pdo->prepare($sql);
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera tutte le materie
   * @return array
   */
  public function getAllSubjects(): array {
    $sql = "SELECT id_subject, name FROM subjects ORDER BY name ASC";
    $stm = $this->pdo->prepare($sql);
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera tutti gli indirizzi
   * @return array
   */
  public function getAllFaculties(): array {
    $sql = "SELECT id_faculty, name FROM faculty ORDER BY name ASC";
    $stm = $this->pdo->prepare($sql);
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera tutte le classi
   * @return array
   */
  public function getAllClasses(): array {
    $sql = "SELECT id_class, class FROM class ORDER BY class ASC";
    $stm = $this->pdo->prepare($sql);
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

}
