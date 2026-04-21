<?php 
<<<<<<< HEAD
#defined("APP") or die("ACCESSO NEGATO");
=======
defined("APP") or die("ACCESSO NEGATO");
>>>>>>> 19f8773144a52c13e2f29f30f7bff9bc4bb06e72
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrello | BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            /* Palette colori coerente con la Home */
            --bs-orange: #ff9900; 
            --bs-dark: #131921;
            --bs-bg: #eaeded;
            /* Blu saturo e vibrante */
            --bs-text-blue-vibrant: #4d94ff; 
            --bs-available-green: #007600;
        }

        body {
            background-color: var(--bs-bg);
            font-family: "Segoe UI", Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar */
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

        /* Card Carrello */
        .cart-card {
            background: white;
            border-radius: 10px; 
            padding: 25px;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        /* Titolo della sezione più grande e in grassetto pesante */
        .section-title {
            font-size: 2.2rem; 
            font-weight: 800; /* Grassetto più marcato */
            color: var(--bs-dark);
            margin-bottom: 1.5rem;
        }

        /* Layout Articoli */
        .cart-item {
            display: flex;
            padding: 1.5rem 0;
            border-top: 1px solid #eee;
            gap: 20px;
        }

        .book-cover-wrapper {
            width: 100px;
            height: 140px;
            background-color: #f8f8f8;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #eee;
            flex-shrink: 0;
            overflow: hidden;
        }
        .book-cover-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex-grow: 1;
        }

        /* Titolo libro: Extra Bold e Blu Saturo */
        .book-title {
            color: var(--bs-text-blue-vibrant);
            font-size: 1.3rem;
            font-weight: 800; /* Grassetto massimo */
            text-decoration: none;
            display: block;
        }
        .book-title:hover {
            color: #c45500;
            text-decoration: underline;
        }

        .seller-info {
            font-size: 0.85rem;
            color: #555;
            margin-top: 4px;
        }

        .stock-status {
            color: var(--bs-available-green);
            font-size: 0.85rem;
            margin-top: 5px;
            font-weight: 600;
        }

        /* Pulsante Rimuovi con Emoji */
        .btn-remove {
            background-color: #fdfdfd;
            border: 1px solid #ddd;
            color: #444;
            font-size: 0.85rem;
            padding: 5px 12px;
            border-radius: 6px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            font-weight: 500;
        }
        .btn-remove:hover {
            background-color: #fff1f1;
            border-color: #f5c2c2;
            color: #b02a37;
        }

        /* Pulsante 'Compra' */
        .btn-bs-orange {
            background-color: var(--bs-orange);
            border: none;
            color: black;
            font-weight: 700;
            border-radius: 20px;
            padding: 12px 20px;
            width: 100%;
            transition: transform 0.1s;
        }
        .btn-bs-orange:hover {
            filter: brightness(0.95);
        }

        .price-text {
            font-size: 1.3rem;
            font-weight: 800; /* Prezzo più evidente */
            color: #333;
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
            <a class="navbar-brand ms-3" href="../index.php">📚 BookSwap</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Torna alla Home</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="cart-card">
                    <h2 class="section-title">Il tuo Carrello</h2>
                    
                    <div class="cart-item">
                        <div class="book-cover-wrapper">
                            <img src="https://via.placeholder.com/100x140?text=Cover" alt="Copertina">
                        </div>
                        <div class="item-details">
                            <a href="#" class="book-title">Il Signore degli Anelli - J.R.R. Tolkien</a>
                            <div class="seller-info">Venditore: <b>Mario Rossi</b></div>
                            <div class="stock-status">Available</div>
                            
                            <form action="../index.php?action=removeFromCart" method="post" class="m-0">
                                <input type="hidden" name="book_id" value="1">
                                <button type="submit" class="btn-remove">
                                    🗑️ Rimuovi
                                </button>
                            </form>
                        </div>
                        <div class="price-text">€ 26,00</div>
                    </div>

                    <div class="cart-item">
                        <div class="book-cover-wrapper">
                            <img src="https://via.placeholder.com/100x140?text=Cover" alt="Copertina">
                        </div>
                        <div class="item-details">
                            <a href="#" class="book-title">1984 - George Orwell</a>
                            <div class="seller-info">Venditore: <b>Luigi Verdi</b></div>
                            <div class="stock-status">Available</div>
                            
                            <form action="../index.php?action=removeFromCart" method="post" class="m-0">
                                <input type="hidden" name="book_id" value="2">
                                <button type="submit" class="btn-remove">
                                    🗑️ Rimuovi
                                </button>
                            </form>
                        </div>
                        <div class="price-text">€ 15,00</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="cart-card">
                    <h5 class="mb-3 fw-bold">Riepilogo Ordine</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotale (2 articoli):</span>
                        <span class="fw-bold">€ 41,00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Spedizione:</span>
                        <span class="text-success fw-bold">GRATIS</span>
                    </div>
                    <hr>
                    <form action="../index.php?action=checkout" method="post">
                        <button type="submit" class="btn-bs-orange mb-3">
                            Procedi all'ordine
                        </button>
                    </form>
                    <p class="small text-muted text-center mb-0">
                        <i class="bi bi-shield-lock"></i> Pagamento protetto
                    </p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>