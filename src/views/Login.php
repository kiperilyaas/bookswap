<?php
defined("APP") or die("ACCESSO NEGATO");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="views/bookswap-responsive.css">
    <style>
        .login-wrap {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: var(--sp-md);
        }
        .login-card {
            background: white;
            padding: var(--sp-lg);
            border-radius: var(--radius-lg);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: clamp(320px, 35vw, 500px);
            border: 1px solid #ddd;
        }
        .login-title {
            color: var(--dark);
            font-weight: 800;
            font-size: var(--text-xl);
            margin-bottom: var(--sp-md);
            text-align: center;
        }
        .register-link {
            display: block;
            text-align: center;
            margin-top: var(--sp-sm);
            color: #0066c0;
            text-decoration: none;
            font-size: var(--text-sm);
        }
        .register-link:hover { color: #c45500; text-decoration: underline; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand ms-2">📚 BookSwap</a>
        </div>
    </nav>

    <div class="login-wrap">
        <div class="login-card">
            <h2 class="login-title">Login</h2>
            <form method="post" action="index.php?action=check&table=Login">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Inserisci Email" required>
                </div>
                <div class="mb-4 position-relative">
                    <input type="password" name="password" id="passwordField" class="form-control" placeholder="Inserisci Password" required>
                    <button type="button" id="togglePassword" class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" style="text-decoration:none;z-index:10;">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
                <button type="submit" class="btn-amazon w-100 d-block text-center">Login</button>
            </form>
            <a href="index.php?table=login&action=registerView" class="register-link">Non hai un account? Registrati</a>
        </div>
    </div>

    <footer>
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-muted">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordField  = document.getElementById('passwordField');
        const eyeIcon        = document.getElementById('eyeIcon');
        const form           = document.querySelector('form');
        const emailInput     = document.querySelector('input[name="email"]');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            eyeIcon.className = type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
        });

        emailInput.addEventListener('blur', function() {
            if (this.value && !this.value.endsWith('@isit100.fe.it')) {
                this.classList.add('is-invalid');
                showErr(this, "Usa un'email @isit100.fe.it");
            } else { this.classList.remove('is-invalid'); removeErr(this); }
        });

        [emailInput, passwordField].forEach(i => i.addEventListener('input', function() {
            this.classList.remove('is-invalid'); removeErr(this);
        }));

        form.addEventListener('submit', function(e) {
            const email = emailInput.value.trim();
            const pwd   = passwordField.value;
            let ok = true;
            if (!email) { e.preventDefault(); emailInput.classList.add('is-invalid'); showErr(emailInput, 'Email obbligatoria'); ok = false; }
            else if (!email.endsWith('@isit100.fe.it')) { e.preventDefault(); emailInput.classList.add('is-invalid'); showErr(emailInput, "Usa un'email @isit100.fe.it"); ok = false; }
            if (!pwd) { e.preventDefault(); passwordField.classList.add('is-invalid'); showErr(passwordField, 'Password obbligatoria'); ok = false; }
            else if (pwd.length < 3) { e.preventDefault(); passwordField.classList.add('is-invalid'); showErr(passwordField, 'Password troppo corta'); ok = false; }
        });

        function showErr(input, msg) {
            removeErr(input);
            const d = document.createElement('div');
            d.className = 'invalid-feedback d-block';
            d.textContent = msg;
            input.parentNode.appendChild(d);
        }
        function removeErr(input) {
            const d = input.parentNode.querySelector('.invalid-feedback');
            if (d) d.remove();
        }
    });
    </script>
</body>
</html>
