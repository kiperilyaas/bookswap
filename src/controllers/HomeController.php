<?php
defined("APP") or die("ACCESSO NEGATO");

class HomeController{

    private $model;
    public function __construct()
    {
        $this->model = 0;
    }

    public function index(){
        include "views/homePage.php";
    }
}

?>
