<?php
    include('server.php');
    if (!isset($_SESSION['reset_username'])) {
        header('location: index.php'); // header(location) für HTTP-Paket, leitet auf index.php um
        exit(); // Exit nach header() um das skript an dieser stelle zu beenden
    }
?>

<!DOCTYPE html>
<html>
	
<head>
  	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Passwort ändern</title>
</head>

<body>
    <nav>
        <div class="studiplanerLogo">
            <h3>Studiplaner</h3>
        </div>

        <div class="nav-bar-container-right">
            <div class="toggle-container">
                <input type="checkbox" id="modeToggleChangePassword" class="toggle-button">
                <label for="modeToggleChangePassword" id="modeToggleChangePasswordLabel" class="toggle-label"></label>
            </div>

            <form method="post" action="index.php">
                <button class="topRightButton" type="submit">Menu</button>
            </form>
        </div>
    </nav>

    <main>
        <div class="logRegForm">
            <h2>Verifizierungscode angeben und Passwort ändern</h2><br>

            <form method="post" action="changePassword.php">
                <?php include('errors.php'); ?>
                <div class="input-group">
                    <label>Verifizierungscode</label><br>
                    <input type="text" name="verifyCode">
                </div><br>
                <div class="input-group">
                    <label>Password</label><br>
                    <input type="password" name="password_1">
                </div><br>
                <div class="input-group">
                    <label>Confirm password</label><br>
                    <input type="password" name="password_2">
                </div><br>
                <div class="input-group">
                    <button type="submit" class="btn" name="change_password">Bestätigen</button>
                </div><br>
                <p>Zurück zum Login? <a href="login.php">Login</a></p>
            </form>
        </div>
    </main>

	<script>
		// Funktion, um den gespeicherten Modus anzuwenden
		function applySavedMode() {
			const modeToggle = document.getElementById('modeToggleChangePassword');
			const modeToggleLabel = document.getElementById('modeToggleChangePasswordLabel');
			const savedMode = sessionStorage.getItem('mode');
			if (savedMode) {
				document.body.className = savedMode; // Gespeicherten Wert anwenden
				if (savedMode === 'dark-mode') {
					modeToggle.checked = true; // Toggle ist dark-mode
				} else {
					modeToggle.checked = false; // Toggle ist light-mode
				}
				modeToggleLabel.classList.add('no-transition'); // Toggle-Animation beim Laden der Seite unterdrücken
			}
		}

		// Eventlistener für den Moduswechsel
		function addModeToggleEventListener() {
			const modeToggle = document.getElementById('modeToggleChangePassword');
			const body = document.body;

			modeToggle.addEventListener('change', () => {
				if (modeToggle.checked) {
					sessionStorage.setItem('mode', 'dark-mode');
					body.classList.remove('light-mode');
					body.classList.add('dark-mode');
				} else {
					sessionStorage.setItem('mode', 'light-mode');
					body.classList.remove('dark-mode');
					body.classList.add('light-mode');
				}
			});
		}

		// Funktionen beim Laden der Seite ausführen
		window.onload = function() {
			applySavedMode();
			addModeToggleEventListener();

			setTimeout(() => { // Verzögert das Hinzufügen der Toggle-Animation um 100 Millisekunden
				document.getElementById('modeToggleChangePasswordLabel').classList.remove('no-transition');
			}, 100);
		};
	</script>
</body>
</html>