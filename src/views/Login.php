<?php 
#defined("APP") or die("ACCESSO NEGATO");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nome Azienda</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

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
        .login-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Card stile Amazon */
        .login-card {
            background: white;
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            border: 1px solid #ddd;
        }

        .login-title {
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
        .btn-login {
            background-color: var(--amazon-orange);
            border: none;
            color: var(--amazon-dark);
            font-weight: 600;
            border-radius: 20px;
            padding: 0.6rem;
        }

        .btn-login:hover {
            background-color: #ec8b00;
            color: var(--amazon-dark);
        }

        .register-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #0066c0;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .register-link:hover {
            color: #c45500;
            text-decoration: underline;
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
            <a href="index.php" class="navbar-brand">📚 BookSwap</a>
        </div>
    </nav>

    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">Login</h2>
            
            <form method="post" action="index.php?action=check&table=Login">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Inserisci Email" required>
                </div>

                <div class="mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Inserisci Password" required>
                </div>

                <div class="d-flex">
                    <button type="submit" class="btn-login w-100">Login</button>
                </div>
            </form>

            <a href="index.php?table=login&action=register" class="register-link">
                Non hai un account? Registrati
            </a>
        </div>
    </div>

    <footer class="text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-muted">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Validazione form login
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const emailInput = document.querySelector('input[name="email"]');
        const passwordInput = document.querySelector('input[name="password"]');

        // Validazione email in tempo reale
        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !email.endsWith('@isit100.fe.it')) {
                this.classList.add('is-invalid');
                showFieldError(this, 'Usa un\'email @isit100.fe.it');
            } else {
                this.classList.remove('is-invalid');
                removeFieldError(this);
            }
        });

        // Rimuovi errore quando l'utente inizia a digitare
        emailInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            removeFieldError(this);
        });

        passwordInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            removeFieldError(this);
        });

        // Validazione al submit
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Valida email
            const email = emailInput.value.trim();
            if (!email) {
                e.preventDefault();
                emailInput.classList.add('is-invalid');
                showFieldError(emailInput, 'Email obbligatoria');
                isValid = false;
            } else if (!email.endsWith('@isit100.fe.it')) {
                e.preventDefault();
                emailInput.classList.add('is-invalid');
                showFieldError(emailInput, 'Usa un\'email @isit100.fe.it');
                isValid = false;
            }

            // Valida password
            const password = passwordInput.value;
            if (!password) {
                e.preventDefault();
                passwordInput.classList.add('is-invalid');
                showFieldError(passwordInput, 'Password obbligatoria');
                isValid = false;
            } else if (password.length < 3) {
                e.preventDefault();
                passwordInput.classList.add('is-invalid');
                showFieldError(passwordInput, 'Password troppo corta (min 6 caratteri)');
                isValid = false;
            }
        });

        function showFieldError(input, message) {
            removeFieldError(input);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback d-block';
            errorDiv.textContent = message;
            input.parentNode.appendChild(errorDiv);
        }

        function removeFieldError(input) {
            const errorDiv = input.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
    });
    </script>
</body>
</html>