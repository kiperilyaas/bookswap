<?php
defined("APP") or die("ACCESSO NEGATO");

if(!empty($table) && is_array($table)){
    foreach($table as $record){
        if(!is_array($record)) continue;

        echo "<div class='col'>";
        echo "  <div class='card h-100 shadow-sm rounded-4 border-0'>";

        // --- PERCORSO IMMAGINE AGGIORNATO ---
        // Avendo spostato il file in utils/immagini/, il percorso corretto è questo:
        $imagePath = "../utils/immagini/prova_libro.png";

        echo "    <img src='".$imagePath."' class='card-img-top rounded-top-4' alt='Copertina' style='object-fit: cover; height: 230px; background-color: #eee;'>";

        echo "    <div class='card-body d-flex flex-column p-4'>";

        // Ciclo dei campi
        foreach($record as $key => $value){
            // Salta ID, Created_at e chiavi immagine
            if(stripos($key, 'id') !== false || 
               stripos($key, 'created') !== false || 
               in_array($key, ['image', 'img', 'copertina'])) {
                continue;
            }

            // Gestione "Stato"
            if ($key === 'is_available' || $key === 'disponibilita') {
                $label = "Stato";
                $statusText = ($value == 1) ? "Disponibile" : "Non disponibile";
                $statusColor = ($value == 1) ? "text-success" : "text-danger";
                
                echo "      <p class='mb-2 text-dark'><strong>".$label.":</strong> <span class='".$statusColor." fw-bold'>".$statusText."</span></p>";
            } else {
                // Label pulita per gli altri campi
                $label = ucfirst(str_replace('_', ' ', $key));
                echo "      <p class='mb-2 text-dark'><strong>".$label.":</strong> ".htmlspecialchars($value)."</p>";
            }
        }

        // --- BOTTONE AGGIUNGI AL CARRELLO ---
        echo "      <div class='mt-auto pt-3'>";
        echo "          <a href='index.php?action=add_to_cart&id=".($record['id'] ?? '')."' 
                           class='btn btn-dark rounded-pill py-2 w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm' 
                           style='font-weight: 600;'>";
        
        // Icona Carrello SVG
        echo '              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>';
        echo "              Aggiungi al carrello";
        echo "          </a>";
        echo "      </div>";

        echo "    </div>";
        echo "  </div>";
        echo "</div>";
    }
}
?>