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
                                $bookIsbn    = $order['isbn']       ?? 'N/D';
                                $bookAuthor  = $order['author']     ?? 'N/D';
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
                                    <div class="flex-grow-1">
                                        <div class="d-flex gap-3 mb-2">
                                            <img src="../utils/immagini/prova_libro.png"
                                                 class="rounded shadow-sm order-main-image"
                                                 alt="Copertina libro"
                                                 data-listing-id="<?= htmlspecialchars($order['id_listing'] ?? '') ?>"
                                                 style="width: 80px; height: 100px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <span class="badge <?= $badgeClass ?> mb-2"><i class="bi bi-hourglass-split"></i> <?= $stateText ?></span>
                                                <?php if($showWarning): ?>
                                                <div class="alert alert-info mb-2 py-1 px-2" style="font-size:var(--text-xs);">
                                                    <i class="bi bi-info-circle-fill me-1"></i><?= htmlspecialchars($warningMsg) ?>
                                                </div>
                                                <?php endif; ?>
                                                <h5 class="mb-1 fw-bold" style="font-size:var(--text-md, 1.1rem);"><?= htmlspecialchars($titoloLibro) ?></h5>
                                                <div class="mb-1">
                                                    <small class="text-muted"><i class="bi bi-upc-scan"></i> ISBN: <?= htmlspecialchars($bookIsbn) ?></small>
                                                </div>
                                                <div class="mb-2">
                                                    <small class="text-muted"><i class="bi bi-pen"></i> Autore: <?= htmlspecialchars($bookAuthor) ?></small>
                                                </div>
                                                <div class="text-success fw-bold fs-5">
                                                    <?= ($order['final_price'] ?? 0) > 0 ? '€ ' . number_format($order['final_price'], 2, ',', '.') : 'Scambio' ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-2 my-2 bg-light rounded border">
                                            <p class="mb-0 small text-dark"><strong><i class="bi bi-person-fill"></i> Acquirente:</strong> <?= $buyerName ?></p>
                                            <p class="mb-0 small text-muted"><strong><i class="bi bi-envelope-fill"></i> Email:</strong> <a href="mailto:<?= $buyerEmail ?>"><?= $buyerEmail ?></a></p>
                                        </div>
                                        <p class="mb-0 text-muted small"><i class="bi bi-calendar-event"></i> <?= htmlspecialchars($dataOrdine) ?></p>
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <button class="btn btn-sm btn-outline-primary order-details-btn"
                                                data-title="<?= htmlspecialchars($titoloLibro) ?>"
                                                data-buyer="<?= $buyerName ?>"
                                                data-email="<?= $buyerEmail ?>"
                                                data-date="<?= htmlspecialchars($dataOrdine) ?>"
                                                data-price="<?= htmlspecialchars($order['final_price'] ?? '0') ?>"
                                                data-time="<?= htmlspecialchars($order['time_meet'] ?? 'N/D') ?>"
                                                data-place="<?= htmlspecialchars($order['place_meet'] ?? 'N/D') ?>"
                                                data-description="<?= htmlspecialchars($order['description_meet'] ?? '') ?>"
                                                data-listing-id="<?= htmlspecialchars($order['id_listing'] ?? '') ?>">
                                            <i class="bi bi-info-circle"></i> Riepilogo
                                        </button>
                                        <?php if($ss === 'pending' && $sc !== 'cancelled'): ?>
                                        <button class="btn btn-sm btn-success confirm-delivery-btn"
                                                data-order-id="<?= htmlspecialchars($order['id_order'] ?? '') ?>"
                                                data-title="<?= htmlspecialchars($titoloLibro) ?>"
                                                title="Conferma di aver consegnato il libro">
                                            <i class="bi bi-check-circle-fill"></i> Ho consegnato
                                        </button>
                                        <?php endif; ?>
                                        <?php if($ss === 'pending' && $sc !== 'cancelled'): ?>
                                        <button class="btn btn-sm btn-outline-danger cancel-sale-btn"
                                                data-order-id="<?= htmlspecialchars($order['id_order'] ?? '') ?>"
                                                data-title="<?= htmlspecialchars($titoloLibro) ?>"
                                                title="Annulla la vendita">
                                            <i class="bi bi-x-circle"></i> Annulla
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
                    <form action="index.php?table=Listings&action=deleteListing" method="POST" id="deleteListingForm">
                        <input type="hidden" name="id" id="deleteListingId">
                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Elimina</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--orange); color: var(--dark);">
                    <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i>Riepilogo Ordine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Carousel Immagini -->
                        <div class="col-md-5 mb-3">
                            <div id="orderImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner" id="orderCarouselImages" style="border-radius: 12px; overflow: hidden;">
                                    <!-- Immagini caricate dinamicamente -->
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#orderImagesCarousel" data-bs-slide="prev" style="display:none;" id="orderCarouselPrev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#orderImagesCarousel" data-bs-slide="next" style="display:none;" id="orderCarouselNext">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>
                            <div class="carousel-indicators position-static mt-2" id="orderCarouselIndicators"></div>
                        </div>

                        <!-- Dettagli Ordine -->
                        <div class="col-md-7">
                            <h5 class="fw-bold mb-3" id="orderBookTitle"></h5>

                            <div class="mb-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Prezzo:</span>
                                    <span class="fs-4 fw-bold text-success" id="orderPrice"></span>
                                </div>
                            </div>

                            <div class="mb-3 p-3 bg-light rounded border">
                                <label class="fw-bold text-muted small mb-1"><i class="bi bi-person-fill"></i> Acquirente</label>
                                <p class="mb-1 text-dark" id="orderBuyerName"></p>
                                <p class="mb-0 text-muted small" id="orderBuyerEmail"></p>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold text-muted small mb-1"><i class="bi bi-calendar-event"></i> Data Ordine</label>
                                <p class="mb-0" id="orderDate"></p>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold text-muted small mb-1"><i class="bi bi-clock-fill"></i> Orario Incontro</label>
                                <p class="mb-0" id="orderTime"></p>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold text-muted small mb-1"><i class="bi bi-geo-alt-fill"></i> Luogo Incontro</label>
                                <p class="mb-0" id="orderPlace"></p>
                            </div>

                            <div class="mb-3" id="orderDescriptionContainer" style="display:none;">
                                <label class="fw-bold text-muted small mb-1"><i class="bi bi-chat-left-text-fill"></i> Note</label>
                                <p class="mb-0 text-muted" id="orderDescription"></p>
                            </div>
                        </div>
                    </div>
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

    <!-- Modale conferma consegna -->
    <div class="modal fade" id="confirmDeliveryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle-fill me-2"></i>Conferma Consegna</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Confermi di aver consegnato il libro:</p>
                    <div class="alert alert-light border">
                        <strong id="confirmDeliveryTitle"></strong>
                    </div>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-info-circle"></i> Questa azione segnalerà la vendita come completata da parte tua.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-success" id="confirmDeliveryBtn">
                        <i class="bi bi-check-circle-fill"></i> Conferma Consegna
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale annulla vendita -->
    <div class="modal fade" id="cancelSaleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-x-circle-fill me-2"></i>Annulla Vendita</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Vuoi annullare la vendita del libro:</p>
                    <div class="alert alert-light border">
                        <strong id="cancelSaleTitle"></strong>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Attenzione:</strong> Questa azione è irreversibile e il libro tornerà disponibile per altri acquirenti.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelSaleBtn">
                        <i class="bi bi-x-circle-fill"></i> Annulla Vendita
                    </button>
                </div>
            </div>
        </div>
    </div>

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
        // Carica immagini principali nelle card degli ordini
        document.querySelectorAll('.order-main-image').forEach(img => {
            const listingId = img.dataset.listingId;
            if (listingId) {
                fetch(`index.php?table=Listings&action=getListingImages&id=${listingId}`)
                    .then(r => r.json())
                    .then(images => {
                        if (images && images.length > 0) {
                            const mainImg = images.find(i => i.is_primary == 1) || images[0];
                            img.src = '../utils/immagini/' + mainImg.image_path;
                        }
                    })
                    .catch(err => console.error('Errore caricamento immagine:', err));
            }
        });

        // Dettagli ordine con carousel
        document.querySelectorAll('.order-details-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const m = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
                const title = this.dataset.title;
                const buyer = this.dataset.buyer;
                const email = this.dataset.email;
                const date = this.dataset.date;
                const price = parseFloat(this.dataset.price);
                const time = this.dataset.time;
                const place = this.dataset.place;
                const description = this.dataset.description;
                const listingId = this.dataset.listingId;

                document.getElementById('orderBookTitle').textContent = title;
                document.getElementById('orderBuyerName').innerHTML = '<strong>Nome:</strong> ' + buyer;
                document.getElementById('orderBuyerEmail').innerHTML = '<strong>Email:</strong> <a href="mailto:' + email + '">' + email + '</a>';
                document.getElementById('orderDate').textContent = date;
                document.getElementById('orderPrice').textContent = price > 0 ? '€ ' + price.toFixed(2).replace('.', ',') : 'Scambio';
                document.getElementById('orderTime').textContent = time;
                document.getElementById('orderPlace').textContent = place;

                const descContainer = document.getElementById('orderDescriptionContainer');
                if (description && description.trim() !== '') {
                    document.getElementById('orderDescription').textContent = description;
                    descContainer.style.display = 'block';
                } else {
                    descContainer.style.display = 'none';
                }

                // Carica carousel immagini
                const carouselImages = document.getElementById('orderCarouselImages');
                const carouselIndicators = document.getElementById('orderCarouselIndicators');
                const carouselPrev = document.getElementById('orderCarouselPrev');
                const carouselNext = document.getElementById('orderCarouselNext');
                const defaultImg = '../utils/immagini/prova_libro.png';

                carouselImages.innerHTML = '';
                carouselIndicators.innerHTML = '';
                carouselPrev.style.display = 'none';
                carouselNext.style.display = 'none';

                if (listingId) {
                    fetch(`index.php?table=Listings&action=getListingImages&id=${listingId}`)
                        .then(r => r.json())
                        .then(images => {
                            if (images && images.length > 0) {
                                if (images.length > 1) {
                                    carouselPrev.style.display = 'block';
                                    carouselNext.style.display = 'block';
                                }
                                images.forEach((img, index) => {
                                    const imgPath = '../utils/immagini/' + img.image_path;
                                    const slide = document.createElement('div');
                                    slide.className = 'carousel-item' + (index === 0 ? ' active' : '');
                                    slide.innerHTML = `<img src="${imgPath}" class="d-block w-100" style="height: 300px; object-fit: contain; background: #f8f9fa;" alt="Foto ${index + 1}">`;
                                    carouselImages.appendChild(slide);

                                    const indicator = document.createElement('button');
                                    indicator.type = 'button';
                                    indicator.setAttribute('data-bs-target', '#orderImagesCarousel');
                                    indicator.setAttribute('data-bs-slide-to', index);
                                    if (index === 0) indicator.className = 'active';
                                    indicator.style.cssText = 'width: 50px; height: 50px; border-radius: 8px; overflow: hidden; margin: 0 4px; border: 2px solid #ddd; background-size: cover; background-position: center;';
                                    indicator.style.backgroundImage = `url('${imgPath}')`;
                                    carouselIndicators.appendChild(indicator);
                                });
                            } else {
                                carouselImages.innerHTML = `<div class="carousel-item active"><img src="${defaultImg}" class="d-block w-100" style="height: 300px; object-fit: contain; background: #f8f9fa;" alt="Nessuna foto"></div>`;
                            }
                        })
                        .catch(err => {
                            carouselImages.innerHTML = `<div class="carousel-item active"><img src="${defaultImg}" class="d-block w-100" style="height: 300px; object-fit: contain; background: #f8f9fa;" alt="Errore"></div>`;
                        });
                } else {
                    carouselImages.innerHTML = `<div class="carousel-item active"><img src="${defaultImg}" class="d-block w-100" style="height: 300px; object-fit: contain; background: #f8f9fa;" alt="Nessuna foto"></div>`;
                }

                m.show();
            });
        });

        // Bottone "Ho consegnato"
        const confirmDeliveryModal = new bootstrap.Modal(document.getElementById('confirmDeliveryModal'));
        let currentDeliveryOrderId = null;

        document.querySelectorAll('.confirm-delivery-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentDeliveryOrderId = this.dataset.orderId;
                const title = this.dataset.title;
                document.getElementById('confirmDeliveryTitle').textContent = title;
                confirmDeliveryModal.show();
            });
        });

        document.getElementById('confirmDeliveryBtn').addEventListener('click', function() {
            if(currentDeliveryOrderId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?table=Order&action=changeStateSeller';

                const orderInput = document.createElement('input');
                orderInput.type = 'hidden';
                orderInput.name = 'currentOrderId';
                orderInput.value = currentDeliveryOrderId;

                const stateInput = document.createElement('input');
                stateInput.type = 'hidden';
                stateInput.name = 'newState';
                stateInput.value = 'confirmed';

                form.appendChild(orderInput);
                form.appendChild(stateInput);
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Bottone "Annulla"
        const cancelSaleModal = new bootstrap.Modal(document.getElementById('cancelSaleModal'));
        let currentCancelSaleOrderId = null;

        document.querySelectorAll('.cancel-sale-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentCancelSaleOrderId = this.dataset.orderId;
                const title = this.dataset.title;
                document.getElementById('cancelSaleTitle').textContent = title;
                cancelSaleModal.show();
            });
        });

        document.getElementById('confirmCancelSaleBtn').addEventListener('click', function() {
            if(currentCancelSaleOrderId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?table=Order&action=changeStateSeller';

                const orderInput = document.createElement('input');
                orderInput.type = 'hidden';
                orderInput.name = 'currentOrderId';
                orderInput.value = currentCancelSaleOrderId;

                const stateInput = document.createElement('input');
                stateInput.type = 'hidden';
                stateInput.name = 'newState';
                stateInput.value = 'cancelled';

                form.appendChild(orderInput);
                form.appendChild(stateInput);
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Cambia stato (vecchio - mantenuto per compatibilità)
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
                document.getElementById('deleteListingId').value = this.dataset.id;
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