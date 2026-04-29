<?php
defined("APP") or die("ACCESSO NEGATO");
?>

<!-- Toast Container - posizionato in alto a destra -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <?php if(isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
        <?php foreach($_SESSION['error'] as $index => $errorMsg): ?>
            <div id="errorToast<?= $index ?>" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Errore:</strong> <?= htmlspecialchars($errorMsg) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['error']); // Pulisci gli errori dopo averli mostrati ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
        <?php foreach($_SESSION['success'] as $index => $successMsg): ?>
            <div id="successToast<?= $index ?>" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Successo:</strong> <?= htmlspecialchars($successMsg) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['warning']) && !empty($_SESSION['warning'])): ?>
        <?php foreach($_SESSION['warning'] as $index => $warningMsg): ?>
            <div id="warningToast<?= $index ?>" class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <strong>Attenzione:</strong> <?= htmlspecialchars($warningMsg) ?>
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['warning']); ?>
    <?php endif; ?>
</div>

<!-- Script per mostrare automaticamente i toast -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Trova tutti i toast e mostrarli
    const toastElements = document.querySelectorAll('.toast');
    toastElements.forEach(function(toastEl) {
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 5000 // 5 secondi prima di scomparire
        });
        toast.show();
    });
});
</script>

<style>
.toast {
    min-width: 300px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.toast-body {
    font-size: 0.95rem;
}
</style>
