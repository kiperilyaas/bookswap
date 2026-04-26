<?php
defined("APP") or die("ACCESSO NEGATO");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Il Mio Account | BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">📚 BookSwap</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#accountNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="accountNavbar">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" href="index.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                              <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/>
                            </svg>
                            Torna alla Home
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 px-3 rounded-pill" href="#" id="logoutBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                            </svg>
                            Logout
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#ff9900" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                </svg>
                <h2 class="mb-0">Area Personale</h2>
            </div>
            <button class="btn btn-outline-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="bi bi-pencil-square"></i> Modifica Profilo
            </button>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">📚 I miei annunci</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($myOffers)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach($myOffers as $offer): 
                                    // Adatta queste chiavi in base a come le chiami nella tua query SQL!
                                    $titolo = $offer['title'] ?? 'Titolo Sconosciuto';
                                    $isbn = $offer['isbn'] ?? "00000000000";
                                    $prezzo = $offer['price'] ?? 0;
                                    $isAvailable = $offer['is_available'] ?? 1;
                                    
                                    $badgeStyle = ($isAvailable == 1) ? 'bg-success' : 'bg-secondary';
                                    $badgeText = ($isAvailable == 1) ? 'Attivo' : 'Venduto';
                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <div>
                                            <h6 class="mb-1 fw-bold"><?= htmlspecialchars($titolo) ?></h6>
                                            <h6 class="mb-1 fw-bold"><?= htmlspecialchars($isbn) ?></h6>
                                            <small class="text-muted">Prezzo: € <?= number_format((float)$prezzo, 2, ',', '.') ?></small><br>
                                            <?php 
                                            if($isAvailable){
                                                echo "<small class='text-muted'>Disponibile</small>";
                                            }
                                            else{
                                                echo "<small class='text-muted'>Non Disponibile</small>";
                                            }
                                            ?>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge <?= $badgeStyle ?> rounded-pill mb-2 d-block"><?= $badgeText ?></span>
                                            <button class="btn btn-sm btn-outline-danger delete-listing-btn"
                                                    data-id="<?php echo htmlspecialchars($offer['id_listing']) ?>"
                                                    data-title="<?php echo htmlspecialchars($titolo) ?>"
                                                    style="font-size: 0.75rem;">
                                                <i class="bi bi-trash"></i> Elimina
                                            </button>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="p-4 text-center text-muted">
                                <p class="mb-0">Non hai ancora pubblicato nessun annuncio.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header text-white" style="background-color: #ff9900;">
                        <h5 class="mb-0 text-dark fw-bold">📦 Le Mie Vendite</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($myOrders)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach($myOrders as $order): 
                                    // Adatta queste chiavi in base alla tua query JOIN degli ordini!
                                    $titoloLibro = $order['title'] ?? 'Libro acquistato';
                                    $dataOrdine = $order['date_order'] ?? 'Data sconosciuta';
                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <div>
                                            <h6 class="mb-1 fw-bold"><?= htmlspecialchars($titoloLibro) ?></h6>
                                            <small class="text-muted">Acquistato il: <?= htmlspecialchars($dataOrdine) ?></small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-dark order-details-btn"
                                                    data-title="<?= htmlspecialchars($titoloLibro) ?>"
                                                    data-date="<?= htmlspecialchars($dataOrdine) ?>"
                                                    data-price="<?= htmlspecialchars($order['final_price'] ?? '0') ?>"
                                                    data-state="<?= htmlspecialchars($order['state'] ?? 'N/D') ?>"
                                                    data-time="<?= htmlspecialchars($order['time_meet'] ?? 'N/D') ?>"
                                                    data-place="<?= htmlspecialchars($order['place_meet'] ?? 'N/D') ?>"
                                                    data-description="<?= htmlspecialchars($order['description_meet'] ?? 'Nessuna nota') ?>">
                                                Dettagli
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning change-state-btn"
                                                    data-order-id="<?= htmlspecialchars($order['id_order'] ?? '') ?>"
                                                    data-title="<?= htmlspecialchars($titoloLibro) ?>"
                                                    data-current-state="<?= htmlspecialchars($order['state'] ?? 'pending') ?>">
                                                <i class="bi bi-arrow-repeat"></i> Cambia Stato
                                            </button>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="p-4 text-center text-muted">
                                <p class="mb-0">Non hai ancora effettuato nessun ordine.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal modifica profilo -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #ff9900;">
                    <h5 class="modal-title text-dark fw-bold">
                        <i class="bi bi-person-fill-gear me-2"></i>Modifica Profilo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?table=User&action=updateProfile">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($userData[0]['name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cognome</label>
                            <input type="text" name="surname" class="form-control" value="<?= htmlspecialchars($userData[0]['surname'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Classe</label>
                            <input type="text" name="class" class="form-control" value="<?= htmlspecialchars($userData[0]['class'] ?? '') ?>" required>
                            <small class="text-muted">Formato: 5N, 3A, ecc.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userData[0]['email'] ?? '') ?>" required>
                            <small class="text-muted">Deve terminare con @isit100.fe.it</small>
                        </div>
                        <hr>
                        <div class="text-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal" data-bs-dismiss="modal">
                                <i class="bi bi-key-fill"></i> Cambia Password
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Salva Modifiche
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal cambio password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-shield-lock-fill me-2"></i>Cambia Password
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?table=User&action=changePassword" id="changePasswordForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Attuale</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nuova Password</label>
                            <input type="password" name="new_password" id="newPassword" class="form-control" required>
                            <small class="text-muted">Minimo 8 caratteri</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Conferma Nuova Password</label>
                            <input type="password" name="confirm_password" id="confirmPassword" class="form-control" required>
                        </div>
                        <div id="passwordError" class="alert alert-danger d-none" role="alert">
                            Le password non coincidono!
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-shield-check"></i> Cambia Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal conferma eliminazione annuncio -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Conferma Eliminazione
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Sei sicuro di voler eliminare questo annuncio?</p>
                    <p class="fw-bold text-dark mb-0" id="deleteBookTitle"></p>
                    <p class="text-muted small mt-2">Questa azione non può essere annullata.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Elimina
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal dettagli ordine -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #ff9900;">
                    <h5 class="modal-title text-dark fw-bold">
                        <i class="bi bi-receipt me-2"></i>Dettagli Ordine
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Libro</label>
                        <p class="mb-0 fs-5 fw-bold" id="orderBookTitle"></p>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="fw-bold text-muted small">Data Ordine</label>
                            <p class="mb-0" id="orderDate"></p>
                        </div>
                        <div class="col-6">
                            <label class="fw-bold text-muted small">Prezzo</label>
                            <p class="mb-0 text-success fw-bold" id="orderPrice"></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Stato</label>
                        <p class="mb-0"><span class="badge bg-primary" id="orderState"></span></p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="fw-bold text-muted small"><i class="bi bi-clock"></i> Orario Incontro</label>
                        <p class="mb-0" id="orderTime"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-muted small"><i class="bi bi-geo-alt"></i> Luogo Incontro</label>
                        <p class="mb-0" id="orderPlace"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-muted small"><i class="bi bi-chat-left-text"></i> Note</label>
                        <p class="mb-0 text-muted" id="orderDescription"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal cambia stato ordine -->
    <form action="index.php?table=Order&action=changeState" method="post">
        <div class="modal fade" id="changeStateModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-dark fw-bold">
                            <i class="bi bi-arrow-repeat me-2"></i>Cambia Stato Ordine
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" id="currentOrderId" name="currentOrderId">
                            <label class="fw-bold text-muted small">Libro</label>
                            <p class="mb-0 fw-bold" id="stateChangeBookTitle"></p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="fw-bold text-muted small">Stato Attuale</label>
                            <p class="mb-0"><span class="badge bg-secondary" id="currentState"></span></p>
                        </div>
                        <div class="mb-3">
                            <label for="newState" class="form-label fw-bold">Nuovo Stato</label>
                            <select class="form-select" id="newState" name="newState">
                                <option value="pending">In attesa</option>
                                <option value="confirmed">Consegnato</option>
                                <option value="cancelled">Annullato</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-warning" id="confirmStateChange">
                            <i class="bi bi-check-circle"></i> Conferma Cambio
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal conferma logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-box-arrow-right me-2"></i>Conferma Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Sei sicuro di voler uscire dal tuo account?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <a href="index.php?table=login&action=logout" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right"></i> Esci
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'views/ToastNotification.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Gestione modal eliminazione annuncio
    document.addEventListener('DOMContentLoaded', function() {
        // Gestione modal dettagli ordine
        const orderDetailsButtons = document.querySelectorAll('.order-details-btn');
        const orderDetailsModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));

        orderDetailsButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('orderBookTitle').textContent = this.dataset.title;
                document.getElementById('orderDate').textContent = this.dataset.date;

                const price = parseFloat(this.dataset.price);
                document.getElementById('orderPrice').textContent = price > 0 ? '€ ' + price.toFixed(2).replace('.', ',') : 'Scambio';

                document.getElementById('orderState').textContent = this.dataset.state;
                document.getElementById('orderTime').textContent = this.dataset.time;
                document.getElementById('orderPlace').textContent = this.dataset.place;
                document.getElementById('orderDescription').textContent = this.dataset.description;

                orderDetailsModal.show();
            });
        });

        // Gestione modal cambia stato
        const changeStateButtons = document.querySelectorAll('.change-state-btn');
        const changeStateModal = new bootstrap.Modal(document.getElementById('changeStateModal'));
        let currentOrderId = null;

        changeStateButtons.forEach(button => {
            button.addEventListener('click', function() {
                currentOrderId = this.dataset.orderId;
                const bookTitle = this.dataset.title;
                const currentState = this.dataset.currentState;

                document.getElementById('currentOrderId').value = currentOrderId;
                document.getElementById('stateChangeBookTitle').textContent = bookTitle;
                document.getElementById('currentState').textContent = currentState;
                document.getElementById('newState').value = currentState;
                
                changeStateModal.show();
            });
        });

        // Conferma cambio stato (da implementare)
        document.getElementById('confirmStateChange').addEventListener('click', function() {
            const newState = document.getElementById('newState').value;
            changeStateModal.hide();

        });

        const deleteButtons = document.querySelectorAll('.delete-listing-btn');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const deleteBookTitle = document.getElementById('deleteBookTitle');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const listingId = this.dataset.id;
                const bookTitle = this.dataset.title;

                deleteBookTitle.textContent = bookTitle;
                confirmDeleteBtn.href = `index.php?table=Listings&action=deleteListing&id=${listingId}`;

                deleteModal.show();
            });
        });

        // Gestione modal logout
        const logoutBtn = document.getElementById('logoutBtn');
        const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));

        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                logoutModal.show();
            });
        }

        // Validazione cambio password
        const changePasswordForm = document.getElementById('changePasswordForm');
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');
        const passwordError = document.getElementById('passwordError');

        if (changePasswordForm) {
            changePasswordForm.addEventListener('submit', function(e) {
                passwordError.classList.add('d-none');

                if (newPassword.value !== confirmPassword.value) {
                    e.preventDefault();
                    passwordError.classList.remove('d-none');
                    confirmPassword.focus();
                    return false;
                }

                if (newPassword.value.length < 8) {
                    e.preventDefault();
                    passwordError.textContent = 'La password deve essere di almeno 8 caratteri!';
                    passwordError.classList.remove('d-none');
                    newPassword.focus();
                    return false;
                }
            });

            // Rimuovi errore quando l'utente digita
            confirmPassword.addEventListener('input', function() {
                if (newPassword.value === confirmPassword.value) {
                    passwordError.classList.add('d-none');
                }
            });
        }
    });
    </script>
</body>
</html>