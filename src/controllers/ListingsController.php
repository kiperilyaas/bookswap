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

    public function liveSearch() {
        
        $query = isset($_GET['query']) ? $_GET['query'] : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'title';
        $results = $this->model->searchBooks($query, $filter);
        
        header('Content-Type: application/json');
        echo json_encode($results);
        exit; 
    }
}

?>