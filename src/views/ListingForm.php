<?php
defined("APP") or die("ACCESSO NEGATO");

if (!isset($_SESSION['id_user'])) {
    $_SESSION['errors'] = ["Devi effettuare il login per aggiungere un'offerta."];
    header("Location: index.php?table=login&action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Annuncio | BookSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="views/bookswap-responsive.css">
    <style>
        .custom-dropdown {
            border-radius: var(--radius-md);
            border: 1px solid #ddd;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 0.5rem 0;
            overflow: hidden;
        }
        .custom-dropdown .dropdown-item { padding: 0.6rem 1.5rem; transition: background-color 0.2s; font-size: var(--text-sm); }
        .custom-dropdown .dropdown-item:hover { background-color: #f7fafa; color: var(--orange); font-weight: 600; }
        .book-result-item {
            border: 1px solid #eee;
            border-radius: var(--radius-md);
            transition: background 0.2s, border-color 0.2s;
            cursor: pointer;
        }
        .book-result-item:hover { background-color: #f7fafa; border-color: var(--orange); }
        .selected-book-alert {
            background-color: #f0f8ff;
            border: 1px solid #0066c0;
            border-radius: var(--radius-md);
        }

        /* Upload Zone Styles */
        .upload-zone { width: 100%; }
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
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand ms-2" href="index.php">📚 BookSwap</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto me-2">
                    <li class="nav-item"><a class="nav-link" href="index.php">Torna alla Home</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-12">
                <div class="form-card">
                    <h2 class="section-title">Crea un nuovo annuncio</h2>

                    <form action="index.php?table=Listings&action=addListing" method="POST" id="offerForm" enctype="multipart/form-data">
                        <input type="hidden" id="id_book_selezionato" name="id_book" required>

                        <div class="mb-4">
                            <label class="form-label">1. Quale libro vuoi vendere?</label>
                            <div id="searchBlock">
                                <div class="input-group">
                                    <button class="btn dropdown-toggle border" type="button" data-bs-toggle="dropdown"
                                            id="filterDropdownBtn"
                                            style="background:#f3f3f3;border-radius:20px 0 0 20px;color:#333;min-width:110px;display:flex;justify-content:space-between;align-items:center;border-color:#ced4da;font-size:var(--text-sm);">
                                        Titolo
                                    </button>
                                    <ul class="dropdown-menu custom-dropdown">
                                        <li><a class="dropdown-item filter-option" href="#" data-value="title">Titolo</a></li>
                                        <li><a class="dropdown-item filter-option" href="#" data-value="author">Autore</a></li>
                                        <li><a class="dropdown-item filter-option" href="#" data-value="isbn">ISBN</a></li>
                                        <li><a class="dropdown-item filter-option" href="#" data-value="class">Classe</a></li>
                                    </ul>
                                    <input type="hidden" id="searchFilter" value="title">
                                    <input type="text" class="form-control" id="searchInput"
                                           placeholder="Cerca per titolo, autore o ISBN…"
                                           style="border-radius:0 20px 20px 0;">
                                </div>
                                <div class="mt-2 text-end">
                                    <small>➕ Non trovi il libro? <a href="index.php?table=Listings&action=addBookForm" style="color:#0066c0;">Aggiungilo al catalogo</a></small>
                                </div>
                                <div id="searchResults" class="mt-3"></div>
                                <div id="noResults" class="alert alert-light border mt-3" style="display:none;">
                                    Nessun risultato. <a href="index.php?table=Listings&action=addBookForm">Crea una nuova scheda libro</a>.
                                </div>
                            </div>

                            <div id="selectedBlock" style="display:none;">
                                <div class="selected-book-alert p-3 d-flex justify-content-between align-items-center gap-3">
                                    <div>
                                        <span class="text-muted" style="font-size:var(--text-xs);">Libro selezionato:</span>
                                        <strong id="selectedBookTitle" class="d-block" style="color:#0066c0;font-size:var(--text-md);"></strong>
                                        <div class="text-secondary" style="font-size:var(--text-sm);">
                                            Prezzo di riferimento: <strong id="selectedBookPrice" class="text-dark"></strong>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-amazon-light flex-shrink-0" onclick="resetSelection()">Cambia</button>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="prezzo" class="form-label">2. Prezzo di vendita</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" class="form-control" id="prezzo" name="prezzo" step="0.01" min="0" max="1000" placeholder="0.00" required>
                                </div>
                                <div id="priceLimitAlert" class="text-danger small mt-1 fw-bold" style="display:none;">
                                    <i class="bi bi-exclamation-circle-fill"></i> Massimo 1.000€.
                                </div>
                                <small class="text-muted">Usa 0.00 per scambi o regali 🎁</small>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="condizioni" class="form-label">3. Condizioni del libro</label>
                                <select class="form-select" id="condizioni" name="condizioni" required>
                                    <option value="" selected disabled>Seleziona stato...</option>
                                    <option value="nuovo">Nuovo</option>
                                    <option value="ottimo">Come nuovo</option>
                                    <option value="buono">Buone condizioni</option>
                                    <option value="usurato">Segni di usura / Scritto</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="descrizione" class="form-label">4. Descrizione (Opzionale)</label>
                            <textarea class="form-control" id="descrizione" name="descrizione" rows="4"
                                placeholder="Aggiungi informazioni utili (es. sottolineature, copertina rovinata…)"></textarea>
                        </div>

                        <hr>
                        <div class="mb-4">
                            <label class="form-label">5. Foto del Libro (Max 3)</label>
                            <div class="upload-zone" id="uploadZone">
                                <div class="drop-area" id="dropArea">
                                    <i class="bi bi-cloud-upload fs-1 text-primary mb-3" style="color:#0066c0;"></i>
                                    <h6 class="fw-bold">Trascina qui le foto o clicca per selezionare</h6>
                                    <p class="text-muted small mb-0">JPG, PNG o WEBP - Max 2MB per foto - Max 3 foto</p>
                                    <input type="file" id="fileInput" accept="image/jpeg,image/png,image/webp" hidden>
                                </div>

                                <!-- Container per input nascosti -->
                                <div id="hiddenInputsContainer"></div>

                                <!-- Preview delle foto caricate -->
                                <div class="preview-grid" id="previewGrid"></div>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> La prima foto sarà quella principale visualizzata nelle card
                            </small>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3 border-top flex-wrap">
                            <button type="reset" class="btn-amazon-light" onclick="resetSelection()">Svuota</button>
                            <button type="submit" class="btn-amazon" id="submitBtn" disabled>Pubblica Annuncio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p class="mb-1 text-white">© 2026 BookSwap Team</p>
            <small style="color:#ccc;">Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo</small>
        </div>
    </footer>

    <?php include 'views/ToastNotification.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let searchTimeout;

    document.querySelectorAll('.filter-option').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('filterDropdownBtn').innerText = this.innerText;
            document.getElementById('searchFilter').value = this.dataset.value;
            if (document.getElementById('searchInput').value.trim().length >= 2)
                document.getElementById('searchInput').dispatchEvent(new Event('input'));
        });
    });

    document.getElementById('prezzo').addEventListener('input', function() {
        const a = document.getElementById('priceLimitAlert');
        if (parseFloat(this.value) > 1000) { a.style.display = 'block'; this.value = 1000; }
        else a.style.display = 'none';
    });

    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const query  = this.value.trim();
        const filter = document.getElementById('searchFilter').value;
        const resDiv = document.getElementById('searchResults');
        const noRes  = document.getElementById('noResults');
        if (query.length < 2) { resDiv.innerHTML = ''; noRes.style.display = 'none'; return; }

        searchTimeout = setTimeout(() => {
            fetch(`index.php?table=Listings&action=liveSearchBooks&query=${encodeURIComponent(query)}&filter=${filter}`)
                .then(r => r.json())
                .then(data => {
                    resDiv.innerHTML = '';
                    if (data.length > 0) {
                        noRes.style.display = 'none';
                        data.forEach(book => {
                            const author  = book.author || 'Autore sconosciuto';
                            const isbn    = book.isbn   || 'N/D';
                            const raw     = book.priceOffer !== undefined ? book.priceOffer : (book.price || '0');
                            const fmt     = parseFloat(raw).toFixed(2).replace('.', ',');
                            resDiv.innerHTML += `
                            <div class="book-result-item p-3 mb-2 bg-white d-flex align-items-center justify-content-between shadow-sm gap-3">
                                <div>
                                    <div class="fw-bold" style="color:#0066c0;font-size:var(--text-sm);">${book.title}</div>
                                    <div class="text-muted" style="font-size:var(--text-xs);">${author} | ISBN: ${isbn}</div>
                                    <div class="text-muted fw-bold" style="font-size:var(--text-xs);">Copertina: ${fmt}€</div>
                                </div>
                                <button type="button" class="btn-amazon flex-shrink-0"
                                        onclick="selectBook(${book.id_book},'${book.title.replace(/'/g,"\\'")}','${fmt}')">
                                    Seleziona
                                </button>
                            </div>`;
                        });
                    } else {
                        noRes.style.display = 'block';
                    }
                })
                .catch(err => console.error('Errore ricerca:', err));
        }, 250);
    });

    function selectBook(id, title, price) {
        document.getElementById('id_book_selezionato').value = id;
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('selectedBookTitle').innerText = title;
        document.getElementById('selectedBookPrice').innerText = price + '€';
        document.getElementById('searchBlock').style.display = 'none';
        document.getElementById('selectedBlock').style.display = 'block';
    }

    function resetSelection() {
        document.getElementById('id_book_selezionato').value = '';
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('searchInput').value = '';
        document.getElementById('searchResults').innerHTML = '';
        document.getElementById('searchBlock').style.display = 'block';
        document.getElementById('selectedBlock').style.display = 'none';
        document.getElementById('selectedBookPrice').innerText = '';
    }

    document.getElementById('offerForm').addEventListener('submit', function(e) {
        if (!document.getElementById('id_book_selezionato').value) {
            e.preventDefault();
            alert('⚠️ Seleziona un libro dal catalogo prima di pubblicare.');
        }
    });

    // ========================================
    // IMAGE UPLOAD SYSTEM
    // ========================================
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const previewGrid = document.getElementById('previewGrid');
    const hiddenInputsContainer = document.getElementById('hiddenInputsContainer');
    let uploadedFiles = [];
    const MAX_FILES = 3;
    const MAX_SIZE = 2 * 1024 * 1024; // 2MB (limite PHP)

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

        files.forEach(file => {
            if (uploadedFiles.length >= MAX_FILES) {
                alert(`Puoi caricare massimo ${MAX_FILES} foto!`);
                return;
            }

            if (!file.type.match('image.*')) {
                alert(`${file.name} non è un'immagine valida!`);
                return;
            }

            if (file.size > MAX_SIZE) {
                alert(`${file.name} è troppo grande! Max 2MB`);
                return;
            }

            uploadedFiles.push(file);
            addFileToForm(file, uploadedFiles.length - 1);
            previewFile(file, uploadedFiles.length - 1);
        });

        // Reset input per permettere di selezionare altri file
        fileInput.value = '';
        updateDropAreaVisibility();
    }

    function addFileToForm(file, index) {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        const newInput = document.createElement('input');
        newInput.type = 'file';
        newInput.name = 'listing_images[]';
        newInput.files = dataTransfer.files;
        newInput.style.display = 'none';
        newInput.dataset.index = index;

        hiddenInputsContainer.appendChild(newInput);
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

        // Rimuovi l'input nascosto corrispondente
        const inputToRemove = hiddenInputsContainer.querySelector(`input[data-index="${index}"]`);
        if (inputToRemove) {
            inputToRemove.remove();
        }

        renderPreviews();
        updateDropAreaVisibility();
    }

    function renderPreviews() {
        previewGrid.innerHTML = '';
        hiddenInputsContainer.innerHTML = '';

        uploadedFiles.forEach((file, index) => {
            addFileToForm(file, index);
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

    // Make removeImage global
    window.removeImage = removeImage;
    </script>
</body>
</html>
