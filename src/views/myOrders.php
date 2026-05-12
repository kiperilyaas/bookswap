<?php
defined("APP") or die("ACCESSO NEGATO");

$ordiniAttivi = [];
$ordiniChiusi = [];
if (!empty($myOrders)) {
    foreach ($myOrders as $order) {
        $gs = $order['state']          ?? 'open';
        $sc = $order['state_customer'] ?? 'pending';
        $ss = $order['state_seller']   ?? 'pending';
        if ($gs === 'closed' || ($sc === 'confirmed' && $ss === 'confirmed') || $sc === 'cancelled')
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
        /* ── Page Header ── */
        .page-header {
            background: linear-gradient(135deg, var(--dark) 0%, var(--mid) 100%);
            padding: var(--sp-lg) 0;
            margin-bottom: var(--sp-lg);
            border-bottom: 3px solid var(--orange);
        }
        .page-title {
            color: white;
            font-weight: 800;
            font-size: var(--text-2xl);
            margin: 0;
            letter-spacing: -0.5px;
        }
        .page-subtitle {
            color: #aaa;
            font-size: var(--text-sm);
            margin: 0;
        }
        .page-header .btn-storico {
            background: transparent;
            border: 2px solid var(--orange);
            color: var(--orange);
            font-weight: 700;
            border-radius: 20px;
            padding: 0.5rem 1.4rem;
            font-size: var(--text-sm);
            transition: all 0.2s;
            white-space: nowrap;
        }
        .page-header .btn-storico:hover {
            background: var(--orange);
            color: var(--dark);
        }

        /* ── Order Card ── */
        .order-card {
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: var(--sp-md);
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.10);
        }
        .order-card-accent {
            height: 4px;
            background: linear-gradient(90deg, var(--orange), #ffb84d);
        }
        .order-card-body {
            padding: var(--sp-md);
        }

        /* ── Order Header Row ── */
        .order-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-bottom: var(--sp-sm);
            padding-bottom: var(--sp-sm);
            border-bottom: 1px solid #f0f0f0;
        }
        .order-id-block .order-id {
            font-size: var(--text-xs);
            color: #888;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .order-id-block .order-id i { color: var(--orange); }

        /* ── Status Badges ── */
        .badge-state {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px clamp(10px, 1vw, 16px);
            border-radius: 20px;
            font-weight: 700;
            font-size: var(--text-xs);
            letter-spacing: 0.3px;
        }
        .state-pending   { background: #fff3cd; color: #7a5c00; border: 1px solid #ffe08a; }
        .state-confirmed { background: #d4edda; color: #145a2a; border: 1px solid #b8dfc5; }
        .state-cancelled { background: #f8d7da; color: #721c24; border: 1px solid #f1b0b7; }

        /* ── Book Title ── */
        .order-book-title {
            font-size: var(--text-md);
            font-weight: 700;
            color: var(--dark);
            margin-bottom: var(--sp-xs);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .order-book-title i { color: var(--orange); }

        /* ── Seller Badge ── */
        .seller-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f3f4f6;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: var(--text-xs);
            font-weight: 600;
            color: #444;
            margin-bottom: var(--sp-sm);
        }
        .seller-badge i { color: var(--orange); }

        /* ── Details Grid ── */
        .order-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: clamp(8px, 1vw, 14px);
            margin-top: var(--sp-sm);
        }
        .detail-chip {
            background: #f8fafc;
            border: 1px solid #e8ecf0;
            border-radius: var(--radius-md);
            padding: clamp(10px, 0.9vw, 14px);
        }
        .detail-chip .chip-label {
            font-size: var(--text-xs);
            color: #888;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 3px;
        }
        .detail-chip .chip-label i { color: var(--orange); }
        .detail-chip .chip-value {
            font-size: var(--text-sm);
            font-weight: 600;
            color: var(--dark);
        }
        .chip-price .chip-value { color: #1a7a3a; font-size: var(--text-md); }

        /* ── Note Box ── */
        .order-note {
            background: #fffbf2;
            border-left: 3px solid var(--orange);
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
            padding: 10px 14px;
            margin-top: var(--sp-sm);
            font-size: var(--text-xs);
            color: #555;
        }
        .order-note strong { color: var(--dark); }

        /* ── Warning Alert ── */
        .order-alert {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: var(--radius-sm);
            padding: 8px 14px;
            font-size: var(--text-xs);
            font-weight: 600;
            color: #7a5c00;
            margin-bottom: var(--sp-sm);
        }
        .order-alert i { color: var(--orange); flex-shrink: 0; }

        /* ── Update Button ── */
        .btn-update-state {
            background: transparent;
            border: 1.5px solid var(--orange);
            color: var(--orange);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: var(--text-xs);
            font-weight: 700;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-update-state:hover {
            background: var(--orange);
            color: var(--dark);
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: var(--sp-xl) var(--sp-md);
            background: white;
            border-radius: var(--radius-lg);
            border: 2px dashed #ddd;
        }
        .empty-state-icon {
            font-size: clamp(3rem, 5vw, 5rem);
            color: #ddd;
            display: block;
            margin-bottom: var(--sp-sm);
        }

        /* ── Offcanvas Storico ── */
        #storicoOrdini .offcanvas-header {
            background: var(--dark);
            border-bottom: 2px solid var(--orange);
        }
        #storicoOrdini .offcanvas-title { color: white; font-weight: 700; }
        #storicoOrdini .btn-close { filter: invert(1); }
        #storicoOrdini .offcanvas-body { background: var(--bg); }

        .history-card {
            background: white;
            border-radius: var(--radius-md);
            padding: var(--sp-sm);
            margin-bottom: var(--sp-sm);
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            transition: box-shadow 0.2s;
        }
        .history-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.10); }
        .history-card-title { font-size: var(--text-sm); font-weight: 700; color: var(--dark); margin-bottom: 2px; }
        .history-card-seller { font-size: var(--text-xs); color: #888; }

        /* ── Modal ── */
        .modal-header.modal-header-warning {
            background: linear-gradient(135deg, var(--orange), #ffb84d);
            border-bottom: none;
        }
        .modal-header.modal-header-warning .modal-title { color: var(--dark); font-weight: 800; }
        .modal-header.modal-header-warning .btn-close { filter: none; }

        /* ── Section Label ── */
        .section-label {
            font-size: var(--text-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #888;
            margin-bottom: var(--sp-sm);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── Count Badge ── */
        .count-pill {
            background: var(--orange);
            color: var(--dark);
            font-weight: 800;
            font-size: 0.7rem;
            border-radius: 20px;
            padding: 2px 9px;
        }

        @media (max-width: 576px) {
            .order-details-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand ms-2" href="index.php">📚 BookSwap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ordersNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="ordersNav">
                <ul class="navbar-nav ms-auto me-2">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house-fill me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?table=User&action=account">
                            <i class="bi bi-person-circle me-1"></i>Area Personale
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-box-seam me-2" style="color:var(--orange);"></i>I Miei Ordini
                </h1>
                <p class="page-subtitle mt-1">Visualizza e gestisci i tuoi acquisti</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if (count($ordiniAttivi) > 0): ?>
                    <span class="count-pill"><?= count($ordiniAttivi) ?> attivi</span>
                <?php endif; ?>
                <?php if (count($ordiniChiusi) > 0): ?>
                    <button class="btn-storico" type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#storicoOrdini">
                        <i class="bi bi-clock-history me-1"></i>Storico
                        <span class="ms-1 badge bg-dark text-white" style="font-size:0.7rem;border-radius:12px;"><?= count($ordiniChiusi) ?></span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container pb-5">

        <?php if (!empty($ordiniAttivi)): ?>

            <p class="section-label"><i class="bi bi-hourglass-split" style="color:var(--orange);"></i> Ordini in corso</p>

            <?php foreach ($ordiniAttivi as $order):
                $orderId    = $order['id_order']        ?? 'N/D';
                $bookTitle  = $order['title']            ?? 'Libro sconosciuto';
                $dateOrder  = $order['date_order']       ?? 'N/D';
                $dateFmt    = ($dateOrder !== 'N/D')
                                ? date('d/m/Y', strtotime($dateOrder)) . ' — ' . date('H:i', strtotime($dateOrder))
                                : 'N/D';
                $sc         = $order['state_customer']   ?? 'pending';
                $ss         = $order['state_seller']     ?? 'pending';
                $finalPrice = $order['final_price']      ?? 0;
                $timeMeet   = $order['time_meet']        ?? 'N/D';
                $timeFmt    = ($timeMeet !== 'N/D')
                                ? date('d/m/Y', strtotime($timeMeet)) . ' — ' . date('H:i', strtotime($timeMeet))
                                : 'Non definito';
                $placeMeet  = $order['place_meet']       ?? 'Non definito';
                $descMeet   = $order['description_meet'] ?? '';
                $sellerFull = strtoupper(trim(($order['name'] ?? 'N/D') . ' ' . ($order['surname'] ?? '')));
                $priceFmt   = ($finalPrice > 0)
                                ? '€ ' . number_format($finalPrice, 2, ',', '.')
                                : 'Scambio';

                // Badge e stato
                $badgeClass  = 'state-pending';
                $stateText   = 'In attesa';
                $stateIcon   = 'bi-hourglass-split';
                $showWarning = false;
                $warningMsg  = '';

                if ($sc === 'cancelled' || $ss === 'cancelled') {
                    $badgeClass = 'state-cancelled'; $stateText = 'Annullato'; $stateIcon = 'bi-x-circle-fill';
                } elseif ($sc === 'confirmed' && $ss === 'confirmed') {
                    $badgeClass = 'state-confirmed'; $stateText = 'Completato'; $stateIcon = 'bi-check-circle-fill';
                } elseif ($sc === 'confirmed' && $ss === 'pending') {
                    $badgeClass = 'state-confirmed'; $stateText = 'Confermato da te'; $stateIcon = 'bi-check-circle';
                    $showWarning = true; $warningMsg = 'In attesa della conferma del venditore';
                } elseif ($sc === 'pending' && $ss === 'confirmed') {
                    $badgeClass = 'state-pending'; $stateText = 'In attesa'; $stateIcon = 'bi-hourglass';
                    $showWarning = true; $warningMsg = 'Il venditore ha confermato la consegna — confermala anche tu!';
                }
            ?>
            <div class="order-card">
                <div class="order-card-accent"></div>
                <div class="order-card-body">

                    <!-- Header: ID + Badge + Azioni -->
                    <div class="order-meta">
                        <div class="order-id-block">
                            <div class="order-id">
                                <i class="bi bi-hash"></i>
                                Ordine #<?= htmlspecialchars($orderId) ?>
                            </div>
                            <div class="order-id mt-1">
                                <i class="bi bi-calendar3"></i>
                                <?= htmlspecialchars($dateFmt) ?>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge-state <?= $badgeClass ?>">
                                <i class="bi <?= $stateIcon ?>"></i>
                                <?= $stateText ?>
                            </span>
                            <?php if ($sc !== 'cancelled' && $ss !== 'cancelled'): ?>
                                <button class="btn-update-state change-order-state-btn"
                                        data-order-id="<?= htmlspecialchars($orderId) ?>"
                                        data-book-title="<?= htmlspecialchars($bookTitle) ?>"
                                        data-current-state="<?= htmlspecialchars($sc) ?>">
                                    <i class="bi bi-arrow-repeat"></i> Aggiorna
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Alert -->
                    <?php if ($showWarning): ?>
                        <div class="order-alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?= htmlspecialchars($warningMsg) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Titolo libro -->
                    <div class="order-book-title">
                        <i class="bi bi-book"></i>
                        <?= htmlspecialchars($bookTitle) ?>
                    </div>

                    <!-- Venditore -->
                    <div class="seller-badge">
                        <i class="bi bi-shop"></i>
                        <?= htmlspecialchars($sellerFull) ?>
                    </div>

                    <!-- Dettagli -->
                    <div class="order-details-grid">
                        <div class="detail-chip chip-price">
                            <div class="chip-label"><i class="bi bi-cash-coin"></i> Prezzo</div>
                            <div class="chip-value"><?= $priceFmt ?></div>
                        </div>
                        <div class="detail-chip">
                            <div class="chip-label"><i class="bi bi-clock"></i> Incontro</div>
                            <div class="chip-value"><?= htmlspecialchars($timeFmt) ?></div>
                        </div>
                        <div class="detail-chip">
                            <div class="chip-label"><i class="bi bi-geo-alt"></i> Luogo</div>
                            <div class="chip-value"><?= htmlspecialchars($placeMeet) ?></div>
                        </div>
                    </div>

                    <!-- Note -->
                    <?php if ($descMeet): ?>
                        <div class="order-note">
                            <strong><i class="bi bi-chat-left-text me-1"></i>Note:</strong>
                            <?= htmlspecialchars($descMeet) ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <h4 class="text-muted mb-2">Nessun ordine in corso</h4>
                <p class="text-muted mb-4" style="font-size:var(--text-sm);">
                    Gli ordini passati li trovi nello storico. Sfoglia i libri disponibili!
                </p>
                <a href="index.php" class="btn-amazon">
                    <i class="bi bi-search me-1"></i> Scopri i Libri
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Offcanvas Storico -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="storicoOrdini"
         style="width: clamp(300px, 38vw, 440px);">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">
                <i class="bi bi-archive-fill me-2" style="color:var(--orange);"></i>Storico Ordini
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <?php if (!empty($ordiniChiusi)): ?>
                <?php foreach ($ordiniChiusi as $order):
                    $fp   = $order['final_price'] ?? 0;
                    $pfmt = ($fp > 0) ? '€ ' . number_format($fp, 2, ',', '.') : 'Scambio';
                    $sc   = $order['state_customer'] ?? 'pending';
                    $sfn  = strtoupper(trim(($order['name'] ?? 'N/D') . ' ' . ($order['surname'] ?? '')));
                    $isCancelled = ($sc === 'cancelled');
                    $bc   = $isCancelled ? 'state-cancelled' : 'state-confirmed';
                    $bt   = $isCancelled ? 'Annullato' : 'Completato';
                    $bi   = $isCancelled ? 'bi-x-circle-fill' : 'bi-check-circle-fill';
                ?>
                <div class="history-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="history-card-title">
                                <?= htmlspecialchars($order['title'] ?? 'N/D') ?>
                            </div>
                            <div class="history-card-seller">
                                <i class="bi bi-shop me-1" style="color:var(--orange);"></i>
                                <?= htmlspecialchars($sfn) ?>
                            </div>
                        </div>
                        <span class="badge-state <?= $bc ?>" style="font-size:0.68rem;">
                            <i class="bi <?= $bi ?>"></i> <?= $bt ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2"
                         style="border-top: 1px solid #f0f0f0;">
                        <span style="font-size:var(--text-xs); color:#888;">Importo</span>
                        <span class="fw-bold <?= $isCancelled ? 'text-muted text-decoration-line-through' : '' ?>"
                              style="color: <?= $isCancelled ? '' : '#1a7a3a' ?>; font-size:var(--text-sm);">
                            <?= $pfmt ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-archive fs-1 d-block mb-3" style="color:#ddd;"></i>
                    <p>Nessun ordine completato.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Cambia Stato -->
    <div class="modal fade" id="changeOrderStateModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-header-warning">
                    <h5 class="modal-title">
                        <i class="bi bi-arrow-repeat me-2"></i>Cambia Stato Ordine
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="index.php?table=Order&action=changeStateCustomer" method="post">
                    <div class="modal-body" style="background:white;">
                        <input type="hidden" name="currentOrderId" id="currentOrderId">

                        <div class="mb-3">
                            <div class="chip-label mb-1"><i class="bi bi-hash" style="color:var(--orange);"></i> Ordine</div>
                            <p class="fw-bold mb-0 text-dark" id="modalOrderId"></p>
                        </div>
                        <div class="mb-3">
                            <div class="chip-label mb-1"><i class="bi bi-book" style="color:var(--orange);"></i> Libro</div>
                            <p class="mb-0 text-dark" id="modalBookTitle"></p>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <label for="modalNewState" class="form-label fw-bold">Nuovo Stato</label>
                            <select class="form-select" id="modalNewState" name="newState">
                                <option value="pending">⏳ In attesa</option>
                                <option value="confirmed">✅ Consegnato</option>
                                <option value="cancelled">❌ Annullato</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="background:#f8f9fa;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn-amazon">
                            <i class="bi bi-check-circle me-1"></i> Conferma
                        </button>
                    </div>
                </form>
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
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('changeOrderStateModal'));

        document.querySelectorAll('.change-order-state-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('currentOrderId').value   = this.dataset.orderId;
                document.getElementById('modalOrderId').textContent   = '#' + this.dataset.orderId;
                document.getElementById('modalBookTitle').textContent = this.dataset.bookTitle;
                document.getElementById('modalNewState').value        = this.dataset.currentState;
                modal.show();
            });
        });
    });
    </script>
</body>
</html>