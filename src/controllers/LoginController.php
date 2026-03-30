<?php 
defined("APP") or die("ACCESSO NEGATO");
require_once("models/user_model.php");


class LoginController{
    private $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function login(){
        include "views/login.php";
    }

    public function check(){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $credenziali = $this->model->findUserByMail([$email]);

        if(password_verify($password, $credenziali[0]['password'])){
            $_SESSION['id_user'] = $credenziali[0]['id_user'];
            header("location: index.php");
            exit;
        }
        else{
            header("location: index.php?table=login&action=loginError");
            exit;
        }
    }

    public function register(){
        include "views/register.php";
    }

    public function insert(){
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $class = $_POST['class'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $param = [$name, $surname, $class, $email, $password_hash];
        $this->model->insertRecord($param);

        header("location: index.php?table=login&action=login");
        exit;
    }
}

?>