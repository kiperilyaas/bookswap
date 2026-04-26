<?php 
defined("APP") or die("ACCESSO NEGATO");
require_once("models/UserModel.php");
require_once "../utils/function.php";
class LoginController{
    private $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function login(){
        include "views/Login.php";
    }

    public function check(){
        //email check
        $email = $_POST['email'] ?? null;
        $domain = substr($email, strpos($email, '@') + 1);

        if($email != null){
            if($domain != "isit100.fe.it"){
                $_SESSION["error"][] = "Dominio email non verificato. Usa un'email @isit100.fe.it";
                header("location: index.php?table=login&action=login");
                exit;
            }
        }

        if(!isEmailExist($email)){
            $_SESSION["error"][] = "Email non registrata nel sistema";
            header("location: index.php?table=login&action=login");
            exit;
        }

        //password check
        $password = $_POST['password'] ?? null;
        $credenziali = $this->model->findUserByMail([$email]);

        if(password_verify($password, $credenziali[0]['password'])){
            $_SESSION['id_user'] = $credenziali[0]['id_user'];
            $_SESSION['success'][] = "Login effettuato con successo!";
            header("location: index.php");
            exit;
        }
        else{
            $_SESSION['error'][] = "Password non corretta";
            header("location: index.php?table=login&action=login");
            exit;
        }
    }

    public function register(){
        include "views/Register.php";
    }

    public function insert(){
        $name = $_POST['name'] ?? null;
        $name = strtoupper($name);
        
        $surname = $_POST['surname'] ?? null;
        $surname = strtoupper($surname);

        $class = $_POST['class'] ?? null;
        $email = $_POST['email'] ?? null;

        if(isEmailExist($email)){
            $_SESSION["error"][] = "Email già registrata";
            header("location: index.php?table=login&action=register");
            exit;
        }

        $password = $_POST['password'] ?? null;

        //check name and surname
        $url = "https://lab.isit100.fe.it/api/studenti.php?elencoclassi";
        $response = file_get_contents($url);
        $classi = json_decode($response, true);

        $isGood = false;
        foreach($classi as $classe){
            if($classe['classe'] == $class){
                $isGood = true;
                break;
            }
            else $isGood = false;
        }
        if(!$isGood){
            $_SESSION['error'][] = "Classe non esistente. Verifica il formato (es: 5N)";
            header("location: index.php?table=login&action=register");
            exit;
        }

        $domain = substr($email, strpos($email, '@') + 1);
        if($domain != "isit100.fe.it"){
            $_SESSION['error'][] = "Dominio email non verificato. Usa un'email @isit100.fe.it";
            header("location: index.php?table=login&action=register");
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $param = [$name, $surname, $class, $email, $password_hash];
        $this->model->insertRecord($param);

        $_SESSION['success'][] = "Registrazione completata! Ora puoi effettuare il login";
        header("location: index.php?table=login&action=login");
        exit;
    }

    public function logout(){
        session_destroy();
        header("location: index.php");
    }
}

?>