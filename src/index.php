<?php 
define("APP", true);
session_start();

if(!isset($_SESSION['error'])){
    $_SESSION['error'] = [];
}

if(isset($_GET['action']) || isset($_GET['table'])){

    if($_GET['action'] == "login"){
        if(isset($_SESSION['id_user'])){
            $action = "index";
            $table = "home";
        }
        else{
            $action = "login";
            $table = "login";
        }
    }
    else{
        $action = $_GET['action'] ?? "index";
        $table = $_GET['table'] ?? "home";
    }
}
else{
    $action = "index";
    $table = "home";
}

$filenameController = ucfirst($table).'Controller';
require_once "controllers/{$filenameController}.php";
$controller = new $filenameController();

if(method_exists($controller, $action)){
    $controller->$action();
}
else{
    die("METODO INESISTENTE");
}

?>