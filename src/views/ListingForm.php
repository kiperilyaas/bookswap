<?php
defined("APP") or die("ACCESSO NEGATO");

// Controllo di sicurezza: solo gli utenti loggati possono accedere
if (!isset($_SESSION['id_user'])) {
    $_SESSION['errors'] = ["📢 Devi effettuare il login per aggiungere un'offerta."];
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

    <style>
        :root {
            --amazon-orange: #ff9900;
            --amazon-orange-hover: #ec8b00;
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
        }

        /* Container Card */
        .form-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--amazon-dark);
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #ddd;
            padding-bottom: 0.5rem;
        }

        /* Stile Input */
        .form-label {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--amazon-orange);
            box-shadow: 0 0 3px rgba(255, 153, 0, 0.5);
        }

        /* Pulsante Arancione Uniformato (Stile Amazon) */
        .btn-amazon {
            background-color: var(--amazon-orange);
            border: 1px solid #a88734;
            color: var(--amazon-dark);
            font-weight: 600;
            border-radius: 20px;
            /* Arrotondato */
            padding: 0.5rem 1.5rem;
            transition: background-color 0.2s;
        }

        /* Bottoni Arancioni - Tutti gli stati */

        /* Stato Passaggio Mouse (Arancione più scuro) */
        .btn-amazon:hover {
            background-color: var(--amazon-orange-hover);
            
            color: var(--amazon-dark);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Stato Disabilitato (Arancione pallido) */
        .btn-amazon:disabled {
            background-color: #ffda9e;
            /* Arancione desaturato */
            
            color: #947a4d;
            cursor: not-allowed;
        }

        /* Pulsante Secondario (Grigio/Lieve) */
        /* Entrambi i bottoni ora seguono il tema arancione */
        .btn-amazon,
        .btn-amazon-light {
            background-color: var(--amazon-orange);
            border: 1px solid #a88734;
            color: var(--amazon-dark);
            font-weight: 600;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s ease;
        }

        .btn-amazon:hover,
        .btn-amazon-light:hover {
            background-color: var(--amazon-orange-hover);
            
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        /* Ricerca Live */
        .book-result-item {
            border: 1px solid #eee;
            border-radius: 8px;
            transition: background 0.2s, border-color 0.2s;
            cursor: pointer;
        }

        .book-result-item:hover {
            background-color: #f7fafa;
            border-color: var(--amazon-orange);
        }

        .selected-book-alert {
            background-color: #f0f8ff;
            border: 1px solid #0066c0;
            border-radius: 8px;
        }

        footer {
            background-color: var(--amazon-dark);
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
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Torna alla Home</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="form-card">
                    <h2 class="section-title">Crea un nuovo annuncio</h2>

                    <form action="index.php?table=Listings&action=addListing" method="POST" id="offerForm">

                        <input type="hidden" id="id_book_selezionato" name="id_book" required>

                        <div class="mb-4">
                            <label class="form-label"> 1. Quale libro vuoi vendere?</label>

                            <div id="searchBlock">
                                <div class="input-group">
                                    <select class="form-select" id="searchFilter"
                                        style="max-width: 130px; background-color: #f3f3f3; border-radius: 20px 0 0 20px;">
                                        <option value="title">Titolo</option>
                                        <option value="author">Autore</option>
                                        <option value="isbn">ISBN</option>
                                    </select>
                                    <input type="text" class="form-control" id="searchInput"
                                        placeholder="Inserisci titolo, autore o codice ISBN..."
                                        style="border-radius: 0 20px 20px 0;">
                                </div>
                                <div class="mt-2 text-end">
                                    <small>➕ Non trovi il libro? <a href="index.php?table=Listings&action=addBookForm"
                                            style="color: #0066c0;">Aggiungilo al catalogo</a></small>
                                </div>

                                <div id="searchResults" class="mt-3"></div>

                                <div id="noResults" class="alert alert-light border mt-3" style="display: none;">
                                    Nessun risultato trovato. Prova a cambiare filtri o <a
                                        href="index.php?table=Listings&action=addBookForm">crea una nuova scheda
                                        libro</a>.
                                </div>
                            </div>

                            <div id="selectedBlock" style="display: none;">
                                <div
                                    class="selected-book-alert p-3 d-flex justify-content-between align-items-center shadow-sm">
                                    <div>
                                        <span class="text-muted small d-block"> Libro selezionato:</span>
                                        <strong id="selectedBookTitle" class="fs-5" style="color: #0066c0;"></strong>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-amazon-light"
                                        onclick="resetSelection()"> Cambia</button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="prezzo" class="form-label"> 2. Prezzo di vendita</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" class="form-control" id="prezzo" name="prezzo" step="0.01"
                                        min="0" placeholder="0.00" required>
                                </div>
                                <small class="text-muted">Usa 0.00 per scambi o regali 🎁.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="condizioni" class="form-label"> 3. Condizioni del libro</label>
                                <select class="form-select" id="condizioni" name="condizioni" required>
                                    <option value="" selected disabled>Seleziona stato...</option>
                                    <option value="nuovo"> Nuovo</option>
                                    <option value="ottimo"> Come nuovo</option>
                                    <option value="buono"> Buone condizioni</option>
                                    <option value="usurato"> Segni di usura / Scritto</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="descrizione" class="form-label"> 4. Descrizione dell'annuncio
                                (Opzionale)</label>
                            <textarea class="form-control" id="descrizione" name="descrizione" rows="4"
                                placeholder="Aggiungi informazioni utili (es. sottolineature a matita, copertina un po' rovinata...)"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                            <button type="reset" class="btn btn-amazon-light" onclick="resetSelection()">
                                Svuota</button>
                            <button type="submit" class="btn btn-amazon" id="submitBtn" disabled> Pubblica
                                Annuncio</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-4">
        <div class="container">
            <p class="mb-1 text-white">© 2026 BookSwap Team</p>
            <small style="color: #ccc;">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <script>
        // Logica Ricerca Live
        document.getElementById('searchInput').addEventListener('input', function () {
            let query = this.value;
            let filter = document.getElementById('searchFilter').value;
            let resultsDiv = document.getElementById('searchResults');
            let noResultsDiv = document.getElementById('noResults');

            if (query.length < 2) {
                resultsDiv.innerHTML = '';
                noResultsDiv.style.display = 'none';
                return;
            }

            let url = `index.php?table=Listings&action=liveSearch&query=${encodeURIComponent(query)}&filter=${filter}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    resultsDiv.innerHTML = '';

                    if (data.length > 0) {
                        noResultsDiv.style.display = 'none';
                        data.forEach(book => {
                            resultsDiv.innerHTML += `
                                <div class="book-result-item p-3 mb-2 bg-white d-flex align-items-center justify-content-between shadow-sm">
                                    <div>
                                        <div class="fw-bold fs-6" style="color: #0066c0;"> ${book.title}</div>
                                        <div class="small text-muted"> ${book.author} |  ISBN: ${book.isbn}</div>
                                        <b><div class="small text-muted"> PREZZO: ${book.price}€</div></b>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-amazon" onclick="selectBook(${book.id_book}, '${book.title.replace(/'/g, "\\'")}')">
                                         Seleziona
                                    </button>
                                </div>
                            `;
                        });
                    } else {
                        noResultsDiv.style.display = 'block';
                    }
                })
                .catch(error => console.error("Errore ricerca:", error));
        });

        function selectBook(idBook, title) {
            document.getElementById('id_book_selezionato').value = idBook;
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('selectedBookTitle').innerText = title;
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
        }

        document.getElementById('offerForm').addEventListener('submit', function (e) {
            if (!document.getElementById('id_book_selezionato').value) {
                e.preventDefault();
                alert("⚠️ Errore: Seleziona un libro dal catalogo prima di pubblicare l'annuncio.");
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>