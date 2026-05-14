<?php
#defined("APP") or die("ACCESSO NEGATO");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati — BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="views/bookswap-responsive.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .reg-wrap {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: var(--sp-md);
        }

        .register-card {
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

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--orange), var(--orange-hover));
        }

        .reg-title {
            color: var(--dark);
            font-weight: 800;
            font-size: var(--text-2xl);
            margin-bottom: 0.5rem;
            text-align: center;
            letter-spacing: -0.02em;
        }

        .reg-subtitle {
            text-align: center;
            color: #6c757d;
            font-size: var(--text-sm);
            margin-bottom: var(--sp-lg);
        }

        .form-group {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .form-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            transition: color 0.3s ease;
            z-index: 2;
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

        .form-control:focus + i {
            color: var(--orange);
        }

        .btn-amazon {
            padding: 1rem;
            font-size: var(--text-base);
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 153, 0, 0.3);
            margin-top: 0.5rem;
        }

        .btn-amazon:hover {
            box-shadow: 0 6px 20px rgba(255, 153, 0, 0.4);
            transform: translateY(-2px);
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #0066c0;
            text-decoration: none;
            font-size: var(--text-sm);
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .login-link:hover {
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
            .register-card {
                padding: 1.5rem;
            }

            .reg-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand ms-2">📚 BookSwap</a>
        </div>
    </nav>

    <div class="reg-wrap">
        <div class="register-card">
            <h2 class="reg-title">Crea Account</h2>
            <p class="reg-subtitle">Unisciti alla community BookSwap</p>
            <form action="index.php?table=login&action=insert" method="post">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Nome" required>
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="form-group">
                    <input type="text" name="surname" class="form-control" placeholder="Cognome" required>
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="form-group">
                    <input type="text" name="class" class="form-control" placeholder="Classe (es. 5N)" required>
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email scolastica @isit100.fe.it" required>
                    <i class="bi bi-envelope-fill"></i>
                    <small class="text-muted d-block mt-1 ms-1"><i class="bi bi-info-circle-fill"></i> Usa solo email @isit100.fe.it</small>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password (min 8 caratteri)" required>
                    <i class="bi bi-lock-fill"></i>
                    <small class="text-muted d-block mt-1 ms-1"><i class="bi bi-shield-lock-fill"></i> Minimo 8 caratteri richiesti</small>
                </div>
                <button type="submit" class="btn-amazon w-100 d-block text-center">
                    <i class="bi bi-person-plus-fill me-2"></i>Registrati
                </button>
            </form>
            <div class="divider">
                <span>oppure</span>
            </div>
            <a href="index.php?table=login&action=loginView" class="login-link">
                <i class="bi bi-box-arrow-in-right me-1"></i>Hai già un account? Accedi
            </a>
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
        const form          = document.querySelector('form');
        const nameInput     = document.querySelector('input[name="name"]');
        const surnameInput  = document.querySelector('input[name="surname"]');
        const classInput    = document.querySelector('input[name="class"]');
        const emailInput    = document.querySelector('input[name="email"]');
        const passwordInput = document.querySelector('input[name="password"]');
        const CLASS_RE      = /^[1-5][A-Z]$/;

        classInput.addEventListener('blur', function() {
            const v = this.value.trim().toUpperCase();
            if (v && !CLASS_RE.test(v)) { this.classList.add('is-invalid'); showErr(this, 'Formato classe non valido (es: 5N)'); }
            else { this.classList.remove('is-invalid'); removeErr(this); }
        });

        emailInput.addEventListener('blur', function() {
            if (this.value && !this.value.endsWith('@isit100.fe.it')) {
                this.classList.add('is-invalid'); showErr(this, "Usa un'email @isit100.fe.it");
            } else { this.classList.remove('is-invalid'); removeErr(this); }
        });

        emailInput.addEventListener('input', function() {
            // Rimuovi caratteri non validi in tempo reale
            const invalidChars = /[^a-zA-Z0-9.@_-]/g;
            if (invalidChars.test(this.value)) {
                this.value = this.value.replace(invalidChars, '');
                this.classList.add('is-invalid');
                showErr(this, 'Caratteri non validi rimossi. Usa solo lettere, numeri, punto, trattino e underscore');
            } else {
                this.classList.remove('is-invalid');
                removeErr(this);
            }
        });

        [nameInput, surnameInput, classInput, emailInput, passwordInput].forEach(i =>
            i.addEventListener('input', function() { this.classList.remove('is-invalid'); removeErr(this); })
        );

        form.addEventListener('submit', function(e) {
            let ok = true;
            if (!nameInput.value.trim())    { e.preventDefault(); nameInput.classList.add('is-invalid');    showErr(nameInput, 'Nome obbligatorio');    ok = false; }
            if (!surnameInput.value.trim()) { e.preventDefault(); surnameInput.classList.add('is-invalid'); showErr(surnameInput, 'Cognome obbligatorio'); ok = false; }
            const cv = classInput.value.trim().toUpperCase();
            if (!cv)                   { e.preventDefault(); classInput.classList.add('is-invalid'); showErr(classInput, 'Classe obbligatoria'); ok = false; }
            else if (!CLASS_RE.test(cv)) { e.preventDefault(); classInput.classList.add('is-invalid'); showErr(classInput, 'Formato classe non valido (es: 5N)'); ok = false; }
            const em = emailInput.value.trim();
            if (!em) { e.preventDefault(); emailInput.classList.add('is-invalid'); showErr(emailInput, 'Email obbligatoria'); ok = false; }
            else if (!em.endsWith('@isit100.fe.it')) { e.preventDefault(); emailInput.classList.add('is-invalid'); showErr(emailInput, "Usa un'email @isit100.fe.it"); ok = false; }
            else if (/[^a-zA-Z0-9.@_-]/.test(em)) { e.preventDefault(); emailInput.classList.add('is-invalid'); showErr(emailInput, 'Email contiene caratteri non validi'); ok = false; }
            const pw = passwordInput.value;
            if (!pw) { e.preventDefault(); passwordInput.classList.add('is-invalid'); showErr(passwordInput, 'Password obbligatoria'); ok = false; }
            else if (pw.length < 8) { e.preventDefault(); passwordInput.classList.add('is-invalid'); showErr(passwordInput, 'Min 8 caratteri'); ok = false; }
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
