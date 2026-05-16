<?php 
defined("APP") or die("ACCESSO NEGATO");
require_once("models/UserModel.php");
require_once "../utils/function.php";

/**
 * Summary of LoginController
 * Il controller che gestisce l'autenticazione di utente
 */
class LoginController{
    private $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function loginView(){
        include "views/Login.php";
    }
    public function registerView(){
        include "views/Register.php";
    }
    public function logout(){
        session_destroy();
        header("location: index.php");
    }

    /**
     * Summary of check
     * Verifica della mail e password
     */
    public function check(){
        //email check
        $email = $_POST['email'] ?? null;
        $domain = substr($email, strpos($email, '@') + 1);

        //verifica del dominio
        if($email != null){
            if($domain != "isit100.fe.it"){
                $_SESSION["error"][] = "Il dominio dell'email non è verificato. Utilizza un'email @isit100.fe.it";
                header("location: index.php?table=login&action=loginView");
                exit;
            }
        }

        //verifica della presenze della mail nel DB
        if(!isEmailExist($email)){
            $_SESSION["error"][] = "L'email non è registrata nel sistema";
            header("location: index.php?table=login&action=loginView");
            exit;
        }

        //password check
        $password = $_POST['password'] ?? null;
        if(!$password){
            $_SESSION['error'][] = "La password non è stata inserita";
            header("index.php?table=Login&action=loginView");
            exit;
        }

        $credenziali = $this->model->findUserByMail([$email]);

        if(password_verify($password, $credenziali[0]['password'])){
            $_SESSION['id_user'] = $credenziali[0]['id_user'];
            $_SESSION['success'][] = "Login effettuato con successo!";
            header("location: index.php");
            exit;
        }
        else{
            $_SESSION['error'][] = "La password non è corretta";
            header("location: index.php?table=login&action=loginView");
            exit;
        }
    }

    /**
     * Summary of insert
     * Funzione di inserimento di un nuovo utente
     * @param $name
     * @param $surname
     * @param $email
     * @param $class
     * @param $password
     */
    public function insert(){
        $name = $_POST['name'] ?? null;
        $name = strtoupper($name);
        if(!$name){
            $_SESSION["error"][] = "Il nome non è stato inserito corretamente";
            header("location: index.php?table=Login&action=registerView");
            exit;
        }

        $surname = $_POST['surname'] ?? null;
        $surname = strtoupper($surname);
        if(!$surname){
            $_SESSION["error"][] = "Il cognome non è stato inserito correttamente";
            header("location: index.php?table=Login&action=registerView");
            exit;
        }

        $email = $_POST['email'] ?? null;
        if(isEmailExist($email)){
            $_SESSION["error"][] = "L'email è già stata registrata";
            header("location: index.php?table=login&action=registerView");
            exit;
        }
        $domain = substr($email, strpos($email, '@') + 1);
        if($domain != "isit100.fe.it"){
            $_SESSION['error'][] = "Il dominio dell'email non è verificato. Utilizza un'email @isit100.fe.it";
            header("location: index.php?table=login&action=registerView");
            exit;
        }

        $class = $_POST['class'] ?? "";
        if(!$class){
            $_SESSION["error"][] = "La classe non è stata inserita correttamente";
            header("location: index.php?table=Login&action=registerView");
            exit;
        }
        
        //verifica della classe all'interno della scuola uttilizzando API della scuola
        $url = "https://lab.isit100.fe.it/api/studenti.php?elencoclassi";
        $response = file_get_contents($url);
        $clases = json_decode($response, true);

        $isGood = false;
        foreach($clases as $item){
            if($item['classe'] == $class){
                $isGood = true;
                break;
            }
            else $isGood = false;
        }
        if(!$isGood){
            $_SESSION['error'][] = "Classe inesistente. Verifica il formato (es: 5N)";
            header("location: index.php?table=login&action=registerView");
            exit;
        }

        $password = $_POST['password'] ?? null;
        if(!$password){
            $_SESSION["error"][] = "La password non è stata inserita correttamente";
            header("location: index.php?table=Login&action=registerView");
            exit;
        }
        if(strlen($password) < 6){
            $_SESSION["error"][] = "La password deve essere di almeno 6 caratteri";
            header("location: index.php?table=Login&action=registerView");
            exit;
        }

        $confirmPassword = $_POST['confirm_password'] ?? null;
        if(!$confirmPassword){
            $_SESSION["error"][] = "La conferma password non è stata inserita";
            header("location: index.php?table=Login&action=registerView");
            exit;
        }
        if($password !== $confirmPassword){
            $_SESSION["error"][] = "Le password non coincidono";
            header("location: index.php?table=Login&action=registerView");
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $param = [$name, $surname, $class, $email, $password_hash];
        $this->model->insertRecord($param);

        $_SESSION['success'][] = "Registrazione completata! Ora puoi effettuare il login";
        header("location: index.php?table=login&action=loginView");
        exit;
    }

    
}

?>