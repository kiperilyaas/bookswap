<?php
// 1. CONFIGURAZIONE DEL DATABASE


// 2. OPZIONI DI SICUREZZA PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Funzione Helper per gestire le Chiavi Esterne (Foreign Keys)
function getOrInsertId($pdo, $table, $idCol, $valCol, $value) {
    if (empty(trim($value))) {
        return null;
    }

    $stmt = $pdo->prepare("SELECT $idCol FROM $table WHERE $valCol = :val LIMIT 1");
    $stmt->execute([':val' => $value]);
    $row = $stmt->fetch();

    if ($row) {
        return $row[$idCol];
    } else {
        $insertStmt = $pdo->prepare("INSERT INTO $table ($valCol) VALUES (:val)");
        $insertStmt->execute([':val' => $value]);
        return $pdo->lastInsertId();
    }
}

try {
    // 3. CONNESSIONE AL DATABASE
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, $options);

    // 4. LETTURA DEL FILE JSON
    $jsonFile = './libriv2.json';
    if (!file_exists($jsonFile)) {
        die("Errore di sicurezza: Il file JSON non trovato.");
    }

    $jsonData = file_get_contents($jsonFile);
    $data = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Errore di sicurezza: File JSON malformato.");
    }

    // 5. INIZIO TRANSAZIONE
    $pdo->beginTransaction();

    // Query AGGIORNATA: Aggiunto il campo `price`
    $sqlBook = "INSERT INTO books (title, isbn, vol, author, school_year, price, id_class, id_subject, id_publish_house, id_faculty) 
                VALUES (:title, :isbn, :vol, :author, :school_year, :price, :id_class, :id_subject, :id_publish_house, :id_faculty)";
    
    $stmtBook = $pdo->prepare($sqlBook);
    $libriInseriti = 0;

    // 6. ITERAZIONE SUI DATI
    if (isset($data['classi'])) {
        foreach ($data['classi'] as $datiClasse) {
            
            $nomeClasse = $datiClasse['classe'];
            $indirizzo = $datiClasse['indirizzo'];
            $annoScolastico = $datiClasse['anno_scolastico'];
            
            $idClass = getOrInsertId($pdo, 'class', 'id_class', 'class', $nomeClasse);
            $idFaculty = getOrInsertId($pdo, 'faculty', 'id_faculty', 'name', $indirizzo);

            if (isset($datiClasse['libri']) && is_array($datiClasse['libri'])) {
                foreach ($datiClasse['libri'] as $libro) {
                    
                    $idSubject = getOrInsertId($pdo, 'subjects', 'id_subject', 'name', $libro['materia']);
                    $idPublishHouse = getOrInsertId($pdo, 'publishing_house', 'id_publish_house', 'name', $libro['editore']);
                    
                    // Formattazione sicura del prezzo: cambia virgola in punto e converte in float
                    $prezzoPulito = str_replace(',', '.', $libro['prezzo']);
                    $prezzoFinale = is_numeric($prezzoPulito) ? floatval($prezzoPulito) : 0.00;
                    
                    // Inserimento del Libro con il PREZZO
                    $stmtBook->execute([
                        ':title'           => $libro['titolo'],
                        ':isbn'             => $libro['isbn'],
                        ':vol'              => '', 
                        ':author'           => $libro['autore'],
                        ':school_year'      => $annoScolastico,
                        ':price'            => $prezzoFinale, // Inserimento del prezzo formattato
                        ':id_class'         => $idClass,
                        ':id_subject'       => $idSubject,
                        ':id_publish_house' => $idPublishHouse,
                        ':id_faculty'       => $idFaculty
                    ]);
                    
                    $libriInseriti++;
                }
            }
        }
    }

    // 7. CONFERMA
    $pdo->commit();
    echo "<h1>Importazione Completata!</h1><p>Sono stati importati <strong>$libriInseriti</strong> libri (inclusi i prezzi) nel database.</p>";

} catch (\PDOException $e) {
    // 8. ROLLBACK IN CASO DI ERRORE
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("DB Error: " . $e->getMessage());
    echo "<h1>Errore Critico</h1><p>Operazione annullata. Controlla i log del server per i dettagli tecnici.</p>";
}
?>