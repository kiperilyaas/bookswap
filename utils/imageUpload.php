<?php
defined("APP") or die("ACCESSO NEGATO");

/**
 * Gestisce l'upload delle immagini per i libri/listings
 * @param array $files - $_FILES['book_images'] o $_FILES['listing_images']
 * @param int $id_listing - ID del listing per naming
 * @return array - Array di path delle immagini salvate
 */
function handleImageUpload($files, $id_listing) {
    $uploadedPaths = [];
    $uploadDir = __DIR__ . '/immagini/books/';

    // Crea la directory se non esiste
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $maxFiles = 3;
    $maxSize = 50 * 1024 * 1024; // 10MB
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    $fileCount = count($files['name']);
    $fileCount = min($fileCount, $maxFiles);

    for ($i = 0; $i < $fileCount; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        // Validazione tipo file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $files['tmp_name'][$i]);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $_SESSION['warning'][] = "File {$files['name'][$i]} non è un'immagine valida";
            continue;
        }

        // Validazione dimensione
        if ($files['size'][$i] > $maxSize) {
            $_SESSION['warning'][] = "File {$files['name'][$i]} è troppo grande (max 10MB)";
            continue;
        }

        // Genera nome file univoco: listing_{id}_{timestamp}_{index}.ext
        $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
        $timestamp = time();
        $filename = "listing_{$id_listing}_{$timestamp}_" . ($i + 1) . ".{$extension}";
        $filepath = $uploadDir . $filename;

        // Sposta il file
        if (move_uploaded_file($files['tmp_name'][$i], $filepath)) {
            // Salva il path relativo per il database (solo books/filename)
            $uploadedPaths[] = "books/{$filename}";
        } else {
            $_SESSION['warning'][] = "Errore nel caricamento di {$files['name'][$i]}";
        }
    }

    return $uploadedPaths;
}
