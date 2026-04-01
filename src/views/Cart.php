<?php 
defined("APP") or die("ACESSO NEGATO");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrello - Nome Azienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0f5fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        /* Stile Header (Immagine 2) */
        .navbar-custom {
            background-color: #004085;
            padding: 1rem 2rem;
        }
        .navbar-brand {
            color: white !important;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
        }

        /* Titolo sezione */
        .page-title {
            color: #004085;
            font-weight: bold;
            border-bottom: 3px solid #004085;
            display: inline-block;
            margin-bottom: 2rem;
        }

        /* Container Carrello (Immagine 1) */
        .cart-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #dee2e6;
            overflow: hidden;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .book-info {
            color: #333;
            font-weight: 500;
        }

        /* Icona Cestino */
        .btn-delete {
            color: #dc3545;
            background: none;
            border: none;
            font-size: 1.2rem;
            transition: transform 0.2s;
        }
        .btn-delete:hover {
            transform: scale(1.2);
            color: #a71d2a;
        }

        /* Bottone Compra (Posizionato in basso a destra) */
        .btn-compra {
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            padding: 0.75rem 3rem;
            border: none;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-compra:hover {
            background-color: #0056b3;
            color: white;
        }

        footer {
            background: white;
            padding: 1rem;
            text-align: center;
            margin-top: auto;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom mb-4">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand">NOME AZIENDA</a>
            <div class="text-white d-none d-md-block">
                <small class="ms-3">VENDI</small>
                <small class="ms-3">LOGIN</small>
                <small class="ms-3 text-warning fw-bold">CARRELLO</small>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <h2 class="page-title">Carrello</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="cart-card mb-4">
                    <div class="cart-item">
                        <span class="book-info">Il Signore degli Anelli - J.R.R. Tolkien</span>
                        <button class="btn-delete" title="Rimuovi dal carrello">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>

                    <div class="cart-item">
                        <span class="book-info">1984 - George Orwell</span>
                        <button class="btn-delete" title="Rimuovi dal carrello">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>

                    <div class="cart-item">
                        <span class="book-info">Il Piccolo Principe - Antoine de Saint-Exupéry</span>
                        <button class="btn-delete" title="Rimuovi dal carrello">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                    
                    </div>

                <div class="d-flex justify-content-end mt-4">
                    <form action="index.php?action=checkout" method="post">
                        <button type="submit" class="btn-compra">
                            Compra
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer>
        © 2026 Nome Azienda - Pagina Carrello
    </footer>

</body>
</html>