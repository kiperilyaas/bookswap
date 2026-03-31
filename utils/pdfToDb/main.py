"""
populate_books.py  v6
=====================
Popola il DB libri dai 49 PDF — IIS Bassi Burgatti.
Genera anche un file JSON con tutti i libri raggruppati per classe.
Compatibile Python 3.9+.

v6: parser basato su coordinate x,y (parole del PDF) invece di righe di testo.
    Ricostruisce titoli completi anche quando spezzati su più righe fisiche.
    Usa ADJ_MAX_DY=6px per trovare solo le righe di continuazione del libro
    (non quelle del libro successivo).

Dipendenze:
    pip install pdfplumber pymysql

Utilizzo:
    python populate_books.py --dry-run              # solo JSON, no DB
    python populate_books.py                         # JSON + inserisce nel DB
    python populate_books.py --json-out libri.json   # nome file JSON custom
"""

import re
import json
import argparse
import pdfplumber
import pymysql
from pathlib import Path
from datetime import datetime

# =============================================================================
# CONFIGURAZIONE
# =============================================================================

PDF_DIR      = "../pdf_libri"
CLASSES_JSON = "./classi.json"
JSON_OUT     = "./libri_per_classe.json"

DB = dict(
    host     = "localhost",
    user     = "root",
    password = "password",
    database = "nome_db",
    charset  = "utf8mb4",
)

# =============================================================================
# LINGUA_OVERRIDE  —  compila con i dati reali della segreteria
# =============================================================================

LINGUA_OVERRIDE = {
    "1A": "INGLESE_TEDESCO",
    "1D": "INGLESE_FRANCESE",
    "1E": "INGLESE_FRANCESE",
    "2A": "INGLESE_FRANCESE",
    "2D": "INGLESE_FRANCESE",
    "2E": "INGLESE_FRANCESE",
    "3A": "INGLESE_FRANCESE",
    "4A": "INGLESE_FRANCESE",
    "5A": "INGLESE_FRANCESE",
    "3D": "INGLESE_FRANCESE_SPAGNOLO",
    "3E": "INGLESE_TEDESCO_SPAGNOLO",
    "4D": "INGLESE_FRANCESE_SPAGNOLO",
    "5B": "INGLESE_FRANCESE_SPAGNOLO",
    "5D": "INGLESE_FRANCESE_SPAGNOLO",
    "5E": "INGLESE_TEDESCO_SPAGNOLO",
}

INDIRIZZO_ALIAS = {
    "LICEO-SCIENT.-SCIENZE-APPL.":        "LICEO-SCIENT.-SCIENZE-APPL.",
    "LICEO SCIENT. SCIENZE APPL.":         "LICEO-SCIENT.-SCIENZE-APPL.",
    "LICEO-QUADRIENNALE":                  "LICEO_QUAD",
    "LICEO QUADRIENNALE":                  "LICEO_QUAD",
    "BIENNIO TECNOLOGICO":                 "INFORMATICA-TELECOMUNICAZIONI",
    "INFORMATICA/TELECOMUNICAZIONI":       "INFORMATICA-TELECOMUNICAZIONI",
    "INFORMATICA-TELECOMUNICAZIONI":       "INFORMATICA-TELECOMUNICAZIONI",
    "MECCANICA":         "MECCANICA",
    "AUTOMAZIONE":       "AUTOMAZIONE",
    "INFORMATICA":       "INFORMATICA",
    "TELECOMUNICAZIONI": "TELECOMUNICAZIONI",
    "AFM": "AFM",
    "RIM": "RIM",
    "SIA": "SIA",
}

