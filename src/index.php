<?php 
define("APP", true);
session_start();

if(isset($_GET['action'])){
    $action = $_GET['action'] ?? "index";
    $table = $_GET['table'] ?? "home";
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
    die("METODO NON ESISTE");
}

?>