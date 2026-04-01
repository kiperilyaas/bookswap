<?php
defined("APP") or die("ACCESSO NEGATO");
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizzazione Libri | BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Piccoli aggiustamenti per personalizzare il tema */
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .navbar-brand {
            font-weight: bold;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">BookSwap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Vendi</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-light btn-sm mx-lg-2 my-2 my-lg-0" href="index.php?table=login&action=login">LOGIN</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Carrello</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="input-group mb-3 shadow-sm">
                    <button class="btn btn-secondary" type="button">Filtro</button>
                    <input type="text" class="form-control" placeholder="Cerca libri, autori, generi..." aria-label="Cerca">
                    <button class="btn btn-primary" type="button">Cerca</button>
                </div>
            </div>
        </div>
    </div>

    <main class="container my-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="display-area">
                    <?php
                    // Assicurati che table.php generi una tabella con classe .table di Bootstrap
                    include 'table.php';
                    ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <small>© Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>