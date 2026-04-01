<?php
defined("APP") or die("ACCESSO NEGATO");
require_once 'models/book_model.php';
class HomeController{

    private $model;
    public function __construct()
    {
        $this->model = new BookModel();
    }

    public function getTable(){
        
    }

    public function index(){
        $table = $this->model->selectAll();
        include "views/Home.php";
    }
}

?>
