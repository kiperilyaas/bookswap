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
    <style>
        :root {
            --bs-orange: #ff9900;
            --bs-dark: #131921;
            --bs-bg: #eaeded;
        }

        body {
            background-color: var(--bs-bg);
            font-family: "Segoe UI", Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: var(--bs-dark) !important;
            padding: 0.5rem 1rem;
        }
        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }

        .main-container {
            padding-top: 2rem;
            padding-bottom: 3rem;
        }

        .checkout-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--bs-dark);
            margin-bottom: 1.5rem;
        }

        .book-summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .book-summary h5 {
            font-weight: 700;
            color: var(--bs-dark);
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .btn-complete-order {
            background-color: var(--bs-orange);
            border: none;
            color: black;
            font-weight: 700;
            border-radius: 20px;
            padding: 12px 30px;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-complete-order:hover {
            filter: brightness(0.95);
            transform: scale(1.02);
        }

        footer {
            background-color: var(--bs-dark);
            color: white;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand ms-3" href="index.php">📚 BookSwap</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item"><a class="nav-link" href="index.php">Torna alla Home</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
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
                            <input type="hidden" name="id_listing" value="<?= htmlspecialchars($listing['id_listing'] ?? '') ?>">
                            <input type="hidden" name="id_seller" value="<?= htmlspecialchars($listing['id_seller'] ?? '') ?>">
                            <input type="hidden" name="final_price" value="<?= htmlspecialchars($price) ?>">

                            <div class="mb-3">
                                <label for="time_meet" class="form-label">
                                    <i class="bi bi-clock"></i> Orario di incontro
                                </label>
                                <input type="datetime-local" class="form-control" id="time_meet" name="time_meet" required>
                                <small class="text-muted">Quando vuoi incontrare il venditore?</small>
                            </div>

                            <div class="mb-3">
                                <label for="place_meet" class="form-label">
                                    <i class="bi bi-geo-alt"></i> Luogo di incontro
                                </label>
                                <input type="text" class="form-control" id="place_meet" name="place_meet"
                                       placeholder="Es: Ingresso principale " required>
                            </div>

                            <div class="mb-4">
                                <label for="description_meet" class="form-label">
                                    <i class="bi bi-chat-left-text"></i> Note aggiuntive (opzionale)
                                </label>
                                <textarea class="form-control" id="description_meet" name="description_meet"
                                          rows="3" placeholder="Aggiungi eventuali dettagli o richieste..."></textarea>
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

    <footer class="text-center py-4">
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-muted">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
