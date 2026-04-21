<?php
defined("APP") or die("ACCESSO NEGATO");

// Assicurati che session_start() sia chiamato nel file principale (index.php)
// Se non lo è, aggiungilo qui:
// if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Notifiche di Errore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <?php
            // 1. Controlliamo se ci sono errori nella sessione
            if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                
                echo '<div class="alert alert-danger shadow-sm" role="alert">';
                echo '    <h4 class="alert-heading">⚠️ Si sono verificati dei problemi:</h4>';
                echo '    <hr>';
                echo '    <ul class="mb-0">';
                
                // Se error è un array, lo cicliamo
                if (is_array($_SESSION['error'])) {
                    foreach ($_SESSION['error'] as $errore) {
                        echo '<li>' . htmlspecialchars($errore) . '</li>';
                    }
                } else {
                    // Se è una stringa singola
                    echo '<li>' . htmlspecialchars($_SESSION['error']) . '</li>';
                }
                
                echo '    </ul>';
                echo '</div>';

                // 2. IMPORTANTE: Puliamo la sessione dopo aver visualizzato gli errori
                // così non appariranno più al prossimo refresh
                unset($_SESSION['error']);
                
            } else {
                // Messaggio opzionale se non ci sono errori
                echo '<div class="alert alert-info text-center shadow-sm">';
                echo '    Nessun errore da segnalare.';
                echo '</div>';
            }
            ?>

            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-primary">Torna alla Home</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>