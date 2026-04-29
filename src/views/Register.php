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
            <a href="index.php" class="navbar-brand">📚 BookSwap</a>
        </div>
    </nav>

    <div class="register-container">
        <div class="register-card">

            <h2 class="register-title">Registrati</h2>

            <form action="index.php?table=login&action=insert" method="post">

                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Inserisci Nome" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="surname" class="form-control" placeholder="Inserisci Cognome" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="class" class="form-control" placeholder="Inserisci Classe/Sezione" required>
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

    <?php include 'views/ToastNotification.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Validazione form registrazione
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const nameInput = document.querySelector('input[name="name"]');
        const surnameInput = document.querySelector('input[name="surname"]');
        const classInput = document.querySelector('input[name="class"]');
        const emailInput = document.querySelector('input[name="email"]');
        const passwordInput = document.querySelector('input[name="password"]');

        // Validazione classe in tempo reale (formato: numero + lettera, es: 5N)
        classInput.addEventListener('blur', function() {
            const classValue = this.value.trim().toUpperCase();
            const classPattern = /^[1-5][A-Z]$/;
            if (classValue && !classPattern.test(classValue)) {
                this.classList.add('is-invalid');
                showFieldError(this, 'Formato classe non valido (es: 5N, 3A)');
            } else {
                this.classList.remove('is-invalid');
                removeFieldError(this);
            }
        });

        // Validazione email
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

        // Rimuovi errori quando l'utente digita
        [nameInput, surnameInput, classInput, emailInput, passwordInput].forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                removeFieldError(this);
            });
        });

        // Validazione al submit
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Valida nome
            if (!nameInput.value.trim()) {
                e.preventDefault();
                nameInput.classList.add('is-invalid');
                showFieldError(nameInput, 'Nome obbligatorio');
                isValid = false;
            }

            // Valida cognome
            if (!surnameInput.value.trim()) {
                e.preventDefault();
                surnameInput.classList.add('is-invalid');
                showFieldError(surnameInput, 'Cognome obbligatorio');
                isValid = false;
            }

            // Valida classe
            const classValue = classInput.value.trim().toUpperCase();
            const classPattern = /^[1-5][A-Z]$/;
            if (!classValue) {
                e.preventDefault();
                classInput.classList.add('is-invalid');
                showFieldError(classInput, 'Classe obbligatoria');
                isValid = false;
            } else if (!classPattern.test(classValue)) {
                e.preventDefault();
                classInput.classList.add('is-invalid');
                showFieldError(classInput, 'Formato classe non valido (es: 5N, 3A)');
                isValid = false;
            }

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
            } else if (password.length < 8) {
                e.preventDefault();
                passwordInput.classList.add('is-invalid');
                showFieldError(passwordInput, 'Password troppo corta (min 8 caratteri)');
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