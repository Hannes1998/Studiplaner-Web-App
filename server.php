<?php

session_start(); // Startet Session. Mehrere ausführungen sind unproblematisch

$username = "";
$email    = "";
$errors = array(); 

// Datenbank verbindung herstellen
$db = mysqli_connect('localhost', 'root', '', 'studiplanerdb');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // Input des Forms
  $username = trim(mysqli_real_escape_string($db, strtolower($_POST['username']))); // strtolower() macht alles zu Kleinbuchstaben
  $email = trim(mysqli_real_escape_string($db, strtolower($_POST['email']))); // strtolower() macht alles zu Kleinbuchstaben
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']); // mysqli_real_escape_string() "neutralisiert" einige Sonderzeichen um SQL-Injektion vorzubeugen
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']); // mysqli_real_escape_string() "neutralisiert" einige Sonderzeichen um SQL-Injektion vorzubeugen

  // Sicherstellen dass alles richtig ausgefüllt ist
  if (empty($username)) {
     array_push($errors, "Bitte Username angeben");
  } else if (strlen($username) < 3) { // strlen() = "String-length"
    array_push($errors, "Der Username muss mindestens 3 Zeichen lang sein");
  } else if (strlen($username) > 16) { // strlen() = "String-length"
    array_push($errors, "Der Username darf maximal 16 Zeichen lang sein");
  }  elseif (preg_match('/\s{2,}/', $username)) { // Wenn der Username an irgendeiner Stelle dieses Muster behinhaltet \s{2,} = mindestens 2 aufeinander folgende Leerzeichen
    array_push($errors, "Keine aufeinander folgenden Leerzeichen.");
}

  if (empty($email)) {
     array_push($errors, "Bitte Email angeben");
  } else if (!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) { // Wenn die Emailadresse nicht diesem Muster entspricht
    array_push($errors, "Bitte eine gültige Email-Adresse eingeben"); // /^ = Beginn der Zeichenkette   ]+ = beliebig viele   \. = genau ein Punkt    ]{2,} = mindestens zwei davon   $/ = Ende der Zeichenkette
  }
  if (empty($password_1)) {
     array_push($errors, "Bitte Password angeben");
  } else if (strlen($password_1) < 5) { // strlen() = "String-length"
    array_push($errors, "Das Passwort muss mindestens 5 Zeichen lang sein");
  } else if (strlen($password_1) > 16) { // strlen() = "String-length"
    array_push($errors, "Das Passwort darf maximal 16 Zeichen lang sein");
  } elseif (preg_match('/\s{2,}/', $password_1)) { // Wenn das Passwort an irgendeiner Stelle dieses Muster behinhaltet \s{2,} = mindestens 2 aufeinander folgende Leerzeichen
    array_push($errors, "Keine aufeinander folgenden Leerzeichen.");
  }
  if ($password_1 != $password_2) {
	array_push($errors, "Die beiden passwörter stimmen nicht überein");
  }

  // Datenbank auf Doppelung prüfen
  $user_check_query = "SELECT Username, Email FROM user WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  // User Exists
  if ($user != null) { 
    if ($user['Username'] === $username) {
      array_push($errors, "Username existiert bereits");
    }

    if ($user['Email'] === $email) {
      array_push($errors, "Email existiert bereits");
    }
  }

  // Registrieren, wenn alles okay ist
  if (count($errors) == 0) {
  	$password = md5($password_1);//Verschlüsselung

  	$query = "INSERT INTO user (username, email, password) VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username; // Session Variable für username setzen
  	$_SESSION['success'] = "Erfolgreich eingeloggt";
  	header('location: index.php');
    exit(); // Exit nach header() um das skript an dieser stelle zu beenden
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, strtolower($_POST['username'])); // strtolower() macht alles zu Kleinbuchstaben
  $password = mysqli_real_escape_string($db, $_POST['password']); // mysqli_real_escape_string() "neutralisiert" einige Sonderzeichen um SQL-Injektion vorzubeugen

  if (empty($username)) {
  	array_push($errors, "Bitte Username angeben");
  }
  if (empty($password)) {
  	array_push($errors, "Bitte Password angeben");
  }

  if (count($errors) == 0) {

  	$password = md5($password); // Verschlüsseln

  	$query = "SELECT * FROM user WHERE Username = '$username' AND Password = '$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "Erfolgreich eingeloggt";
  	  header('location: index.php');
      exit(); // Exit nach header() um das skript an dieser stelle zu beenden
  	}else {
  		array_push($errors, "falsche Username / Password Kombination");
  	}
  }
}

