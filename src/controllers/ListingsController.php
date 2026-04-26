<?php
defined("APP") or die("ACESSO NEGATO");
require_once 'models/ListingsModel.php';
require_once "models/BookModel.php";
require_once "../utils/function.php";

class ListingsController
{
    private $model;
    private $modelBook;
    public function __construct()
    {
        $this->model = new ListingsModel();
        $this->modelBook = new BookModel();
    }

    public function createListings()
    {
        $books = $this->modelBook->selectAll();
        include "views/ListingForm.php";
    }

    public function liveSearch()
    {
        $query = isset($_GET['query']) ? $_GET['query'] : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'title';
        $results = $this->model->searchBook($query, $filter);

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    public function liveSearchBooks() {
    $query = $_GET['query'] ?? '';
    $filter = $_GET['filter'] ?? 'title';
    
    // Chiamiamo un nuovo metodo del Model specifico per il catalogo
    $risultati = $this->model->searchOnlyBooks($query, $filter);
    
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($risultati);
    exit;
}

    public function addListing()
    {
        $price = $_POST['prezzo'] ?? -1;
        if ($price == -1) {
            $_SESSION['error'][] = "Prezzo dell'offerta non valido";
            header("location: index.php?table=Listings&action=createListings");
            exit;
        }

        $condition = $_POST['condizioni'] ?? "";
        if ($condition == "") {
            $_SESSION['error'][] = "Condizioni dell'offerta non valide";
            header("location: index.php?table=Listings&action=createListings");
            exit;
        }

        $book = $_POST['id_book'] ?? -1;
        if ($book == -1) {
            $_SESSION['error'][] = "Libro non selezionato";
            header("location: index.php?table=Listings&action=createListings");
            exit;
        }

        $seller = $_SESSION['id_user'] ?? -1;
        if ($seller == -1) {
            $_SESSION['error'][] = "Devi effettuare il login";
            header("location: index.php?table=login&action=login");
            exit;
        }
        $description = $_POST['descrizione'] ?? "";



        $param = [$book, $seller, $price, $condition, $description, 1];
        $this->model->insertRecord($param);
        $_SESSION['success'][] = "Annuncio creato con successo!";
        header("location: index.php");
        exit;
    }

    public function deleteListing()
    {
        $id = $_GET['id'] ?? -1;
        if ($id == -1) {
            $_SESSION['error'][] = "ID dell'offerta non valido";
            header("location: index.php?table=User&action=account");
            exit;
        }

        $this->model->deleteListing([$id]);
        $_SESSION['success'][] = "Annuncio eliminato con successo";
        header("location: index.php?table=User&action=account");
        exit;
    }

    public function addBookForm()
    {
        include "views/AddBook.php";
    }

    public function addBook()
    {
        $title = $_POST['title'] ?? "";

        $isbn = $_POST['isbn'] ?? "";
        if (!isValidISBN($isbn)) {
            $_SESSION['error'][] = "ISBN non valido (deve essere di 13 caratteri)";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $vol = $_POST['vol'] ?? "";
        if($vol !== "U" && $vol !== "1" && $vol !== "2" && $vol !== "3"){
            $_SESSION['error'][] = "Volume non valido (U, 1, 2 o 3)";
            header("Location: index.php?table=Listings&action=addBookForm");
            exit;
        }
        $author = $_POST['author'] ?? "";

        $class = $_POST['class'] ?? "";
        if (!classExist($class)) {
            $_SESSION['error'][] = "Classe non esistente";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $subject = $_POST['subject'] ?? "";
        /* if(!subjectExist($subject)){
            $_SESSION['error'][] = "Materia non esiste";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        } */

        $faculty = $_POST['faculty'] ?? "";
        /* if(!facultyExist($faculty)){
            $_SESSION['error'][] = "Indirizzo non esiste";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        } */

        $price = $_POST['price'] ?? -1;
        if ($price < 0) {
            $_SESSION['error'][] = "Prezzo non valido";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $publish = $_POST['publish'] ?? "";

        $this->modelBook->getOrCreateBook($title, $isbn, $vol, $author, $class, $subject, $publish, $faculty, $price);

        $_SESSION['success'][] = "Libro aggiunto al catalogo!";
        header("location:index.php?table=Listings&action=createListings");
        exit;
    }

}

?>