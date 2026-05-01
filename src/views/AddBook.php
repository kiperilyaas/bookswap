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
    <style>
        .text-primary-amazon { color: #0066c0 !important; }
        h5.fw-bold { font-size: var(--text-md); }
    </style>
</head>
<body>
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand ms-2" href="index.php">📚 BookSwap</a>
                <div class="ms-auto me-2">
                    <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill">Annulla e Torna</a>
                </div>
            </div>
        </nav>

        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-card">
                        <h2 class="section-title">Aggiungi un libro al catalogo</h2>
                        <p class="text-muted" style="font-size:var(--text-sm);">Inserisci i dettagli tecnici. Potrai metterlo in vendita dopo averlo creato.</p>

                        <form action="index.php?table=Listings&action=addBook" method="POST">

                            <h5 class="fw-bold text-primary mb-3">1. Dettagli Principali</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-8 col-12">
                                    <label for="title" class="form-label">Titolo *</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Es. Matematica Blu 2.0" required>
                                </div>
                                <div class="col-md-4 col-12">
                                    <label for="vol" class="form-label">Volume</label>
                                    <select class="form-control" id="vol" name="vol">
                                        <option value="">--Seleziona--</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="U">Unico</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="author" class="form-label">Autore *</label>
                                    <input type="text" class="form-control" id="author" name="author" placeholder="Es. Massimo Bergamini" required>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="isbn" class="form-label">Codice ISBN *</label>
                                    <input type="text" class="form-control" id="isbn" name="isbn" placeholder="Es. 9788808123456" required>
                                </div>
                            </div>

                            <hr class="text-muted mb-4">
                            <h5 class="fw-bold text-primary-amazon mb-3">2. Classificazione scolastica</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-12">
                                    <label for="publish" class="form-label">Casa Editrice</label>
                                    <input list="publishList" class="form-control" id="publish" name="publish" placeholder="Cerca o scrivi editore...">
                                    <datalist id="publishList">
                                        <option value="Zanichelli">
                                        <option value="Mondadori">
                                        <option value="Pearson">
                                        <option value="De Agostini">
                                    </datalist>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="subject" class="form-label">Materia</label>
                                    <input list="subjectList" class="form-control" id="subject" name="subject" placeholder="Cerca o scrivi materia...">
                                    <datalist id="subjectList">
                                        <option value="Matematica">
                                        <option value="Italiano">
                                        <option value="Informatica">
                                        <option value="Sistemi e Reti">
                                    </datalist>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="faculty" class="form-label">Indirizzo di Studio</label>
                                    <input list="facultyList" class="form-control" id="faculty" name="faculty" placeholder="Es. Informatica, Liceo Classico...">
                                    <datalist id="facultyList">
                                        <option value="Informatica e Telecomunicazioni">
                                        <option value="Liceo Scientifico">
                                        <option value="Meccanica e Meccatronica">
                                    </datalist>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="class" class="form-label">Classe</label>
                                    <input list="classList" class="form-control" id="class" name="class" placeholder="Es. 5N, 3A...">
                                    <datalist id="classList">
                                        <option value="1A"><option value="2A"><option value="3N"><option value="4N"><option value="5N">
                                    </datalist>
                                </div>
                            </div>

                            <hr class="text-muted mb-4">
                            <h5 class="fw-bold text-primary-amazon mb-3">3. Prezzo di Copertina</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-12">
                                    <label for="price" class="form-label">Prezzo Originale (Nuovo)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" max="1000" placeholder="0.00" required>
                                    </div>
                                    <div id="priceLimitAlert" class="text-danger small mt-1 fw-bold" style="display:none;">
                                        <i class="bi bi-exclamation-circle-fill"></i> Massimo 1.000€.
                                    </div>
                                    <div class="form-text">Il prezzo di listino ufficiale.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4 flex-wrap">
                                <button type="reset" class="btn-amazon-light">Svuota campi</button>
                                <button type="submit" class="btn-amazon">Salva nel Catalogo</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('price').addEventListener('input', function() {
        const alert = document.getElementById('priceLimitAlert');
        if (parseFloat(this.value) > 1000) { alert.style.display = 'block'; this.value = 1000; }
        else { alert.style.display = 'none'; }
    });
    </script>
</body>
</html>
