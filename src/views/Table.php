<?php

defined("APP") or die("ACCESSO NEGATO");

// Componente generalizzato per stampare array in formato card Bootstrap
if(!empty($table) && is_array($table)){
    foreach($table as $record){
        if(!is_array($record)) continue;

        echo "<div class='col'>";
        echo "  <div class='card h-100'>";

        // Immagine se presente (cerca chiavi comuni per immagini)
        $imageKeys = ['image', 'img', 'picture', 'photo', 'thumbnail'];
        $imageUrl = null;
        foreach($imageKeys as $key){
            if(isset($record[$key]) && !empty($record[$key])){
                $imageUrl = $record[$key];
                break;
            }
        }

        // Se c'è un'immagine, stampala
        if($imageUrl){
            echo "    <img src='".$imageUrl."' class='card-img-top' alt='Immagine' style='object-fit: cover; height: 200px;'>";
        }

        echo "    <div class='card-body d-flex flex-column'>";

        // Stampa tutti i campi dell'array (esclusi id e immagini)
        foreach($record as $key => $value){
            // Salta campi id e immagini
            if(stripos($key, 'id') !== false || in_array($key, $imageKeys)) continue;

            // Formatta la chiave (prima lettera maiuscola, sostituisce underscore)
            $label = ucfirst(str_replace('_', ' ', $key));

            // Stampa il campo
            echo "      <p class='mb-2'><strong>".$label.":</strong> ".htmlspecialchars($value)."</p>";
        }

        echo "    </div>";
        echo "  </div>";
        echo "</div>";
    }
}