PDF_MAP = {
    "01. PRIMA_AFM_INGLESE_TEDESCO":                           (1, "AFM",         "INGLESE_TEDESCO"),
    "02. PRIMA_AFM_INGLESE_FRANCESE":                          (1, "AFM",         "INGLESE_FRANCESE"),
    "03. PRIMA_SIA_QUADRIENNALE":                              (1, "SIA_QUAD",    None),
    "04. PRIMA_ELETTRONICA_ELETTROTECNICA":                    (1, "ELETTRONICA", None),
    "05. PRIMA_MECCANICA_MECCATRONICA":                        (1, "MECCANICA",   None),
    "06. PRIMA_MECCANICA_MECCATRONICA_QUADRIENNALE":           (1, "MECC_QUAD",   None),
    "07. PRIMA_INFORMATICA_TELECOMUNICAZIONI":                 (1, "INFORMATICA-TELECOMUNICAZIONI", None),
    "08. PRIMA_LICEO SCIENZE APPLICATE":                       (1, "LICEO-SCIENT.-SCIENZE-APPL.", None),
    "09. PRIMA_LICEO SCIENZE APPLICATE_QUADRIENNALE":          (1, "LICEO_QUAD",  None),
    "10. SECONDA_AFM_INGLESE_FRANCESE":                        (2, "AFM",         "INGLESE_FRANCESE"),
    "11. SECONDA_SIA QUADRIENNALE":                            (2, "SIA_QUAD",    None),
    "12. SECONDA_ELETTRONICA ED ELETTROTECNICA":               (2, "ELETTRONICA", None),
    "13. SECONDA_MECCANICA, MECCATRONICA E ENERGIA":           (2, "MECCANICA",   None),
    "14. SECONDA_MECCANICA, MECCATRONICA_QUADRIENNALE":        (2, "MECC_QUAD",   None),
    "15. SECONDA_INFORMATICA E TELECOMUNICAZIONI":             (2, "INFORMATICA-TELECOMUNICAZIONI", None),
    "16. SECONDA_LICEO SCIENTIFICO - OPZIONE SCIENZE APPLICATE":(2, "LICEO-SCIENT.-SCIENZE-APPL.", None),
    "17. SECONDA_LICEO DELLE SCIENZE APPLICATE QUADRIENNALE":  (2, "LICEO_QUAD",  None),
    "18. TERZA_AFM_INGLESE_FRANCESE":                          (3, "AFM",         "INGLESE_FRANCESE"),
    "19. TERZA_AFM_INGLESE_TEDESCO":                           (3, "AFM",         "INGLESE_TEDESCO"),
    "20. TERZA_RIM_INGLESE_FRANCESE_SPAGNOLO":                 (3, "RIM",         "INGLESE_FRANCESE_SPAGNOLO"),
    "21. TERZA_RIM_INGLESE_TEDESCO_SPAGNOLO":                  (3, "RIM",         "INGLESE_TEDESCO_SPAGNOLO"),
    "22. TERZA_SIA_INGLESE_FRANCESE":                          (3, "SIA",         "INGLESE_FRANCESE"),
    "23. TERZA_SIA_INGLESE_TEDESCO":                           (3, "SIA",         "INGLESE_TEDESCO"),
    "24. TERZA_AUTOMAZIONE":                                   (3, "AUTOMAZIONE", None),
    "25. TERZA_MECCANICA_MECCATRONICA":                        (3, "MECCANICA",   None),
    "26. TERZA_INFORMATICA":                                   (3, "INFORMATICA", None),
    "27. TERZA_TELECOMUNICAZIONI":                             (3, "TELECOMUNICAZIONI", None),
    "28. TERZA_LICEO SCIENTIFICO - OPZIONE SCIENZE APPLICATE": (3, "LICEO-SCIENT.-SCIENZE-APPL.", None),
    "29. TERZA_LICEO DELLE SCIENZE APPLICATE_QUADRIENNALE":    (3, "LICEO_QUAD",  None),
    "30. QUARTA_AFM_INGLESE_FRANCESE":                         (4, "AFM",         "INGLESE_FRANCESE"),
    "31. QUARTA_AFM_INGLESE_TEDESCO":                          (4, "AFM",         "INGLESE_TEDESCO"),
    "32. QUARTA_SIA":                                          (4, "SIA",         None),
    "33. QUARTA_RIM_INGLESE_FRANCESE_SPAGNOLO":                (4, "RIM",         "INGLESE_FRANCESE_SPAGNOLO"),
    "34. QUARTA_RIM_INGLESE_TEDESCO_SPAGNOLO":                 (4, "RIM",         "INGLESE_TEDESCO_SPAGNOLO"),
    "35. QUARTA_AUTOMAZIONE":                                  (4, "AUTOMAZIONE", None),
    "36. QUARTA_MECCANICA_MECCATRONICA":                       (4, "MECCANICA",   None),
    "37. QUARTA_INFORMATICA":                                  (4, "INFORMATICA", None),
    "38. QUARTA_TELECOMUNICAZIONE":                            (4, "TELECOMUNICAZIONI", None),
    "39. QUARTA_LICEO SCIENZE APPLICATE":                      (4, "LICEO-SCIENT.-SCIENZE-APPL.", None),
    "40. QUARTA_LICEO SCIENZE APPLICATE QUADRIENNALE":         (4, "LICEO_QUAD",  None),
    "41. QUINTA_AFM_INGLESE_FRANCESE":                         (5, "AFM",         "INGLESE_FRANCESE"),
    "42. QUINTA_AFM_INGLESE_TEDESCO":                          (5, "AFM",         "INGLESE_TEDESCO"),
    "43. QUINTA_RIM_INGLESE_FRANCESE_SPAGNOLO":                (5, "RIM",         "INGLESE_FRANCESE_SPAGNOLO"),
    "44. QUINTA_RIM_INGLESE_TEDESCO_SPAGNOLO":                 (5, "RIM",         "INGLESE_TEDESCO_SPAGNOLO"),
    "45. QUINTA_AUTOMAZIONE":                                  (5, "AUTOMAZIONE", None),
    "46. QUINTA_MECCANICA_MECCATRONICA":                       (5, "MECCANICA",   None),
    "47. QUINTA_INFORMATICA":                                  (5, "INFORMATICA", None),
    "48. QUINTA_TELECOMUNICAZIONI":                            (5, "TELECOMUNICAZIONI", None),
    "49. QUINTA_LICEO SCIENZE APPLICATE":                      (5, "LICEO-SCIENT.-SCIENZE-APPL.", None),
}

