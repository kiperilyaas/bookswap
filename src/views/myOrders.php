<?php
defined("APP") or die("ACCESSO NEGATO");

// --- LOGICA DI SEPARAZIONE DEGLI ORDINI ---
$ordiniAttivi = [];
$ordiniChiusi = [];

if (!empty($myOrders)) {
    foreach ($myOrders as $order) {
        $generalState = $order['state'] ?? 'open';
        $stateCustomer = $order['state_customer'] ?? 'pending';
        $stateSeller = $order['state_seller'] ?? 'pending';
        
        if ($generalState === 'closed' || ($stateCustomer === 'confirmed' && $stateSeller === 'confirmed') || $stateCustomer === 'cancelled') {
            $ordiniChiusi[] = $order;
        } else {
            $ordiniAttivi[] = $order;
        }
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
    <style>
        :root {
            --bs-orange: #ff9900;
            --bs-dark: #131921;
            --bs-bg: #eaeded;
        }

        /* --- FIX FOOTER A FONDO PAGINA --- */
        html, body {
            height: 100%;
        }

        body {
            background-color: var(--bs-bg);
            font-family: "Segoe UI", Arial, sans-serif;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        .navbar {
            background-color: var(--bs-dark) !important;
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }

        .page-header {
            background: linear-gradient(135deg, var(--bs-orange) 0%, #ffb84d 100%);
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .page-title {
            color: var(--bs-dark);
            font-weight: 800;
            font-size: 2.5rem;
            margin: 0;
        }

        .order-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 1.5rem;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .order-card-history {
            background: #fdfdfd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 1rem;
            border: 1px solid #e0e0e0;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .order-id {
            font-size: 0.9rem;
            color: #666;
            font-weight: 600;
        }

        .book-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--bs-dark);
            margin-bottom: 10px;
        }

        .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .detail-item {
            background-color: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
        }

        .badge-state {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .state-pending { background-color: #fff3cd; color: #856404; }
        .state-confirmed { background-color: #d4edda; color: #155724; }
        .state-cancelled { background-color: #f8d7da; color: #721c24; }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
        }

        footer {
            background-color: var(--bs-dark);
            color: white;
            padding: 2rem 0;
            margin-top: auto; /* Spinge il footer in fondo */
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand ms-3" href="index.php">📚 BookSwap</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="bi bi-house-fill"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?table=User&action=account"><i class="bi bi-person-circle"></i> Area Personale</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title"><i class="bi bi-box-seam me-3"></i>I Miei Ordini</h1>
                <p class="text-dark mb-0">Visualizza e gestisci tutti i tuoi acquisti in corso</p>
            </div>
            <?php if(count($ordiniChiusi) > 0): ?>
            <button class="btn btn-dark shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#storicoOrdini">
                <i class="bi bi-clock-history"></i> Visualizza Storico (<?= count($ordiniChiusi) ?>)
            </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="container pb-5">
        <?php if (!empty($ordiniAttivi)): ?>
            <?php foreach($ordiniAttivi as $order):
                $orderId = $order['id_order'] ?? 'N/D';
                $bookTitle = $order['title'] ?? 'Libro sconosciuto';
                $dateOrder = $order['date_order'] ?? 'Data sconosciuta';
                $dateOrderFormatted = ($dateOrder != 'Data sconosciuta') ? date('d/m/Y', strtotime($dateOrder)) . ' alle ' . date('H:i', strtotime($dateOrder)) : 'Data sconosciuta';
                $stateCustomer = $order['state_customer'] ?? 'pending';
                $finalPrice = $order['final_price'] ?? 0;
                $timeMeet = $order['time_meet'] ?? 'N/D';
                $timeMeetFormatted = ($timeMeet != 'N/D') ? date('d/m/Y', strtotime($timeMeet)) . ' alle ' . date('H:i', strtotime($timeMeet)) : 'N/D';
                $placeMeet = $order['place_meet'] ?? 'N/D';
                $descriptionMeet = $order['description_meet'] ?? 'Nessuna nota';
                
                $sellerName = $order['seller_name'] ?? ($order['name'] ?? 'N/D');
                $sellerSurname = $order['seller_surname'] ?? ($order['surname'] ?? '');
                $sellerFullName = trim($sellerName . ' ' . $sellerSurname);

                $badgeClass = 'state-pending';
                $stateText = 'In attesa';
                if($stateCustomer == 'confirmed') { $badgeClass = 'state-confirmed'; $stateText = 'Confermato'; } 
                elseif($stateCustomer == 'cancelled') { $badgeClass = 'state-cancelled'; $stateText = 'Annullato'; }

                $priceFormatted = ($finalPrice > 0) ? '€ ' . number_format($finalPrice, 2, ',', '.') : 'Scambio';
            ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id"><i class="bi bi-hash"></i> Ordine: <?= htmlspecialchars($orderId) ?></div>
                            <div class="order-date"><i class="bi bi-calendar3"></i> <?= htmlspecialchars($dateOrderFormatted) ?></div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge-state <?= $badgeClass ?>"><?= $stateText ?></span>
                            <button class="btn btn-sm btn-outline-warning change-order-state-btn"
                                    data-order-id="<?= htmlspecialchars($orderId) ?>"
                                    data-book-title="<?= htmlspecialchars($bookTitle) ?>"
                                    data-current-state="<?= htmlspecialchars($stateCustomer) ?>">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                        </div>
                    </div>
                    <div class="book-title"><i class="bi bi-book"></i> <?= htmlspecialchars($bookTitle) ?></div>
                    <div class="mb-3">
                        <span class="badge bg-secondary"><i class="bi bi-person-fill"></i> Venditore: <?= htmlspecialchars($sellerFullName) ?></span>
                    </div>
                    <div class="order-details">
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-cash-coin"></i> Prezzo</div>
                            <div class="detail-value text-success"><?= $priceFormatted ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-clock"></i> Orario Incontro</div>
                            <div class="detail-value"><?= htmlspecialchars($timeMeetFormatted) ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-geo-alt"></i> Luogo Incontro</div>
                            <div class="detail-value"><?= htmlspecialchars($placeMeet) ?></div>
                        </div>
                    </div>
                    <?php if($descriptionMeet && $descriptionMeet != 'Nessuna nota'): ?>
                        <div class="mt-3 p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                            <div class="detail-label mb-2"><i class="bi bi-chat-left-text"></i> Note</div>
                            <p class="mb-0 text-muted"><?= htmlspecialchars($descriptionMeet) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon" style="font-size: 5rem; color: #ccc;"><i class="bi bi-inbox"></i></div>
                <h3 class="text-muted mb-3">Nessun ordine in corso</h3>
                <p class="text-muted mb-4">Tutti i tuoi acquisti passati sono nello storico o non hai ancora ordini.</p>
                <a href="index.php" class="btn btn-lg" style="background-color: var(--bs-orange); color: var(--bs-dark); font-weight: 700; border-radius: 20px; padding: 12px 30px;">
                    <i class="bi bi-search"></i> Scopri i Libri
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="storicoOrdini" style="width: 400px;">
        <div class="offcanvas-header bg-light border-bottom">
            <h5 class="offcanvas-title fw-bold"><i class="bi bi-archive-fill text-secondary me-2"></i> Storico Ordini</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="background-color: var(--bs-bg);">
            <?php if (!empty($ordiniChiusi)): ?>
                <?php foreach($ordiniChiusi as $order): 
                    $bookTitle = $order['title'] ?? 'Libro sconosciuto';
                    $finalPrice = $order['final_price'] ?? 0;
                    $priceFormatted = ($finalPrice > 0) ? '€ ' . number_format($finalPrice, 2, ',', '.') : 'Scambio';
                    $stateCustomer = $order['state_customer'] ?? 'pending';
                    $sellerFullName = trim(($order['seller_name'] ?? 'N/D') . ' ' . ($order['seller_surname'] ?? ''));
                    $badgeClass = ($stateCustomer == 'cancelled') ? 'state-cancelled' : 'state-confirmed';
                    $stateText = ($stateCustomer == 'cancelled') ? 'Annullato' : 'Completato';
                ?>
                    <div class="order-card-history">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="order-id small"><i class="bi bi-hash"></i> <?= htmlspecialchars($sellerFullName) ?></span>
                            <span class="badge-state <?= $badgeClass ?> px-2 py-1" style="font-size: 0.75rem;"><?= $stateText ?></span>
                        </div>
                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($bookTitle) ?></h6>
                        <div class="d-flex justify-content-between align-items-end mt-3">
                            <span class="small text-muted">Prezzo:</span>
                            <span class="fw-bold <?= ($stateCustomer == 'cancelled') ? 'text-muted text-decoration-line-through' : 'text-success' ?>"><?= $priceFormatted ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer class="text-center">
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-muted">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>

    <div class="modal fade" id="changeOrderStateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark fw-bold"><i class="bi bi-arrow-repeat me-2"></i>Cambia Stato Ordine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="index.php?table=Order&action=changeStateCustomer" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="currentOrderId" id="currentOrderId">
                        <div class="mb-3">
                            <label class="fw-bold text-muted small">Ordine ID</label>
                            <p class="mb-0 fw-bold" id="modalOrderId"></p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-muted small">Libro</label>
                            <p class="mb-0" id="modalBookTitle"></p>
                        </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const changeStateButtons = document.querySelectorAll('.change-order-state-btn');
        const modal = new bootstrap.Modal(document.getElementById('changeOrderStateModal'));

        changeStateButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('currentOrderId').value = this.dataset.orderId;
                document.getElementById('modalOrderId').textContent = this.dataset.orderId;
                document.getElementById('modalBookTitle').textContent = this.dataset.bookTitle;
                document.getElementById('modalNewState').value = this.dataset.currentState;
                modal.show();
            });
        });
    });
    </script>
</body>
</html>