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
                $dateOrder  = $order['date_order']      ?? 'N/D';
                $dateFmt    = ($dateOrder !== 'N/D') ? date('d/m/Y', strtotime($dateOrder)) . ' alle ' . date('H:i', strtotime($dateOrder)) : 'N/D';
                $sc         = $order['state_customer']  ?? 'pending';
                $finalPrice = $order['final_price']     ?? 0;
                $timeMeet   = $order['time_meet']       ?? 'N/D';
                $timeFmt    = ($timeMeet !== 'N/D') ? date('d/m/Y', strtotime($timeMeet)) . ' alle ' . date('H:i', strtotime($timeMeet)) : 'N/D';
                $placeMeet  = $order['place_meet']      ?? 'N/D';
                $descMeet   = $order['description_meet'] ?? '';
                $sellerFull = strtoupper(trim(($order['name'] ?? 'N/D') . ' ' . ($order['surname'] ?? '')));
                $badgeClass = 'state-pending';
                $stateText  = 'In attesa';
                if($sc === 'confirmed') { $badgeClass = 'state-confirmed'; $stateText = 'Confermato'; }
                elseif($sc === 'cancelled') { $badgeClass = 'state-cancelled'; $stateText = 'Annullato'; }
                $priceFmt = ($finalPrice > 0) ? '€ ' . number_format($finalPrice, 2, ',', '.') : 'Scambio';
            ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <div class="order-id"><i class="bi bi-hash"></i> Ordine: <?= htmlspecialchars($orderId) ?></div>
                        <div class="order-id"><i class="bi bi-calendar3"></i> <?= htmlspecialchars($dateFmt) ?></div>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="badge-state <?= $badgeClass ?>"><?= $stateText ?></span>
                        <button class="btn btn-sm btn-outline-warning change-order-state-btn"
                                data-order-id="<?= htmlspecialchars($orderId) ?>"
                                data-book-title="<?= htmlspecialchars($bookTitle) ?>"
                                data-current-state="<?= htmlspecialchars($sc) ?>">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    </div>
                </div>
                <div class="book-title"><i class="bi bi-book"></i> <?= htmlspecialchars($bookTitle) ?></div>
                <span class="badge bg-secondary mb-2"><i class="bi bi-person-fill"></i> Venditore: <?= htmlspecialchars($sellerFull) ?></span>
                <div class="order-details">
                    <div class="detail-item">
                        <div class="detail-label"><i class="bi bi-cash-coin"></i> Prezzo</div>
                        <div class="detail-value text-success fw-bold"><?= $priceFmt ?></div>
                    </div>
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

    <!-- Modale cambia stato -->
    <div class="modal fade" id="changeOrderStateModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark fw-bold"><i class="bi bi-arrow-repeat me-2"></i>Cambia Stato Ordine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="index.php?table=Order&action=changeStateCustomer" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="currentOrderId" id="currentOrderId">
                        <div class="mb-3"><label class="fw-bold text-muted small">Ordine</label><p class="mb-0 fw-bold" id="modalOrderId"></p></div>
                        <div class="mb-3"><label class="fw-bold text-muted small">Libro</label><p class="mb-0" id="modalBookTitle"></p></div>
                        <hr>
                        <div class="mb-3">
                            <label for="modalNewState" class="form-label fw-bold">Nuovo Stato</label>
                            <select class="form-select" id="modalNewState" name="newState">
                                <option value="pending">In attesa</option>
                                <option value="confirmed">Consegnato</option>
                                <option value="cancelled">Annullato</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-warning"><i class="bi bi-check-circle"></i> Conferma</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('changeOrderStateModal'));
        document.querySelectorAll('.change-order-state-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('currentOrderId').value = this.dataset.orderId;
                document.getElementById('modalOrderId').textContent  = this.dataset.orderId;
                document.getElementById('modalBookTitle').textContent = this.dataset.bookTitle;
                document.getElementById('modalNewState').value = this.dataset.currentState;
                modal.show();
            });
        });
    });
    </script>
</body>
</html>
