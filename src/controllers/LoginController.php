<?php 
defined("APP") or die("ACCESSO NEGATO");

class LoginController{
    private $model;

    public function __construct()
    {
        $this->model = 0;
    }

    public function login(){
        include "views/login.php";
    }

    public function check(){
        die("ENTRATO");
    }
}

?>