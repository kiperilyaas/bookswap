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
                        <a class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 px-3 rounded-pill" href="index.php?table=login&action=logout">
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
        <div class="d-flex align-items-center mb-4 gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#ff9900" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
            <h2 class="mb-0">Area Personale</h2>
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
                                            <a href="index.php?table=Listings&action=deleteListing&id=<?php echo urlencode($offer['id_listing']) ?>" class="btn btn-sm btn-outline-danger" style="font-size: 0.75rem;">Elimina</a>
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
                        <h5 class="mb-0 text-dark fw-bold">📦 I miei ordini</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($myOrders)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach($myOrders as $order): 
                                    // Adatta queste chiavi in base alla tua query JOIN degli ordini!
                                    $titoloLibro = $order['title'] ?? 'Libro acquistato';
                                    $dataOrdine = $order['order_date'] ?? 'Data sconosciuta';
                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <div>
                                            <h6 class="mb-1 fw-bold"><?= htmlspecialchars($titoloLibro) ?></h6>
                                            <small class="text-muted">Acquistato il: <?= htmlspecialchars($dataOrdine) ?></small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-dark">Dettagli</button>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>