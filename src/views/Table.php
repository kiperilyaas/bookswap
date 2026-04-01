<?php

defined("APP") or die("ACCESSO NEGATO");
/*

if(!empty($table)){
    $keys = array_keys($table[0]); #prende tutte le chiavi della tabella

    echo "<table border = 0>";
    echo "<tr>";
    foreach($table as $record){ #cerca nella tabella ogni record
        echo "<tr>";
        foreach($record as $posto){ #cerca per ogni record il valore
            echo "<td>$posto</td>"; #stampa
        }
        echo "</tr>";
    }
    echo "</table>";
}


if(!empty($table)){
    echo "<table class='blue-table'>";

    // Intestazioni (TH)
    echo "<thead><tr>";
    $keys = array_keys($table[0]);
    foreach($keys as $key){
        // Salta gli ID anche nelle intestazioni
        if (strpos($key, 'id') === false) {
            echo "<th>" . strtoupper($key) . "</th>";
        }
    }
    echo "</tr></thead>";

    // Dati (TD)
    echo "<tbody>";
    foreach($table as $record){
        echo "<tr>";
        foreach($record as $key => $posto){
            if (strpos($key, 'id') === false) {
                // Aggiungiamo una classe speciale se è il titolo per gestirlo meglio nel CSS
                $class = ($key == 'title') ? "class='title'" : "";
                echo "<td $class>$posto</td>";
            }
        }
        echo "</tr>";
    }
    echo "</tbody></table>";
}


// Esempio di come stampare un "cart" (scheda libro) nel tuo loop

foreach($table as $record){
    echo "<div class='book-cart'>";
    echo "  <div class='book-info'>";
    echo "    <h3 class='book-title'>" . $record['title'] . "</h3>";
    echo "    <p class='book-author'>Autore: " . $record['author'] . "</p>";
    echo "    <span class='book-isbn'>ISBN: " . $record['isbn'] . "</span>";
    echo "  </div>";
    echo "  <div class='book-actions'>";
    echo "    <button class='btn-add'>Aggiungi</button>";
    echo "  </div>";
    echo "</div>";
}
*/

// Ciclo per stampare ogni scheda libro
foreach($table as $record){
    echo "<div class='book-cart'>";
    
    // --- 1. SPAZIO PER L'IMMAGINE (A MANO) ---
    echo "  <div class='book-image-container'>";
    // Se hai un campo nel database con il nome del file, usa: <img src='img/".$record['foto']."' alt='Copertina'>
    // Altrimenti, per ora lasciamo il segnaposto:
    echo "    <div class='image-placeholder'>Foto<br>Libro</div>"; 
    echo "  </div>";

    // --- 2. INFORMAZIONI LIBRO ---
    echo "  <div class='book-info'>";
    echo "    <h3 class='book-title'>" . $record['title'] . "</h3>";
    echo "    <p class='book-author'>Autore: " . $record['author'] . "</p>";
    echo "    <span class='book-isbn'>ISBN: " . $record['isbn'] . "</span>";
    echo "  </div>";

    // --- 3. AZIONI ---
    echo "  <div class='book-actions'>";
    echo "    <button class='btn-add'>Aggiungi</button>";
    echo "  </div>";

    echo "</div>";
}