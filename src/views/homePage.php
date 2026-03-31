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
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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

        .btn-filter, .btn-search {
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

        .btn-filter:hover, .btn-search:hover {
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
            height: 500px; /* Altezza fissa: regola questo valore secondo le tue esigenze */
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Impedisce al contenuto di uscire dai bordi arrotondati */
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
            overflow-y: auto; /* Attiva lo scroll verticale se la tabella è troppo lunga */
            overflow-x: hidden; /* Evita lo scroll orizzontale se non necessario */
            background-color: #ffffff;
            padding: 10px;
        }

        /* Personalizzazione della barra di scorrimento (Scrollbar) */
        .display-area::-webkit-scrollbar {
            width: 10px; /* Larghezza della barra a destra */
        }

        .display-area::-webkit-scrollbar-track {
            background: #f1f7ff; /* Colore della traccia (sfondo barra) */
            border-radius: 0 8px 8px 0;
        }

        .display-area::-webkit-scrollbar-thumb {
            background: #007bff; /* Colore della parte scorrevole (blu) */
            border: 2px solid #f1f7ff; /* Crea un piccolo distacco visivo */
            border-radius: 10px;
        }

        .display-area::-webkit-scrollbar-thumb:hover {
            background: #0056b3; /* Blu più scuro al passaggio del mouse */
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
    </style>
</head>
<body>

    <header>
        <div class="logo">BookSwap</div>
        <nav>
            <ul>
                <li><a href="#">vendi</a></li>
                <li><a href="views/login.php">LOGIN</a></li>
                <li><a href="views/carrello.php">carrello</a></li>
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