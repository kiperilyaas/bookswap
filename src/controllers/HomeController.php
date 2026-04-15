<?php
defined("APP") or die("ACCESSO NEGATO");
require_once 'models/OrderModel.php';
class HomeController{

    private $model;
    public function __construct()
    {
        $this->model = new OrderModel();
    }

    public function getTable(){
        
    }

    public function index(){
        $table = $this->model->selectListings();
        include "views/Home.php";
    }
}

?>
