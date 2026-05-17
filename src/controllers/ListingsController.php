<?php
defined("APP") or die("ACESSO NEGATO");
require_once 'models/ListingsModel.php';
require_once "models/BookModel.php";
require_once 'models/ListingImagesModel.php';
require_once "../utils/function.php";
require_once "../utils/imageUpload.php";

/**
 * Summary of ListingsController
 * controller viene usato per gestione dei annunci sul sito
 */
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

    /**
     * Reindirizzamento sulla pagina della creazione del annuncio
     * @return void
     */
    public function createListings()
    {
        $books = $this->modelBook->selectAll();
        include "views/ListingForm.php";
    }

    /**
     * Reindirizzamento sulla pagina del inserimento di un nuovo libro
     * @return void
     */
    public function addBookForm()
    {
        include "views/AddBook.php";
    }

    /**
     * Summary of addListing
     * Una semplice funzione di inserimento di un annuncio 
     * con tutti appositi controlli
     * @param $price
     * @param $condition -> condizioni del libro
     * @param $book -> id del libro
     * @param $seller -> id del venditore
     * @param $description
     */
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
            // Recupero l'ID del listing appena creato
            $id_listing = $this->model->getLastInsertedId();

            // Gestione upload immagini
            if (isset($_FILES['listing_images']) && !empty($_FILES['listing_images']['name'][0])) {
                $uploadedImages = handleImageUpload($_FILES['listing_images'], $id_listing);

                if (!empty($uploadedImages)) {
                    //per ogni immagine presente viene eseguito il caricament nel DB.
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

    /**
     * Summary of deleteListing
     * Funzione di eliminazione di un annuncio
     */
    public function deleteListing()
    {
        // Verifica che la richiesta sia POST per sicurezza contro CSRF e azioni accidentali
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("location: index.php?table=User&action=account");
            exit;
        }

        $id = $_POST['id'] ?? -1;
        $userId = $_SESSION['id_user'] ?? -1;

        // Controllo autenticazione
        if ($userId == -1) {
            $_SESSION['error'][] = "Devi effettuare il login";
            header("location: index.php?table=login&action=login");
            exit;
        }

        if ($id == -1) {
            $_SESSION['error'][] = "L'id dell'offerta non è valido";
            header("location: index.php?table=User&action=account");
            exit;
        }

        // Verifica autorizzazione (IDOR check): l'annuncio deve appartenere all'utente loggato
        $listings = $this->model->getListingsById([$id]);
        if (empty($listings) || $listings[0]['id_seller'] != $userId) {
            $_SESSION['error'][] = "Non hai l'autorizzazione per eliminare questo annuncio";
            header("location: index.php?table=User&action=account");
            exit;
        }

        $this->model->deleteListing([$id]);
        $_SESSION['success'][] = "Annuncio eliminato con successo";
        header("location: index.php?table=User&action=account");
        exit;
    }


    /**
     * Summary of liveSearchListings
     * la funzione di ricerca a vivo usando fetch di JS.
     * @param $query La querry inserita da utente
     * @param $filter il filtro per Titolo/Autore etc
     */
    public function liveSearchListings()
    {
        $query =  $_GET['query'] ?? '';
        $filter = $_GET['filter'] ?? 'title';

        //methodo di ricerca dei annunci dal DB.
        $results = $this->model->searchBookOnly($query, $filter);

        //serve per mandare il risultato sulla pagina  
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    /**
     * Summary of liveSearchBooks
     * Funzione quasi lo stessa del liveSearchListings
     * che cerca tutti libri presenti nel catalogo del DB
     * @param $query La querry di ricerca
     * @param $filter il filtro di ricerca
     */
    public function liveSearchBooks() {
        $query = $_GET['query'] ?? '';
        $filter = $_GET['filter'] ?? 'title';
        
        //methodo di ricerca dei libri presenti nel DB
        $results = $this->model->searchBooksListings($query, $filter);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($results);
        exit;
    }

    

    /**
     * Summary of getListingImages
     * Funzione che restituisci a vivo le immagine di un annuncio.
     */
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

    /**
     * Summary of addBook
     * La funzione che aggiunge un libro al catalogo
     * @param $title
     * @param $isbn
     * @param $vol
     * @param $author
     * @param $class
     * @param $subject
     * @param $faculty
     * @param $price
     * @param $publish
     */
    public function addBook()
    {
        $title = $_POST['title'] ?? "";
        if($title == ""){
            $_SESSION['error'][] = "Il titolo del libro non è valido";
            header("location: index.php?table=Listings&action=addBookForm");
            exit;
        }

        $isbn = $_POST['isbn'] ?? "";
        // Rimuovi trattini e spazi dall'ISBN
        $isbn = str_replace(['-', ' '], '', $isbn);

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

    /**
     * API per recuperare tutte le case editrici
     */
    public function getPublishingHouses() {
        $data = $this->modelBook->getAllPublishingHouses();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * API per recuperare tutte le materie
     */
    public function getSubjects() {
        $data = $this->modelBook->getAllSubjects();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * API per recuperare tutti gli indirizzi
     */
    public function getFaculties() {
        $data = $this->modelBook->getAllFaculties();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * API per recuperare tutte le classi
     */
    public function getClasses() {
        $data = $this->modelBook->getAllClasses();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

}

?>