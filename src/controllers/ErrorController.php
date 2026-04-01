<?php 
defined("APP") or die("ACESSO NEGATO");
class ErrorController{
    private $model;

    public function __construct()
    {
        $this->model = 0;
    }

    public function errorview(){
        $errors = $_SESSION['error'];
        include "views/Error.php";
    }
}
?>