<?php
if(!defined('APP')) die('Accesso negato');

// Il percorso corretto basato sulla tua struttura src/
require_once 'models/user_model.php'; 

class UserController
{
  private $page;
  private $model;

  public function __construct()
  {
    $this->page = 'user'; 
    $this->model = new UserModel(); 
  }

  //------------- VISTE -----------------
  public function index()
  {
    $title = 'Gestione Utenti';
    $table = $this->model->selectAll();
    $view = 'views/user_index.php'; 
    require 'views/template.php';
  }

  public function create()
  {
    $title = 'Nuovo Utente';
    $view = 'views/user_form_create.php';
    require 'views/template.php';
  }

  public function update()
  {
    $title = 'Modifica Utente';
    // Utilizza selectIds() definito nel tuo UserModel
    $col_id = $this->model->selectIds();
    $view = 'views/user_form_update.php';
    require 'views/template.php';
  }

  public function delete()
  {
    $title = 'Elimina Utente';
    $col_id = $this->model->selectIds();
    $view = 'views/user_form_delete.php';
    require 'views/template.php';
  }

  //------------- AZIONI FORM -----------------

  public function store()
  {
    // Recupero dati dai nomi dei campi del form (name, surname, class, email, password)
    $name     = trim($_POST['name'] ?? '');
    $surname  = trim($_POST['surname'] ?? '');
    $class    = trim($_POST['class'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validazione minima
    if ($name !== '' && $surname !== '' && $email !== '') {
      // L'array deve avere 5 elementi per corrispondere ai 5 "?" che aggiungeremo nel Model
      $param = [$name, $surname, $class, $email, $password];
      $this->model->insertRecord($param);
    }
    
    header("Location: index.php?page=user");
    exit;
  }

  public function edit()
  {
    $id_user  = (int)($_POST['id_user'] ?? 0);
    $name     = trim($_POST['name'] ?? '');
    $surname  = trim($_POST['surname'] ?? '');
    $class    = trim($_POST['class'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($id_user > 0 && $name !== '' && $surname !== '') {
      // L'ordine corrisponde ai ? in updateRecord: name, surname, class, email, password, id_user
      $param = [$name, $surname, $class, $email, $password, $id_user];
      $this->model->updateRecord($param);
    }
    
    header("Location: index.php?page=user");
    exit;
  }

  public function destroy()
  {
    $id_user = (int)($_POST['id_user'] ?? 0);
    
    if ($id_user > 0) {
      $param = [$id_user];
      $this->model->deleteRecord($param);
    }
    
    header("Location: index.php?page=user");
    exit;
  }
}