//LOGOUT USER
if (isset($_GET['logout'])) {
  //Sitzungsdaten löschen
  unset($_SESSION['username']);
}


// Speichert User Input in die Datenbank
if (isset($_POST['save_input'])) {

  // PHP läuft serverseitig, $_SESSION['username'] ist schwer zu manipulieren
  if (!isset($_SESSION['username'])) {
    //Beendet die Ausführung des Codes sofort
    die('Error: Unauthorized access. Please log in.');
  }
  
  $username = $_SESSION['username'];
  $datum = mysqli_real_escape_string($db, $_POST['datum']); // mysqli_real_escape_string() "neutralisiert" einige Sonderzeichen um SQL-Injektion vorzubeugen
  $zeitraum = mysqli_real_escape_string($db, $_POST['zeitraum']);
  $inhalt = mysqli_real_escape_string($db, $_POST['inhalt']);
  $farbe = mysqli_real_escape_string($db, $_POST['farbe']);

  // Überprüfe, ob ein Eintrag mit den gleichen Werten für Username, Datum und Zeitraum existiert
  $query = "SELECT * FROM nutzereingaben WHERE username='$username' AND datum='$datum' AND zeitraum='$zeitraum'";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) > 0) {

    if ($inhalt === "") {
      // Wenn das Eingabefeld leer ist, lösche den Eintrag
      $delete_query = "DELETE FROM nutzereingaben WHERE username='$username' AND datum='$datum' AND zeitraum='$zeitraum'";
      if (mysqli_query($db, $delete_query)) {
          echo "Eintrag erfolgreich gelöscht.";
      } else {
          echo "Fehler beim Löschen des Eintrags: " . mysqli_error($db);
      }
    } else {
      // Wenn das Eingabefeld nicht leer ist, aktualisiere den Eintrag
      $update_query = "UPDATE nutzereingaben SET inhalt='$inhalt', farbe='$farbe' WHERE username='$username' AND datum='$datum' AND zeitraum='$zeitraum'";
      if (mysqli_query($db, $update_query)) {
          echo "Eintrag erfolgreich aktualisiert.";
      } else {
          echo "Fehler beim Aktualisieren des Eintrags: " . mysqli_error($db);
      }
    }
  } else {
    if($inhalt !== ""){
      // Neuen Eintrag anlegen
      $query = "INSERT INTO nutzereingaben (username, datum, zeitraum, inhalt, farbe) VALUES ('$username', '$datum', '$zeitraum', '$inhalt', '$farbe')";
      if (mysqli_query($db, $query)) {
          echo "Daten erfolgreich in die Datenbank eingefügt.";
      } else {
          echo "Fehler beim Einfügen der Daten: " . mysqli_error($db);
      }
    }
  }
}


