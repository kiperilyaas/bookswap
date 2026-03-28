<?php
if(!defined('APP')) die('Accesso negato');

require_once 'models/book_model.php'; 

class BookController
{
  private $page;
  private $model;

  public function __construct()
  {
    $this->page = 'book'; 
    $this->model = new BookModel(); 
  }

  public function index()
  {
    $title = 'Catalogo Libri';
    $table = $this->model->selectAll();
    $view = 'views/book_index.php';
    require 'views/template.php';
  }

  // ... (create, update, delete rimangono simili a prima)

  public function store()
  {
    // Recuperiamo tutti i parametri dal form
    $title_book = trim($_POST['title'] ?? '');
    $author     = trim($_POST['author'] ?? '');
    $isbn       = trim($_POST['isbn'] ?? '');
    $price      = trim($_POST['price'] ?? '');

    if ($title_book !== '' && $author !== '') {
      // Passiamo l'array con 4 parametri
      $param = [$title_book, $author, $isbn, $price];
      $this->model->insertRecord($param);
    }
    
    header("Location: index.php?page=book");
    exit;
  }

  public function edit()
  {
    $id_book    = (int)($_POST['id_book'] ?? 0);
    $title_book = trim($_POST['title'] ?? '');
    $author     = trim($_POST['author'] ?? '');
    $isbn       = trim($_POST['isbn'] ?? '');
    $price      = trim($_POST['price'] ?? '');

    if ($id_book > 0 && $title_book !== '') {
      // L'ordine deve essere: campi da aggiornare + ID finale per il WHERE
      $param = [$title_book, $author, $isbn, $price, $id_book];
      $this->model->updateRecord($param);
    }
    
    header("Location: index.php?page=book");
    exit;
  }
}