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
            display: flex;
            flex-direction: column;
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

        .form-group > i {
            position: absolute;
            left: 1rem;
            top: 0.75rem;
            color: #adb5bd;
            transition: color 0.3s ease;
            z-index: 2;
            pointer-events: none;
        }

        .form-group input.form-control {
            padding-left: 2.75rem;
        }

        footer {
            width: 100%;
            margin-top: auto;
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
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email scolastica @isit100.fe.it" required>
                    <i class="bi bi-envelope-fill"></i>
                    <div id="emailRequirements" class="mt-2 ms-1 small">
                        <div id="emailDomain" class="text-muted">
                            <i class="bi bi-circle"></i> Deve terminare con @isit100.fe.it
                        </div>
                        <div id="emailChars" class="text-muted">
                            <i class="bi bi-circle"></i> Solo lettere, numeri, punto, trattino e underscore
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password (min 6 caratteri)" required minlength="6">
                    <i class="bi bi-lock-fill"></i>
                    <div id="passwordRequirements" class="mt-2 ms-1 small">
                        <div id="pwLength" class="text-muted">
                            <i class="bi bi-circle"></i> Minimo 6 caratteri
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Conferma Password" required minlength="6">
                    <i class="bi bi-lock-fill"></i>
                    <div id="confirmPasswordRequirements" class="mt-2 ms-1 small">
                        <div id="pwMatch" class="text-muted">
                            <i class="bi bi-circle"></i> Le password devono coincidere
                        </div>
                    </div>
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
        const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
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
            const email = this.value;
            const emailDomainCheck = document.getElementById('emailDomain');
            const emailCharsCheck = document.getElementById('emailChars');

            // Verifica dominio
            if (email.endsWith('@isit100.fe.it')) {
                emailDomainCheck.className = 'text-success';
                emailDomainCheck.innerHTML = '<i class="bi bi-check-circle-fill"></i> Deve terminare con @isit100.fe.it';
            } else {
                emailDomainCheck.className = 'text-muted';
                emailDomainCheck.innerHTML = '<i class="bi bi-circle"></i> Deve terminare con @isit100.fe.it';
            }

            // Verifica caratteri validi
            const invalidChars = /[^a-zA-Z0-9.@_-]/g;
            if (email && !invalidChars.test(email)) {
                emailCharsCheck.className = 'text-success';
                emailCharsCheck.innerHTML = '<i class="bi bi-check-circle-fill"></i> Solo lettere, numeri, punto, trattino e underscore';
                this.classList.remove('is-invalid');
                removeErr(this);
            } else if (email && invalidChars.test(email)) {
                this.value = this.value.replace(invalidChars, '');
                emailCharsCheck.className = 'text-danger';
                emailCharsCheck.innerHTML = '<i class="bi bi-x-circle-fill"></i> Caratteri non validi rimossi';
            } else {
                emailCharsCheck.className = 'text-muted';
                emailCharsCheck.innerHTML = '<i class="bi bi-circle"></i> Solo lettere, numeri, punto, trattino e underscore';
            }
        });

        passwordInput.addEventListener('input', function() {
            const pw = this.value;
            const pwLengthCheck = document.getElementById('pwLength');

            if (pw.length >= 6) {
                pwLengthCheck.className = 'text-success';
                pwLengthCheck.innerHTML = '<i class="bi bi-check-circle-fill"></i> Minimo 6 caratteri';
            } else if (pw.length > 0) {
                pwLengthCheck.className = 'text-warning';
                pwLengthCheck.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Minimo 6 caratteri (' + pw.length + '/6)';
            } else {
                pwLengthCheck.className = 'text-muted';
                pwLengthCheck.innerHTML = '<i class="bi bi-circle"></i> Minimo 6 caratteri';
            }

            // Verifica corrispondenza se conferma password è già compilata
            checkPasswordMatch();
        });

        confirmPasswordInput.addEventListener('input', function() {
            checkPasswordMatch();
        });

        function checkPasswordMatch() {
            const pw = passwordInput.value;
            const confirmPw = confirmPasswordInput.value;
            const pwMatchCheck = document.getElementById('pwMatch');

            if (confirmPw.length === 0) {
                pwMatchCheck.className = 'text-muted';
                pwMatchCheck.innerHTML = '<i class="bi bi-circle"></i> Le password devono coincidere';
            } else if (pw === confirmPw) {
                pwMatchCheck.className = 'text-success';
                pwMatchCheck.innerHTML = '<i class="bi bi-check-circle-fill"></i> Le password coincidono';
            } else {
                pwMatchCheck.className = 'text-danger';
                pwMatchCheck.innerHTML = '<i class="bi bi-x-circle-fill"></i> Le password non coincidono';
            }
        }

        [nameInput, surnameInput, classInput, emailInput, passwordInput, confirmPasswordInput].forEach(i =>
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
            else if (pw.length < 6) { e.preventDefault(); passwordInput.classList.add('is-invalid'); showErr(passwordInput, 'Min 6 caratteri'); ok = false; }
            const confirmPw = confirmPasswordInput.value;
            if (!confirmPw) { e.preventDefault(); confirmPasswordInput.classList.add('is-invalid'); showErr(confirmPasswordInput, 'Conferma password obbligatoria'); ok = false; }
            else if (pw !== confirmPw) { e.preventDefault(); confirmPasswordInput.classList.add('is-invalid'); showErr(confirmPasswordInput, 'Le password non coincidono'); ok = false; }
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