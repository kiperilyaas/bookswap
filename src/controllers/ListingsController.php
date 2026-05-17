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
        $price = floatval($_POST['prezzo'] ?? -1);
        if ($price < 0) {
            $_SESSION['error'][] = "Il prezzo dell'offerta non è valido";
            header("location: index.php?table=Listings&action=createListings");
            exit;
        }

        $condition = $_POST['condizioni'] ?? "";
        if ($condition == "") {
            $_SESSION['error'][] = "Le condizioni dell'offerta non sono valide";
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
        if (!isset($_SESSION['id_user'])) {
            $_SESSION['error'][] = "Devi effettuare il login";
            header("location: index.php?table=login&action=login");
            exit;
        }

        $id = $_POST['id'] ?? -1;
        if ($id == -1) {
            $_SESSION['error'][] = "L'id dell'offerta non è valido";
            header("location: index.php?table=User&action=account");
            exit;
        }

        // Verifica la proprietà del listing prima di eliminarlo (Prevenzione IDOR)
        $listing = $this->model->getListingsById([$id]);
        if (empty($listing) || $listing[0]['id_seller'] != $_SESSION['id_user']) {
            $_SESSION['error'][] = "Non hai i permessi per eliminare questo annuncio";
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
            $_SESSION['error'][] = "Il titolo del libro non è valido";
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
            $_SESSION['error'][] = "Il volume non è valido, solo (U, 1, 2 o 3)";
            header("Location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $author = $_POST['author'] ?? "";
        if($author == ""){
            $_SESSION['error'][] = "Il nome dell'autore non è valido";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $class = $_POST['class'] ?? "";
        if (!classExist($class)) {
            $_SESSION['error'][] = "Classe inesistente, scelgi una classe presente nell' elenco delle classi dell' ISIT";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $subject = $_POST['subject'] ?? "";
        /*if(!subjectExist($subject)){
            $_SESSION['error'][] = "Materia inesistente";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }*/

        $faculty = $_POST['faculty'] ?? "";
        /* if(!facultyExist($faculty)){
            $_SESSION['error'][] = "Indirizzo inesistente";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        } */

        $price = floatval($_POST['price'] ?? -1);
        if ($price < 0) {
            $_SESSION['error'][] = "Il prezzo nonè valido";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $publish = $_POST['publish'] ?? "";
        if($publish == ""){
            $_SESSION['error'][] = "La casa editrice non è valida";
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