"""
populate_books.py  v2
=================
Legge i 49 PDF delle liste libri (IIS Bassi Burgatti), abbina ogni PDF
alle classi corrette tramite il JSON delle classi + LINGUA_OVERRIDE,
estrae i libri e li inserisce nel DB.

Struttura colonne PDF:
  Materia | Codice(ISBN) | Autore | Titolo | Vol. | Tipo | Editore | Prezzo | Nuova Adoz. | Da Acq. | Cons.

Dipendenze:
    pip install pdfplumber pymysql

Utilizzo:
    python populate_books.py --dry-run    # stampa senza toccare il DB
    python populate_books.py              # inserisce nel DB
"""

import re
import json
import argparse
import pdfplumber
import pymysql
from pathlib import Path

# ─────────────────────────────────────────────────────────────────────────────
# CONFIGURAZIONE
# ─────────────────────────────────────────────────────────────────────────────

PDF_DIR      = "./pdf_libri"
CLASSES_JSON = "./classi.json"

DB = dict(
    host     = "localhost",
    user     = "root",
    password = "password",
    database = "nome_db",
    charset  = "utf8mb4",
)

# ── Mappatura lingua per classi AFM, RIM, SIA ────────────────────────────────
# Compila con i dati reali. Classi con indirizzo univoco (MECCANICA, ecc.)
# non hanno bisogno di essere elencate qui.
LINGUA_OVERRIDE = {
    # AFM prima
    "1A": "INGLESE_TEDESCO",
    "1B": "INGLESE_FRANCESE",
    "1C": "INGLESE_FRANCESE",
    "1D": "INGLESE_FRANCESE",
    # AFM seconda (esiste solo un PDF → francese per tutti)
    "2A": "INGLESE_FRANCESE",
    "2B": "INGLESE_FRANCESE",
    "2C": "INGLESE_FRANCESE",
    "2D": "INGLESE_FRANCESE",
    # AFM terza/quarta/quinta  ← controlla con la segreteria
    "3A": "INGLESE_FRANCESE",
    "4A": "INGLESE_FRANCESE",
    "5A": "INGLESE_FRANCESE",
    # RIM
    "3B": "INGLESE_FRANCESE_SPAGNOLO",
    "3D": "INGLESE_FRANCESE_SPAGNOLO",
    "3E": "INGLESE_TEDESCO_SPAGNOLO",
    "4D": "INGLESE_FRANCESE_SPAGNOLO",
    "4E": "INGLESE_TEDESCO_SPAGNOLO",
    "5D": "INGLESE_FRANCESE_SPAGNOLO",
    "5E": "INGLESE_TEDESCO_SPAGNOLO",
}

# ─────────────────────────────────────────────────────────────────────────────
# MAPPATURA NOME PDF → (anno, indirizzo_base, variante_lingua)
# ─────────────────────────────────────────────────────────────────────────────

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

# ─────────────────────────────────────────────────────────────────────────────
# REGEX
# ─────────────────────────────────────────────────────────────────────────────

ISBN_RE     = re.compile(r"\b97[89]\d{10}\b")
PRICE_RE    = re.compile(r"\b\d{1,3}\.\d{2}\b")
SCHOOL_YR_RE= re.compile(r"Anno Scolastico\s+(\d{4}/\d{4})", re.IGNORECASE)

HEADER_SKIP = {
    "materia", "disciplina", "codice", "volume", "autore", "curatore",
    "titolo", "sottotitolo", "tipo", "editore", "prezzo",
    "adottati", "consigliati", "elenco", "classe", "corso",
    "anno scolastico", "nuova", "adoz", "da acq", "cons",
    "iis", "fetd",
}


def _is_header(text: str) ->bool:
    low = text.lower()
    return any(kw in low for kw in HEADER_SKIP)


# ─────────────────────────────────────────────────────────────────────────────
# PARSER PDF
#
# Struttura tabella:
#   col 0  → Materia/Disciplina
#   col 1  → Codice ISBN
#   col 2  → Autore
#   col 3  → Titolo / Sottotitolo
#   col 4  → Vol.
#   col 5  → Tipo
#   col 6  → Editore
#   col 7  → Prezzo
#   col 8  → Nuova Adoz.
#   col 9  → Da Acq.
#   col 10 → Cons.
# ─────────────────────────────────────────────────────────────────────────────

