<?php 
defined("APP") or die("ACCESSO NEGATO"); 

// ==========================================
// 1. ESTRAZIONE E PREPARAZIONE DI TUTTI I DATI
// ==========================================
$annunciPreparati = [];

if (!empty($table) && is_array($table)) {
    foreach ($table as $record) {
        if (!is_array($record)) continue; 

        // Dati di base
        $titolo      = $record['title'] ?? 'Title Unknown';
        $imagePath   = "../utils/immagini/prova_libro.png";
        $idItem      = $record['id_listing'] ?? ($record['id_book'] ?? ($record['id'] ?? ''));
        
        // Estrazione dati venditore (tabella users)
        $nomeVenditore    = $record['Name'] ?? ($record['name'] ?? 'Unknown');
        $cognomeVenditore = $record['Surname'] ?? ($record['surname'] ?? '');
        $venditore = trim($nomeVenditore . ' ' . $cognomeVenditore);

        // Disponibilità
        $isAvailable = $record['is_available'] ?? 1;
        $statusText  = ($isAvailable == 1) ? "Available" : "Not Available";
        $statusColor = ($isAvailable == 1) ? "text-success" : "text-danger";

        $dettagliExtra = [];
        $prezzoLibro = null;

        // Parole e chiavi da NASCONDERE dal ciclo generico
        $daIgnorare = [
            'id', 'created', 'title', 'is_available', 
            'image', 'img', 'cover', 
            'author', 'isbn', 'vol', 'school_year',
            'name', 'surname', 'email' // Nascondiamo i dati dell'utente dal ciclo extra
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

            // Se troviamo il prezzo, lo isoliamo
            if ($keyLower === 'price') {
                $prezzoLibro = $value;
                continue;
            }

            // Pulisce l'etichetta rimuovendo gli underscore (es. 'book_condition' -> 'Book condition')
            $label = ucfirst(str_replace('_', ' ', $key));
            $dettagliExtra[$label] = $value;
        }

        $annunciPreparati[] = [
            'titolo'        => $titolo,
            'immagine'      => $imagePath,
            'idItem'        => $idItem,
            'statusText'    => $statusText,
            'statusColor'   => $statusColor,
            'prezzo'        => $prezzoLibro,
            'venditore'     => $venditore,
            'dettagliExtra' => $dettagliExtra
        ];
    }
}
?>

<?php if (!empty($annunciPreparati)): ?>
    
    <?php foreach ($annunciPreparati as $annuncio): ?>
        
        <div class="col">
            <div class="card book-card">
                
                <img src="<?= htmlspecialchars($annuncio['immagine']) ?>" class="card-img-top book-img" alt="Cover">

                <div class="card-body d-flex flex-column p-3">
                    
                    <h5 class="card-title text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= htmlspecialchars($annuncio['titolo']) ?>
                    </h5>

                    <p class="mb-2 text-muted" style="font-size: 0.85rem;">
                        👤 Venditore: <strong><?= htmlspecialchars($annuncio['venditore']) ?></strong>
                    </p>

                    <div class="mb-2">
                        <?php if ($annuncio['prezzo'] !== null && $annuncio['prezzo'] > 0): ?>
                            <span class="price">€ <?= number_format((float)$annuncio['prezzo'], 2, ',', '.') ?></span>
                        <?php else: ?>
                            <span class="price text-success" style="font-size: 1.2rem;">Exchange</span>
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
                            🛒 Add to cart
                        </a>
                    </div>

                </div>
            </div>
        </div>

    <?php endforeach; ?>

<?php else: ?>
    <div class="col-12 text-center py-5 w-100">
        <h4 class="text-muted">No books available at the moment.</h4>
        <p>Come back later or publish a new listing!</p>
    </div>
<?php endif; ?>