<?php 
defined("APP") or die("ACCESSO NEGATO");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nome Azienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f5fa;
            height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        /* Stile Header (Immagine 2) */
        .navbar-custom {
            background-color: #004085;
            padding: 1rem 2rem;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
        }

        /* Container centrale */
        .login-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 450px;
            border: 1px solid #dee2e6;
        }

        .login-title {
            color: #004085;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }

        /* Input Arrotondati (Immagine 1) */
        .form-control {
            border-radius: 25px; 
            padding: 0.75rem 1.5rem;
            border: 1px solid #ced4da;
        }

        /* Bottoni (Immagine 2) */
        .btn-login {
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            padding: 0.6rem 2rem;
            border: none;
            font-weight: 500;
        }

        .btn-login:hover {
            background-color: #0056b3;
            color: white;
        }

        .btn-annulla {
            background-color: transparent;
            color: #6c757d;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 0.6rem 2rem;
            text-decoration: none;
            text-align: center;
        }

        .register-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #007bff;
            text-decoration: none;
            font-size: 0.9rem;
        }

        /* Footer */
        footer {
            background: white;
            padding: 1rem;
            text-align: center;
            font-size: 0.85rem;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">NOME AZIENDA</a>
            <div class="text-white d-none d-md-block">
                <small class="ms-3">VENDI</small>
                <small class="ms-3">LOGIN</small>
                <small class="ms-3">CARRELLO</small>
            </div>
        </div>
    </nav>

    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">Metti la email!!</h2>
            
            <form method="post" action="index.php?action=check&table=Login">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="inserisci email" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="password" class="form-control" placeholder="metti la password" required>
                </div>

                <div class="d-flex justify-content-between gap-2">
                    <a href="index.php" class="btn-annulla flex-grow-1">annulla</a>
                    <button type="submit" class="btn-login flex-grow-1">login</button>
                </div>
            </form>

            <a href="index.php?table=login&action=register" class="register-link">
                Non hai un account? Registrati
            </a>
        </div>
    </div>

    <footer>
        footer - © 2026 Nome Azienda
    </footer>

</body>
</html>