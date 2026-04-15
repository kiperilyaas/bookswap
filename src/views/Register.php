<?php 
#defined("APP") or die("ACCESSO NEGATO");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nome Azienda</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
            margin: 0;
        }

        /* Navbar stile Amazon */
        .navbar-custom {
            background-color: var(--amazon-dark);
            padding: 0.7rem 2rem;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }

        .navbar-brand:hover {
            color: var(--amazon-orange) !important;
        }

        /* Container centrato */
        .register-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Card stile Amazon */
        .register-card {
            background: white;
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            border: 1px solid #ddd;
        }

        .register-title {
            color: var(--amazon-dark);
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-control {
            border-radius: 4px;
            padding: 0.7rem;
            border: 1px solid #ccc;
        }

        .form-control:focus {
            box-shadow: none;
            border: 2px solid var(--amazon-orange);
        }

        /* Bottone stile Amazon */
        .btn-register {
            background-color: var(--amazon-orange);
            border: none;
            color: var(--amazon-dark);
            font-weight: 600;
            border-radius: 20px;
            padding: 0.6rem;
        }

        .btn-register:hover {
            background-color: #ec8b00;
            color: var(--amazon-dark);
        }

        footer {
            background-color: var(--amazon-dark);
            color: white;
            margin-top: auto;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">📚 BookSwap</a>
        </div>
    </nav>

    <div class="register-container">
        <div class="register-card">

            <h2 class="register-title">Registrati</h2>

            <form action="index.php?table=login&action=insert" method="post">

                <div class="mb-3">
                    <input type="text" name="nome" class="form-control" placeholder="Inserisci Nome" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="cognome" class="form-control" placeholder="Inserisci Cognome" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="classesezione" class="form-control" placeholder="Inserisci Classe/Sezione" required>
                </div>

                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Inserisci Email" required>
                </div>

                <div class="mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Inserisci Password" required>
                </div>

                <div class="d-flex">
                    <button type="submit" class="btn-register w-100">Registrati</button>
                </div>

            </form>

        </div>
    </div>

    <footer class="text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-muted">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

</body>
</html>