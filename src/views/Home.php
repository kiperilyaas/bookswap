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

        /* BOTTONE ACCEDI TONDO */
        .btn-login {
            background-color: var(--amazon-orange);
            border: none;
            color: var(--amazon-dark);
            font-weight: 600;
            padding: 0.4rem 1.5rem;
            border-radius: 20px; /* Rende il bottone tondo */
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            background-color: #ec8b00;
            color: var(--amazon-dark);
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(255, 153, 0, 0.3);
        }

        /* Search Bar Super Pulita */
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

        /* Card Libri stile Amazon */
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
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

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

        <div id="defaultResults" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
            <?php include 'Table.php'; ?>
        </div>

        <div id="searchResults" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4 d-none">
        </div>

        <div id="noResults" class="text-center py-5 w-100 d-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.498 3.498 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.498 4.498 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
            </svg>
            <h4 class="text-muted fw-bold">Nessun libro trovato.</h4>
            <p class="text-secondary">Prova a cercare con un termine diverso o cambia il filtro!</p>
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
                    <a href="#" id="modalBookCartBtn" class="btn btn-warning" style="border-radius: 20px; font-weight: 600; padding: 0.5rem 1.5rem;"><i class="bi bi-bag-check-fill"></i> Compra!</a>
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
    function confirmPurchase(event, bookTitle) {
        event.stopPropagation();
        event.preventDefault();
        const confirmed = confirm('Vuoi davvero procedere con l\'acquisto di:\n\n"' + bookTitle + '"?\n\nVerrai reindirizzato alla pagina di checkout.');
        if (confirmed) {
            window.location.href = event.target.closest('a').href;
        }
        return false;
    } 

    document.addEventListener('DOMContentLoaded', function () {
        const bookModal = document.getElementById('bookDetailModal');
        bookModal.addEventListener('show.bs.modal', function (event) {
            const element = event.relatedTarget;
            
            bookModal.querySelector('#modalBookTitle').textContent = element.getAttribute('data-title') || 'N/D';
            bookModal.querySelector('#modalBookImg').src = element.getAttribute('data-img') || '../utils/immagini/prova_libro.png';
            bookModal.querySelector('#modalBookPrice').textContent = element.getAttribute('data-price') || '';
            bookModal.querySelector('#modalBookDescription').textContent = element.getAttribute('data-description') || 'Nessuna descrizione disponibile.';
            
            bookModal.querySelector('#modalBookAuthor').textContent = element.getAttribute('data-author') || 'N/D';
            bookModal.querySelector('#modalBookSeller').textContent = element.getAttribute('data-seller') || 'N/D';
            bookModal.querySelector('#modalBookISBN').textContent = element.getAttribute('data-isbn') || 'N/D';
            bookModal.querySelector('#modalBookPublisher').textContent = element.getAttribute('data-publisher') || 'N/D';
            bookModal.querySelector('#modalBookClasse').textContent = element.getAttribute('data-classe') || 'N/D';
            
            let listingId = element.getAttribute('data-id');
            let bookTitle = element.getAttribute('data-title');
            if(listingId) {
                let checkoutBtn = bookModal.querySelector('#modalBookCartBtn');
                checkoutBtn.href = "index.php?table=Order&action=checkout&id=" + listingId;
                checkoutBtn.onclick = function(e) {
                    return confirmPurchase(e, bookTitle);
                };
            }
        });
    });

    // RICERCA LIVE
    let searchTimeout;

    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimeout);

        let query = this.value.trim();
        let filter = document.getElementById('searchFilter').value;
        let defaultDiv = document.getElementById('defaultResults');
        let resultsDiv = document.getElementById('searchResults');
        let noResultsDiv = document.getElementById('noResults');
        let searchIcon = document.getElementById('searchIcon');
        let loadingSpinner = document.getElementById('loadingSpinner');

        if (query.length < 2) {
            resultsDiv.innerHTML = '';
            resultsDiv.classList.add('d-none');
            noResultsDiv.classList.add('d-none');
            defaultDiv.classList.remove('d-none'); 
            searchIcon.classList.remove('d-none');
            loadingSpinner.classList.add('d-none');
            return;
        }

        searchIcon.classList.add('d-none');
        loadingSpinner.classList.remove('d-none');

        searchTimeout = setTimeout(() => {
            let url = `index.php?table=Listings&action=liveSearchListings&query=${encodeURIComponent(query)}&filter=${filter}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    searchIcon.classList.remove('d-none');
                    loadingSpinner.classList.add('d-none');

                    defaultDiv.classList.add('d-none');
                    resultsDiv.innerHTML = ''; 

                    if (data.length > 0) {
                        noResultsDiv.classList.add('d-none');
                        resultsDiv.classList.remove('d-none'); 
                        
                        data.forEach(book => {
                            let imgSrc = book.immagine ? book.immagine : '../utils/immagini/prova_libro.png';
                            let title = book.title ? book.title : 'Title Unknown';
                            let sellerName = book.Name ? book.Name : (book.name ? book.name : '');
                            let sellerSurname = book.Surname ? book.Surname : (book.surname ? book.surname : '');
                            let seller = (sellerName + ' ' + sellerSurname).trim() || 'Unknown';
                            
                            let rawPrice = book.priceOffer !== undefined ? book.priceOffer : book.price;
                            let priceHTML = '';
                            let cleanPriceTesto = '';
                            
                            if (rawPrice !== null && parseFloat(rawPrice) > 0) {
                                let formattedPrice = parseFloat(rawPrice).toFixed(2).replace('.', ',');
                                priceHTML = `<span class="price">€ ${formattedPrice}</span>`;
                                cleanPriceTesto = `€ ${formattedPrice}`;
                            } else {
                                priceHTML = `<span class="price text-success" style="font-size: 1.2rem;">Exchange</span>`;
                                cleanPriceTesto = 'Scambio';
                            }

                            let isAvailable = book.is_available !== undefined ? book.is_available : 1;
                            let statusText = (isAvailable == 1) ? "Available" : "Not Available";
                            let statusColor = (isAvailable == 1) ? "text-success" : "text-danger";

                            let extraHTML = '';
                            let detailsCount = 0;
                            let possibleExtras = [
                                { label: 'Condition', value: book.condition || book.condizioni },
                                { label: 'Author', value: book.author },
                                { label: 'ISBN', value: book.isbn }
                            ];

                            possibleExtras.forEach(extra => {
                                if (extra.value && detailsCount < 3) {
                                    extraHTML += `<div class="text-truncate"><strong>${extra.label}:</strong> ${extra.value}</div>`;
                                    detailsCount++;
                                }
                            });

                            let idItem = book.id_listing || book.id_book || '';
                            let safeDescription = (book.description || book.descrizione || '').replace(/"/g, '&quot;');
                            let safeAuthor = (book.author || '').replace(/"/g, '&quot;');
                            let safePublisher = (book.publishing_house || book.publish || '').replace(/"/g, '&quot;');
                            let safeClass = (book.class || book.classe || '').replace(/"/g, '&quot;');
                            let safeIsbn = (book.isbn || '').replace(/"/g, '&quot;');

                            let cardHTML = `
                                <div class="col">
                                    <div class="card book-card h-100" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#bookDetailModal"
                                        data-id="${encodeURIComponent(idItem)}"
                                        data-title="${title.replace(/"/g, '&quot;')}"
                                        data-img="${imgSrc}"
                                        data-price="${cleanPriceTesto}"
                                        data-description="${safeDescription}"
                                        data-author="${safeAuthor}"
                                        data-seller="${seller.replace(/"/g, '&quot;')}"
                                        data-isbn="${safeIsbn}"
                                        data-publisher="${safePublisher}"
                                        data-classe="${safeClass}">
                                        
                                        <img src="${imgSrc}" class="card-img-top book-img" alt="Cover">
                                        <div class="card-body d-flex flex-column p-3">
                                            <h5 class="card-title text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                ${title}
                                            </h5>
                                            <p class="mb-2 text-muted" style="font-size: 0.85rem;">
                                                👤 Seller: <strong>${seller}</strong>
                                            </p>
                                            <div class="mb-2">${priceHTML}</div>
                                            <p class="mb-2 small">
                                                <span class="${statusColor} fw-bold">● ${statusText}</span>
                                            </p>
                                            <div class="small text-muted mb-3">${extraHTML}</div>
                                            <div class="mt-auto">
                                                <a href="index.php?table=Order&action=checkout&id=${encodeURIComponent(idItem)}"
                                                   onclick="return confirmPurchase(event, '${title.replace(/'/g, "\\'")}');"
                                                   class="btn btn-warning w-100 shadow-sm d-flex justify-content-center align-items-center gap-2">
                                                     <i class="bi bi-bag-check-fill"></i> Compra!
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            resultsDiv.innerHTML += cardHTML;
                        });
                        
                    } else {
                        resultsDiv.classList.add('d-none');
                        noResultsDiv.classList.remove('d-none'); 
                    }
                })
                .catch(error => {
                    console.error("Errore ricerca:", error);
                    searchIcon.classList.remove('d-none');
                    loadingSpinner.classList.add('d-none');
                });
        }, 50);
    });
    </script>

    <?php include 'views/ToastNotification.php'; ?>
</body>
</html>