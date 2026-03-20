<?php
// file di connesione al dbms
defined("APP") or die('Acesso negato');
require_once "dbconfig.php";
class DB
{
  public static function connect()
  {
    // gestione degli errori di connessione
    try {
      $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USERNAME,
        DB_PASSWORD,
        // quando c'è un errore solleva eccezzione
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
      );
      return $pdo;
    } catch (PDOException $e) {
      echo $e->getMessage();

      // Scrive l'errore nel file di log del server (es. error.log di Apache/Nginx)
      // error_log("Errore di connessione al database: " . $e->getMessage());

      // // Opzionale: Messaggio generico per l'utente (non rivela dettagli tecnici)
      // die("Si è verificato un errore tecnico. Riprova più tardi.");
    }
  }
}
