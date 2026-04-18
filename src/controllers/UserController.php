<?php
if(!defined('APP')) die('Accesso negato');

require_once 'models/UserModel.php';

class UserController{
  private $model;

  public function __construct()
  {
    $this->model = new UserModel();
  }

  public function account(){
    $myOffers = $this->model->getListingsOfUser([$_SESSION['id_user']]);
    $myOrders = $this->model->getOrdersOfUser([$_SESSION['id_user']]);

    include "views/Account.php";

  }
  
}