def extract_books_from_pdf(pdf_path: str) ->tuple:
    """Ritorna (libri, anno_scolastico)."""
    books       = []
    school_year = ""

    with pdfplumber.open(pdf_path) as pdf:
        full_text = "\n".join(p.extract_text() or "" for p in pdf.pages)

        m = SCHOOL_YR_RE.search(full_text)
        if m:
            school_year = m.group(1)

        for page in pdf.pages:
            tables = page.extract_tables() or []
            for table in tables:
                for row in table:
                    b = _parse_row(row, school_year)
                    if b:
                        books.append(b)

        if not books:
            books = _parse_text_fallback(full_text, school_year)

    # deduplicazione per ISBN
    deduped = {}
    for b in books:
        deduped.setdefault(b["isbn"], b)
    return list(deduped.values()), school_year


def _parse_row(row: list, school_year: str) -> "dict | None":
    if not row:
        return None
    cells = [(c or "").strip() for c in row]
    joined = " ".join(cells)

    if not ISBN_RE.search(joined):
        return None
    if _is_header(joined):
        return None

    # trova la colonna dell'ISBN
    isbn_col = next((i for i, c in enumerate(cells) if ISBN_RE.search(c)), -1)
    if isbn_col < 0:
        return None

    isbn    = ISBN_RE.search(cells[isbn_col]).group()
    materia = cells[0] if isbn_col >= 1 else ""
    autore  = cells[isbn_col + 1].strip() if isbn_col + 1 < len(cells) else ""
    titolo  = cells[isbn_col + 2].strip() if isbn_col + 2 < len(cells) else ""
    # Vol. e Tipo occupano 2 celle, Editore è alla posizione +5
    editore_idx = isbn_col + 5
    editore = cells[editore_idx].replace("\n", " ").strip() if editore_idx < len(cells) else ""
    # Prezzo
    pm      = PRICE_RE.search(joined)
    prezzo  = pm.group() if pm else ""

    if not titolo:
        return None

    return {
        "isbn":        isbn,
        "titolo":      titolo[:255],
        "author":      autore[:255],
        "school_year": school_year,
        "editore":     editore[:255],
        "materia":     materia[:255],
        "prezzo":      prezzo,
    }


def _parse_text_fallback(text: str, school_year: str) ->list:
    """Fallback: riga per riga, ancora sull'ISBN."""
    books   = []
    materia = ""
    for line in text.splitlines():
        line = line.strip()
        if not line:
            continue
        if not ISBN_RE.search(line):
            if line.isupper() and 3 < len(line) < 80 and not _is_header(line):
                materia = line
            continue
        isbn = ISBN_RE.search(line).group()
        rest = line.replace(isbn, "").strip()
        pm   = PRICE_RE.search(rest)
        books.append({
            "isbn":        isbn,
            "titolo":      rest[:255],
            "author":      "",
            "school_year": school_year,
            "editore":     "",
            "materia":     materia[:255],
            "prezzo":      pm.group() if pm else "",
        })
    return books


# ─────────────────────────────────────────────────────────────────────────────
# ABBINAMENTO CLASSI ↔ PDF
# ─────────────────────────────────────────────────────────────────────────────

def build_class_to_pdf_map(classes: list) ->dict:
    inv = {v: k for k, v in PDF_MAP.items()}
    result = {}
    for entry in classes:
        classe    = entry["classe"]
        indirizzo = entry["indirizzo"]
        anno      = int(classe[0])
        variante  = LINGUA_OVERRIDE.get(classe)

        key = (anno, indirizzo, variante)
        if key in inv:
            result[classe] = inv[key]
            continue
        key2 = (anno, indirizzo, None)
        if key2 in inv:
            result[classe] = inv[key2]
            continue
        print(f"  ⚠️  Nessun PDF per {classe} "
              f"(anno={anno}, indirizzo={indirizzo}, variante={variante})")
    return result


# ─────────────────────────────────────────────────────────────────────────────
# DB HELPERS
# ─────────────────────────────────────────────────────────────────────────────

def get_or_create(cursor, table: str, id_col: str,
                  where_col: str, where_val: str,
                  extra: dict = None) ->int:
    cursor.execute(
        f"SELECT {id_col} FROM {table} WHERE {where_col}=%s", (where_val,)
    )
    row = cursor.fetchone()
    if row:
        return row[0]
    cols = [where_col] + list((extra or {}).keys())
    vals = [where_val] + list((extra or {}).values())
    ph   = ", ".join(["%s"] * len(vals))
    cursor.execute(
        f"INSERT INTO {table} ({', '.join(cols)}) VALUES ({ph})", vals
    )
    return cursor.lastrowid


