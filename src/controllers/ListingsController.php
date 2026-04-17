<?php 
defined("APP") or die("ACESSO NEGATO");
require_once 'models/ListingsModel.php' ;
require_once "models/BookModel.php";

class ListingsController{
    private $model;
    private $modelBook;
    public function __construct()
    {   
        $this->model = new ListingsModel();
        $this->modelBook = new BookModel();
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

    public function addListing(){
        $price = $_POST['prezzo'] ?? -1;
        if($price == -1){
            $_SESSION['error'][] = "prezzo della offerta non e' valido";
            header("location: index.php?table=error&action=errorview");
            exit;
        }
        
        $condition = $_POST['condizioni'] ?? "";
        if($condition == ""){
            $_SESSION['error'][] = "Condizioni della offerta non e' valida";
            header("location: index.php?table=error&action=errorview");
            exit;
        }

        $book = $_POST['id_book'] ?? -1;
        if($book == -1){
            $_SESSION['error'][] = "Id del libro nella offerta non e' valida";
            header("location: index.php?table=error&action=errorview");
            exit;
        }

        $seller = $_SESSION['id_user'] ?? -1;
        if($seller == -1){
            $_SESSION['error'][] = "Vendtore non esiste";
            header("location: index.php?table=error&action=errorview");
            exit;
        }
        $description = $_POST['descrizione'] ?? "";

        

        $param = [$book, $seller, $price, $condition, $description, 1];
        $this->model->insertRecord($param);
        header("location: index.php");
        exit;
    }

    public function deleteListing(){
        $id = $_GET['id'] ?? -1;
        if($id == -1) {
            $_SESSION['error'][] = "ID della offerta non e' valido";
            header("location: index.php?table=error&action=errorview");
            exit;
        }

        $this->model->deleteListing([$id]);
        header("location: index.php?table=User&action=account");
        exit;
    }

    public function addBookForm(){
        include "views/AddBook.php";
    }

    public function addBook(){
        $title = $_POST['title'] ?? "";
        $isbn = $_POST['isbn'] ?? "";
        $vol = $_POST['vol'] ?? "";
        $author = $_POST['author'] ?? "";
        $class = $_POST['class'] ?? "";
        $subject = $_POST['subject'] ?? "";
        $publish = $_POST['publish'] ?? "";
        $faculty = $_POST['faculty'] ?? "";
        $price = $_POST['price'] ?? -1;
    }

}

?>