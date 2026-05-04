<?php
defined("APP") or die("ACCESSO NEGATO");

$annunciAttivi = [];
if (!empty($myOffers)) {
    foreach($myOffers as $offer) {
        if (($offer['is_available'] ?? 1) == 1) $annunciAttivi[] = $offer;
    }
}
$venditeInCorso = [];
$venditeCompletate = [];
if (!empty($myOrders)) {
    foreach($myOrders as $order) {
        $sc = $order['state_customer'] ?? 'pending';
        $ss = $order['state_seller']   ?? 'pending';
        $gs = $order['state']          ?? 'open';
        if ($gs === 'closed' || ($sc === 'confirmed' && $ss === 'confirmed') || $ss === 'cancelled' || $sc === 'cancelled')
            $venditeCompletate[] = $order;
        else
            $venditeInCorso[] = $order;
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
    <link rel="stylesheet" href="views/bookswap-responsive.css">
    <style>
        .nav-tabs .nav-link { color: #212529; font-weight: 600; border: none; border-bottom: 3px solid transparent; padding: 0.8rem 1.2rem; font-size: var(--text-sm); }
        .nav-tabs .nav-link:hover { color: var(--orange); background-color: #f1f3f5; }
        .nav-tabs .nav-link.active { color: var(--orange); background: white; border-bottom: 3px solid var(--orange); }
        .action-card { transition: transform 0.2s, box-shadow 0.2s; border-left: 4px solid transparent; }
        .action-card:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important; }
        .border-left-active  { border-left-color: #198754; }
        .border-left-warning { border-left-color: var(--orange, #ff9900); }
        .modal-header.bg-amazon { background-color: var(--orange, #ff9900); color: #131921; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #131921;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">📚 BookSwap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#accountNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="accountNavbar">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php" style="color: white !important;"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
                    <li class="nav-item"><a class="btn btn-outline-danger btn-sm rounded-pill px-3" href="#" id="logoutBtn"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Header profilo -->
        <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-4 rounded shadow-sm border flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-person-circle" style="font-size:clamp(2.5rem,4vw,4rem);color:var(--orange, #ff9900);"></i>
                <div>
                    <h2 class="mb-0 fw-bold" style="font-size:var(--text-xl, 1.5rem);">Area Personale</h2>
                    <?php if(!empty($userData)): ?>
                        <p class="text-muted mb-0" style="font-size:var(--text-md, 1rem);"><?= htmlspecialchars(($userData[0]['name'] ?? '') . ' ' . ($userData[0]['surname'] ?? '')) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="bi bi-gear-fill me-1"></i>Impostazioni
            </button>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded shadow-sm border overflow-hidden">
            <ul class="nav nav-tabs border-bottom-0" id="accountTabs" role="tablist" style="overflow-x: hidden;">
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

            <div class="tab-content p-3 p-md-4" id="accountTabsContent">

                <!-- Vetrina -->
                <div class="tab-pane fade show active" id="vetrina" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="fw-bold mb-0 text-success" style="font-size:var(--text-md, 1.1rem);">Annunci visibili agli acquirenti</h5>
                        <a href="index.php?table=Listings&action=createListings" class="btn btn-sm btn-success rounded-pill px-3">
                            <i class="bi bi-plus-lg"></i> Nuovo Annuncio
                        </a>
                    </div>
                    <?php if (!empty($annunciAttivi)): ?>
                        <div class="row g-3">
                            <?php foreach($annunciAttivi as $offer): ?>
                            <div class="col-md-6 col-lg-4 col-12">
                                <div class="card action-card border-left-active h-100 p-3">
                                    <h6 class="fw-bold mb-1 text-truncate" title="<?= htmlspecialchars($offer['title']) ?>"><?= htmlspecialchars($offer['title']) ?></h6>
                                    <div class="text-muted mb-2" style="font-size:var(--text-xs, 0.8rem);"><i class="bi bi-upc-scan"></i> ISBN: <?= htmlspecialchars($offer['isbn']) ?></div>
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
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Da Consegnare -->
                <div class="tab-pane fade" id="vendite" role="tabpanel">
                    <h5 class="fw-bold mb-4" style="color:var(--orange, #ff9900);font-size:var(--text-md, 1.1rem);">Ordini da consegnare</h5>
                    <?php if (!empty($venditeInCorso)): ?>
                        <div class="list-group">
                            <?php foreach($venditeInCorso as $order):
                                $titoloLibro = $order['title']      ?? 'Libro';
                                $dataOrdine  = $order['date_order'] ?? 'N/D';
                                $buyerName   = htmlspecialchars(($order['customerName'] ?? 'Utente') . ' ' . ($order['customerSurname'] ?? ''));
                                $buyerEmail  = htmlspecialchars($order['customerEmail'] ?? 'N/D');
                                $sc = $order['state_customer'] ?? 'pending';
                                $ss = $order['state_seller']   ?? 'pending';

                                // Determina stato e badge
                                $badgeClass = 'bg-warning text-dark';
                                $stateText = 'In Lavorazione';
                                $showWarning = false;
                                $warningMsg = '';

                                if($sc === 'cancelled' || $ss === 'cancelled') {
                                    $badgeClass = 'bg-danger text-white';
                                    $stateText = 'Annullato';
                                } elseif($sc === 'confirmed' && $ss === 'confirmed') {
                                    $badgeClass = 'bg-success text-white';
                                    $stateText = 'Completato';
                                } elseif($ss === 'confirmed' && $sc === 'pending') {
                                    $badgeClass = 'bg-success text-white';
                                    $stateText = 'Confermato da te';
                                    $showWarning = true;
                                    $warningMsg = 'In attesa della conferma dell\'acquirente';
                                } elseif($ss === 'pending' && $sc === 'confirmed') {
                                    $badgeClass = 'bg-warning text-dark';
                                    $stateText = 'In Lavorazione';
                                    $showWarning = true;
                                    $warningMsg = 'L\'acquirente ha confermato la ricezione';
                                }
                            ?>
                            <div class="list-group-item action-card border-left-warning p-3 mb-2 rounded shadow-sm">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <span class="badge <?= $badgeClass ?> mb-2"><i class="bi bi-hourglass-split"></i> <?= $stateText ?></span>
                                        <?php if($showWarning): ?>
                                        <div class="alert alert-info mb-2 py-1 px-2" style="font-size:var(--text-xs);">
                                            <i class="bi bi-info-circle-fill me-1"></i><?= htmlspecialchars($warningMsg) ?>
                                        </div>
                                        <?php endif; ?>
                                        <h5 class="mb-1 fw-bold" style="font-size:var(--text-md, 1.1rem);"><?= htmlspecialchars($titoloLibro) ?></h5>
                                        <div class="p-2 my-2 bg-light rounded border">
                                            <p class="mb-0 small text-dark"><strong><i class="bi bi-person-fill"></i> Acquirente:</strong> <?= $buyerName ?></p>
                                            <p class="mb-0 small text-muted"><strong><i class="bi bi-envelope-fill"></i> Email:</strong> <a href="mailto:<?= $buyerEmail ?>"><?= $buyerEmail ?></a></p>
                                        </div>
                                        <p class="mb-0 text-muted small"><i class="bi bi-calendar-event"></i> <?= htmlspecialchars($dataOrdine) ?></p>
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <button class="btn btn-sm btn-outline-dark order-details-btn"
                                                data-title="<?= htmlspecialchars($titoloLibro) ?>"
                                                data-buyer="<?= $buyerName ?>"
                                                data-email="<?= $buyerEmail ?>"
                                                data-date="<?= htmlspecialchars($dataOrdine) ?>"
                                                data-price="<?= htmlspecialchars($order['final_price'] ?? '0') ?>"
                                                data-time="<?= htmlspecialchars($order['time_meet'] ?? 'N/D') ?>"
                                                data-place="<?= htmlspecialchars($order['place_meet'] ?? 'N/D') ?>"
                                                data-description="<?= htmlspecialchars($order['description_meet'] ?? '') ?>">
                                            <i class="bi bi-info-circle"></i> Riepilogo
                                        </button>
                                        <?php if($sc !== 'cancelled' && $ss !== 'cancelled'): ?>
                                        <button class="btn btn-sm btn-success change-state-btn"
                                                data-order-id="<?= htmlspecialchars($order['id_order'] ?? '') ?>"
                                                data-title="<?= htmlspecialchars($titoloLibro) ?>"
                                                data-current-state="<?= htmlspecialchars($ss) ?>">
                                            <i class="bi bi-arrow-repeat"></i> Aggiorna Stato
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted border rounded bg-light">
                            <i class="bi bi-emoji-smile fs-1 d-block mb-3"></i>
                            <h5>Nessuna consegna in sospeso!</h5>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Storico -->
                <div class="tab-pane fade" id="storico" role="tabpanel">
                    <h5 class="fw-bold mb-4 text-secondary" style="font-size:var(--text-md, 1.1rem);">Vendite completate o annullate</h5>
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
                                        $ss = $order['state_seller'] ?? 'pending';
                                        $sc = $order['state_customer'] ?? '';
                                        $isAnn = ($ss === 'cancelled' || $sc === 'cancelled');
                                        $badgeClass = $isAnn ? 'bg-danger' : 'bg-secondary';
                                        $badgeText  = $isAnn ? 'Annullato' : 'Completato';
                                        $prezzoFmt  = ($order['final_price'] > 0) ? '€ ' . number_format($order['final_price'], 2, ',', '.') : 'Scambio';
                                        $buyerStorico = htmlspecialchars(($order['customerName'] ?? 'Utente') . ' ' . ($order['customerSurname'] ?? ''));
                                    ?>
                                    <tr>
                                        <td class="fw-bold text-dark <?= $isAnn ? 'text-muted text-decoration-line-through' : '' ?>"><?= htmlspecialchars($order['title']) ?></td>
                                        <td class="text-dark"><small><?= $buyerStorico ?></small></td>
                                        <td class="text-muted small"><?= htmlspecialchars($order['date_order']) ?></td>
                                        <td class="<?= $isAnn ? 'text-muted' : 'text-success fw-bold' ?>"><?= $prezzoFmt ?></td>
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
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <!-- Modali -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-amazon text-dark">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-fill-gear me-2"></i>Modifica Profilo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?table=User&action=updateProfile">
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold text-dark">Nome</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($userData[0]['name'] ?? '') ?>" required></div>
                        <div class="mb-3"><label class="form-label fw-bold text-dark">Cognome</label><input type="text" name="surname" class="form-control" value="<?= htmlspecialchars($userData[0]['surname'] ?? '') ?>" required></div>
                        <div class="mb-3"><label class="form-label fw-bold text-dark">Classe</label><input type="text" name="class" class="form-control" value="<?= htmlspecialchars($userData[0]['class'] ?? '') ?>" required><small class="text-muted">Es: 5N, 3A</small></div>
                        <div class="mb-3"><label class="form-label fw-bold text-dark">Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userData[0]['email'] ?? '') ?>" required><small class="text-muted">@isit100.fe.it</small></div>
                        <hr>
                        <div class="text-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal" data-bs-dismiss="modal">
                                <i class="bi bi-key-fill"></i> Cambia Password
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-dark"><i class="bi bi-check-circle"></i> Salva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-shield-lock-fill me-2"></i>Cambia Password</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?table=User&action=changePassword" id="changePasswordForm">
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold text-dark">Password Attuale</label><input type="password" name="current_password" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label fw-bold text-dark">Nuova Password</label><input type="password" name="new_password" id="newPassword" class="form-control" required><small class="text-muted">Minimo 6 caratteri</small></div>
                        <div class="mb-3"><label class="form-label fw-bold text-dark">Conferma Password</label><input type="password" name="confirm_password" id="confirmPassword" class="form-control" required></div>
                        <div id="passwordError" class="alert alert-danger d-none">Le password non coincidono!</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-shield-check"></i> Conferma</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Conferma Eliminazione</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2 text-dark">Vuoi eliminare questo annuncio?</p>
                    <p class="fw-bold text-dark fs-5 mb-0" id="deleteBookTitle"></p>
                    <p class="text-muted small mt-2">Azione non reversibile.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger"><i class="bi bi-trash"></i> Elimina</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-amazon text-dark">
                    <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i>Riepilogo Ordine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="fw-bold text-muted small">Libro</label><p class="mb-0 fs-5 fw-bold text-dark" id="orderBookTitle"></p></div>
                    <hr>
                    <div class="mb-3 bg-light p-3 rounded border">
                        <label class="fw-bold text-muted small">Acquirente</label>
                        <p class="mb-1 text-dark" id="orderBuyerName"></p>
                        <p class="mb-0 text-muted small" id="orderBuyerEmail"></p>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6"><label class="fw-bold text-muted small">Data Vendita</label><p class="mb-0 text-dark" id="orderDate"></p></div>
                        <div class="col-6"><label class="fw-bold text-muted small">Prezzo</label><p class="mb-0 text-success fw-bold fs-5" id="orderPrice"></p></div>
                    </div>
                    <hr>
                    <div class="mb-3"><label class="fw-bold text-muted small"><i class="bi bi-geo-alt"></i> Luogo</label><p class="mb-0 text-dark" id="orderPlace"></p></div>
                    <div class="mb-3"><label class="fw-bold text-muted small"><i class="bi bi-clock"></i> Orario</label><p class="mb-0 text-dark" id="orderTime"></p></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button></div>
            </div>
        </div>
    </div>

    <form action="index.php?table=Order&action=changeStateSeller" method="post">
        <div class="modal fade" id="changeStateModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-arrow-repeat me-2"></i>Aggiorna Stato Vendita</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="currentOrderId" name="currentOrderId">
                        <div class="mb-3"><label class="fw-bold text-muted small">Libro</label><p class="mb-0 fw-bold fs-5 text-dark" id="stateChangeBookTitle"></p></div>
                        <hr>
                        <div class="mb-3">
                            <label for="newState" class="form-label fw-bold text-dark">Nuovo Stato</label>
                            <select class="form-select border-success text-dark" id="newState" name="newState">
                                <option value="pending">In attesa (Da Consegnare)</option>
                                <option value="confirmed">Consegnato</option>
                                <option value="cancelled">Annulla Vendita</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Indietro</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Salva Stato</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-box-arrow-right me-2"></i>Conferma Logout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><p class="mb-0 fs-5 text-center py-3 text-dark">Sei sicuro di voler uscire?</p></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <a href="index.php?table=login&action=logout" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Esci</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 bg-dark text-white mt-5">
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-white-50">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dettagli ordine
        document.querySelectorAll('.order-details-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const m = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
                document.getElementById('orderBookTitle').textContent = this.dataset.title;
                document.getElementById('orderBuyerName').innerHTML  = '<strong>Nome:</strong> ' + this.dataset.buyer;
                document.getElementById('orderBuyerEmail').innerHTML = '<strong>Email:</strong> <a href="mailto:' + this.dataset.email + '">' + this.dataset.email + '</a>';
                document.getElementById('orderDate').textContent = this.dataset.date;
                const p = parseFloat(this.dataset.price);
                document.getElementById('orderPrice').textContent = p > 0 ? '€ ' + p.toFixed(2).replace('.', ',') : 'Scambio';
                document.getElementById('orderTime').textContent  = this.dataset.time;
                document.getElementById('orderPlace').textContent = this.dataset.place;
                m.show();
            });
        });

        // Cambia stato
        document.querySelectorAll('.change-state-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const m = new bootstrap.Modal(document.getElementById('changeStateModal'));
                document.getElementById('currentOrderId').value = this.dataset.orderId;
                document.getElementById('stateChangeBookTitle').textContent = this.dataset.title;
                document.getElementById('newState').value = this.dataset.currentState;
                m.show();
            });
        });

        // Elimina annuncio
        document.querySelectorAll('.delete-listing-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const m = new bootstrap.Modal(document.getElementById('deleteModal'));
                document.getElementById('deleteBookTitle').textContent = this.dataset.title;
                document.getElementById('confirmDeleteBtn').href = `index.php?table=Listings&action=deleteListing&id=${this.dataset.id}`;
                m.show();
            });
        });

        // Logout
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', e => {
                e.preventDefault();
                new bootstrap.Modal(document.getElementById('logoutModal')).show();
            });
        }

        // Validazione password
        const cpForm = document.getElementById('changePasswordForm');
        if (cpForm) {
            cpForm.addEventListener('submit', function(e) {
                const np = document.getElementById('newPassword').value;
                const cp = document.getElementById('confirmPassword').value;
                const errDiv = document.getElementById('passwordError');
                errDiv.classList.add('d-none');
                if (np !== cp) { e.preventDefault(); errDiv.textContent = 'Le password non coincidono!'; errDiv.classList.remove('d-none'); return; }
                if (np.length < 6) { e.preventDefault(); errDiv.textContent = 'Min 6 caratteri!'; errDiv.classList.remove('d-none'); }
            });
        }
    });
    </script>
</body>
</html>