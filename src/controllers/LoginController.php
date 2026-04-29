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
                $_SESSION["error"][] = "dominio di email non verificato";
                header("location: index.php?table=error&action=errorview");
                exit;
            }
        }        

        if(!isEmailExist($email)){
            $_SESSION["error"][] = "Email non esiste";
            header("location: index.php?table=error&action=errorview");
            exit;
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
        include "views/Register.php";
    }

    public function insert(){
        $name = $_POST['name'] ?? null;
        $surname = $_POST['surname'] ?? null;
        $class = $_POST['class'] ?? null;
        $email = $_POST['email'] ?? null;

        if(isEmailExist($email)){        
            $_SESSION["error"][] = "email esiste gia'";
            header("location: index.php?table=error&action=errorview");
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
            $_SESSION['error'][] = "classe non esistente";
            header("location: index.php?table=error&action=errorview");
            exit;
        }
        
        $domain = substr($email, strpos($email, '@') + 1);
        if($domain != "isit100.fe.it"){
            $_SESSION['error'][] = "dominio del email non verificato";
            header("location: index.php?table=error&action=errorview");
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $param = [$name, $surname, $class, $email, $password_hash];
        $this->model->insertRecord($param);

        header("location: index.php?table=login&action=login");
        exit;
    }

    public function logout(){
        session_destroy();
        header("location: index.php");
    }
}

?>