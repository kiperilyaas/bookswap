<?php
defined("APP") or die("ACCESSO NEGATO");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="views/bookswap-responsive.css">
    <style>
        .checkout-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: var(--sp-lg);
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .book-summary {
            background-color: #f8f9fa;
            padding: var(--sp-md);
            border-radius: var(--radius-md);
            margin-bottom: var(--sp-md);
        }
        .book-summary h5 { font-weight: 700; color: var(--dark); margin-bottom: var(--sp-sm); }
        .btn-complete-order {
            background-color: var(--orange);
            border: none;
            color: black;
            font-weight: 700;
            border-radius: 20px;
            padding: clamp(0.6rem,0.9vw,1rem) clamp(1.5rem,2vw,2.5rem);
            width: 100%;
            font-size: var(--text-md);
            transition: filter 0.2s, transform 0.15s;
        }
        .btn-complete-order:hover { filter: brightness(0.94); transform: scale(1.02); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand ms-2" href="index.php">📚 BookSwap</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto me-2">
                    <li class="nav-item"><a class="nav-link" href="index.php">Torna alla Home</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-12">
                <div class="checkout-card">
                    <h2 class="section-title">Completa il tuo ordine</h2>

                    <?php if(isset($listing) && !empty($listing)): ?>
                        <div class="book-summary">
                            <h5><i class="bi bi-book"></i> Riepilogo Libro</h5>
                            <p class="mb-1"><strong>Titolo:</strong> <?= htmlspecialchars($listing['title'] ?? 'N/D') ?></p>
                            <p class="mb-1"><strong>Venditore:</strong> <?= htmlspecialchars(strtoupper(($listing['name'] ?? '') . ' ' . ($listing['surname'] ?? ''))) ?></p>
                            <p class="mb-0"><strong>Prezzo:</strong>
                                <?php
                                $price = $listing['priceOffer'] ?? $listing['price'] ?? 0;
                                echo ($price > 0) ? '€ ' . number_format($price, 2, ',', '.') : 'Scambio';
                                ?>
                            </p>
                        </div>

                        <form action="index.php?table=Order&action=processCheckout" method="post">
                            <input type="hidden" name="id_listing"  value="<?= htmlspecialchars($listing['id_listing'] ?? '') ?>">
                            <input type="hidden" name="id_seller"   value="<?= htmlspecialchars($listing['id_seller'] ?? '') ?>">
                            <input type="hidden" name="final_price" value="<?= htmlspecialchars($price) ?>">

                            <div class="mb-3">
                                <label for="time_meet" class="form-label"><i class="bi bi-clock"></i> Orario di incontro</label>
                                <input type="datetime-local" class="form-control" id="time_meet" name="time_meet" required>
                                <small class="text-muted">Quando vuoi incontrare il venditore?</small>
                            </div>
                            <div class="mb-3">
                                <label for="place_meet" class="form-label"><i class="bi bi-geo-alt"></i> Luogo di incontro</label>
                                <input type="text" class="form-control" id="place_meet" name="place_meet" placeholder="Es: Ingresso principale" required>
                            </div>
                            <div class="mb-4">
                                <label for="description_meet" class="form-label"><i class="bi bi-chat-left-text"></i> Note (opzionale)</label>
                                <textarea class="form-control" id="description_meet" name="description_meet" rows="3" placeholder="Aggiungi dettagli o richieste..."></textarea>
                            </div>

                            <button type="submit" class="btn-complete-order">
                                <i class="bi bi-check-circle"></i> Conferma Ordine
                            </button>
                        </form>

                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> Errore: libro non trovato.
                        </div>
                        <a href="index.php" class="btn btn-secondary">Torna alla Home</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-muted">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
