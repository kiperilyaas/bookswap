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
            --amazon-orange: #ff9900;
            --amazon-dark: #131921;
            --amazon-light: #232f3e;
            --light-bg: #eaeded;
        }

        body {
            background-color: var(--light-bg);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Amazon Ember', Arial, sans-serif;
        }

        /* Navbar stile Amazon */
        .navbar {
            background-color: var(--amazon-dark) !important;
            padding: 0.5rem 0;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .navbar-brand:hover {
            color: var(--amazon-orange) !important;
        }

        .nav-link {
            color: white !important;
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
        }

        .nav-link:hover {
            color: var(--amazon-orange) !important;
        }

        .btn-login {
            background-color: var(--amazon-orange);
            border: none;
            color: var(--amazon-dark);
            font-weight: 600;
            padding: 0.4rem 1.5rem;
        }

        .btn-login:hover {
            background-color: #ec8b00;
            color: var(--amazon-dark);
        }

        /* Search Bar stile Amazon */
        .search-container {
            background-color: var(--amazon-light);
            padding: 15px 0;
        }

        .search-container .input-group {
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .search-container input {
            border: none;
            padding: 0.7rem;
        }

        .search-container input:focus {
            box-shadow: none;
            border: 2px solid var(--amazon-orange);
        }

        .btn-search {
            background-color: var(--amazon-orange);
            border: none;
            color: var(--amazon-dark);
            font-weight: 600;
            padding: 0 1.5rem;
        }

        .btn-search:hover {
            background-color: #ec8b00;
        }

        .dropdown-toggle {
            background-color: #f3f3f3;
            border: none;
            border-radius: 4px 0 0 4px;
        }

        /* Card Libri stile Amazon */
        .book-card {
            transition: all 0.2s ease;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
            height: 100%;
            cursor: pointer;
        }

        .book-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-color: #bbb;
        }

        .book-img {
            height: 280px;
            object-fit: contain;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px 8px 0 0;
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0066c0;
            line-height: 1.3;
            min-height: 2.6rem;
        }

        .card-title:hover {
            color: #c45500;
            text-decoration: underline;
        }

        .price {
            font-size: 1.4rem;
            font-weight: 700;
            color: #B12704;
        }

        .btn-warning {
            background-color: var(--amazon-orange);
            border: none;
            color: var(--amazon-dark);
            font-weight: 600;
            border-radius: 20px;
            padding: 0.5rem;
            font-size: 0.9rem;
        }

        .btn-warning:hover {
            background-color: #ec8b00;
            color: var(--amazon-dark);
        }

        .badge {
            position: absolute;
            top: -8px;
            right: -8px;
        }

        footer {
            background-color: var(--amazon-dark);
            color: white;
            margin-top: auto;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--amazon-dark);
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand ms-3" href="index.php">📚 BookSwap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center me-3">
                    <li class="nav-item">
                        <?php if(isset($_SESSION['id_user'])): ?>
                            <a class="nav-link" href="index.php?table=Listings&action=createListings">Crea Annuncio</a>
                        <?php else: ?>
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                                Crea Annuncio
                            </a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Ordini</a></li>
                    <?php
                            if(!isset($_SESSION['id_user'])){
                                echo '<li class="nav-item mx-2">';
                                echo '  <a class="btn btn-login d-flex align-items-center gap-2" href="index.php?table=login&action=login">';
                                echo 'Accedi';
                                echo '</a>';
                                echo '</li>';
                            }
                            else{
                                echo '<li class="nav-item mx-2">';
                                echo '  <a class="btn btn-login d-flex align-items-center gap-2" href="index.php?table=User&action=account">';
                                // Icona Uscita (Logout)
                                echo '      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                                            </svg>';
                                echo '      Il tuo Account';
                                echo '  </a>';
                                echo '</li>';
                            }
                        ?>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="index.php?table=Home&action=cart">
                            🛒 Carrello
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
                <div class="col-lg-10 offset-lg-1">
                    <div class="input-group">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Tutte le categorie
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Tutti i libri</a></li>
                            <li><a class="dropdown-item" href="#">Narrativa</a></li>
                            <li><a class="dropdown-item" href="#">Saggistica</a></li>
                            <li><a class="dropdown-item" href="#">Scolastica</a></li>
                        </ul>
                        <input type="text" class="form-control" placeholder="Cerca libri per titolo, autore o ISBN...">
                        <button class="btn btn-search" type="button">
                            🔍 Cerca
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container my-5">
        <h2 class="section-title">Libri disponibili</h2>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">

                <?php include 'Table.php'; ?>


        </div>
    </main>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Accesso richiesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Per pubblicare un annuncio su <strong>BookSwap</strong> e vendere i tuoi libri, devi prima autenticarti.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <a href="index.php?table=login&action=login" class="btn btn-warning">Accedi ora</a>
            </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-muted">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>