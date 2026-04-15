<?php 
defined("APP") or die("ACESSO NEGATO");
require_once 'models/ListingsModel.php' ;

class ListingsController{
    private $model;

    public function __construct()
    {   
        $this->model = new ListingsModel();
    }

    public function createListings(){
        $books = $this->model->selectAllBooks();
        include "views/ListingForm.php";
    }

    public function getBooks(){
        
    }
}

?>