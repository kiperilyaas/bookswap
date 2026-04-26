<?php
defined("APP") or die("ACCESSO NEGATO");
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

        body {
            background-color: var(--bs-bg);
            font-family: "Segoe UI", Arial, sans-serif;
            min-height: 100vh;
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

        .order-date {
            font-size: 0.85rem;
            color: #999;
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
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 1rem;
            color: var(--bs-dark);
            font-weight: 600;
        }

        .badge-state {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .state-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .state-confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .state-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .empty-icon {
            font-size: 5rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        footer {
            background-color: var(--bs-dark);
            color: white;
            margin-top: auto;
            padding: 2rem 0;
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
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house-fill"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?table=User&action=account">
                            <i class="bi bi-person-circle"></i> Area Personale
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="bi bi-box-seam me-3"></i>I Miei Ordini
            </h1>
            <p class="text-dark mb-0">Visualizza e gestisci tutti i tuoi acquisti</p>
        </div>
    </div>

    <div class="container pb-5">
        <?php if (!empty($myOrders)): ?>
            <?php foreach($myOrders as $order):
                $orderId = $order['id_order'] ?? 'N/D';
                $bookTitle = $order['title'] ?? 'Libro sconosciuto';
                $dateOrder = $order['date_order'] ?? 'Data sconosciuta';
                $state = $order['state'] ?? 'pending';
                $finalPrice = $order['final_price'] ?? 0;
                $timeMeet = $order['time_meet'] ?? 'N/D';
                $placeMeet = $order['place_meet'] ?? 'N/D';
                $descriptionMeet = $order['description_meet'] ?? 'Nessuna nota';

                // Determina classe badge stato
                $badgeClass = 'state-pending';
                $stateText = 'In attesa';
                if($state == 'confirmed' || $state == 'Consegnato') {
                    $badgeClass = 'state-confirmed';
                    $stateText = 'Consegnato';
                } elseif($state == 'cancelled' || $state == 'Annullato') {
                    $badgeClass = 'state-cancelled';
                    $stateText = 'Annullato';
                }

                // Formatta prezzo
                $priceFormatted = ($finalPrice > 0) ? '€ ' . number_format($finalPrice, 2, ',', '.') : 'Scambio';
            ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">
                                <i class="bi bi-hash"></i> Ordine: <?= htmlspecialchars($orderId) ?>
                            </div>
                            <div class="order-date">
                                <i class="bi bi-calendar3"></i> <?= htmlspecialchars($dateOrder) ?>
                            </div>
                        </div>
                        <span class="badge-state <?= $badgeClass ?>">
                            <?= $stateText ?>
                        </span>
                    </div>

                    <div class="book-title">
                        <i class="bi bi-book"></i> <?= htmlspecialchars($bookTitle) ?>
                    </div>

                    <div class="order-details">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="bi bi-cash-coin"></i> Prezzo
                            </div>
                            <div class="detail-value text-success">
                                <?= $priceFormatted ?>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="bi bi-clock"></i> Orario Incontro
                            </div>
                            <div class="detail-value">
                                <?= htmlspecialchars($timeMeet) ?>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="bi bi-geo-alt"></i> Luogo Incontro
                            </div>
                            <div class="detail-value">
                                <?= htmlspecialchars($placeMeet) ?>
                            </div>
                        </div>
                    </div>

                    <?php if($descriptionMeet && $descriptionMeet != 'Nessuna nota'): ?>
                        <div class="mt-3 p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                            <div class="detail-label mb-2">
                                <i class="bi bi-chat-left-text"></i> Note
                            </div>
                            <p class="mb-0 text-muted">
                                <?= htmlspecialchars($descriptionMeet) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3 class="text-muted mb-3">Nessun ordine trovato</h3>
                <p class="text-muted mb-4">Non hai ancora effettuato nessun acquisto su BookSwap.</p>
                <a href="index.php" class="btn btn-lg" style="background-color: var(--bs-orange); color: var(--bs-dark); font-weight: 700; border-radius: 20px; padding: 12px 30px;">
                    <i class="bi bi-search"></i> Scopri i Libri
                </a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="text-center">
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
            <small class="text-muted">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