# =============================================================================
# REGEX
# =============================================================================

ISBN_RE      = re.compile(r"\b97[89]\d{10}\b")
PRICE_RE     = re.compile(r"\b\d{1,3}\.\d{2}\b")
PRICE_ONLY   = re.compile(r"^\d{1,3}\.\d{2}$")   # stringa che è SOLO un prezzo
SCHOOL_YR_RE = re.compile(r"Anno Scolastico\s+(\d{4}/\d{4})", re.IGNORECASE)

# Righe di intestazione — non devono essere scambiate per libri
HEADER_RE = re.compile(
    r"^(Materia|Disciplina|Codice\s+Volume|Autore|Curatore|Traduttore|"
    r"Titolo\s*/|Tipo|Editore|Prezzo|Da\s+Acq|Nuova\s+Adoz|Cons\.|"
    r"ELENCO DEI LIBRI|ADOTTATI O CONSIGLIATI|IIS |FETD|"
    r"AMMINISTRAZIONE|Anno Scolastico|Classe:|Corso:)",
    re.IGNORECASE
)

# Costanti per il parser a coordinate
Y_TOL      = 2.5   # px: due parole sono sulla stessa riga logica
ADJ_MAX_DY = 6.0   # px: max distanza tra riga ISBN e riga di continuazione
DATA_Y_MIN = 120   # px: ignora tutto sopra (intestazioni)

# Posizioni colonne x nel PDF (rilevate dall'intestazione)
COL_MATERIA  = (0,   167)
COL_AUTORE   = (223, 395)
COL_TITOLO   = (395, 601)
COL_EDITORE  = (640, 722)
COL_PREZZO   = (695, 730)


# =============================================================================
# PARSER A COORDINATE
# =============================================================================

def _group_rows(words):
    """Raggruppa le parole in righe logiche tramite coordinata y."""
    rows = []
    for w in sorted(words, key=lambda x: (x['top'], x['x0'])):
        placed = False
        for row in rows:
            if abs(w['top'] - row['y_avg']) <= Y_TOL:
                row['words'].append(w)
                row['y_avg'] = sum(ww['top'] for ww in row['words']) / len(row['words'])
                placed = True
                break
        if not placed:
            rows.append({'y_avg': w['top'], 'words': [w]})
    for row in rows:
        row['words'].sort(key=lambda w: w['x0'])
    return sorted(rows, key=lambda r: r['y_avg'])


def _col(row_words, x_from, x_to):
    """Testo delle parole nella fascia colonna [x_from, x_to], senza prezzi isolati."""
    parts = [
        w['text'] for w in row_words
        if w['x0'] >= x_from and w['x0'] < x_to
        and not PRICE_ONLY.match(w['text'])
    ]
    return " ".join(parts).strip()


