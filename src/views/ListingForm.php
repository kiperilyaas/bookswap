<?php 
defined("APP") or die("ACCESSO NEGATO");

// Controllo di sicurezza: solo gli utenti loggati possono accedere
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
    <title>Aggiungi Offerta Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Inserisci una nuova offerta</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Cerca il libro che vuoi mettere in vendita o scambiare, selezionalo e compila i dettagli.</p>

                    <form action="index.php?table=Listings&action=addListing" method="POST" id="offerForm">
                        
                        <input type="hidden" id="id_book_selezionato" name="id_book" required>

                        <div class="mb-4 p-3 border rounded bg-white">
                            <label class="form-label fw-bold">1. Libro da mettere in vendita</label>
                            
                            <div id="searchBlock">
                                <div class="input-group mb-2">
                                    <select class="form-select" id="searchFilter" style="max-width: 150px;">
                                        <option value="title">Titolo</option>
                                        <option value="author">Autore</option>
                                        <option value="isbn">ISBN</option>
                                        <option value="class">Classe (es. 5N)</option>
                                    </select>
                                    <input type="text" class="form-control" id="searchInput" placeholder="Inizia a digitare...">
                                </div>

                                <div id="quickAddBook" class="text-end mb-3">
                                    <a href="index.php?table=books&action=create" class="btn btn-sm btn-success">+ Aggiungi un nuovo libro al catalogo</a>
                                </div>

                                <div id="searchResults"></div>

                                <div id="noResults" class="alert alert-warning mt-3" style="display: none;">
                                    Nessun libro trovato. 
                                    <a href="index.php?table=books&action=create" class="alert-link">Aggiungilo al catalogo</a>.
                                </div>
                            </div>

                            <div id="selectedBlock" style="display: none;">
                                <div class="alert alert-success d-flex justify-content-between align-items-center mb-0">
                                    <div>
                                        <strong>✔ Hai selezionato:</strong> <br>
                                        <span id="selectedBookTitle" class="fs-5"></span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="resetSelection()">
                                        Cambia libro
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="prezzo" class="form-label fw-bold">2. Prezzo (€)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" class="form-control" id="prezzo" name="prezzo" step="0.01" min="0" placeholder="0.00" required>
                            </div>
                            <div class="form-text">Inserisci 0.00 se vuoi solo scambiarlo senza venderlo.</div>
                        </div>

                        <div class="mb-3">
                            <label for="condizioni" class="form-label fw-bold">3. Condizioni del libro</label>
                            <select class="form-select" id="condizioni" name="condizioni" required>
                                <option value="" selected disabled>-- Seleziona condizione --</option>
                                <option value="nuovo">Nuovo (mai aperto)</option>
                                <option value="ottimo">Quasi nuovo (ottime condizioni)</option>
                                <option value="buono">Buono (segni di usura minimi)</option>
                                <option value="usurato">Usato (scritte o pieghe)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="descrizione" class="form-label fw-bold">4. Descrizione dell'offerta (Opzionale)</label>
                            <textarea class="form-control" id="descrizione" name="descrizione" rows="4" placeholder="Aggiungi dettagli..."></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">Svuota Campi</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Pubblica Offerta</button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="mt-3 text-center">
                <a href="index.php" class="text-decoration-none text-secondary">&larr; Annulla e torna alla home</a>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function() {
    let query = this.value;
    let filter = document.getElementById('searchFilter').value;
    let resultsDiv = document.getElementById('searchResults');
    let noResultsDiv = document.getElementById('noResults');
    let quickAddBtn = document.getElementById('quickAddBook');

    if (query.length < 2) {
        resultsDiv.innerHTML = '';
        noResultsDiv.style.display = 'none';
        quickAddBtn.style.display = 'block'; 
        return; 
    }

    quickAddBtn.style.display = 'none';

    let url = `index.php?table=Listings&action=liveSearch&query=${encodeURIComponent(query)}&filter=${filter}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            resultsDiv.innerHTML = ''; 

            if (data.length > 0) {
                noResultsDiv.style.display = 'none'; 
                data.forEach(book => {
                    let coverImg = book.cover_image ? book.cover_image : 'https://via.placeholder.com/100x140?text=No+Cover';
                    let classBadge = book.class_name ? `<span class="badge bg-secondary">${book.class_name}</span>` : '';

                    resultsDiv.innerHTML += `
                        <div class="card mb-2 book-result-card shadow-sm">
                            <div class="row g-0">
                                <div class="col-3 col-md-2 d-flex align-items-center justify-content-center p-2">
                                    <img src="${coverImg}" class="img-fluid rounded" alt="Copertina" style="max-height: 120px; object-fit: cover;">
                                </div>
                                <div class="col-9 col-md-10">
                                    <div class="card-body py-2">
                                        <h6 class="card-title mb-1">${book.title}</h6>
                                        <p class="card-text text-muted small mb-2">
                                            Autore: ${book.author} | ISBN: ${book.isbn} <br>
                                            ${classBadge}
                                        </p>
                                        <h6 class="card-price mb-1">${book.price}€</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectBook(${book.id_book}, this)">
                                            Seleziona questo libro
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                noResultsDiv.style.display = 'block';
            }
        })
        .catch(error => console.error("Errore nella ricerca:", error));
});

// FUNZIONE AGGIORNATA: Seleziona il libro e nasconde la ricerca
function selectBook(idBook, btnElement) {
    // 1. Salvo l'ID per il database e sblocco il bottone di invio
    document.getElementById('id_book_selezionato').value = idBook;
    document.getElementById('submitBtn').disabled = false;

    // 2. Recupero il titolo dalla Card su cui ho appena cliccato
    let cardBody = btnElement.closest('.card-body');
    let title = cardBody.querySelector('.card-title').innerText;
    let price = cardBody.querySelector('.card-price').innerText;

    // 3. Inserisco il titolo nel blocco verde di successo
    document.getElementById('selectedBookTitle').innerText = title;
    document.getElementById('selectedBookTitle').innerText += " | Prezzo originale: " + price
    // 4. NASCONDO la ricerca e MOSTRO il blocco del libro selezionato
    document.getElementById('searchBlock').style.display = 'none';
    document.getElementById('selectedBlock').style.display = 'block';
}

// NUOVA FUNZIONE: Permette di annullare la scelta e tornare a cercare
function resetSelection() {
    // 1. Svuoto l'ID e blocco il bottone di invio
    document.getElementById('id_book_selezionato').value = '';
    document.getElementById('submitBtn').disabled = true;

    // 2. Pulisco la barra di ricerca e i vecchi risultati
    document.getElementById('searchInput').value = '';
    document.getElementById('searchResults').innerHTML = '';
    document.getElementById('quickAddBook').style.display = 'block';

    // 3. MOSTRO di nuovo la ricerca e NASCONDO il blocco del libro selezionato
    document.getElementById('searchBlock').style.display = 'block';
    document.getElementById('selectedBlock').style.display = 'none';
}

// Controllo sicurezza sul submit
document.getElementById('offerForm').addEventListener('submit', function(e) {
    if(!document.getElementById('id_book_selezionato').value) {
        e.preventDefault();
        alert("Attenzione: devi prima cercare e selezionare un libro dal catalogo!");
    }
});
</script>

</body>
</html>