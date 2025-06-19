<?php

session_start();

// PHP läuft serverseitig, $_SESSION['username'] ist schwer zu manipulieren
if (!isset($_SESSION['username'])) {
    die('Error: Unauthorized access. Please log in.'); // Beendet die Ausführung des Codes sofort
}

$db = mysqli_connect('localhost', 'root', '', 'studiplanerdb');

if (isset($_POST['display_input'])) {
    $username = $_SESSION['username'];

    $query = "SELECT * FROM nutzereingaben WHERE username='$username'"; // Alle Einträge des angemeldeten Nutzers
    $result = mysqli_query($db, $query);

    // Array zum Speichern der abgerufenen Einträge
    $entries = array();

    // Überprüfen, ob Einträge gefunden wurden
    if (mysqli_num_rows($result) > 0) {
        // Einträge in das Array einfügen
        while ($row = mysqli_fetch_assoc($result)) { // 'row' ist ein 'associative array' aus Schlüssel- und Wertpaaren (Spaltenname und Wert)
            array_push($entries, $row); // Jede Zeile zu entries hinzufügen (jede Zeile ist ein Nutzereintrag)
        }
    }

    // JSON (JavaScript Object Notation) um Daten an Client zu senden
    echo json_encode($entries);
}
?>
