<?php
defined("APP") or die("ACCESSO NEGATO");

if (!isset($_SESSION['id_user'])) {
    $_SESSION['errors'] = ["Devi effettuare il login per aggiungere un'offerta."];
    header("Location: index.php?table=login&action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Annuncio | BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="views/bookswap-responsive.css">
    <style>
        .custom-dropdown {
            border-radius: var(--radius-md);
            border: 1px solid #ddd;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 0.5rem 0;
            overflow: hidden;
        }
        .custom-dropdown .dropdown-item { padding: 0.6rem 1.5rem; transition: background-color 0.2s; font-size: var(--text-sm); }
        .custom-dropdown .dropdown-item:hover { background-color: #f7fafa; color: var(--orange); font-weight: 600; }
        .book-result-item {
            border: 1px solid #eee;
            border-radius: var(--radius-md);
            transition: background 0.2s, border-color 0.2s;
            cursor: pointer;
        }
        .book-result-item:hover { background-color: #f7fafa; border-color: var(--orange); }
        .selected-book-alert {
            background-color: #f0f8ff;
            border: 1px solid #0066c0;
            border-radius: var(--radius-md);
        }
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

    <main class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-12">
                <div class="form-card">
                    <h2 class="section-title">Crea un nuovo annuncio</h2>

                    <form action="index.php?table=Listings&action=addListing" method="POST" id="offerForm">
                        <input type="hidden" id="id_book_selezionato" name="id_book" required>

                        <div class="mb-4">
                            <label class="form-label">1. Quale libro vuoi vendere?</label>
                            <div id="searchBlock">
                                <div class="input-group">
                                    <button class="btn dropdown-toggle border" type="button" data-bs-toggle="dropdown"
                                            id="filterDropdownBtn"
                                            style="background:#f3f3f3;border-radius:20px 0 0 20px;color:#333;min-width:110px;display:flex;justify-content:space-between;align-items:center;border-color:#ced4da;font-size:var(--text-sm);">
                                        Titolo
                                    </button>
                                    <ul class="dropdown-menu custom-dropdown">
                                        <li><a class="dropdown-item filter-option" href="#" data-value="title">Titolo</a></li>
                                        <li><a class="dropdown-item filter-option" href="#" data-value="author">Autore</a></li>
                                        <li><a class="dropdown-item filter-option" href="#" data-value="isbn">ISBN</a></li>
                                        <li><a class="dropdown-item filter-option" href="#" data-value="class">Classe</a></li>
                                    </ul>
                                    <input type="hidden" id="searchFilter" value="title">
                                    <input type="text" class="form-control" id="searchInput"
                                           placeholder="Cerca per titolo, autore o ISBN…"
                                           style="border-radius:0 20px 20px 0;">
                                </div>
                                <div class="mt-2 text-end">
                                    <small>➕ Non trovi il libro? <a href="index.php?table=Listings&action=addBookForm" style="color:#0066c0;">Aggiungilo al catalogo</a></small>
                                </div>
                                <div id="searchResults" class="mt-3"></div>
                                <div id="noResults" class="alert alert-light border mt-3" style="display:none;">
                                    Nessun risultato. <a href="index.php?table=Listings&action=addBookForm">Crea una nuova scheda libro</a>.
                                </div>
                            </div>

                            <div id="selectedBlock" style="display:none;">
                                <div class="selected-book-alert p-3 d-flex justify-content-between align-items-center gap-3">
                                    <div>
                                        <span class="text-muted" style="font-size:var(--text-xs);">Libro selezionato:</span>
                                        <strong id="selectedBookTitle" class="d-block" style="color:#0066c0;font-size:var(--text-md);"></strong>
                                        <div class="text-secondary" style="font-size:var(--text-sm);">
                                            Prezzo di riferimento: <strong id="selectedBookPrice" class="text-dark"></strong>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-amazon-light flex-shrink-0" onclick="resetSelection()">Cambia</button>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="prezzo" class="form-label">2. Prezzo di vendita</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" class="form-control" id="prezzo" name="prezzo" step="0.01" min="0" max="1000" placeholder="0.00" required>
                                </div>
                                <div id="priceLimitAlert" class="text-danger small mt-1 fw-bold" style="display:none;">
                                    <i class="bi bi-exclamation-circle-fill"></i> Massimo 1.000€.
                                </div>
                                <small class="text-muted">Usa 0.00 per scambi o regali 🎁</small>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="condizioni" class="form-label">3. Condizioni del libro</label>
                                <select class="form-select" id="condizioni" name="condizioni" required>
                                    <option value="" selected disabled>Seleziona stato...</option>
                                    <option value="nuovo">Nuovo</option>
                                    <option value="ottimo">Come nuovo</option>
                                    <option value="buono">Buone condizioni</option>
                                    <option value="usurato">Segni di usura / Scritto</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="descrizione" class="form-label">4. Descrizione (Opzionale)</label>
                            <textarea class="form-control" id="descrizione" name="descrizione" rows="4"
                                placeholder="Aggiungi informazioni utili (es. sottolineature, copertina rovinata…)"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3 border-top flex-wrap">
                            <button type="reset" class="btn-amazon-light" onclick="resetSelection()">Svuota</button>
                            <button type="submit" class="btn-amazon" id="submitBtn" disabled>Pubblica Annuncio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p class="mb-1 text-white">© 2026 BookSwap Team</p>
            <small style="color:#ccc;">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let searchTimeout;

    document.querySelectorAll('.filter-option').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('filterDropdownBtn').innerText = this.innerText;
            document.getElementById('searchFilter').value = this.dataset.value;
            if (document.getElementById('searchInput').value.trim().length >= 2)
                document.getElementById('searchInput').dispatchEvent(new Event('input'));
        });
    });

    document.getElementById('prezzo').addEventListener('input', function() {
        const a = document.getElementById('priceLimitAlert');
        if (parseFloat(this.value) > 1000) { a.style.display = 'block'; this.value = 1000; }
        else a.style.display = 'none';
    });

    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const query  = this.value.trim();
        const filter = document.getElementById('searchFilter').value;
        const resDiv = document.getElementById('searchResults');
        const noRes  = document.getElementById('noResults');
        if (query.length < 2) { resDiv.innerHTML = ''; noRes.style.display = 'none'; return; }

        searchTimeout = setTimeout(() => {
            fetch(`index.php?table=Listings&action=liveSearchBooks&query=${encodeURIComponent(query)}&filter=${filter}`)
                .then(r => r.json())
                .then(data => {
                    resDiv.innerHTML = '';
                    if (data.length > 0) {
                        noRes.style.display = 'none';
                        data.forEach(book => {
                            const author  = book.author || 'Autore sconosciuto';
                            const isbn    = book.isbn   || 'N/D';
                            const raw     = book.priceOffer !== undefined ? book.priceOffer : (book.price || '0');
                            const fmt     = parseFloat(raw).toFixed(2).replace('.', ',');
                            resDiv.innerHTML += `
                            <div class="book-result-item p-3 mb-2 bg-white d-flex align-items-center justify-content-between shadow-sm gap-3">
                                <div>
                                    <div class="fw-bold" style="color:#0066c0;font-size:var(--text-sm);">${book.title}</div>
                                    <div class="text-muted" style="font-size:var(--text-xs);">${author} | ISBN: ${isbn}</div>
                                    <div class="text-muted fw-bold" style="font-size:var(--text-xs);">Copertina: ${fmt}€</div>
                                </div>
                                <button type="button" class="btn-amazon flex-shrink-0"
                                        onclick="selectBook(${book.id_book},'${book.title.replace(/'/g,"\\'")}','${fmt}')">
                                    Seleziona
                                </button>
                            </div>`;
                        });
                    } else {
                        noRes.style.display = 'block';
                    }
                })
                .catch(err => console.error('Errore ricerca:', err));
        }, 250);
    });

    function selectBook(id, title, price) {
        document.getElementById('id_book_selezionato').value = id;
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('selectedBookTitle').innerText = title;
        document.getElementById('selectedBookPrice').innerText = price + '€';
        document.getElementById('searchBlock').style.display = 'none';
        document.getElementById('selectedBlock').style.display = 'block';
    }

    function resetSelection() {
        document.getElementById('id_book_selezionato').value = '';
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('searchInput').value = '';
        document.getElementById('searchResults').innerHTML = '';
        document.getElementById('searchBlock').style.display = 'block';
        document.getElementById('selectedBlock').style.display = 'none';
        document.getElementById('selectedBookPrice').innerText = '';
    }

    document.getElementById('offerForm').addEventListener('submit', function(e) {
        if (!document.getElementById('id_book_selezionato').value) {
            e.preventDefault();
            alert('⚠️ Seleziona un libro dal catalogo prima di pubblicare.');
        }
    });
    </script>
</body>
</html>