def extract_books_from_pdf(pdf_path):
    """Ritorna (lista_libri, anno_scolastico)."""
    all_rows    = []
    school_year = ""

    with pdfplumber.open(pdf_path) as pdf:
        full_text = "\n".join(p.extract_text() or "" for p in pdf.pages)
        m = SCHOOL_YR_RE.search(full_text)
        if m:
            school_year = m.group(1)

        for page in pdf.pages:
            words = page.extract_words(x_tolerance=3, y_tolerance=3)
            all_rows.extend(_group_rows(words))

    # Tieni solo le righe dati (sotto le intestazioni)
    data_rows = [r for r in all_rows if r['y_avg'] > DATA_Y_MIN]

    books   = []
    deduped = set()

    for i, row in enumerate(data_rows):
        row_text = " ".join(w['text'] for w in row['words'])

        # Riga valida = ha ISBN + prezzo
        isbn_m = ISBN_RE.search(row_text)
        if not isbn_m:
            continue
        pm = PRICE_RE.search(row_text)
        if not pm:
            continue

        isbn   = isbn_m.group()
        prezzo = pm.group()
        y0     = row['y_avg']

        materia = _col(row['words'], *COL_MATERIA)
        autore  = _col(row['words'], *COL_AUTORE)
        titolo  = _col(row['words'], *COL_TITOLO)
        editore = _col(row['words'], *COL_EDITORE)

        # ── Righe di CONTINUAZIONE (entro ADJ_MAX_DY) ────────────────────
        for adj in data_rows:
            if adj is row:
                continue
            dy = abs(adj['y_avg'] - y0)
            if dy > ADJ_MAX_DY:
                continue
            adj_text = " ".join(w['text'] for w in adj['words'])
            # salta righe di altri libri
            if ISBN_RE.search(adj_text) or PRICE_RE.search(adj_text):
                continue

            above = adj['y_avg'] < y0

            adj_titolo = _col(adj['words'], *COL_TITOLO)
            if adj_titolo:
                titolo = (adj_titolo + " " + titolo) if above else (titolo + " " + adj_titolo)

            adj_autore = _col(adj['words'], *COL_AUTORE)
            if adj_autore:
                autore = (adj_autore + " " + autore) if above else (autore + " " + adj_autore)

            adj_editore = _col(adj['words'], *COL_EDITORE)
            if adj_editore:
                editore = (adj_editore + " " + editore) if above else (editore + " " + adj_editore)

            if not materia:
                adj_materia = _col(adj['words'], *COL_MATERIA)
                if adj_materia:
                    materia = adj_materia

        if isbn not in deduped:
            deduped.add(isbn)
            books.append({
                "isbn":        isbn,
                "titolo":      titolo.strip()[:255],
                "author":      autore.strip()[:255],
                "school_year": school_year,
                "editore":     editore.strip()[:255],
                "materia":     materia.strip()[:255],
                "prezzo":      prezzo,
            })

    return books, school_year


# =============================================================================
# ABBINAMENTO CLASSI ↔ PDF
# =============================================================================

def normalize_indirizzo(raw):
    return INDIRIZZO_ALIAS.get(raw, raw)


def build_class_to_pdf_map(classes):
    inv    = {v: k for k, v in PDF_MAP.items()}
    result = {}
    for entry in classes:
        classe    = entry["classe"]
        raw_ind   = entry["indirizzo"]
        indirizzo = normalize_indirizzo(raw_ind)
        anno      = int(classe[0])
        variante  = LINGUA_OVERRIDE.get(classe)
        for key in [(anno, indirizzo, variante), (anno, indirizzo, None)]:
            if key in inv:
                result[classe] = inv[key]
                break
        else:
            print("  ⚠️  Nessun PDF per {} (anno={}, ind='{}', var={})".format(
                classe, anno, raw_ind, variante))
    return result


# =============================================================================
# DB HELPERS
# =============================================================================

def get_or_create(cursor, table, id_col, where_col, where_val, extra=None):
    cursor.execute(
        "SELECT {} FROM {} WHERE {}=%s".format(id_col, table, where_col),
        (where_val,)
    )
    row = cursor.fetchone()
    if row:
        return row[0]
    cols = [where_col] + list((extra or {}).keys())
    vals = [where_val] + list((extra or {}).values())
    ph   = ", ".join(["%s"] * len(vals))
    cursor.execute(
        "INSERT INTO {} ({}) VALUES ({})".format(table, ", ".join(cols), ph),
        vals
    )
    return cursor.lastrowid


def upsert_book(cursor, book, id_class, id_subject, id_publish_house, id_order):
    cursor.execute("""
        INSERT IGNORE INTO books
            (titolo, isbn, author, school_year,
             id_class, id_subject, id_publish_house, id_order)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
    """, (
        book["titolo"], book["isbn"], book["author"], book["school_year"],
        id_class, id_subject, id_publish_house, id_order,
    ))


# =============================================================================
# MAIN
# =============================================================================

