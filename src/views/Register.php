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
        .reg-wrap {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: var(--sp-md);
        }
        .register-card {
            background: white;
            padding: var(--sp-lg);
            border-radius: var(--radius-lg);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: clamp(320px, 35vw, 500px);
            border: 1px solid #ddd;
        }
        .reg-title {
            color: var(--dark);
            font-weight: 800;
            font-size: var(--text-xl);
            margin-bottom: var(--sp-md);
            text-align: center;
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
            <h2 class="reg-title">Registrati</h2>
            <form action="index.php?table=login&action=insert" method="post">
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Inserisci Nome" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="surname" class="form-control" placeholder="Inserisci Cognome" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="class" class="form-control" placeholder="Classe/Sezione (es. 5N)" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Inserisci Email" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Inserisci Password" required>
                </div>
                <button type="submit" class="btn-amazon w-100 d-block text-center">Registrati</button>
            </form>
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
