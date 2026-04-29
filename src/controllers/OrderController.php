<?php
if(!defined('APP')) die('Accesso negato');

require_once 'models/OrderModel.php';
require_once 'models/ListingsModel.php';
require_once '../utils/function.php';

class OrderController {
    private $orderModel;
    private $listingsModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->listingsModel = new ListingsModel();
    }

    public function viewMyOrders(){
        $myOrders = $this->orderModel->findMyOrders($_SESSION['id_user']);
        include "views/myOrders.php";
    }

    public function checkout() {
        if(!isset($_SESSION['id_user'])) {
            $_SESSION['error'][] = "Devi effettuare il login per acquistare";
            header("location: index.php");
            exit;
        }

        $id_listing = $_GET['id'] ?? -1;
        if($id_listing == -1) {
            $_SESSION['error'][] = "Annuncio non valido";
            header("location: index.php");
            exit;
        }

        $listings = $this->listingsModel->getListingsById([$id_listing]);

        if(empty($listings)) {
            $_SESSION['error'][] = "Libro non trovato";
            header("location: index.php");
            exit;
        }

        $listing = $listings[0];

        // Verifica che non stia comprando il proprio libro
        if($listing['id_seller'] == $_SESSION['id_user']) {
            $_SESSION['error'][] = "Non puoi acquistare il tuo stesso libro!";
            header("location: index.php");
            exit;
        }

        // Verifica disponibilità
        if($listing['is_available'] != 1) {
            $_SESSION['error'][] = "Questo libro non è più disponibile";
            header("location: index.php");
            exit;
        }

        include "views/Checkout.php";
    }

    public function processCheckout() {
        // Verifica login
        if(!isset($_SESSION['id_user'])) {
            $_SESSION['error'][] = "Devi effettuare il login";
            header("location: index.php?table=login&action=login");
            exit;
        }

        $id_listing = $_POST['id_listing'] ?? -1;
        $id_seller = $_POST['id_seller'] ?? -1;
        $id_customer = $_SESSION['id_user'];
        $final_price = $_POST['final_price'] ?? 0;
        $time_meet = $_POST['time_meet'] ?? null;
        $place_meet = $_POST['place_meet'] ?? null;
        $description_meet = $_POST['description_meet'] ?? '';

        // Validazione
        if($id_listing == -1 || $id_seller == -1 || !$time_meet || !$place_meet) {
            $_SESSION['error'][] = "Tutti i campi obbligatori devono essere compilati";
            header("location: index.php?table=Order&action=checkout&id=$id_listing");
            exit;
        }

        $date_order = date('Y-m-d H:i:s');
        $state = 'pending';

        // Parametri per insertRecord: id_listing, id_customer, id_seller, final_price, date_order, state, time_meet, place_meet, description_meet
        $param = [
            $id_listing,
            $id_customer,
            $id_seller,
            $final_price,
            $date_order,
            $state,
            $time_meet,
            $place_meet,
            $description_meet
        ];

        $result = $this->orderModel->insertRecord($param);

        if($result) {
            // Aggiorna il listing come non disponibile
            $this->markListingAsUnavailable($id_listing);
            $_SESSION['success'][] = "Ordine creato con successo! Il venditore è stato notificato.";
            header("location: index.php");

        } else {
            $_SESSION['error'][] = "Errore durante la creazione dell'ordine";
            header("location: index.php?table=Order&action=checkout&id=$id_listing");
        }
        exit;
    }

    private function markListingAsUnavailable($id_listing) {
        $this->listingsModel->updateAvailability($id_listing, 0);
    }

    public function changeStateSeller(){
        $newState = $_POST['newState'] ?? null;
        if(!$newState){
            $_SESSION['error'][] = "Stato del libro non esiste";
            header("location: index.php?table=User&action=account");
            exit;
        }

        $orderId = $_POST['currentOrderId'] ?? -1;
        if($orderId == -1){
            $_SESSION['error'][] = "Ordine non esiste";
            header("location: index.php?table=User&acton=account");
            exit;
        }

        $result = $this->orderModel->changeOrderStateSeller([$newState, $orderId]);
        if(!$result){
            $_SESSION['error'][] = "Non e' stato possibile aggiornare lo stato del ordine";
            header("location: index.php?table=User&action=account");
            exit;
        }

        if(checkStateIsEqual($orderId));

        $_SESSION['success'][] = "Stato del ordine e' stato cambiato";
        header("location: index.php?table=User&action=account");
        exit;
    }

    public function changeStateCustomer(){
        $orderId = $_POST['currentOrderId'] ?? -1;
        if($orderId == -1){
            $_SESSION['error'][] = "Ordine non esiste";
            header("location: index.php?table=Order&table=viewMyOrders");
            exit;
        }

        $newState = $_POST['newState'] ?? null;
        if(!$newState){
            $_SESSION['error'][] = "Stato del libro non esiste";
            header("location: index.php?table=User&action=account");
            exit;
        }
        
        $result = $this->orderModel->changeOrderStateCustomer([$newState, $orderId]);

        if(!$result){
            $_SESSION['error'][] = "Non e' stato possibile aggiornare lo stato del ordine";
            header("location: index.php?table=Order&action=viewMyOrders");
            exit;
        }

        if(checkStateIsEqual($orderId));

        $_SESSION['success'][] = "Stato del ordine e' stato cambiato";
        header("location: index.php?table=Order&action=viewMyOrders");
        exit;

    }
}
