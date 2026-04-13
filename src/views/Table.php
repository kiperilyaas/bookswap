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

// Card stile Amazon con Bootstrap
foreach($table as $record){
    echo "<div class='col'>";
    echo "  <div class='card book-card h-100'>";

    // Immagine libro
    echo "    <img src='https://via.placeholder.com/200x300/f8f9fa/6c757d?text=".urlencode($record['title'])."' class='card-img-top book-img' alt='Copertina'>";

    echo "    <div class='card-body d-flex flex-column'>";
    // Titolo
    echo "      <h6 class='card-title text-truncate' title='".$record['title']."'>".$record['title']."</h6>";

    // Autore
    echo "      <p class='card-text text-muted small mb-2'>".$record['author']."</p>";

    // Prezzo (se disponibile nel database, altrimenti placeholder)
    $price = isset($record['price']) ? $record['price'] : '12.99';
    echo "      <p class='price mb-2'>€ ".$price."</p>";

    // ISBN piccolo
    echo "      <p class='text-muted small mb-3'>ISBN: ".$record['isbn']."</p>";

    // Bottone aggiungi al carrello
    echo "      <button class='btn btn-warning w-100 mt-auto'>Aggiungi al carrello</button>";
    echo "    </div>";

    echo "  </div>";
    echo "</div>";
}