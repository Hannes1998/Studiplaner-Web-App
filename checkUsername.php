<?php

session_start(); // Startet Session. Mehrere ausführungen sind unproblematisch
header('Content-Type: application/json'); // Header für HTTP-Paket mit Informationen zu Paket


$response = ['loggedIn' => false]; // $response ist ein 'associative array' mit einem Key-Value-Paar (loggedIn und true/false)

if (isset($_SESSION['username'])) {
    $response['loggedIn'] = true; // true wird als Value-Wert zum Key loggedIn gesetzt
}

echo json_encode($response); // json_encode 'konvertiert' den php array, damit er lesbar für Client wird
?>
