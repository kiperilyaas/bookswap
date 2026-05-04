<?php
#defined("APP") or die("ACCESSO NEGATO");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Libro | BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="views/bookswap-responsive.css">
    <style>
        .text-primary-amazon { color: #0066c0 !important; }
        h5.fw-bold { font-size: var(--text-md); }

        /* Upload Zone Styles */
        .upload-zone {
            width: 100%;
        }

        .drop-area {
            border: 3px dashed #ddd;
            border-radius: var(--radius-lg);
            padding: 3rem 2rem;
            text-align: center;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .drop-area:hover {
            border-color: #0066c0;
            background-color: #f0f8ff;
        }

        .drop-area.drag-over {
            border-color: #ff9900;
            background-color: #fff8e1;
            transform: scale(1.02);
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .preview-item {
            position: relative;
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 2px solid #ddd;
            background: white;
            transition: all 0.2s;
        }

        .preview-item:hover {
            border-color: #0066c0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .preview-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .preview-item .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .preview-item .remove-btn:hover {
            background: #dc3545;
            transform: scale(1.1);
        }

        .preview-item .primary-badge {
            position: absolute;
            bottom: 5px;
            left: 5px;
            background: rgba(255, 153, 0, 0.95);
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .preview-item .order-badge {
            position: absolute;
            top: 5px;
            left: 5px;
            background: rgba(0, 102, 192, 0.9);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand ms-2" href="index.php">📚 BookSwap</a>
                <div class="ms-auto me-2">
                    <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill">Annulla e Torna</a>
                </div>
            </div>
        </nav>

        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-card">
                        <h2 class="section-title">Aggiungi un libro al catalogo</h2>
                        <p class="text-muted" style="font-size:var(--text-sm);">Inserisci i dettagli tecnici. Potrai metterlo in vendita dopo averlo creato.</p>

                        <form action="index.php?table=Listings&action=addBook" method="POST">

                            <h5 class="fw-bold text-primary mb-3">1. Dettagli Principali</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-8 col-12">
                                    <label for="title" class="form-label">Titolo *</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Es. Matematica Blu 2.0" required>
                                </div>
                                <div class="col-md-4 col-12">
                                    <label for="vol" class="form-label">Volume</label>
                                    <select class="form-control" id="vol" name="vol">
                                        <option value="">--Seleziona--</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="U">Unico</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="author" class="form-label">Autore *</label>
                                    <input type="text" class="form-control" id="author" name="author" placeholder="Es. Massimo Bergamini" required>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="isbn" class="form-label">Codice ISBN *</label>
                                    <input type="text" class="form-control" id="isbn" name="isbn" placeholder="Es. 9788808123456" required>
                                </div>
                            </div>

                            <hr class="text-muted mb-4">
                            <h5 class="fw-bold text-primary-amazon mb-3">2. Classificazione scolastica</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-12">
                                    <label for="publish" class="form-label">Casa Editrice</label>
                                    <input list="publishList" class="form-control" id="publish" name="publish" placeholder="Cerca o scrivi editore...">
                                    <datalist id="publishList">
                                        <option value="Zanichelli">
                                        <option value="Mondadori">
                                        <option value="Pearson">
                                        <option value="De Agostini">
                                    </datalist>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="subject" class="form-label">Materia</label>
                                    <input list="subjectList" class="form-control" id="subject" name="subject" placeholder="Cerca o scrivi materia...">
                                    <datalist id="subjectList">
                                        <option value="Matematica">
                                        <option value="Italiano">
                                        <option value="Informatica">
                                        <option value="Sistemi e Reti">
                                    </datalist>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="faculty" class="form-label">Indirizzo di Studio</label>
                                    <input list="facultyList" class="form-control" id="faculty" name="faculty" placeholder="Es. Informatica, Liceo Classico...">
                                    <datalist id="facultyList">
                                        <option value="Informatica e Telecomunicazioni">
                                        <option value="Liceo Scientifico">
                                        <option value="Meccanica e Meccatronica">
                                    </datalist>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="class" class="form-label">Classe</label>
                                    <input list="classList" class="form-control" id="class" name="class" placeholder="Es. 5N, 3A...">
                                    <datalist id="classList">
                                        <option value="1A"><option value="2A"><option value="3N"><option value="4N"><option value="5N">
                                    </datalist>
                                </div>
                            </div>

                            <hr class="text-muted mb-4">
                            <h5 class="fw-bold text-primary-amazon mb-3">3. Prezzo di Copertina</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-12">
                                    <label for="price" class="form-label">Prezzo Originale (Nuovo)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" max="1000" placeholder="0.00" required>
                                    </div>
                                    <div id="priceLimitAlert" class="text-danger small mt-1 fw-bold" style="display:none;">
                                        <i class="bi bi-exclamation-circle-fill"></i> Massimo 1.000€.
                                    </div>
                                    <div class="form-text">Il prezzo di listino ufficiale.</div>
                                </div>
                            </div>

                            <hr class="text-muted mb-4">
                            <h5 class="fw-bold text-primary-amazon mb-3">4. Foto del Libro (Max 3)</h5>
                            <div class="mb-4">
                                <div class="upload-zone" id="uploadZone">
                                    <div class="drop-area" id="dropArea">
                                        <i class="bi bi-cloud-upload fs-1 text-primary-amazon mb-3"></i>
                                        <h6 class="fw-bold">Trascina qui le foto o clicca per selezionare</h6>
                                        <p class="text-muted small mb-0">JPG, PNG o WEBP - Max 10MB per foto - Max 3 foto</p>
                                        <input type="file" id="fileInput" name="book_images[]" multiple accept="image/jpeg,image/png,image/webp" hidden>
                                    </div>

                                    <!-- Preview delle foto caricate -->
                                    <div class="preview-grid" id="previewGrid"></div>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> La prima foto sarà quella principale visualizzata nelle card
                                </small>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4 flex-wrap">
                                <button type="reset" class="btn-amazon-light">Svuota campi</button>
                                <button type="submit" class="btn-amazon">Salva nel Catalogo</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p class="mb-1">© 2026 BookSwap Team</p>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Price validation
    document.getElementById('price').addEventListener('input', function() {
        const alert = document.getElementById('priceLimitAlert');
        if (parseFloat(this.value) > 1000) { alert.style.display = 'block'; this.value = 1000; }
        else { alert.style.display = 'none'; }
    });

    // Image Upload System
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const previewGrid = document.getElementById('previewGrid');
    let uploadedFiles = [];
    const MAX_FILES = 3;
    const MAX_SIZE = 10 * 1024 * 1024; // 10MB

    // Click to select files
    dropArea.addEventListener('click', () => fileInput.click());

    // Drag & Drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.add('drag-over'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.remove('drag-over'), false);
    });

    dropArea.addEventListener('drop', handleDrop, false);
    fileInput.addEventListener('change', handleFiles, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles({ target: { files: files } });
    }

    function handleFiles(e) {
        const files = Array.from(e.target.files);

        if (uploadedFiles.length + files.length > MAX_FILES) {
            alert(`Puoi caricare massimo ${MAX_FILES} foto!`);
            return;
        }

        files.forEach(file => {
            if (!file.type.match('image.*')) {
                alert(`${file.name} non è un'immagine valida!`);
                return;
            }

            if (file.size > MAX_SIZE) {
                alert(`${file.name} è troppo grande! Max 10MB`);
                return;
            }

            uploadedFiles.push(file);
            previewFile(file, uploadedFiles.length - 1);
        });

        updateDropAreaVisibility();
    }

    function previewFile(file, index) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function() {
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item';
            previewItem.dataset.index = index;

            previewItem.innerHTML = `
                <img src="${reader.result}" alt="Preview">
                <button type="button" class="remove-btn" onclick="removeImage(${index})">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="order-badge">${index + 1}</div>
                ${index === 0 ? '<div class="primary-badge"><i class="bi bi-star-fill"></i> Principale</div>' : ''}
            `;

            previewGrid.appendChild(previewItem);
        }
    }

    function removeImage(index) {
        uploadedFiles.splice(index, 1);
        renderPreviews();
        updateDropAreaVisibility();
        updateFileInput();
    }

    function renderPreviews() {
        previewGrid.innerHTML = '';
        uploadedFiles.forEach((file, index) => {
            previewFile(file, index);
        });
    }

    function updateDropAreaVisibility() {
        if (uploadedFiles.length >= MAX_FILES) {
            dropArea.style.display = 'none';
        } else {
            dropArea.style.display = 'block';
        }
    }

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        uploadedFiles.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }

    // Make removeImage global
    window.removeImage = removeImage;
    </script>
</body>
</html>
