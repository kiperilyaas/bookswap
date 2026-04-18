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
    <style>
        .btn-amazon {
            background-color: #ff9900;
            border: none;
            color: #131921;
            font-weight: 600;
        }
        .btn-amazon:hover {
            background-color: #ec8b00;
            color: #131921;
        }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">📚 BookSwap</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Annulla e Torna alla Home</a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h3 class="fw-bold mb-0 text-dark">Aggiungi un nuovo libro al catalogo</h3>
                        <p class="text-muted small mt-1">Inserisci i dettagli del libro. I dati verranno salvati nel database.</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="index.php?table=Listings&action=addBook" method="POST">
                            
                            <h5 class="fw-bold text-primary mb-3">1. Dettagli Principali</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-8">
                                    <label for="title" class="form-label fw-semibold">Titolo del Libro *</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Es. Matematica Blu 2.0" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="vol" class="form-label fw-semibold">Volume</label>
                                    <input type="text" class="form-control" id="vol" name="vol" placeholder="Es. 1, 2, Unico">
                                </div>
                                <div class="col-md-6">
                                    <label for="author" class="form-label fw-semibold">Autore *</label>
                                    <input type="text" class="form-control" id="author" name="author" placeholder="Es. Massimo Bergamini" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="isbn" class="form-label fw-semibold">Codice ISBN *</label>
                                    <input type="text" class="form-control" id="isbn" name="isbn" placeholder="Es. 9788808123456" required>
                                </div>
                            </div>

                            <hr class="text-muted mb-4">

                            <h5 class="fw-bold text-primary mb-3">2. Classificazione scolastica</h5>
                            <div class="row g-3 mb-4">
                                
                                <div class="col-md-6">
                                    <label for="publish" class="form-label fw-semibold">Casa Editrice</label>
                                    <input list="publishList" class="form-control" id="publish" name="publish" placeholder="Cerca o scrivi editore...">
                                    <datalist id="publishList">
                                        <option value="Zanichelli">
                                        <option value="Mondadori">
                                        <option value="Pearson">
                                        <option value="De Agostini">
                                    </datalist>
                                </div>

                                <div class="col-md-6">
                                    <label for="subject" class="form-label fw-semibold">Materia</label>
                                    <input list="subjectList" class="form-control" id="subject" name="subject" placeholder="Cerca o scrivi materia...">
                                    <datalist id="subjectList">
                                        <option value="Matematica">
                                        <option value="Italiano">
                                        <option value="Informatica">
                                        <option value="Sistemi e Reti">
                                    </datalist>
                                </div>

                                <div class="col-md-6">
                                    <label for="faculty" class="form-label fw-semibold">Indirizzo di Studio</label>
                                    <input list="facultyList" class="form-control" id="faculty" name="faculty" placeholder="Es. Informatica, Liceo Classico...">
                                    <datalist id="facultyList">
                                        <option value="Informatica e Telecomunicazioni">
                                        <option value="Liceo Scientifico">
                                        <option value="Meccanica e Meccatronica">
                                    </datalist>
                                </div>

                                <div class="col-md-6">
                                    <label for="class" class="form-label fw-semibold">Classe</label>
                                    <input list="classList" class="form-control" id="class" name="class" placeholder="Es. 5N, 3A...">
                                    <datalist id="classList">
                                        <option value="1A">
                                        <option value="2A">
                                        <option value="3N">
                                        <option value="4N">
                                        <option value="5N">
                                    </datalist>
                                </div>

                            </div>

                            <hr class="text-muted mb-4">

                            <h5 class="fw-bold text-primary mb-3">3. Prezzo di Copertina</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="price" class="form-label fw-semibold">Prezzo Originale (€)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">€</span>
                                        <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" placeholder="0.00" required>
                                    </div>
                                    <div class="form-text">Il prezzo a cui il libro viene venduto nuovo.</div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                <button type="reset" class="btn btn-light border me-md-2 px-4">Svuota campi</button>
                                <button type="submit" class="btn btn-amazon px-5 rounded-pill shadow-sm">Salva nel Catalogo</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>