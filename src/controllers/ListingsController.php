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
            $_SESSION['error'][] = "prezzo della offerta non e' valido";
            header("location: index.php?table=error&action=errorview");
            exit;
        }

        $condition = $_POST['condizioni'] ?? "";
        if ($condition == "") {
            $_SESSION['error'][] = "Condizioni della offerta non e' valida";
            header("location: index.php?table=error&action=errorview");
            exit;
        }

        $book = $_POST['id_book'] ?? -1;
        if ($book == -1) {
            $_SESSION['error'][] = "Id del libro nella offerta non e' valida";
            header("location: index.php?table=error&action=errorview");
            exit;
        }

        $seller = $_SESSION['id_user'] ?? -1;
        if ($seller == -1) {
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

    public function deleteListing()
    {
        $id = $_GET['id'] ?? -1;
        if ($id == -1) {
            $_SESSION['error'][] = "ID della offerta non e' valido";
            header("location: index.php?table=error&action=errorview");
            exit;
        }

        $this->model->deleteListing([$id]);
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
            $_SESSION['error'][] = "LUNGHEZZA DEL ISBN NON E' DI 13 CARATTERI";
            header("location: index.php?table=error&action=errorview");
            exit;
        }
        
        $vol = $_POST['vol'] ?? "";
        if($vol !== "U" && $vol !== "1" && $vol !== "2" && $vol !== "3"){
            $_SESSION['error'][] = "Volume non valido";
            header("Location: index.php?table=error&action=errorview"); // Aggiunto Location:
            exit;
        }
        $author = $_POST['author'] ?? "";

        $class = $_POST['class'] ?? "";
        if (!classExist($class)) {
            $_SESSION['error'][] = "classe non esiste";
            header("location: index.php?table=error&action=errorview");
            exit;
        }

        $subject = $_POST['subject'] ?? "";
        /* if(!subjectExist($subject)){
            $_SESSION['error'][] = "Materia non esiste";
            header("location: index.php?table=error&action=errorview");
            exit;
        } */

        $faculty = $_POST['faculty'] ?? "";
        /* if(!facultyExist($faculty)){
            $_SESSION['error'][] = "Indirizzo non esiste";
            header("location: index.php?table=error&action=errorview");
            exit;
        } */

        $price = $_POST['price'] ?? -1;
        if ($price < 0) {
            $_SESSION['error'][] = "Prezzo non valido";
            header("location: index.php?table=error&action=errorview");
            exit;
        }

        $publish = $_POST['publish'] ?? "";

        $this->modelBook->getOrCreateBook($title, $isbn, $vol, $author, $class, $subject, $publish, $faculty, $price);
        
        header("location:index.php?table=Listings&action=createListings");
        exit;
    }

}

?>