// RESET PASSWORD
if (isset($_POST['reset_password'])) {
  $username = mysqli_real_escape_string($db, strtolower($_POST['username'])); // mysqli_real_escape_string() "neutralisiert" einige Sonderzeichen um SQL-Injektion vorzubeugen

  if (empty($username)) { // Kein Username angegeben
  	array_push($errors, "Bitte Username angeben");
  }

  if (count($errors) == 0) {

  	$query = "SELECT * FROM user WHERE Username = '$username'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
      $verifyCode = generateRandomString();

      $update_query = "UPDATE user SET verifizierungscode='$verifyCode' WHERE username='$username'";
      if (mysqli_query($db, $update_query)) {
           
          $receiver = "studiplaner@outlook.com"; // Hier müsste die Emailadresse aus der DB stehen
          $subject = "Studiplaner Passwort zuruecksetzen";
          $body = "Hier ist ihr angeforderter Verifizierungscode: $verifyCode.\nBitte erneuern Sie Ihr Passwort schnellstmoeglich.";
          $sender = "From:postmaster@sandbox4079895eb9574cd5952a199611d2ba48.mailgun.org"; // Bei Mailguns kostenloser Version nur mit Sandbox-Emailadresse

          // E-Mail senden
          if(mail($receiver, $subject, $body, $sender)){
              echo "Email mit Verifizierungscode erfolgreich an $receiver versandt";
          } else {
              echo "Senden der Email fehlgeschlagen";
          }

          $_SESSION['reset_username'] = $username; // Username in der Session speichern

      } else {
          echo "Fehler beim Eintragen des Verfizierungscodes: " . mysqli_error($db);
      }

      // Umleitung zur Seite zum Ändern des Passworts
  	  header('location: changePassword.php');
      exit(); // Exit nach header() um das skript an dieser stelle zu beenden
  	}else { 
  		array_push($errors, "Dieser Username existiert nicht");
  	}
  }
}



// CHANGE PASSWORD
if (isset($_POST['change_password'])) {
  $username = trim(mysqli_real_escape_string($db, strtolower($_SESSION['reset_username']))); // Reset_username aus Session (kleingeschrieben)
  $verifyCode = trim(mysqli_real_escape_string($db, $_POST['verifyCode'])); // mysqli_real_escape_string() "neutralisiert" einige Sonderzeichen um SQL-Injektion vorzubeugen
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  if (empty($verifyCode)) {
      array_push($errors, "Bitte Verifizierungscode angeben");
  }
  if (empty($password_1)) {
      array_push($errors, "Bitte Passwort angeben");
  } else if (strlen($password_1) < 5) { // strlen() = "String-length"
    array_push($errors, "Das Passwort muss mindestens 5 Zeichen lang sein");
  }
  if ($password_1 != $password_2) {
      array_push($errors, "Die Passwörter stimmen nicht überein");
  }

  if (count($errors) == 0) {
      $query = "SELECT * FROM user WHERE username='$username' AND verifizierungscode='$verifyCode'"; // Sucht nach angegebenem Verifizierungscode und Username
      $results = mysqli_query($db, $query);

      if (mysqli_num_rows($results) == 1) { // Wenn ein Eintrag mit dem eingetragenen verifizierungscode für diesen Nutzenden gefunden wurde
        $password = md5($password_1); // Verschlüsselung
        $update_query = "UPDATE user SET password='$password', verifizierungscode=NULL WHERE username='$username'"; // Speichert neues Passwort und setzt den verifycode wieder null
        if (mysqli_query($db, $update_query)) {
    
          unset($_SESSION['reset_username']); // Den Benutzernamen aus der Session entfernen
          header('location: login.php'); // header(location) für HTTP-Paket
          exit(); // Exit nach header() um das skript an dieser stelle zu beenden
        } else {
          array_push($errors, "Fehler beim Ändern des Passworts: " . mysqli_error($db));
        }
      } else {
        array_push($errors, "Falscher Verifizierungscode");
      }
  }
}

function generateRandomString() { // Generiert einen 6 Zeichen langen String
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters); // strlen($characters) = String-Length
  $randomString = '';
  for ($i = 0; $i < 6; $i++) { // Bis der $randomString 6 Zeichen lang ist
      $randomString .= $characters[random_int(0, $charactersLength - 1)]; // Fügt einen zufälligen character aus $characters zum String hinzu
  }
  return $randomString;
}
?>