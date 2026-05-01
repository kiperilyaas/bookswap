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
    <link rel="stylesheet" href="views/bookswap-responsive.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand ms-2" href="index.php">📚 BookSwap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center me-2">
                    <li class="nav-item">
                        <?php if(isset($_SESSION['id_user'])): ?>
                            <a class="nav-link" href="index.php?table=Listings&action=createListings">Crea Annuncio</a>
                        <?php else: ?>
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Crea Annuncio</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="index.php?table=Order&action=viewMyOrders">Ordini</a></li>
                    <?php
                    if (!isset($_SESSION['id_user'])) {
                        echo '<li class="nav-item mx-1">';
                        echo '<a class="btn-nav-cta" href="index.php?table=login&action=loginView">Accedi</a>';
                        echo '</li>';
                    } else {
                        echo '<li class="nav-item mx-1">';
                        echo '<a class="btn-nav-cta" href="index.php?table=User&action=account">';
                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/></svg>';
                        echo 'Area Personale';
                        echo '</a></li>';
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
                        <select class="form-select text-center" id="searchFilter" style="max-width:140px;font-weight:500;">
                            <option value="title">Titolo</option>
                            <option value="author">Autore</option>
                            <option value="isbn">ISBN</option>
                        </select>
                        <input type="text" class="form-control" id="searchInput" placeholder="Cerca un libro…">
                        <div class="d-flex align-items-center px-3 bg-white">
                            <svg id="searchIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                            <div id="loadingSpinner" class="spinner-border spinner-border-sm text-warning d-none ms-1" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container my-4">
        <h2 class="section-title">Libri disponibili</h2>

        <div id="defaultResults" class="row row-cols-2 row-cols-sm-3 row-cols-lg-5 g-3">
            <?php include 'views/Table.php'; ?>
        </div>

        <div id="searchResults" class="row row-cols-2 row-cols-sm-3 row-cols-lg-5 g-3 d-none"></div>

        <div id="noResults" class="text-center py-5 w-100 d-none">
            <h4 class="text-muted">Nessun libro trovato.</h4>
            <p class="text-secondary">Prova a cercare con un termine diverso o cambia il filtro!</p>
        </div>
    </main>

    <!-- Modale dettaglio libro -->
    <div class="modal fade" id="bookDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBookTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalBookImg" src="" class="img-fluid mb-3" style="max-height:220px;object-fit:contain;">
                    <div class="mb-3 text-start">
                        <p class="mb-1"><strong>Autore:</strong> <span id="modalBookAuthor"></span></p>
                        <p class="mb-1"><strong>Venditore:</strong> <span id="modalBookSeller"></span> | <strong>Classe:</strong> <span id="modalBookClasse"></span></p>
                        <p class="mb-1"><strong>ISBN:</strong> <span id="modalBookISBN"></span></p>
                        <p class="mb-1"><strong>Casa Editrice:</strong> <span id="modalBookPublisher"></span></p>
                    </div>
                    <div id="modalBookPrice" class="price mb-3"></div>
                    <div class="text-start p-3 bg-light rounded">
                        <h6><strong>Descrizione:</strong></h6>
                        <p id="modalBookDescription" class="mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Chiudi</button>
                    <a href="#" id="modalBookCartBtn" class="btn-amazon rounded-pill"><i class="bi bi-bag-check-fill"></i> Compra!</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale login richiesto -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Accesso richiesto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">Per pubblicare un annuncio su <strong>BookSwap</strong> devi prima autenticarti.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    <a href="index.php?table=login&action=login" class="btn-amazon">Accedi ora</a>
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
    <script>
    function confirmPurchase(event, bookTitle) {
        event.stopPropagation();
        event.preventDefault();
        const confirmed = confirm('Vuoi acquistare:\n\n"' + bookTitle + '"?\n\nVerrai portato al checkout.');
        if (confirmed) window.location.href = event.target.closest('a').href;
        return false;
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Modale libro
        document.getElementById('bookDetailModal').addEventListener('show.bs.modal', function (event) {
            const el = event.relatedTarget;
            this.querySelector('#modalBookTitle').textContent     = el.dataset.title || 'N/D';
            this.querySelector('#modalBookImg').src               = el.dataset.img   || '';
            this.querySelector('#modalBookPrice').textContent     = el.dataset.price || '';
            this.querySelector('#modalBookDescription').textContent = el.dataset.description || 'Nessuna descrizione.';
            this.querySelector('#modalBookAuthor').textContent    = el.dataset.author    || 'N/D';
            this.querySelector('#modalBookSeller').textContent    = el.dataset.seller    || 'N/D';
            this.querySelector('#modalBookISBN').textContent      = el.dataset.isbn      || 'N/D';
            this.querySelector('#modalBookPublisher').textContent = el.dataset.publisher || 'N/D';
            this.querySelector('#modalBookClasse').textContent    = el.dataset.classe    || 'N/D';
            const cartBtn = this.querySelector('#modalBookCartBtn');
            const idItem = el.dataset.id;
            if (idItem) {
                cartBtn.href = "index.php?table=Order&action=checkout&id=" + idItem;
                cartBtn.onclick = e => confirmPurchase(e, el.dataset.title);
            }
        });

        // Ricerca live
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query   = this.value.trim();
            const filter  = document.getElementById('searchFilter').value;
            const defDiv  = document.getElementById('defaultResults');
            const resDiv  = document.getElementById('searchResults');
            const noRes   = document.getElementById('noResults');
            const icon    = document.getElementById('searchIcon');
            const spinner = document.getElementById('loadingSpinner');

            if (query.length < 2) {
                resDiv.innerHTML = '';
                resDiv.classList.add('d-none');
                noRes.classList.add('d-none');
                defDiv.classList.remove('d-none');
                icon.classList.remove('d-none');
                spinner.classList.add('d-none');
                return;
            }

            icon.classList.add('d-none');
            spinner.classList.remove('d-none');

            searchTimeout = setTimeout(() => {
                fetch(`index.php?table=Listings&action=liveSearchListings&query=${encodeURIComponent(query)}&filter=${filter}`)
                    .then(r => r.json())
                    .then(data => {
                        icon.classList.remove('d-none');
                        spinner.classList.add('d-none');
                        defDiv.classList.add('d-none');
                        resDiv.innerHTML = '';

                        if (data.length > 0) {
                            noRes.classList.add('d-none');
                            resDiv.classList.remove('d-none');
                            data.forEach(book => {
                                const title   = book.title || 'Titolo sconosciuto';
                                const seller  = ((book.Name || book.name || '') + ' ' + (book.Surname || book.surname || '')).trim() || 'N/D';
                                const rawPrice = book.priceOffer !== undefined ? book.priceOffer : book.price;
                                const priceText = (rawPrice !== null && parseFloat(rawPrice) > 0)
                                    ? '€ ' + parseFloat(rawPrice).toFixed(2).replace('.', ',')
                                    : 'Scambio';
                                const imgSrc = book.immagine || '../utils/immagini/prova_libro.png';
                                const idItem = book.id_listing || book.id_book || '';
                                const safeQ  = s => (s || '').replace(/"/g, '&quot;');

                                resDiv.innerHTML += `
                                <div class="col">
                                    <div class="card book-card"
                                        data-bs-toggle="modal" data-bs-target="#bookDetailModal"
                                        data-id="${encodeURIComponent(idItem)}"
                                        data-title="${safeQ(title)}"
                                        data-img="${imgSrc}"
                                        data-price="${priceText}"
                                        data-description="${safeQ(book.description || '')}"
                                        data-author="${safeQ(book.author || '')}"
                                        data-seller="${safeQ(seller)}"
                                        data-isbn="${safeQ(book.isbn || '')}"
                                        data-publisher="${safeQ(book.publishing_house || book.publish || '')}"
                                        data-classe="${safeQ(book.class || book.classe || '')}">
                                        <img src="${imgSrc}" class="card-img-top book-img" alt="Copertina">
                                        <div class="card-body d-flex flex-column p-3">
                                            <h5 class="card-title" style="-webkit-line-clamp:2;display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;">${title}</h5>
                                            <div class="seller-info"><i class="bi bi-person-fill"></i> <strong>${seller}</strong></div>
                                            <div class="mb-2"><span class="price">${priceText}</span></div>
                                            <div class="mt-auto">
                                                <a href="index.php?table=Order&action=checkout&id=${encodeURIComponent(idItem)}"
                                                   onclick="return confirmPurchase(event,'${title.replace(/'/g,"\\'")}');"
                                                   class="btn btn-warning w-100 d-flex justify-content-center align-items-center gap-2">
                                                    <i class="bi bi-bag-check-fill"></i> Compra!
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            });
                        } else {
                            resDiv.classList.add('d-none');
                            noRes.classList.remove('d-none');
                        }
                    })
                    .catch(() => { icon.classList.remove('d-none'); spinner.classList.add('d-none'); });
            }, 50);
        });
    });
    </script>
</body>
</html>
