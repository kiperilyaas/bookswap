<?php 
defined("APP") or die("ACCESSO NEGATO");

// Controllo di sicurezza: solo gli utenti loggati possono accedere
if (!isset($_SESSION['id_user'])) {
    // Se non è loggato, salviamo l'errore e rimandiamo al login
    $_SESSION['errors'] = ["Devi effettuare il login per aggiungere un'offerta."];
    header("Location: index.php?table=login&action=login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Offerta Libro</title>
</head>
<body>

    <h1>Inserisci una nuova offerta</h1>
    <p>Compila i campi sottostanti per mettere in vendita il tuo libro.</p>

    <form action="index.php?table=libri&action=save" method="POST">
        
        <div>
            <label for="libro">Ricerca per ISBN</label><br>
            <input type="search" id="search" name="isbn" placeholder="Esempio: Informatica" required>
            <?php 
            
                

            ?>
        </div>

        <br>

        <div>
            <label for="prezzo">Prezzo (€):</label><br>
            <input type="number" id="prezzo" name="prezzo" step="0.01" min="0" placeholder="0.00" required>
        </div>

        <br>

        <div>
            <label for="condizioni">Condizioni del libro:</label><br>
            <select id="condizioni" name="condizioni" required>
                <option value="">-- Seleziona condizione --</option>
                <option value="nuovo">Nuovo (mai aperto)</option>
                <option value="quasi_nuovo">Quasi nuovo (ottime condizioni)</option>
                <option value="usato_buono">Usato - Buono (segni di usura minimi)</option>
                <option value="usato_accettabile">Usato - Accettabile (scritte o pieghe)</option>
                <option value="rovinato">Rovinato (ma leggibile)</option>
            </select>
        </div>

        <br>

        <div>
            <label for="descrizione">Descrizione dell'offerta:</label><br>
            <textarea id="descrizione" name="descrizione" rows="5" cols="40" placeholder="Aggiungi dettagli come l'edizione, se ci sono sottolineature, ecc..."></textarea>
        </div>

        <br>

        <div>
            <button type="submit">Pubblica Offerta</button>
            <button type="reset">Svuota Campi</button>
        </div>

    </form>

    <br>
    <a href="index.php">Annulla e torna alla home</a>

    <script>
        
    </script>

</body>
</html>