def upsert_book(cursor, book: dict,
                id_class: int, id_subject: int,
                id_publish_house, id_order) ->None:
    cursor.execute("""
        INSERT IGNORE INTO books
            (titolo, isbn, author, school_year,
             id_class, id_subject, id_publish_house, id_order)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
    """, (
        book["titolo"], book["isbn"], book["author"], book["school_year"],
        id_class, id_subject, id_publish_house, id_order,
    ))


# ─────────────────────────────────────────────────────────────────────────────
# MAIN
# ─────────────────────────────────────────────────────────────────────────────

def main(dry_run: bool = False) ->None:
    with open(CLASSES_JSON, encoding="utf-8") as f:
        classes = json.load(f)

    class_to_pdf = build_class_to_pdf_map(classes)
    print(f"\n✅ Abbinate {len(class_to_pdf)}/{len(classes)} classi a un PDF\n")

    conn   = None if dry_run else pymysql.connect(**DB, autocommit=False)
    cursor = conn.cursor() if conn else None

    pdf_to_classes: dict = {}
    for classe, pdf_name in class_to_pdf.items():
        pdf_to_classes.setdefault(pdf_name, []).append(classe)

    pdf_dir     = Path(PDF_DIR)
    total_books = 0
    errors      = []

    for pdf_name, classi in sorted(pdf_to_classes.items()):
        # ricerca file flessibile: nome esatto, poi partial match
        candidates = [
            pdf_dir / f"{pdf_name}.pdf",
            *pdf_dir.glob(f"*{pdf_name[:10]}*.pdf"),
        ]
        pdf_file = next((p for p in candidates if p.exists()), None)

        if not pdf_file:
            # prova anche con underscore al posto di spazi
            safe = pdf_name.replace(" ", "_").replace(",", "")
            pdf_file = next(pdf_dir.glob(f"*{safe[:15]}*"), None)

        if not pdf_file:
            print(f"⚠️  PDF non trovato: {pdf_name}")
            errors.append(f"PDF mancante: {pdf_name}")
            continue

        print(f"\n📄 {pdf_file.name}")
        print(f"   Classi: {', '.join(sorted(classi))}")

        try:
            books, school_year = extract_books_from_pdf(str(pdf_file))
        except Exception as e:
            print(f"   ❌ Errore: {e}")
            errors.append(f"Errore lettura {pdf_file.name}: {e}")
            continue

        print(f"   Anno: {school_year or '?'}  |  Libri: {len(books)}")

        if not books:
            print("   ⚠️  0 libri estratti — controlla il parser")
            errors.append(f"0 libri da {pdf_file.name}")
            continue

        for classe in sorted(classi):
            indirizzo = next(
                e["indirizzo"] for e in classes if e["classe"] == classe
            )

            if dry_run:
                for b in books:
                    print(f"   [DRY] {classe} | {b['isbn']} | "
                          f"{b['materia'][:20]:20} | {b['titolo'][:40]}")
                total_books += len(books)
                continue

            id_class = get_or_create(
                cursor, "class", "id_class", "class", classe,
                {"description": indirizzo}
            )

            for book in books:
                id_subject = get_or_create(
                    cursor, "subjects", "id_subject",
                    "name", book["materia"] or "VARIE"
                )
                id_ph = None
                if book["editore"]:
                    id_ph = get_or_create(
                        cursor, "publishing_house", "id_publish_house",
                        "name", book["editore"]
                    )
                upsert_book(cursor, book, id_class, id_subject, id_ph, None)
                total_books += 1

        if conn:
            conn.commit()
            print(f"   ✅ Commit OK per {', '.join(sorted(classi))}")

    if conn:
        conn.close()

    print(f"\n{'[DRY-RUN] ' if dry_run else ''}Totale libri inseriti: {total_books}")
    if errors:
        print("\n⚠️  Attenzione:")
        for e in errors:
            print(f"   • {e}")


if __name__ == "__main__":
    ap = argparse.ArgumentParser(
        description="Popola DB libri dai 49 PDF — IIS Bassi Burgatti"
    )
    ap.add_argument("--dry-run", action="store_true",
                    help="Stampa senza scrivere nel DB")
    args = ap.parse_args()
    main(dry_run=args.dry_run)