<?php 
defined("APP") or die("ACCESSO NEGATO"); 

// ==========================================
// 1. ESTRAZIONE E PREPARAZIONE DI TUTTI I DATI
// ==========================================
$annunciPreparati = [];

if (!empty($table) && is_array($table)) {
    foreach ($table as $record) {
        if (!is_array($record)) continue; 

        $titolo      = $record['title'] ?? 'Titolo Sconosciuto';
        $imagePath   = "../utils/immagini/prova_libro.png";
        $idItem      = $record['id_listing'] ?? ($record['id_book'] ?? ($record['id'] ?? ''));
        
        $isAvailable = $record['is_available'] ?? 1;
        $statusText  = ($isAvailable == 1) ? "Disponibile" : "Non disponibile";
        $statusColor = ($isAvailable == 1) ? "text-success" : "text-danger";

        $dettagliExtra = [];
        $prezzoLibro = null;

        // Parole e chiavi da NASCONDERE
        $daIgnorare = [
            'id', 'created', 'title', 'titolo', 'is_available', 'disponibilita', 
            'image', 'img', 'copertina', 
            'author', 'autore', 'isbn', 'vol', 'volume', 'school_year', 'school year' // <--- Aggiunti qui!
        ];

        // Piccolo dizionario per TRADURRE in italiano le chiavi del DB
        $traduzioni = [
            'Price' => 'Prezzo',
            'Condition' => 'Condizioni',
            'Conditions' => 'Condizioni',
            'Description' => 'Descrizione',
            'Class' => 'Classe',
            'Faculty' => 'Indirizzo',
            'Subject' => 'Materia'
        ];

        foreach ($record as $key => $value) {
            $keyLower = strtolower($key);
            
            // Controllo se la chiave contiene una delle parole da ignorare
            $saltaCampo = false;
            foreach ($daIgnorare as $ignore) {
                if (str_contains($keyLower, $ignore)) {
                    $saltaCampo = true;
                    break;
                }
            }
            if ($saltaCampo) continue;

            // Se troviamo il prezzo, lo isoliamo per stamparlo grande con lo stile Amazon
            if ($keyLower === 'price' || $keyLower === 'prezzo') {
                $prezzoLibro = $value;
                continue;
            }

            // Pulisce e traduce l'etichetta
            $labelOriginale = ucfirst(str_replace('_', ' ', $key));
            $labelItaliano = $traduzioni[$labelOriginale] ?? $labelOriginale;
            
            $dettagliExtra[$labelItaliano] = $value;
        }

        $annunciPreparati[] = [
            'titolo'        => $titolo,
            'immagine'      => $imagePath,
            'idItem'        => $idItem,
            'statusText'    => $statusText,
            'statusColor'   => $statusColor,
            'prezzo'        => $prezzoLibro,
            'dettagliExtra' => $dettagliExtra
        ];
    }
}
?>

<?php if (!empty($annunciPreparati)): ?>
    
    <?php foreach ($annunciPreparati as $annuncio): ?>
        
        <div class="col">
            <div class="card book-card">
                
                <img src="<?= htmlspecialchars($annuncio['immagine']) ?>" class="card-img-top book-img" alt="Copertina">

                <div class="card-body d-flex flex-column p-3">
                    
                    <h5 class="card-title text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= htmlspecialchars($annuncio['titolo']) ?>
                    </h5>

                    <div class="mb-2">
                        <?php if ($annuncio['prezzo'] !== null && $annuncio['prezzo'] > 0): ?>
                            <span class="price">€ <?= number_format((float)$annuncio['prezzo'], 2, ',', '.') ?></span>
                        <?php else: ?>
                            <span class="price text-success" style="font-size: 1.2rem;">Scambio</span>
                        <?php endif; ?>
                    </div>

                    <p class="mb-2 small">
                        <span class="<?= $annuncio['statusColor'] ?> fw-bold">
                            ● <?= $annuncio['statusText'] ?>
                        </span>
                    </p>

                    <div class="small text-muted mb-3">
                        <?php 
                        $count = 0;
                        foreach ($annuncio['dettagliExtra'] as $label => $valore): 
                            // Opzionale: mostriamo solo massimo 3 dettagli per mantenere la card pulita
                            if($count >= 3) break; 
                        ?>
                            <div class="text-truncate">
                                <strong><?= htmlspecialchars($label) ?>:</strong> <?= htmlspecialchars($valore) ?>
                            </div>
                        <?php 
                            $count++;
                        endforeach; 
                        ?>
                    </div>

                    <div class="mt-auto">
                        <a href="index.php?action=add_to_cart&id=<?= urlencode($annuncio['idItem']) ?>" class="btn btn-warning w-100 shadow-sm d-flex justify-content-center align-items-center gap-2">
                            🛒 Aggiungi
                        </a>
                    </div>

                </div>
            </div>
        </div>

    <?php endforeach; ?>

<?php else: ?>
    <div class="col-12 text-center py-5 w-100">
        <h4 class="text-muted">Nessun libro disponibile al momento.</h4>
        <p>Torna più tardi o inserisci un nuovo annuncio!</p>
    </div>
<?php endif; ?>