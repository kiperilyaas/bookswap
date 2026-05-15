<?php
#defined("APP") or die("ACCESSO NEGATO");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Libro | BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="views/bookswap-responsive.css">
</head>

<body class="d-flex flex-column min-vh-100" style="background-color: #eaeded;">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #131921;">
        <div class="container-fluid">
            <a class="navbar-brand ms-2 fw-bold text-white" href="index.php">📚 BookSwap</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto me-2">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Torna alla Home</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 12px;">
                    <h2 class="section-title fw-bold" style="color: #131921;">Aggiungi un libro al catalogo</h2>
                    <p class="text-muted mb-4" style="font-size:var(--text-sm);">Inserisci i dettagli tecnici. Potrai metterlo in vendita dopo averlo creato.</p>

                    <form action="index.php?table=Listings&action=addBook" method="POST">

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #c45500;">1. Dettagli Principali</label>
                            <div class="row g-3">
                                <div class="col-md-8 col-12">
                                    <label for="title" class="form-label fw-semibold">Titolo *</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Es. Matematica Blu 2.0" required>
                                </div>
                                <div class="col-md-4 col-12">
                                    <label for="vol" class="form-label fw-semibold">Volume</label>
                                    <select class="form-control" id="vol" name="vol">
                                        <option value="">--Seleziona--</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="U">Unico</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="author" class="form-label fw-semibold">Autore *</label>
                                    <input type="text" class="form-control" id="author" name="author" placeholder="Es. Massimo Bergamini" required>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="isbn" class="form-label fw-semibold">Codice ISBN *</label>
                                    <input type="text" class="form-control" id="isbn" name="isbn" placeholder="Es. 9788808123456" required>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #c45500;">2. Classificazione scolastica</label>
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <label for="publish" class="form-label fw-semibold">Casa Editrice</label>
                                    <select class="form-select" id="publish" name="publish">
                                        <option value="">--Seleziona--</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="subject" class="form-label fw-semibold">Materia</label>
                                    <select class="form-select" id="subject" name="subject">
                                        <option value="">--Seleziona--</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="faculty" class="form-label fw-semibold">Indirizzo di Studio</label>
                                    <select class="form-select" id="faculty" name="faculty">
                                        <option value="">--Seleziona--</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="class" class="form-label fw-semibold">Classe</label>
                                    <select class="form-select" id="class" name="class">
                                        <option value="">--Seleziona--</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #c45500;">3. Prezzo di Copertina</label>
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <label for="price" class="form-label fw-semibold">Prezzo Originale (Nuovo)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">€</span>
                                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" max="1000" placeholder="0.00" required>
                                    </div>
                                    <div id="priceLimitAlert" class="text-danger small mt-1 fw-bold" style="display:none;">
                                        <i class="bi bi-exclamation-circle-fill"></i> Massimo 1.000€.
                                    </div>
                                    <div class="form-text">Il prezzo di listino ufficiale.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-4 mt-2 border-top flex-wrap">
                            <button type="reset" class="btn btn-light border fw-bold rounded-pill px-4">Svuota campi</button>
                            <button type="submit" class="btn fw-bold rounded-pill px-4" style="background-color: #ff9900; color: #131921;">Salva nel Catalogo</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 bg-dark text-white mt-auto">
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-white-50">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Carica case editrici
        fetch('index.php?table=Listings&action=getPublishingHouses')
            .then(r => r.json())
            .then(data => {
                const select = document.getElementById('publish');
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id_publish_house;
                    option.textContent = item.name;
                    select.appendChild(option);
                });
            })
            .catch(err => console.error('Errore caricamento case editrici:', err));

        // Carica materie
        fetch('index.php?table=Listings&action=getSubjects')
            .then(r => r.json())
            .then(data => {
                const select = document.getElementById('subject');
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id_subject;
                    option.textContent = item.name;
                    select.appendChild(option);
                });
            })
            .catch(err => console.error('Errore caricamento materie:', err));

        // Carica indirizzi
        fetch('index.php?table=Listings&action=getFaculties')
            .then(r => r.json())
            .then(data => {
                const select = document.getElementById('faculty');
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id_faculty;
                    option.textContent = item.name;
                    select.appendChild(option);
                });
            })
            .catch(err => console.error('Errore caricamento indirizzi:', err));

        // Carica classi
        fetch('index.php?table=Listings&action=getClasses')
            .then(r => r.json())
            .then(data => {
                const select = document.getElementById('class');
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.class;
                    option.textContent = item.class;
                    select.appendChild(option);
                });
            })
            .catch(err => console.error('Errore caricamento classi:', err));
    });

    // Price validation
    document.getElementById('price').addEventListener('input', function() {
        const alert = document.getElementById('priceLimitAlert');
        if (parseFloat(this.value) > 1000) { alert.style.display = 'block'; this.value = 1000; }
        else { alert.style.display = 'none'; }
    });
    </script>
</body>
</html>
