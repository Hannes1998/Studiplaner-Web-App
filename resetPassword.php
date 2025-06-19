<?php include('server.php') ?>

<!DOCTYPE html>
<html>


<head>
  	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Neues Passwort anfordern</title>
</head>

<body>	
    <nav>
        <div class="studiplanerLogo">
            <h3>Studiplaner</h3>
        </div>

        <div class="nav-bar-container-right">
            <div class="toggle-container">
                <input type="checkbox" id="modeToggleResetPassword" class="toggle-button">
                <label for="modeToggleResetPassword" id="modeToggleResetPasswordLabel" class="toggle-label"></label>
            </div>

            <form method="post" action="index.php">
                <button class="topRightButton" type="submit">Menu</button>
            </form>
        </div>
    </nav>

    <main>
        <div class="logRegForm">

            <h2>Neues Passwort anfordern</h2><br>

            <form method="post" action="resetPassword.php">
                <?php include('errors.php'); ?>
                <div class="input-group">
                    <label>Username</label><br>
                    <input type="text" name="username">
                </div><br>
                <div class="input-group">
                    <button type="submit" class="btn" name="reset_password">Anfordern</button>
                </div><br>
                <p>Zurück zum Login? <a href="login.php">Login</a></p>
            </form>
        </div>
    </main>

	<script>
        // Funktion, um den gespeicherten Modus anzuwenden
        function applySavedMode() {
            const modeToggle = document.getElementById('modeToggleResetPassword');
            const modeToggleLabel = document.getElementById('modeToggleResetPasswordLabel');
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
            const modeToggle = document.getElementById('modeToggleResetPassword');
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
                document.getElementById('modeToggleResetPasswordLabel').classList.remove('no-transition');
            }, 100);
        };
	</script>

</body>
</html>