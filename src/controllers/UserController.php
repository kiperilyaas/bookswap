<?php
if(!defined('APP')) die('Accesso negato');

require_once 'models/UserModel.php';

class UserController
{
  private $page;
  private $model;

  public function __construct()
  {
    $this->page = 'user';
    $this->model = new UserModel();

    // ora il carrello contiene UN SOLO libro
    if(!isset($_SESSION['cart'])){
      $_SESSION['cart'] = null;
    }
  }

  //------------- VISTA CARRELLO -----------------
  public function index()
  {
    $title = 'Carrello';
    $cart = $_SESSION['cart'];

    $view = 'views/cart.php';
    require 'views/template.php';
  }

  //------------- AGGIUNGI LIBRO -----------------
  public function add()
  {
    $id_book = (int)($_GET['id_book'] ?? 0);

    if($id_book > 0){
      // sostituisce sempre il libro (1 solo alla volta)
      $_SESSION['cart'] = $id_book;
    }

    header("Location: index.php?table=user&action=index");
    exit;
  }

  //------------- RIMUOVI LIBRO -----------------
  public function remove()
  {
    $_SESSION['cart'] = null;

    header("Location: index.php?table=user&action=index");
    exit;
  }

  //------------- CHECKOUT -----------------
  public function checkout()
  {
    if(empty($_SESSION['cart'])){
      die("Nessun libro nel carrello");
    }

    $id_user = $_SESSION['id_user'] ?? 0;

    if($id_user == 0){
      die("Devi fare login");
    }

    $id_book = $_SESSION['cart'];

    // crea ordine (1 libro solo)
    $this->model->insertRecord($id_user, $id_book);

    // svuota carrello
    $_SESSION['cart'] = null;

    header("Location: index.php?table=user&action=success");
    exit;
  }

  //------------- PAGINA SUCCESSO -----------------
  public function success()
  {
    $title = "Ordine completato";
    $view = 'views/success.php';
    require 'views/template.php';
  }
}