def main(dry_run=False, json_out=None):
    json_out = json_out or JSON_OUT

    with open(CLASSES_JSON, encoding="utf-8") as f:
        classes = json.load(f)

    class_to_pdf = build_class_to_pdf_map(classes)
    print("\n✅ Abbinate {}/{} classi a un PDF\n".format(
        len(class_to_pdf), len(classes)))

    class_info = {e["classe"]: e for e in classes}

    conn   = None if dry_run else pymysql.connect(**DB, autocommit=False)
    cursor = conn.cursor() if conn else None

    pdf_to_classes = {}
    for classe, pdf_name in class_to_pdf.items():
        pdf_to_classes.setdefault(pdf_name, []).append(classe)

    pdf_dir     = Path(PDF_DIR)
    total_books = 0
    errors      = []

    json_output = {
        "generato_il": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
        "classi": {}
    }

    for pdf_name, classi in sorted(pdf_to_classes.items()):
        pdf_file = pdf_dir / "{}.pdf".format(pdf_name)
        if not pdf_file.exists():
            safe = pdf_name.replace(" ", "_").replace(",", "")
            candidates = list(pdf_dir.glob("*{}*.pdf".format(pdf_name[:12])))
            if not candidates:
                candidates = list(pdf_dir.glob("*{}*.pdf".format(safe[:12])))
            pdf_file = candidates[0] if candidates else None

        if not pdf_file or not pdf_file.exists():
            print("⚠️  PDF non trovato: {}".format(pdf_name))
            errors.append("PDF mancante: {}".format(pdf_name))
            for classe in sorted(classi):
                json_output["classi"][classe] = {
                    "classe": classe, "indirizzo": class_info[classe]["indirizzo"],
                    "anno_scolastico": "", "pdf_sorgente": pdf_name,
                    "errore": "PDF non trovato", "totale_libri": 0, "libri": []
                }
            continue

        print("\n📄 {}".format(pdf_file.name))
        print("   Classi: {}".format(", ".join(sorted(classi))))

        try:
            books, school_year = extract_books_from_pdf(str(pdf_file))
        except Exception as e:
            print("   ❌ Errore: {}".format(e))
            errors.append("Errore {}: {}".format(pdf_file.name, e))
            continue

        print("   Anno: {}  |  Libri: {}".format(school_year or "?", len(books)))

        if not books:
            print("   ⚠️  0 libri estratti")
            errors.append("0 libri da {}".format(pdf_file.name))

        libri_json = [
            {"isbn": b["isbn"], "titolo": b["titolo"], "autore": b["author"],
             "materia": b["materia"], "editore": b["editore"], "prezzo": b["prezzo"]}
            for b in books
        ]

        for classe in sorted(classi):
            raw_ind   = class_info[classe]["indirizzo"]
            indirizzo = normalize_indirizzo(raw_ind)

            json_output["classi"][classe] = {
                "classe":          classe,
                "indirizzo":       raw_ind,
                "anno_scolastico": school_year,
                "pdf_sorgente":    pdf_name,
                "totale_libri":    len(libri_json),
                "libri":           libri_json,
            }

            if dry_run:
                for b in books:
                    print("   [DRY] {} | {} | {}".format(
                        classe, b["isbn"], b["titolo"][:60]))
                total_books += len(books)
                continue

            id_class = get_or_create(cursor, "class", "id_class", "class", classe,
                                     {"description": indirizzo})
            for book in books:
                id_subject = get_or_create(cursor, "subjects", "id_subject",
                                           "name", book["materia"] or "VARIE")
                id_ph = None
                if book["editore"]:
                    id_ph = get_or_create(cursor, "publishing_house",
                                          "id_publish_house", "name", book["editore"])
                upsert_book(cursor, book, id_class, id_subject, id_ph, None)
                total_books += 1

        if conn:
            conn.commit()
            print("   ✅ Commit per {}".format(", ".join(sorted(classi))))

    if conn:
        conn.close()

    json_output["classi"] = dict(
        sorted(json_output["classi"].items(),
               key=lambda x: (int(x[0][0]), x[0][1:]))
    )
    json_output["totale_classi"] = len(json_output["classi"])
    json_output["totale_libri"]  = sum(
        v["totale_libri"] for v in json_output["classi"].values()
    )

    with open(json_out, "w", encoding="utf-8") as f:
        json.dump(json_output, f, ensure_ascii=False, indent=2)

    print("\n{}Totale libri: {}".format("[DRY-RUN] " if dry_run else "", total_books))
    print("📝 JSON: {}  ({} classi)".format(json_out, json_output["totale_classi"]))

    if errors:
        print("\n⚠️  Problemi:")
        for e in errors:
            print("   • {}".format(e))


if __name__ == "__main__":
    ap = argparse.ArgumentParser(
        description="Popola DB libri dai 49 PDF + genera JSON per classe"
    )
    ap.add_argument("--dry-run",  action="store_true",
                    help="Genera solo il JSON, non scrive nel DB")
    ap.add_argument("--json-out", default=None,
                    help="Percorso file JSON output (default: libri_per_classe.json)")
    args = ap.parse_args()
    main(dry_run=args.dry_run, json_out=args.json_out)