<?php
defined("APP") or die("ACESSO NEGATO");
require_once 'models/ListingsModel.php';
require_once "models/BookModel.php";
require_once 'models/ListingImagesModel.php';
require_once "../utils/function.php";
require_once "../utils/imageUpload.php";

class ListingsController
{
    private $model;
    private $modelBook;
    private $modelImages;

    public function __construct()
    {
        $this->model = new ListingsModel();
        $this->modelBook = new BookModel();
        $this->modelImages = new ListingImagesModel();
    }

    public function createListings()
    {
        $books = $this->modelBook->selectAll();
        include "views/ListingForm.php";
    }


    public function liveSearchListings()
    {
        $query =  $_GET['query'] ?? '';
        $filter = $_GET['filter'] ?? 'title';
        $results = $this->model->searchBookOnly($query, $filter);

        //serve per mandare il risultato sulla pagina  
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    public function liveSearchBooks() {
        $query = $_GET['query'] ?? '';
        $filter = $_GET['filter'] ?? 'title';
        
        $results = $this->model->searchBooksListings($query, $filter);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($results);
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


        // Crea il listing
        $param = [$book, $seller, $price, $condition, $description, 1];
        $result = $this->model->insertRecord($param);

        if ($result) {
            // Recupera l'ID del listing appena creato
            $id_listing = $this->model->getLastInsertedId();

            // Gestione upload immagini
            if (isset($_FILES['listing_images']) && !empty($_FILES['listing_images']['name'][0])) {
                $uploadedImages = handleImageUpload($_FILES['listing_images'], $id_listing);

                if (!empty($uploadedImages)) {
                    foreach ($uploadedImages as $index => $imagePath) {
                        $is_primary = ($index === 0) ? 1 : 0; // Prima immagine = principale
                        $this->modelImages->addImage($id_listing, $imagePath, $is_primary);
                    }
                    $_SESSION['success'][] = "Annuncio creato con " . count($uploadedImages) . " foto!";
                } else {
                    $_SESSION['success'][] = "Annuncio creato senza foto";
                }
            } else {
                $_SESSION['success'][] = "Annuncio creato con successo, senza foto!";
            }
        } else {
            $_SESSION['error'][] = "Errore durante la creazione dell'annuncio";
        }

        header("location: index.php");
        exit;
    }

    public function getListingImages() {
        $id_listing = $_GET['id'] ?? -1;

        if ($id_listing == -1) {
            echo json_encode([]);
            exit;
        }

        $images = $this->modelImages->getImagesByListing($id_listing);

        header('Content-Type: application/json');
        echo json_encode($images);
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
        if($title == ""){
            $_SESSION['error'][] = "Titolo del libro non valido ";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $isbn = $_POST['isbn'] ?? "";
        if (!isValidISBN($isbn)) {
            $_SESSION['error'][] = "ISBN non valido (deve essere di 13 caratteri)";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $vol = $_POST['vol'] ?? "";
        if($vol !== "U" && $vol !== "1" && $vol !== "2" && $vol !== "3"){
            $_SESSION['error'][] = "Volume non valido solo (U, 1, 2 o 3)";
            header("Location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $author = $_POST['author'] ?? "";
        if($author == ""){
            $_SESSION['error'][] = "Nome di Autore non valido";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $class = $_POST['class'] ?? "";
        if (!classExist($class)) {
            $_SESSION['error'][] = "Classe non esistente, scelgi una classe presente nel elenco delle classi del ISIT";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $subject = $_POST['subject'] ?? "";
        /*if(!subjectExist($subject)){
            $_SESSION['error'][] = "Materia non esiste";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }*/

        $faculty = $_POST['faculty'] ?? "";
        /* if(!facultyExist($faculty)){
            $_SESSION['error'][] = "Indirizzo non esiste";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        } */

        $price = $_POST['price'] ?? -1;
        if ($price == -1 || $price < 0) {
            $_SESSION['error'][] = "Prezzo non valido";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $publish = $_POST['publish'] ?? "";
        if($publish == ""){
            $_SESSION['error'][] = "Casa editrice non valida";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $this->modelBook->getOrCreateBook($title, $isbn, $vol, $author, $class, $subject, $publish, $faculty, $price);

        $_SESSION['success'][] = "Libro aggiunto al catalogo!";
        header("location:index.php?table=Listings&action=createListings");
        exit;
    }

}

?>