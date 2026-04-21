<?php 
defined("APP") or die("ACCESSO NEGATO");
require_once "../src/models/UtilsModel.php";

$model = new UtilsModel();
function classExist($class){
    $clases = $GLOBALS['model']->getNameFromClass();

    foreach($clases as $item){
        if($item['class'] == $class){
            return true;
        }
    }
    return false;
}

function facultyExist($faculty){
    $facultys = $GLOBALS['model']->getNameFromFaculty();
    foreach($facultys as $item){
        if($item['name'] == $faculty){
            return true;
        }
    }
    return false;
}

function subjectExist($subject){
    $subects = $GLOBALS['model']->getNameFromFaculty();
    foreach($subects as $item){
        if($item['name'] == $subject){
            return true;
        }
    }
    return false;
}

function isValidISBN($isbn) {
    // Rimuove trattini e spazi
    $isbn = str_replace(['-', ' '], '', $isbn);
    $length = strlen($isbn);

    if ($length === 10) {
        // Validazione ISBN-10
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            if (!is_numeric($isbn[$i])) return false;
            $sum += (int)$isbn[$i] * (10 - $i);
        }

        // L'ultimo carattere può essere un numero o 'X' (che vale 10)
        $lastChar = strtoupper($isbn[9]);
        if ($lastChar === 'X') {
            $sum += 10;
        } elseif (is_numeric($lastChar)) {
            $sum += (int)$lastChar;
        } else {
            return false;
        }

        return true;

    } elseif ($length === 13) {
        // Validazione ISBN-13
        if (!is_numeric($isbn)) return false;

        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            // Moltiplica per 1 i numeri in posizione dispari e per 3 quelli in posizione pari
            $multiplier = ($i % 2 === 0) ? 1 : 3;
            $sum += (int)$isbn[$i] * $multiplier;
        }

        return true;
    }

    return false;
}

function isEmailExist($email){
    $emailDB = $GLOBALS['model']->selectAllEmail();
    foreach($emailDB as $item){
        if($item == $email){
            return true;
        }
    }
    return false;
}

?>