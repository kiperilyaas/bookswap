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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

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

        /* Search Bar Super Pulita (Dal file 2) */
        .search-container {
            background-color: var(--amazon-light);
            padding: 20px 0;
        }

        .search-wrapper {
            background-color: white;
            border-radius: 50px;
            overflow: hidden;
            border: 2px solid transparent;
            transition: border-color 0.2s;
        }

        .search-wrapper:focus-within {
            border-color: var(--amazon-orange);
        }

        .search-wrapper select, 
        .search-wrapper input {
            border: none !important;
            box-shadow: none !important;
        }

        .search-wrapper select {
            background-color: #f3f3f3;
            border-right: 1px solid #ddd !important;
            cursor: pointer;
        }

        .search-icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            color: var(--amazon-dark);
            background-color: white;
        }

        /* Card Libri stile Amazon - MIGLIORATO */
        .book-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #ddd;
            border-radius: 12px;
            background: white;
            height: 100%;
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
            border-color: var(--amazon-orange);
        }

        .book-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--amazon-orange), #ffb84d);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .book-card:hover::before {
            transform: scaleX(1);
        }

        .book-img {
            height: 280px;
            object-fit: contain;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 12px 12px 0 0;
            transition: transform 0.3s ease;
        }

        .book-card:hover .book-img {
            transform: scale(1.05);
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0066c0;
            line-height: 1.3;
            min-height: 2.6rem;
            transition: color 0.2s ease;
        }

        .card-title:hover {
            color: #c45500;
            text-decoration: underline;
        }

        .price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #B12704;
        }

        /* Ripristino stile bottoni arrotondati come da file originale */
        .btn-warning {
            background-color: var(--amazon-orange);
            border: none;
            color: var(--amazon-dark);
            font-weight: 600;
            border-radius: 20px;
            padding: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .btn-warning:hover {
            background-color: #ec8b00;
            color: var(--amazon-dark);
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(255, 153, 0, 0.3);
        }

        .btn-warning:active {
            transform: scale(0.98);
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
            <div class="collapse navbar-collapse" id=\"navbarNav\">
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
                    <li class="nav-item"><a class="nav-link" href="index.php?table=Order&action=viewMyOrders">Ordini</a></li>
                    <?php
                            if(!isset($_SESSION['id_user'])){
                                echo '<li class="nav-item mx-2">';
                                echo '  <a class="btn btn-login d-flex align-items-center gap-2" href="index.php?table=login&action=loginView">';
                                echo 'Accedi';
                                echo '</a>';
                                echo '</li>';
                            }
                            else{
                                echo '<li class="nav-item mx-2">';
                                echo '  <a class="btn btn-login d-flex align-items-center gap-2" href="index.php?table=User&action=account">';
                                echo '      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                                            </svg>';
                                echo '      Area Personale';
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

    <div class="search-container shadow-sm">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="input-group search-wrapper">
                        <select class="form-select text-center" id="searchFilter" style="max-width: 140px; font-weight: 500;">
                            <option value="title">Titolo</option>
                            <option value="author">Autore</option>
                            <option value="isbn">ISBN</option>
                        </select>
                        <input type="text" class="form-control" id="searchInput" placeholder="Inizia a digitare per cercare...">
                        
                        <div class="search-icon-box">
                            <svg id="searchIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                            <div id="loadingSpinner" class="spinner-border spinner-border-sm text-warning d-none" role="status">
                                <span class="visually-hidden">Caricamento...</span>
                            </div>
                        </div>
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

    <div class="modal fade" id="bookDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBookTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalBookImg" src="" class="img-fluid mb-3" style="max-height: 250px; object-fit: contain;">
                    
                    <div class="mb-3">
                        <p class="mb-1"><strong>Autore:</strong> <span id="modalBookAuthor"></span></p>
                        <p class="mb-1"><strong>Venditore:</strong> <span id="modalBookSeller"></span> | <strong>Classe:</strong> <span id="modalBookClasse"></span></p>
                        <p class="mb-1"><strong>ISBN:</strong> <span id="modalBookISBN"></span></p>
                        <p class="mb-1"><strong>Casa Editrice:</strong> <span id="modalBookPublisher"></span></p>
                    </div>

                    <div id="modalBookPrice" class="price mb-3" style="font-size: 1.8rem;"></div>
                    
                    <div class="text-start p-3 bg-light rounded">
                        <h6><strong>Descrizione:</strong></h6>
                        <p id="modalBookDescription" class="mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 20px; font-weight: 600; padding: 0.5rem 1.5rem;">Chiudi</button>
                    <button type="button" class="btn btn-warning" style="border-radius: 20px; font-weight: 600; padding: 0.5rem 1.5rem;">Aggiungi al carrello</button>
                </div>
            </div>
        </div>
    </div>

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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bookModal = document.getElementById('bookDetailModal');
            bookModal.addEventListener('show.bs.modal', function (event) {
                const element = event.relatedTarget;
                
                bookModal.querySelector('#modalBookTitle').textContent = element.getAttribute('data-title');
                bookModal.querySelector('#modalBookImg').src = element.getAttribute('data-img');
                bookModal.querySelector('#modalBookPrice').textContent = element.getAttribute('data-price');
                bookModal.querySelector('#modalBookDescription').textContent = element.getAttribute('data-description');
                
                bookModal.querySelector('#modalBookAuthor').textContent = element.getAttribute('data-author');
                bookModal.querySelector('#modalBookSeller').textContent = element.getAttribute('data-seller');
                bookModal.querySelector('#modalBookISBN').textContent = element.getAttribute('data-isbn');
                bookModal.querySelector('#modalBookPublisher').textContent = element.getAttribute('data-publisher');
                // Popolamento del campo classe nel modale
                bookModal.querySelector('#modalBookClasse').textContent = element.getAttribute('data-classe');
            });
        });
    </script>
</body>
</html>