<?php
defined("APP") or die("ACCESSO NEGATO");

// --- LOGICA DI SMISTAMENTO INTELLIGENTE ---
$annunciAttivi = [];
if (!empty($myOffers)) {
    foreach($myOffers as $offer) {
        if (($offer['is_available'] ?? 1) == 1) {
            $annunciAttivi[] = $offer;
        }
    }
}

$venditeInCorso = [];
$venditeCompletate = [];
if (!empty($myOrders)) {
    foreach($myOrders as $order) {
        $stateCustomer = $order['state_customer'] ?? 'pending';
        $stateSeller = $order['state_seller'] ?? 'pending';
        $generalState = $order['state'] ?? 'open';
        
        if ($generalState === 'closed' || ($stateCustomer === 'confirmed' && $stateSeller === 'confirmed') || $stateSeller === 'cancelled' || $stateCustomer === 'cancelled') {
            $venditeCompletate[] = $order;
        } else {
            $venditeInCorso[] = $order;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Il Mio Account | BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --amazon-orange: #ff9900;
            --amazon-dark: #131921;
        }
        body { background-color: #f8f9fa; }
        .navbar { background-color: var(--amazon-dark) !important; }
        .navbar-brand { color: white !important; font-weight: 700; }
        
        /* Stile Custom per le Tab */
        .nav-tabs .nav-link { color: #495057; font-weight: 600; border: none; border-bottom: 3px solid transparent; padding: 1rem 1.5rem; }
        .nav-tabs .nav-link:hover { color: var(--amazon-orange); background-color: #f1f3f5; }
        .nav-tabs .nav-link.active { color: var(--amazon-dark); background-color: white; border-bottom: 3px solid var(--amazon-orange); }
        
        /* Card interattive */
        .action-card { transition: transform 0.2s, box-shadow 0.2s; border-left: 4px solid transparent; }
        .action-card:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.1)!important; }
        .border-left-active { border-left-color: #198754; }
        .border-left-warning { border-left-color: var(--amazon-orange); }
        
        /* Modali */
        .modal-header.bg-amazon { background-color: #ff9900; color: #131921; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">📚 BookSwap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#accountNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="accountNavbar">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php"><i class="bi bi-house-door-fill me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger btn-sm rounded-pill px-3" href="#" id="logoutBtn"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="d-flex align-items-center justify-content-between mb-5 bg-white p-4 rounded shadow-sm border">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-person-circle" style="font-size: 3.5rem; color: var(--amazon-orange);"></i>
                <div>
                    <h2 class="mb-0 fw-bold">Area Personale</h2>
                    <?php if(!empty($userData)): ?>
                        <p class="text-muted mb-0 fs-5"><?= htmlspecialchars(($userData[0]['name'] ?? '') . ' ' . ($userData[0]['surname'] ?? '')) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="bi bi-gear-fill me-1"></i> Impostazioni Profilo
            </button>
        </div>

        <div class="bg-white rounded shadow-sm border overflow-hidden">
            <ul class="nav nav-tabs bg-light border-bottom-0" id="accountTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="vetrina-tab" data-bs-toggle="tab" data-bs-target="#vetrina" type="button" role="tab">
                        <i class="bi bi-shop me-2"></i>La Mia Vetrina <span class="badge bg-secondary ms-1"><?= count($annunciAttivi) ?></span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="vendite-tab" data-bs-toggle="tab" data-bs-target="#vendite" type="button" role="tab">
                        <i class="bi bi-handshake me-2"></i>Da Consegnare <span class="badge bg-warning text-dark ms-1"><?= count($venditeInCorso) ?></span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="storico-tab" data-bs-toggle="tab" data-bs-target="#storico" type="button" role="tab">
                        <i class="bi bi-archive me-2"></i>Storico Vendite
                    </button>
                </li>
            </ul>

            <div class="tab-content p-4" id="accountTabsContent">
                
                <div class="tab-pane fade show active" id="vetrina" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0 text-success">Annunci attualmente visibili agli acquirenti</h5>
                        <a href="index.php?table=Listings&action=createListings" class="btn btn-sm btn-success rounded-pill px-3">
                            <i class="bi bi-plus-lg"></i> Nuovo Annuncio
                        </a>
                    </div>

                    <?php if (!empty($annunciAttivi)): ?>
                        <div class="row g-3">
                            <?php foreach($annunciAttivi as $offer): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card action-card border-left-active h-100 p-3">
                                    <h6 class="fw-bold mb-1 text-truncate" title="<?= htmlspecialchars($offer['title']) ?>"><?= htmlspecialchars($offer['title']) ?></h6>
                                    <div class="text-muted small mb-2"><i class="bi bi-upc-scan"></i> ISBN: <?= htmlspecialchars($offer['isbn']) ?></div>
                                    <h5 class="text-success fw-bold mb-3">€ <?= number_format((float)$offer['price'], 2, ',', '.') ?></h5>
                                    <div class="text-end mt-auto">
                                        <button class="btn btn-sm btn-outline-danger delete-listing-btn w-100"
                                                data-id="<?= htmlspecialchars($offer['id_listing']) ?>"
                                                data-title="<?= htmlspecialchars($offer['title']) ?>">
                                            <i class="bi bi-trash"></i> Rimuovi
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted border rounded bg-light">
                            <i class="bi bi-shop fs-1 d-block mb-3"></i>
                            <h5>La tua vetrina è vuota!</h5>
                            <p>Non hai nessun libro in vendita al momento.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="vendite" role="tabpanel">
                    <h5 class="fw-bold mb-4" style="color: var(--amazon-orange);">Ordini da consegnare agli acquirenti</h5>
                    
                    <?php if (!empty($venditeInCorso)): ?>
                        <div class="list-group">
                            <?php foreach($venditeInCorso as $order): 
                                $titoloLibro = $order['title'] ?? 'Libro acquistato';
                                $dataOrdine = $order['date_order'] ?? 'Data sconosciuta';
                                
                                // AGGIORNAMENTO QUI: Utilizziamo le nuove chiavi customerName, customerSurname, customerEmail
                                $buyerName = htmlspecialchars(($order['customerName'] ?? 'Utente') . ' ' . ($order['customerSurname'] ?? ''));
                                $buyerEmail = htmlspecialchars($order['customerEmail'] ?? 'N/D');
                            ?>
                            <div class="list-group-item list-group-item-action action-card border-left-warning p-3 mb-2 rounded shadow-sm">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <span class="badge bg-warning text-dark mb-2"><i class="bi bi-hourglass-split"></i> In Lavorazione</span>
                                        <h5 class="mb-1 fw-bold"><?= htmlspecialchars($titoloLibro) ?></h5>
                                        
                                        <div class="p-2 my-2 bg-light rounded border">
                                            <p class="mb-0 text-dark small"><strong><i class="bi bi-person-fill"></i> Acquirente:</strong> <?= $buyerName ?></p>
                                            <p class="mb-0 text-muted small"><strong><i class="bi bi-envelope-fill"></i> Email:</strong> <a href="mailto:<?= $buyerEmail ?>"><?= $buyerEmail ?></a></p>
                                        </div>
                                        
                                        <p class="mb-0 text-muted small"><i class="bi bi-calendar-event"></i> Venduto il: <?= htmlspecialchars($dataOrdine) ?></p>
                                    </div>
                                    <div class="d-flex flex-column gap-2 mt-2 mt-md-0">
                                        <button class="btn btn-sm btn-outline-dark order-details-btn"
                                                data-title="<?= htmlspecialchars($titoloLibro) ?>"
                                                data-buyer="<?= $buyerName ?>"
                                                data-email="<?= $buyerEmail ?>"
                                                data-date="<?= htmlspecialchars($dataOrdine) ?>"
                                                data-price="<?= htmlspecialchars($order['final_price'] ?? '0') ?>"
                                                data-time="<?= htmlspecialchars($order['time_meet'] ?? 'N/D') ?>"
                                                data-place="<?= htmlspecialchars($order['place_meet'] ?? 'N/D') ?>"
                                                data-description="<?= htmlspecialchars($order['description_meet'] ?? 'Nessuna nota') ?>">
                                            <i class="bi bi-info-circle"></i> Riepilogo
                                        </button>
                                        <button class="btn btn-sm btn-success change-state-btn"
                                                data-order-id="<?= htmlspecialchars($order['id_order'] ?? '') ?>"
                                                data-title="<?= htmlspecialchars($titoloLibro) ?>"
                                                data-current-state="<?= htmlspecialchars($order['state_seller'] ?? 'pending') ?>">
                                            <i class="bi bi-arrow-repeat"></i> Aggiorna Stato
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted border rounded bg-light">
                            <i class="bi bi-emoji-smile fs-1 d-block mb-3"></i>
                            <h5>Nessuna consegna in sospeso!</h5>
                            <p>Hai gestito tutti i tuoi ordini o non hai ancora venduto nulla.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="storico" role="tabpanel">
                    <h5 class="fw-bold mb-4 text-secondary">Le tue vendite completate o annullate</h5>
                    
                    <?php if (!empty($venditeCompletate)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th>Libro</th>
                                        <th>Acquirente</th>
                                        <th>Data</th>
                                        <th>Prezzo</th>
                                        <th>Stato</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($venditeCompletate as $order): 
                                        $stateSeller = $order['state_seller'] ?? 'pending';
                                        $isAnnullato = ($stateSeller === 'cancelled' || ($order['state_customer'] ?? '') === 'cancelled');
                                        $badgeClass = $isAnnullato ? 'bg-danger' : 'bg-secondary';
                                        $badgeText = $isAnnullato ? 'Annullato' : 'Completato';
                                        $prezzoFormattato = ($order['final_price'] > 0) ? '€ ' . number_format($order['final_price'], 2, ',', '.') : 'Scambio';
                                        
                                        // AGGIORNAMENTO QUI: Utilizziamo le nuove chiavi
                                        $buyerNameStorico = htmlspecialchars(($order['customerName'] ?? 'Utente') . ' ' . ($order['customerSurname'] ?? ''));
                                    ?>
                                    <tr>
                                        <td class="fw-bold <?= $isAnnullato ? 'text-muted text-decoration-line-through' : '' ?>"><?= htmlspecialchars($order['title']) ?></td>
                                        <td><small><?= $buyerNameStorico ?></small></td>
                                        <td class="text-muted small"><?= htmlspecialchars($order['date_order']) ?></td>
                                        <td class="<?= $isAnnullato ? 'text-muted' : 'text-success fw-bold' ?>"><?= $prezzoFormattato ?></td>
                                        <td><span class="badge <?= $badgeClass ?>"><?= $badgeText ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted border rounded bg-light">
                            <i class="bi bi-archive fs-1 d-block mb-3"></i>
                            <h5>Lo storico è vuoto</h5>
                            <p>Non hai ancora completato nessuna vendita.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-amazon">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-fill-gear me-2"></i>Modifica Profilo</h5>
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
                        <button type="submit" class="btn btn-dark"><i class="bi bi-check-circle"></i> Salva Modifiche</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-shield-lock-fill me-2"></i>Cambia Password</h5>
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
                            <small class="text-muted">Minimo 6 caratteri</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Conferma Nuova Password</label>
                            <input type="password" name="confirm_password" id="confirmPassword" class="form-control" required>
                        </div>
                        <div id="passwordError" class="alert alert-danger d-none" role="alert">Le password non coincidono!</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-shield-check"></i> Conferma</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Conferma Eliminazione</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Sei sicuro di voler eliminare questo annuncio dalla vetrina?</p>
                    <p class="fw-bold text-dark mb-0 fs-5" id="deleteBookTitle"></p>
                    <p class="text-muted small mt-2">Questa azione non può essere annullata.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger"><i class="bi bi-trash"></i> Elimina Definitivamente</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-amazon">
                    <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i>Riepilogo Ordine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Libro</label>
                        <p class="mb-0 fs-5 fw-bold" id="orderBookTitle"></p>
                    </div>
                    <hr>
                    <div class="mb-3 bg-light p-3 rounded border">
                        <label class="fw-bold text-muted small"><i class="bi bi-person-lines-fill"></i> Acquirente</label>
                        <p class="mb-1" id="orderBuyerName"></p>
                        <p class="mb-0 text-muted small" id="orderBuyerEmail"></p>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="fw-bold text-muted small">Data Vendita</label>
                            <p class="mb-0" id="orderDate"></p>
                        </div>
                        <div class="col-6">
                            <label class="fw-bold text-muted small">Prezzo da incassare</label>
                            <p class="mb-0 text-success fw-bold fs-5" id="orderPrice"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="fw-bold text-muted small"><i class="bi bi-geo-alt"></i> Luogo Incontro</label>
                        <p class="mb-0" id="orderPlace"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-muted small"><i class="bi bi-clock"></i> Orario Incontro</label>
                        <p class="mb-0" id="orderTime"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <form action="index.php?table=Order&action=changeStateSeller" method="post">
        <div class="modal fade" id="changeStateModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-arrow-repeat me-2"></i>Aggiorna Stato Vendita</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" id="currentOrderId" name="currentOrderId">
                            <label class="fw-bold text-muted small">Libro Venduto</label>
                            <p class="mb-0 fw-bold fs-5" id="stateChangeBookTitle"></p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="newState" class="form-label fw-bold">Nuovo Stato</label>
                            <select class="form-select border-success" id="newState" name="newState">
                                <option value="pending">In attesa (Da Consegnare)</option>
                                <option value="confirmed">Consegnato (Il cliente ha ricevuto il libro)</option>
                                <option value="cancelled">Annulla Vendita</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Indietro</button>
                        <button type="submit" class="btn btn-success" id="confirmStateChange">
                            <i class="bi bi-check-circle"></i> Salva Stato
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-box-arrow-right me-2"></i>Conferma Logout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0 fs-5 text-center py-3">Sei sicuro di voler uscire dal tuo account?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <a href="index.php?table=login&action=logout" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Esci</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'views/ToastNotification.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. Dettagli Ordine ---
        const orderDetailsButtons = document.querySelectorAll('.order-details-btn');
        const orderDetailsModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));

        orderDetailsButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('orderBookTitle').textContent = this.dataset.title;
                document.getElementById('orderBuyerName').innerHTML = "<strong>Nome:</strong> " + this.dataset.buyer;
                document.getElementById('orderBuyerEmail').innerHTML = "<strong>Email:</strong> <a href='mailto:" + this.dataset.email + "'>" + this.dataset.email + "</a>";
                document.getElementById('orderDate').textContent = this.dataset.date;
                
                const price = parseFloat(this.dataset.price);
                document.getElementById('orderPrice').textContent = price > 0 ? '€ ' + price.toFixed(2).replace('.', ',') : 'Scambio';
                
                document.getElementById('orderTime').textContent = this.dataset.time;
                document.getElementById('orderPlace').textContent = this.dataset.place;
                
                orderDetailsModal.show();
            });
        });

        // --- 2. Cambia Stato Ordine ---
        const changeStateButtons = document.querySelectorAll('.change-state-btn');
        const changeStateModal = new bootstrap.Modal(document.getElementById('changeStateModal'));

        changeStateButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('currentOrderId').value = this.dataset.orderId;
                document.getElementById('stateChangeBookTitle').textContent = this.dataset.title;
                document.getElementById('newState').value = this.dataset.currentState;
                
                changeStateModal.show();
            });
        });

        // --- 3. Elimina Annuncio ---
        const deleteButtons = document.querySelectorAll('.delete-listing-btn');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('deleteBookTitle').textContent = this.dataset.title;
                confirmDeleteBtn.href = `index.php?table=Listings&action=deleteListing&id=${this.dataset.id}`;
                deleteModal.show();
            });
        });

        // --- 4. Logout ---
        const logoutBtn = document.getElementById('logoutBtn');
        const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                logoutModal.show();
            });
        }

        // --- 5. Validazione Cambio Password ---
        const changePasswordForm = document.getElementById('changePasswordForm');
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');
        const passwordError = document.getElementById('passwordError');

        if (changePasswordForm) {
            changePasswordForm.addEventListener('submit', function(e) {
                passwordError.classList.add('d-none');
                
                if (newPassword.value !== confirmPassword.value) {
                    e.preventDefault();
                    passwordError.textContent = 'Le password non coincidono!';
                    passwordError.classList.remove('d-none');
                    confirmPassword.focus();
                    return false;
                }
                
                if (newPassword.value.length < 6) {
                    e.preventDefault();
                    passwordError.textContent = 'La password deve essere di almeno 6 caratteri!';
                    passwordError.classList.remove('d-none');
                    newPassword.focus();
                    return false;
                }
            });

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