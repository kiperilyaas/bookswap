<?php
if(!defined('APP')) die('Accesso negato');

require_once 'models/UserModel.php';

class UserController{
  private $Usermodel;
  public function __construct()
  {
    $this->Usermodel = new UserModel();
  }

  public function account(){
    $myOffers = $this->Usermodel->getListingsOfUser([$_SESSION['id_user']]);
    $myOrders = $this->Usermodel->getOrdersOfUser([$_SESSION['id_user']]);

    // Recupera i dati dell'utente per il form di modifica
    $userData = $this->Usermodel->getUserById([$_SESSION['id_user']]);

    include "views/Account.php";
  }

  public function updateProfile(){
    $userId = $_SESSION['id_user'] ?? -1;
    if($userId == -1){
      $_SESSION['error'][] = "Devi effettuare il login";
      header("location: index.php?table=login&action=login");
      exit;
    }

    $name = $_POST['name'] ?? null;
    $surname = $_POST['surname'] ?? null;
    $class = $_POST['class'] ?? null;
    $email = $_POST['email'] ?? null;

    // Validazione
    if(!$name || !$surname || !$class || !$email){
      $_SESSION['error'][] = "Tutti i campi sono obbligatori";
      header("location: index.php?table=User&action=account");
      exit;
    }

    // Verifica dominio email
    $domain = substr($email, strpos($email, '@') + 1);
    if($domain != "isit100.fe.it"){
      $_SESSION['error'][] = "Dominio email non verificato. Usa un'email @isit100.fe.it";
      header("location: index.php?table=User&action=account");
      exit;
    }

    // Recupera password attuale
    $currentUser = $this->Usermodel->getUserById([$userId]);
    $password = $currentUser[0]['password'];

    $param = [$name, $surname, $class, $email, $password, $userId];
    $result = $this->Usermodel->updateRecord($param);

    if($result){
      $_SESSION['success'][] = "Profilo aggiornato con successo!";
    } else {
      $_SESSION['error'][] = "Errore durante l'aggiornamento del profilo";
    }

    header("location: index.php?table=User&action=account");
    exit;
  }

  public function changePassword(){
    $userId = $_SESSION['id_user'] ?? -1;
    if($userId == -1){
      $_SESSION['error'][] = "Devi effettuare il login";
      header("location: index.php?table=login&action=login");
      exit;
    }

    $currentPassword = $_POST['current_password'] ?? null;
    $newPassword = $_POST['new_password'] ?? null;
    $confirmPassword = $_POST['confirm_password'] ?? null;

    if(!$currentPassword || !$newPassword || !$confirmPassword){
      $_SESSION['error'][] = "Tutti i campi sono obbligatori";
      header("location: index.php?table=User&action=account");
      exit;
    }

    if($newPassword !== $confirmPassword){
      $_SESSION['error'][] = "Le nuove password non coincidono";
      header("location: index.php?table=User&action=account");
      exit;
    }

    if(strlen($newPassword) < 6){
      $_SESSION['error'][] = "La nuova password deve essere di almeno 8 caratteri";
      header("location: index.php?table=User&action=account");
      exit;
    }

    // Verifica password attuale
    $userData = $this->Usermodel->getUserById([$userId]);
    if(!password_verify($currentPassword, $userData[0]['password'])){
      $_SESSION['error'][] = "Password attuale non corretta";
      header("location: index.php?table=User&action=account");
      exit;
    }

    // Aggiorna password
    $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
    $param = [
      $userData[0]['name'],
      $userData[0]['surname'],
      $userData[0]['class'],
      $userData[0]['email'],
      $newPasswordHash,
      $userId
    ];

    $result = $this->Usermodel->updateRecord($param);

    if($result){
      $_SESSION['success'][] = "Password cambiata con successo!";
    } else {
      $_SESSION['error'][] = "Errore durante il cambio password";
    }

    header("location: index.php?table=User&action=account");
    exit;
  }


}