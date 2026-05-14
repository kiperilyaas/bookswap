<?php
defined("APP") or die("ACCESSO NEGATO");

$ordiniAttivi = [];
$ordiniChiusi = [];
if (!empty($myOrders)) {
    foreach ($myOrders as $order) {
        $gs = $order['state']          ?? 'open';
        $sc = $order['state_customer'] ?? 'pending';
        $ss = $order['state_seller']   ?? 'pending';
        // Ordini chiusi: completati o annullati
        if ($gs === 'closed' || ($sc === 'confirmed' && $ss === 'confirmed') || $sc === 'cancelled' || $ss === 'cancelled')
            $ordiniChiusi[] = $order;
        else
            $ordiniAttivi[] = $order;
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I Miei Ordini | BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="views/bookswap-responsive.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--orange) 0%, #ffb84d 100%);
            padding: var(--sp-lg) 0;
            margin-bottom: var(--sp-lg);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .page-title { color: var(--dark); font-weight: 800; font-size: var(--text-2xl); margin: 0; }

        .order-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: var(--sp-md);
            margin-bottom: var(--sp-md);
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: all 0.3s;
        }
        .order-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.13); transform: translateY(-2px); }
        .order-card-history { background: #fdfdfd; border-radius: var(--radius-sm); padding: 15px; margin-bottom: 1rem; border: 1px solid #e0e0e0; }
        .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--sp-sm); padding-bottom: var(--sp-sm); border-bottom: 2px solid #f0f0f0; flex-wrap: wrap; gap: 0.5rem; }
        .order-id { font-size: var(--text-xs); color: #666; font-weight: 600; }
        .book-title { font-size: var(--text-md); font-weight: 700; color: var(--dark); margin-bottom: 10px; }
        .order-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: clamp(8px, 1vw, 16px); margin-top: var(--sp-sm); }
        .detail-item { background-color: #f8f9fa; padding: clamp(8px, 0.8vw, 14px); border-radius: var(--radius-sm); }
        .detail-label { font-size: var(--text-xs); color: #666; text-transform: uppercase; font-weight: 600; }
        .detail-value { font-size: var(--text-sm); margin-top: 2px; }
        .badge-state { padding: clamp(5px,0.5vw,9px) clamp(10px,1vw,18px); border-radius: 20px; font-weight: 600; font-size: var(--text-xs); }
        .state-pending   { background: #fff3cd; color: #856404; }
        .state-confirmed { background: #d4edda; color: #155724; }
        .state-cancelled { background: #f8d7da; color: #721c24; }
        .empty-state { text-align: center; padding: var(--sp-xl) var(--sp-md); background: white; border-radius: var(--radius-lg); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand ms-2" href="index.php">📚 BookSwap</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto me-2">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-fill"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?table=User&action=account"><i class="bi bi-person-circle"></i> Area Personale</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title"><i class="bi bi-box-seam me-2"></i>I Miei Ordini</h1>
                <p class="text-dark mb-0" style="font-size:var(--text-sm);">Visualizza e gestisci i tuoi acquisti</p>
            </div>
            <?php if(count($ordiniChiusi) > 0): ?>
            <button class="btn btn-dark shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#storicoOrdini">
                <i class="bi bi-clock-history"></i> Storico (<?= count($ordiniChiusi) ?>)
            </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="container pb-5">
        <?php if (!empty($ordiniAttivi)): ?>
            <?php foreach($ordiniAttivi as $order):
                $orderId    = $order['id_order']       ?? 'N/D';
                $bookTitle  = $order['title']           ?? 'Libro sconosciuto';
                $bookIsbn   = $order['isbn']            ?? 'N/D';
                $bookAuthor = $order['author']          ?? 'N/D';
                $dateOrder  = $order['date_order']      ?? 'N/D';
                $dateFmt    = ($dateOrder !== 'N/D') ? date('d/m/Y', strtotime($dateOrder)) . ' alle ' . date('H:i', strtotime($dateOrder)) : 'N/D';
                $sc         = $order['state_customer']  ?? 'pending';
                $ss         = $order['state_seller']    ?? 'pending';
                $finalPrice = $order['final_price']     ?? 0;
                $timeMeet   = $order['time_meet']       ?? 'N/D';
                $timeFmt    = ($timeMeet !== 'N/D') ? date('d/m/Y', strtotime($timeMeet)) . ' alle ' . date('H:i', strtotime($timeMeet)) : 'N/D';
                $placeMeet  = $order['place_meet']      ?? 'N/D';
                $descMeet   = $order['description_meet'] ?? '';
                $sellerFull = strtoupper(trim(($order['name'] ?? 'N/D') . ' ' . ($order['surname'] ?? '')));

                // Determina stato e badge
                $badgeClass = 'state-pending';
                $stateText  = 'In attesa';
                $showWarning = false;
                $warningMsg = '';

                if($sc === 'cancelled' || $ss === 'cancelled') {
                    $badgeClass = 'state-cancelled';
                    $stateText = 'Annullato';
                } elseif($sc === 'confirmed' && $ss === 'confirmed') {
                    $badgeClass = 'state-confirmed';
                    $stateText = 'Completato';
                } elseif($sc === 'confirmed' && $ss === 'pending') {
                    $badgeClass = 'state-confirmed';
                    $stateText = 'Confermato da te';
                    $showWarning = true;
                    $warningMsg = 'In attesa della conferma del venditore';
                } elseif($sc === 'pending' && $ss === 'confirmed') {
                    $badgeClass = 'state-pending';
                    $stateText = 'In attesa';
                    $showWarning = true;
                    $warningMsg = 'Il venditore ha confermato la consegna';
                }

                $priceFmt = ($finalPrice > 0) ? '€ ' . number_format($finalPrice, 2, ',', '.') : 'Scambio';
            ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <div class="order-id"><i class="bi bi-calendar3"></i> <?= htmlspecialchars($dateFmt) ?></div>
                    </div>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <span class="badge-state <?= $badgeClass ?>"><?= $stateText ?></span>
                        <button class="btn btn-sm btn-outline-primary view-order-details-btn"
                                data-order-id="<?= htmlspecialchars($orderId) ?>"
                                data-book-title="<?= htmlspecialchars($bookTitle) ?>"
                                data-seller="<?= htmlspecialchars($sellerFull) ?>"
                                data-price="<?= htmlspecialchars($priceFmt) ?>"
                                data-time="<?= htmlspecialchars($timeFmt) ?>"
                                data-place="<?= htmlspecialchars($placeMeet) ?>"
                                data-description="<?= htmlspecialchars($descMeet) ?>"
                                data-listing-id="<?= htmlspecialchars($order['id_listing'] ?? '') ?>">
                            <i class="bi bi-info-circle"></i> Riepilogo
                        </button>
                        <?php if($sc === 'pending' && $ss !== 'cancelled'): ?>
                        <button class="btn btn-sm btn-success confirm-receipt-btn"
                                data-order-id="<?= htmlspecialchars($orderId) ?>"
                                data-book-title="<?= htmlspecialchars($bookTitle) ?>"
                                title="Conferma di aver ricevuto il libro">
                            <i class="bi bi-check-circle-fill"></i> Ho ricevuto
                        </button>
                        <button class="btn btn-sm btn-outline-danger cancel-order-btn"
                                data-order-id="<?= htmlspecialchars($orderId) ?>"
                                data-book-title="<?= htmlspecialchars($bookTitle) ?>"
                                title="Annulla l'ordine">
                            <i class="bi bi-x-circle"></i> Annulla
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($showWarning): ?>
                <div class="alert alert-warning mb-3 py-2" style="font-size:var(--text-xs);">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i><?= htmlspecialchars($warningMsg) ?>
                </div>
                <?php endif; ?>

                <!-- Immagine e dettagli libro -->
                <div class="row mb-3">
                    <div class="col-md-3 col-4 mb-2">
                        <img src="../utils/immagini/prova_libro.png"
                             class="img-fluid rounded shadow-sm order-main-image"
                             alt="Copertina libro"
                             data-listing-id="<?= htmlspecialchars($order['id_listing'] ?? '') ?>"
                             style="width: 100%; height: auto; object-fit: cover;">
                    </div>
                    <div class="col-md-9 col-8">
                        <div class="book-title mb-2"><i class="bi bi-book"></i> <?= htmlspecialchars($bookTitle) ?></div>
                        <div class="mb-2">
                            <small class="text-muted"><i class="bi bi-upc-scan"></i> ISBN: <?= htmlspecialchars($bookIsbn) ?></small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted"><i class="bi bi-pen"></i> Autore: <?= htmlspecialchars($bookAuthor) ?></small>
                        </div>
                        <span class="badge bg-secondary mb-2"><i class="bi bi-person-fill"></i> Venditore: <?= htmlspecialchars($sellerFull) ?></span>
                        <div class="text-success fw-bold fs-5"><?= $priceFmt ?></div>
                    </div>
                </div>

                <div class="order-details">
                    <div class="detail-item">
                        <div class="detail-label"><i class="bi bi-clock"></i> Orario Incontro</div>
                        <div class="detail-value"><?= htmlspecialchars($timeFmt) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label"><i class="bi bi-geo-alt"></i> Luogo Incontro</div>
                        <div class="detail-value"><?= htmlspecialchars($placeMeet) ?></div>
                    </div>
                </div>
                <?php if($descMeet): ?>
                <div class="mt-3 p-3" style="background:#f8f9fa;border-radius:var(--radius-sm);">
                    <div class="detail-label mb-1"><i class="bi bi-chat-left-text"></i> Note</div>
                    <p class="mb-0 text-muted" style="font-size:var(--text-sm);"><?= htmlspecialchars($descMeet) ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-inbox" style="font-size:clamp(3rem,5vw,5rem);color:#ccc;display:block;margin-bottom:1rem;"></i>
                <h3 class="text-muted mb-3">Nessun ordine in corso</h3>
                <p class="text-muted mb-4">Tutti i tuoi acquisti passati sono nello storico.</p>
                <a href="index.php" class="btn-amazon">
                    <i class="bi bi-search"></i> Scopri i Libri
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Storico offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="storicoOrdini" style="width:clamp(300px,40vw,440px);">
        <div class="offcanvas-header bg-light border-bottom">
            <h5 class="offcanvas-title fw-bold"><i class="bi bi-archive-fill text-secondary me-2"></i>Storico Ordini</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="background-color:var(--bg);">
            <?php if (!empty($ordiniChiusi)): ?>
                <?php foreach($ordiniChiusi as $order):
                    $fp  = $order['final_price'] ?? 0;
                    $pfmt = ($fp > 0) ? '€ ' . number_format($fp, 2, ',', '.') : 'Scambio';
                    $sc  = $order['state_customer'] ?? 'pending';
                    $sfn = strtoupper(trim(($order['name'] ?? 'N/D') . ' ' . ($order['surname'] ?? '')));
                    $bc  = ($sc === 'cancelled') ? 'state-cancelled' : 'state-confirmed';
                    $bt  = ($sc === 'cancelled') ? 'Annullato' : 'Completato';
                ?>
                <div class="order-card-history">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size:var(--text-xs);color:#666;font-weight:600;">Venditore: <?= htmlspecialchars($sfn) ?></span>
                        <span class="badge-state <?= $bc ?>" style="font-size:0.72rem;"><?= $bt ?></span>
                    </div>
                    <h6 class="fw-bold mb-1" style="font-size:var(--text-sm);">Titolo: <?= htmlspecialchars($order['title'] ?? 'N/D') ?></h6>
                    <div class="d-flex justify-content-between align-items-end mt-3">
                        <span class="text-muted" style="font-size:var(--text-xs);">Prezzo:</span>
                        <span class="fw-bold <?= ($sc === 'cancelled') ? 'text-muted text-decoration-line-through' : 'text-success' ?>" style="font-size:var(--text-sm);"><?= $pfmt ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modale riepilogo ordine -->
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
                            <h5 class="fw-bold mb-3" id="detailBookTitle"></h5>

                            <div class="mb-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Prezzo:</span>
                                    <span class="fs-4 fw-bold text-success" id="detailPrice"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold text-muted small mb-1"><i class="bi bi-person-fill"></i> Venditore</label>
                                <p class="mb-0 fw-bold" id="detailSeller"></p>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold text-muted small mb-1"><i class="bi bi-clock-fill"></i> Orario Incontro</label>
                                <p class="mb-0" id="detailTime"></p>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold text-muted small mb-1"><i class="bi bi-geo-alt-fill"></i> Luogo Incontro</label>
                                <p class="mb-0" id="detailPlace"></p>
                            </div>

                            <div class="mb-3" id="detailDescriptionContainer" style="display:none;">
                                <label class="fw-bold text-muted small mb-1"><i class="bi bi-chat-left-text-fill"></i> Note</label>
                                <p class="mb-0 text-muted" id="detailDescription"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale conferma ricezione -->
    <div class="modal fade" id="confirmReceiptModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle-fill me-2"></i>Conferma Ricezione</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Confermi di aver ricevuto il libro:</p>
                    <div class="alert alert-light border">
                        <strong id="confirmBookTitle"></strong>
                    </div>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-info-circle"></i> Questa azione segnalerà l'ordine come completato da parte tua.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-success" id="confirmReceiptBtn">
                        <i class="bi bi-check-circle-fill"></i> Conferma Ricezione
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale annulla ordine -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-x-circle-fill me-2"></i>Annulla Ordine</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Vuoi annullare l'ordine del libro:</p>
                    <div class="alert alert-light border">
                        <strong id="cancelBookTitle"></strong>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Attenzione:</strong> Questa azione è irreversibile e il libro tornerà disponibile per altri acquirenti.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                        <i class="bi bi-x-circle-fill"></i> Annulla Ordine
                    </button>
                </div>
            </div>
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
        const detailsModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));

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

        // Gestione bottone "Ho ricevuto"
        const confirmReceiptModal = new bootstrap.Modal(document.getElementById('confirmReceiptModal'));
        let currentReceiptOrderId = null;

        document.querySelectorAll('.confirm-receipt-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentReceiptOrderId = this.dataset.orderId;
                const bookTitle = this.dataset.bookTitle;
                document.getElementById('confirmBookTitle').textContent = bookTitle;
                confirmReceiptModal.show();
            });
        });

        document.getElementById('confirmReceiptBtn').addEventListener('click', function() {
            if(currentReceiptOrderId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?table=Order&action=changeStateCustomer';

                const orderInput = document.createElement('input');
                orderInput.type = 'hidden';
                orderInput.name = 'currentOrderId';
                orderInput.value = currentReceiptOrderId;

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

        // Gestione bottone "Annulla"
        const cancelOrderModal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
        let currentCancelOrderId = null;

        document.querySelectorAll('.cancel-order-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentCancelOrderId = this.dataset.orderId;
                const bookTitle = this.dataset.bookTitle;
                document.getElementById('cancelBookTitle').textContent = bookTitle;
                cancelOrderModal.show();
            });
        });

        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
            if(currentCancelOrderId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?table=Order&action=changeStateCustomer';

                const orderInput = document.createElement('input');
                orderInput.type = 'hidden';
                orderInput.name = 'currentOrderId';
                orderInput.value = currentCancelOrderId;

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

        // Gestione modale riepilogo ordine
        document.querySelectorAll('.view-order-details-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const bookTitle = this.dataset.bookTitle;
                const seller = this.dataset.seller;
                const price = this.dataset.price;
                const time = this.dataset.time;
                const place = this.dataset.place;
                const description = this.dataset.description;
                const listingId = this.dataset.listingId;

                document.getElementById('detailBookTitle').textContent = bookTitle;
                document.getElementById('detailSeller').textContent = seller;
                document.getElementById('detailPrice').textContent = price;
                document.getElementById('detailTime').textContent = time;
                document.getElementById('detailPlace').textContent = place;

                const descContainer = document.getElementById('detailDescriptionContainer');
                if (description && description.trim() !== '') {
                    document.getElementById('detailDescription').textContent = description;
                    descContainer.style.display = 'block';
                } else {
                    descContainer.style.display = 'none';
                }

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

                detailsModal.show();
            });
        });
    });
    </script>
</body>
</html>
