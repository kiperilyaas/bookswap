<?php
defined("APP") or die("ACCESSO NEGATO");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizzazione Libri</title>
    <style>
        :root {
            --primary-blue: #004085;
            --secondary-blue: #007bff;
            --light-blue: #e7f1ff;
            --white: #ffffff;
            --text-color: #333333;
            --border-color: #b8daff;
        }

        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--white);
            color: var(--text-color);
        }


        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: var(--primary-blue);
            color: var(--white);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 1.2rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 25px;
            margin: 0;
            padding: 0;
        }

        nav a {
            text-decoration: none;
            color: var(--white);
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav a:hover {
            color: var(--light-blue);
        }


        .search-container {
            display: flex;
            gap: 15px;
            padding: 25px 30px;
            background-color: var(--light-blue);
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .btn-filter,
        .btn-search {
            border: 1px solid var(--secondary-blue);
            background-color: var(--secondary-blue);
            color: var(--white);
            padding: 8px 20px;
            cursor: pointer;
            border-radius: 4px;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-filter:hover,
        .btn-search:hover {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .search-input {
            flex-grow: 1;
            border: 1px solid var(--border-color);
            padding: 8px 15px;
            max-width: 600px;
            border-radius: 4px;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }


        main {
            flex-grow: 1;
            padding: 30px;
            background-color: var(--white);
        }

        .content-box {
            border: 1px solid #b8daff;
            border-radius: 8px;
            width: 100%;
            height: 500px;
            /* Altezza fissa: regola questo valore secondo le tue esigenze */
            display: flex;
            flex-direction: column;
            overflow: hidden;
            /* Impedisce al contenuto di uscire dai bordi arrotondati */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table-row {
            border-bottom: 1px solid var(--border-color);
            height: 45px;
            background-color: #fafcfd;
        }

        .table-row:nth-child(even) {
            background-color: var(--white);
        }

        .display-area {
            flex-grow: 1;
            overflow-y: auto;
            /* Attiva lo scroll verticale se la tabella è troppo lunga */
            overflow-x: hidden;
            /* Evita lo scroll orizzontale se non necessario */
            background-color: #ffffff;
            padding: 10px;

            display: flex;
            flex-direction: column;
            gap: 20px; /* Spazio verticale tra un libro e l'altro */
            padding: 20px;
            background-color: #f0f5fa;
            overflow-y: auto;
            height: 600px;
        }

        /* Personalizzazione della barra di scorrimento (Scrollbar) */
        .display-area::-webkit-scrollbar {
            width: 10px;
            /* Larghezza della barra a destra */
        }

        .display-area::-webkit-scrollbar-track {
            background: #f1f7ff;
            /* Colore della traccia (sfondo barra) */
            border-radius: 0 8px 8px 0;
        }

        .display-area::-webkit-scrollbar-thumb {
            background: #007bff;
            /* Colore della parte scorrevole (blu) */
            border: 2px solid #f1f7ff;
            /* Crea un piccolo distacco visivo */
            border-radius: 10px;
        }

        .display-area::-webkit-scrollbar-thumb:hover {
            background: #0056b3;
            /* Blu più scuro al passaggio del mouse */
        }


        footer {
            padding: 20px 30px;
            font-size: 12px;
            text-transform: uppercase;
            background-color: var(--light-blue);
            color: var(--primary-blue);
            border-top: 1px solid var(--border-color);
            margin-top: auto;
        }

        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            padding: 10px;
            font-size: 12px;
        }

        /* Container dell'immagine a sinistra */
        .book-image-container {
            width: 100px;
            min-width: 100px; /* Non si restringe */
            height: 130px;
            background-color: #f8fbff;
            border: 1px solid #e1eefc;
            border-radius: 6px;
            margin-right: 20px; /* Spazio tra foto e testo */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Stile per l'immagine vera e propria */
        .book-image-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain; /* Mantiene le proporzioni della copertina */
        }

        /* Placeholder se non c'è l'immagine */
        .image-placeholder {
            font-size: 10px;
            color: #007bff;
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Modifica al blocco info per allinearlo all'immagine */
        .book-info {
            flex-grow: 1; /* Prende tutto lo spazio rimanente al centro */
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .book-cart {
            background-color: #ffffff;
            border: 1px solid #d1e3f8;
            border-left: 5px solid #007bff;
            border-radius: 12px;
            padding: 15px;
            
            /* ALLINEAMENTO ORIZZONTALE */
            display: flex; 
            flex-direction: row; /* Foto, Info e Bottone sulla stessa riga */
            align-items: center; /* Centra tutto verticalmente */
            
            box-shadow: 0 4px 12px rgba(0, 64, 133, 0.08);
        }

        .book-actions {
            margin-left: 20px;
            min-width: 120px;
            text-align: right;
        }

        .btn-add {
            /* Colori e Sfondo */
            background-color: #007bff; /* Blu vivace */
            color: #ffffff;            /* Testo bianco */
            
            /* Dimensioni e Spaziatura */
            padding: 12px 24px;
            border: none;
            border-radius: 6px;        /* Angoli leggermente arrotondati */
            
            /* Tipografia */
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase; /* Testo tutto in maiuscolo */
            letter-spacing: 0.5px;
            
            /* Interazione */
            cursor: pointer;
            transition: all 0.3s ease; /* Animazione fluida per tutti i cambiamenti */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            
            /* Ombra leggera per profondità */
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2);
        }

        /* Effetto quando ci passi sopra con il mouse */
        .btn-add:hover {
            background-color: #0056b3; /* Blu più scuro */
            box-shadow: 0 6px 12px rgba(0, 86, 179, 0.3);
            transform: translateY(-2px); /* Si alza leggermente */
        }

        /* Effetto quando lo clicchi */
        .btn-add:active {
            transform: translateY(0); /* Torna giù */
            box-shadow: 0 2px 4px rgba(0, 86, 179, 0.2);
        }

        /* Opzionale: Stile per l'icona del carrello se decidi di aggiungerla */
        .btn-add::before {
            content: '🛒'; /* Icona emoji semplice o usa un font-icon */
            margin-right: 8px;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">BookSwap</div>
        <nav>
            <ul>
                <li><a href="#">vendi</a></li>
                <li><a href="#">LOGIN</a></li>
                <li><a href="#">carrello</a></li>
            </ul>
        </nav>
    </header>

    <div class="search-container">
        <button class="btn-filter">filtro</button>
        <input type="text" class="search-input" placeholder="Cerca libri, autori, generi...">
        <button class="btn-search">cerca</button>
    </div>

    <main>
        <div class="content-box">
            <div class="display-area">
                <?php
                include 'table.php';
                ?>
            </div>
        </div>
    </main>

    <footer>
        © Kiper Illia, Melega Leonardo, Trevisani Martina, Bertolani Leo
    </footer>

</body>

</html>