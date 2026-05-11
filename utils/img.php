<?php
$upload_dir    = 'uploads/';
$max_size_mb   = 10;
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$allowed_ext   = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {

    $files = $_FILES['images'];
    $count = count($files['name']);

    for ($i = 0; $i < $count; $i++) {

        $name  = $files['name'][$i];
        $tmp   = $files['tmp_name'][$i];
        $size  = $files['size'][$i];
        $error = $files['error'][$i];
        $type  = mime_content_type($tmp);
        $ext   = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if ($error !== UPLOAD_ERR_OK) {
            $messages[] = "Errore nel caricamento di " . $name;
            continue;
        }

        if ($size > $max_size_mb * 1024 * 1024) {
            $messages[] = $name . " supera il limite di " . $max_size_mb . " MB";
            continue;
        }

        if (!in_array($type, $allowed_types) || !in_array($ext, $allowed_ext)) {
            $messages[] = $name . " — formato non supportato";
            continue;
        }

        $safe_name = uniqid('img_', true) . '.' . $ext;
        $dest      = $upload_dir . $safe_name;

        if (move_uploaded_file($tmp, $dest)) {
            $messages[] = $name . " caricata con successo";
        } else {
            $messages[] = "Impossibile salvare " . $name;
        }
    }
}
?>