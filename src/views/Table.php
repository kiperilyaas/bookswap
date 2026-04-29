<?php 
defined("APP") or die("ACCESSO NEGATO"); 

// ==========================================
// 1. ESTRAZIONE E PREPARAZIONE DI TUTTI I DATI
// ==========================================
$annunciPreparati = [];

if (!empty($table) && is_array($table)) {
    foreach ($table as $record) {
        if (!is_array($record)) continue; 

        // Dati di base tradotti in italiano
        $titolo      = $record['title'] ?? 'Titolo sconosciuto';
        $imagePath   = "../utils/immagini/prova_libro.png";
        $idItem      = $record['id_listing'] ?? ($record['id_book'] ?? ($record['id'] ?? ''));
        
        // Estrazione dati venditore - Convertito in tutto minuscolo
        $nomeVenditore    = $record['Name'] ?? ($record['name'] ?? 'sconosciuto');
        $cognomeVenditore = $record['Surname'] ?? ($record['surname'] ?? '');
        $venditore = strtolower(trim($nomeVenditore . ' ' . $cognomeVenditore));

        // Dati per il modale (inclusa la classe)
        $autore    = $record['author'] ?? 'N/D';
        $isbn      = $record['isbn'] ?? 'N/D';
        $editore   = $record['publisher'] ?? ($record['editore'] ?? 'N/D');
        $classe    = $record['classe'] ?? ($record['class'] ?? 'N/D');

        // Disponibilità in italiano
        $isAvailable = $record['is_available'] ?? 1;
        $statusText  = ($isAvailable == 1) ? "Disponibile" : "Non disponibile";
        $statusColor = ($isAvailable == 1) ? "text-success" : "text-danger";

        $prezzoLibro = $record['priceOffer'] ?? ($record['priceoffer'] ?? ($record['price'] ?? null));

        $dettagliExtra = [];

        $daIgnorare = [
            'id', 'created', 'title', 'is_available', 
            'image', 'img', 'cover', 
            'author', 'isbn', 'vol', 'school_year',
            'name', 'surname', 'email', 
            'priceoffer', 'price',
            'password', 'pass', 'pwd',
            'classe', 'condizione', 'editore', 'publisher'
        ];

        $descrizionePerModale = $record['description'] ?? "";
        
        foreach ($record as $key => $value) {
            $keyLower = strtolower($key);
            $saltaCampo = false;
            foreach ($daIgnorare as $ignore) {
                if (str_contains($keyLower, $ignore)) {
                    $saltaCampo = true;
                    break;
                }
            }
            if ($saltaCampo) continue;

            $label = ucfirst(str_replace('_', ' ', $key));
            $dettagliExtra[$label] = $value;
        }

        $prezzoFinal = ($prezzoLibro !== null && $prezzoLibro > 0) ? "€ " . number_format((float)$prezzoLibro, 2, ',', '.') : "Scambio";

        $annunciPreparati[] = [
            'titolo'        => $titolo,
            'immagine'      => $imagePath,
            'idItem'        => $idItem,
            'statusText'    => $statusText,
            'statusColor'   => $statusColor,
            'prezzo'        => $prezzoFinal,
            'venditore'     => $venditore,
            'autore'        => $autore,
            'isbn'          => $isbn,
            'editore'       => $editore,
            'classe'        => $classe,
            'dettagliExtra' => $dettagliExtra,
            'descrizione'   => $descrizionePerModale ?: "Nessun dettaglio extra."
        ];
    }
}
?>

<?php if (!empty($annunciPreparati)): ?>
    
    <?php foreach ($annunciPreparati as $annuncio): ?>
        
        <div class="col">
            <div class="card book-card" 
                 data-bs-toggle="modal" 
                 data-bs-target="#bookDetailModal"
                 data-title="<?= htmlspecialchars($annuncio['titolo']) ?>"
                 data-img="<?= htmlspecialchars($annuncio['immagine']) ?>"
                 data-price="<?= htmlspecialchars($annuncio['prezzo']) ?>"
                 data-author="<?= htmlspecialchars($annuncio['autore']) ?>"
                 data-seller="<?= htmlspecialchars($annuncio['venditore']) ?>"
                 data-isbn="<?= htmlspecialchars($annuncio['isbn']) ?>"
                 data-publisher="<?= htmlspecialchars($annuncio['editore']) ?>"
                 data-classe="<?= htmlspecialchars($annuncio['classe']) ?>"
                 data-description="<?= htmlspecialchars($annuncio['descrizione']) ?>">
                
                <img src="<?= htmlspecialchars($annuncio['immagine']) ?>" class="card-img-top book-img" alt="Copertina">

                <div class="card-body d-flex flex-column p-3">
                    
                    <h5 class="card-title text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= htmlspecialchars($annuncio['titolo']) ?>
                    </h5>

                    <p class="mb-2 text-muted" style="font-size: 0.85rem;">
                        👤 Venditore: <strong><?= htmlspecialchars($annuncio['venditore']) ?></strong>
                    </p>

                    <div class="mb-2">
                        <span class="price"><?= $annuncio['prezzo'] ?></span>
                    </div>

                    <p class="mb-2 small">
                        <span class="<?= $annuncio['statusColor'] ?> fw-bold">
                            ● <?= $annuncio['statusText'] ?>
                        </span>
                    </p>

                    <div class="mt-auto">
                        <a href="index.php?action=add_to_cart&id=<?= urlencode($annuncio['idItem']) ?>" class="btn btn-warning w-100 shadow-sm d-flex justify-content-center align-items-center gap-2" onclick="event.stopPropagation();">
                            🛒 Aggiungi al carrello
                        </a>
                    </div>

                </div>
            </div>
        </div>

    <?php endforeach; ?>

<?php else: ?>
    <div class="col-12 text-center py-5 w-100">
        <h4 class="text-muted">Nessun libro disponibile al momento.</h4>
        <p>Torna più tardi o pubblica un nuovo annuncio!</p>
    </div>
<?php endif; ?>