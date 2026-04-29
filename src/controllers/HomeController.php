<?php
defined("APP") or die("ACCESSO NEGATO");
require_once 'models/ListingsModel.php';
class HomeController{

    private $model;
    public function __construct()
    {
        $this->model = new ListingsModel();
    }

    public function index(){
        $table = $this->model->selectListings();
        include "views/Home.php";
    }
}

?>
