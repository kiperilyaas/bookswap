<?php
#defined("APP") or die("ACCESSO NEGATO");
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
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .login-wrap {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: var(--sp-md);
        }

        .login-card {
            background: white;
            padding: clamp(2rem, 3vw, 3rem);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: clamp(320px, 35vw, 480px);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--orange), var(--orange-hover));
        }
        .login-title {
            color: var(--dark);
            font-weight: 800;
            font-size: var(--text-2xl);
            margin-bottom: 0.5rem;
            text-align: center;
            letter-spacing: -0.02em;
        }

        .login-subtitle {
            text-align: center;
            color: #6c757d;
            font-size: var(--text-sm);
            margin-bottom: var(--sp-lg);
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        /* CORREZIONE QUI: Aggiunto il ">" per targettare solo lucchetto e busta */
        .form-group > i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            transition: color 0.3s ease;
            z-index: 2;
            pointer-events: none; /* Evita che l'icona blocchi il click sull'input */
        }

        .form-control {
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: var(--text-sm);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 4px rgba(255, 153, 0, 0.1);
            transform: translateY(-2px);
        }

        /* Quando l'input è a fuoco, colora l'icona a sinistra */
        .form-control:focus + i {
            color: var(--orange);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 3rem;
        }

        #togglePassword {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6c757d;
            padding: 0.5rem;
            z-index: 3;
            transition: color 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* L'icona dell'occhio ora si comporterà normalmente */
        #togglePassword i {
            font-size: 1.1rem;
        }

        #togglePassword:hover {
            color: var(--orange);
        }

        #togglePassword:focus {
            outline: none;
            box-shadow: none;
        }

        .btn-amazon {
            padding: 1rem;
            font-size: var(--text-base);
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 153, 0, 0.3);
            border: none;
            background-color: var(--orange);
            color: white;
        }

        .btn-amazon:hover {
            background-color: var(--orange-hover);
            color: white;
            box-shadow: 0 6px 20px rgba(255, 153, 0, 0.4);
            transform: translateY(-2px);
        }

        .register-link {
            display: block;
            text-align: center;
            margin-top: var(--sp-sm);
            color: #0066c0;
            text-decoration: none;
            font-size: var(--text-sm);
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .register-link:hover {
            color: var(--orange);
            transform: translateX(2px);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: #adb5bd;
            font-size: var(--text-xs);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e9ecef;
        }

        .divider span {
            padding: 0 1rem;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem;
            }

            .login-title {
                font-size: 1.8rem;
            }
        }
        .register-link:hover { color: #c45500; text-decoration: underline; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: var(--dark);">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand ms-2 fw-bold text-white">📚 BookSwap</a>
        </div>
    </nav>

    <div class="login-wrap">
        <div class="login-card">
            <h2 class="login-title">Bentornato!</h2>
            <p class="login-subtitle">Accedi al tuo account BookSwap</p>
            <form method="post" action="index.php?action=check&table=Login">
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div class="form-group password-wrapper">
                    <input type="password" name="password" id="passwordField" class="form-control" placeholder="Password" required>
                    <i class="bi bi-lock-fill"></i>
                    <button type="button" id="togglePassword" aria-label="Mostra password">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
                <button type="submit" class="btn-amazon w-100 d-block text-center mt-4">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Accedi
                </button>
            </form>
            <div class="divider">
                <span>oppure</span>
            </div>
            <a href="index.php?table=login&action=registerView" class="register-link">
                <i class="bi bi-person-plus-fill me-1"></i>Non hai un account? Registrati
            </a>
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
