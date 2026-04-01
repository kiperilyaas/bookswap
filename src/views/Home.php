<?php
defined("APP") or die("ACCESSO NEGATO");
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookSwap | Compra e Vendi Libri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --amazon-blue: #007bff; /* Azzurro vivace */
            --light-bg: #f0f2f2;    /* Grigio chiarissimo stile Amazon */
        }

        body {
            background-color: var(--light-bg);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar personalizzata azzurra */
        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid #ddd;
        }

        .navbar-brand {
            color: var(--amazon-blue) !important;
            font-weight: 800;
            text-transform: uppercase;
        }

        /* Search Bar stile Amazon */
        .search-container {
            background-color: #232f3e; /* Blu scuro per contrasto ricerca */
            padding: 10px 0;
        }

        .btn-search {
            background-color: var(--amazon-blue);
            border-color: var(--amazon-blue);
            color: white;
        }

        .btn-search:hover {
            background-color: #0056b3;
            color: white;
        }

        /* Card Libri */
        .book-card {
            transition: transform 0.2s;
            border: none;
            height: 100%;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .book-img {
            height: 250px;
            object-fit: contain; /* Mantiene le proporzioni della copertina */
            padding: 15px;
            background-color: #fff;
        }

        .price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #B12704; /* Colore prezzo tipico */
        }

        footer {
            background-color: #232f3e;
            color: white;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">BookSwap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#">Vendi</a></li>
                    <li class="nav-item mx-2"><a class="btn btn-primary btn-sm px-4" href="index.php?table=login&action=login">LOGIN</a></li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="#">
                            Carrello
                            <span class="badge rounded-pill bg-danger">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="search-container">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="input-group">
                        <button class="btn btn-light dropdown-toggle" type="button">Tutti</button>
                        <input type="text" class="form-control" placeholder="Cerca il tuo prossimo libro...">
                        <button class="btn btn-search px-4" type="button">
                             Cerca
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container my-5">
        <h3 class="mb-4">Risultati della ricerca</h3>
        
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            
                <?php include 'Table.php'; ?>
            

        </div>
    </main>

    <footer class="text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-muted">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>