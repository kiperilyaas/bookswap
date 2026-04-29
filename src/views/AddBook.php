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
    <style>
        :root {
            --amazon-orange: #ff9900;
            --amazon-orange-hover: #ec8b00;
            --amazon-dark: #131921;
            --amazon-light: #232f3e;
            --light-bg: #eaeded;
        }

        /* FIX PER FOOTER AL FONDO */
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background-color: var(--light-bg);
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Forza il body a coprire l'intera altezza dello schermo */
            font-family: 'Amazon Ember', Arial, sans-serif;
        }

        /* La navbar e il container principale rimangono nella parte alta */
        .content {
            flex: 1 0 auto; /* Questo elemento si espande spingendo il footer in basso */
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
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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

        .form-control:focus, .form-select:focus {
            border-color: var(--amazon-orange);
            box-shadow: 0 0 3px rgba(255, 153, 0, 0.5);
        }

        /* Pulsanti Arancioni Uniformati */
        .btn-amazon {
            background-color: var(--amazon-orange);
            border: 1px solid #a88734;
            color: var(--amazon-dark);
            font-weight: 600;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s ease;
        }

        .btn-amazon:hover {
            background-color: var(--amazon-orange-hover);
            border-color: #a88734;
            color: var(--amazon-dark);
        }

        .btn-amazon-light {
            background-color: #ffda9e;
            border: 1px solid #e0c28d;
            color: #947a4d;
            font-weight: 600;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
        }

        .btn-amazon-light:hover {
            background-color: #f7dfa1;
            border-color: #a88734;
            color: var(--amazon-dark);
        }

        .text-primary-amazon {
            color: #0066c0 !important;
        }

        /* Footer non collassabile */
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body>

    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand ms-3" href="index.php">📚 BookSwap</a>
                <div class="ms-auto me-3">
                    <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill"> Annulla e Torna alla Home</a>
                </div>
            </div>
        </nav>

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <div class="form-card">
                        <h2 class="section-title"> Aggiungi un nuovo libro al catalogo</h2>
                        <p class="text-muted small">Inserisci i dettagli tecnici del libro. Una volta creato, potrai metterlo in vendita.</p>
                        
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
                                       <select class="form-control" id="vol" name="vol">
                                            <option value="">--Seleziona--</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="U">Unico</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="author" class="form-label fw-semibold">Autore *</label>
                                        <input type="text" class="form-control" id="author" name="author" placeholder="Es. Massimo Bergamini" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="isbn" class="form-label fw-semibold">Codice ISBN *</label>
                                        <input type="text" class="form-control" id="isbn" name="isbn" placeholder="Es. 9788808123456 -- 9 o 13 caratteri" required>
                                    </div>
                                </div>

                            <hr class="text-muted mb-4">

                            <h5 class="fw-bold text-primary-amazon mb-3">2. Classificazione scolastica</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="publish" class="form-label">Casa Editrice</label>
                                    <input list="publishList" class="form-control" id="publish" name="publish" placeholder="Cerca o scrivi editore...">
                                    <datalist id="publishList">
                                        <option value="Zanichelli">
                                        <option value="Mondadori">
                                        <option value="Pearson">
                                        <option value="De Agostini">
                                    </datalist>
                                </div>

                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Materia</label>
                                    <input list="subjectList" class="form-control" id="subject" name="subject" placeholder="Cerca o scrivi materia...">
                                    <datalist id="subjectList">
                                        <option value="Matematica">
                                        <option value="Italiano">
                                        <option value="Informatica">
                                        <option value="Sistemi e Reti">
                                    </datalist>
                                </div>

                                <div class="col-md-6">
                                    <label for="faculty" class="form-label">Indirizzo di Studio</label>
                                    <input list="facultyList" class="form-control" id="faculty" name="faculty" placeholder="Es. Informatica, Liceo Classico...">
                                    <datalist id="facultyList">
                                        <option value="Informatica e Telecomunicazioni">
                                        <option value="Liceo Scientifico">
                                        <option value="Meccanica e Meccatronica">
                                    </datalist>
                                </div>

                                <div class="col-md-6">
                                    <label for="class" class="form-label">Classe</label>
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

                            <h5 class="fw-bold text-primary-amazon mb-3"> 3. Prezzo di Copertina</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Prezzo Originale (Nuovo)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">€</span>
                                        <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" placeholder="0.00" required>
                                    </div>
                                    <div class="form-text">Il prezzo di listino ufficiale.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-5 border-top pt-4">
                                <button type="reset" class="btn btn-amazon-light"> Svuota campi</button>
                                <button type="submit" class="btn btn-amazon"> Salva nel Catalogo</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> <footer class="text-center py-4 mt-auto" style="background-color: var(--amazon-dark); color: white;">
        <div class="container">
            <p class="mb-1 text-white">© 2026 BookSwap Team</p>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>