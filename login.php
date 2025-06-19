<?php include('server.php') ?>
<!DOCTYPE html>
<html>
	
<head>
  	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Login</title>
</head>

<body>

	<nav>
		<div class="studiplanerLogo">
            <h3>Studiplaner</h3>
        </div>

		<div class="nav-bar-container-right">
			<div class="toggle-container">
				<input type="checkbox" id="modeToggleLogin" class="toggle-button">
				<label for="modeToggleLogin" id="modeToggleLoginLabel" class="toggle-label"></label>
			</div>

			<form method="post" action="index.php">
				<button class="topRightButton" type="submit">Menu</button>
			</form>
		</div>
	</nav>

	<main>
		<div class="logRegForm">
			<h2>Login</h2><br>

			<form method="post" action="login.php">
				<?php include('errors.php'); ?>
				<div class="input-group">
					<label>Username</label><br>
					<input type="text" name="username" value="<?php echo $username; ?>">
				</div><br>
				<div class="input-group">
					<label>Password</label><br>
					<input type="password" name="password">
				</div><br>
				<div class="input-group">
					<button type="submit" class="btn" name="login_user">Login</button>
				</div><br>
				<p>Passwort vergessen? <a href="resetPassword.php">Neues Passwort anfordern</a></p>
				<p>Noch nicht registriert? <a href="register.php">Registrieren</a></p>
			</form>
		</div>
	</main>

	<script>
		// Funktion, um den gespeicherten Modus anzuwenden
		function applySavedMode() {
			const modeToggle = document.getElementById('modeToggleLogin');
			const modeToggleLabel = document.getElementById('modeToggleLoginLabel');
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
			const modeToggle = document.getElementById('modeToggleLogin');
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
				document.getElementById('modeToggleLoginLabel').classList.remove('no-transition');
			}, 100);
		};
	</script>

</body>
</html>