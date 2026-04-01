<?php 
defined("APP") or die("ACCESSO NEGATO");
require_once("models/user_model.php");

class LoginController{
    private $model;
    private $emailVerified = [
        "google.com",
        "amazon.it",
        "wikipedia.org",
        "github.com",
        "apple.com",
        "microsoft.it",
        "linkedin.com",
        "netflix.com",
        "wordpress.org",
        "italia.it",
        "yahoo.com",
        "adobe.com",
        "cloudflare.com",
        "stackoverflow.com",
        "reddit.com",
        "medium.com",
        "dropbox.com",
        "spotify.com",
        "zoom.us",
        "nasa.gov"   
    ];

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function login(){
        include "views/login.php";
    }

    public function check(){
        //email check
        $email = $_POST['email'] ?? null;
        if($email != null){
            if(!in_array($email, $this->emailVerified)){
                $_SESSION["error"][] = "dominio di email non verificato";
                header("location: index.php?table=error&action=errorview");
                exit;
            }
        }
        //password check
        $password = $_POST['password'] ?? null;
        $credenziali = $this->model->findUserByMail([$email]);

        if(password_verify($password, $credenziali[0]['password'])){
            $_SESSION['id_user'] = $credenziali[0]['id_user'];
            header("location: index.php");
            exit;
        }
        else{
            $_SESSION['error'][] = "La password non e' valida";
            header("location: index.php?table=error&action=errorview");
            exit;
        }
    }

    public function register(){
        include "views/register.php";
    }

    public function insert(){
        $name = $_POST['name'] ?? null;
        $surname = $_POST['surname'] ?? null;
        $class = $_POST['class'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        //check name and surname
        $url = "https://lab.isit100.fe.it/api/studenti.php?elencoclassi";
        $response = file_get_contents($url);
        $classi = json_decode($response, true);

        foreach($classi as $classe){
            if(!in_array($class, $classe)){
                $_SESSION['error'][] = "Classe insesitente";
                header("location: index.php?table=error&action=error");
                exit;
            }
        }

        if(!in_array($email, $this->emailVerified)){
            $_SESSION['error'][] = "dominio del email non esiste";
            header("location: index.php?table=error&action=error");
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $param = [$name, $surname, $class, $email, $password_hash];
        $this->model->insertRecord($param);

        header("location: index.php?table=login&action=login");
        exit;
    }
}

?>