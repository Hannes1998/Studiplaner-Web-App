<?php include('server.php') ?>
<!DOCTYPE html>
<html>

<head>
  <title>Registrierung</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<nav>
		<div class="studiplanerLogo">
            <h3>Studiplaner</h3>
        </div>

		<div class="nav-bar-container-right">
			<div class="toggle-container">
				<input type="checkbox" id="modeToggleRegister" class="toggle-button">
				<label for="modeToggleRegister" id="modeToggleRegisterLabel" class="toggle-label"></label>
			</div>

			<form method="post" action="index.php">
				<button class="topRightButton" type="submit">Menu</button>
			</form>
		</div>
	</nav>
	
	<main>
		<div class="logRegForm">

			<h2>Registrieren</h2>

			<form method="post" action="register.php">
				<?php include('errors.php'); ?>
				<div class="input-group">
					<label>Username</label><br>
					<input type="text" name="username" value="<?php echo $username; ?>">
				</div><br>
				<div class="input-group">
					<label>Email</label><br>
					<input type="email" name="email" value="<?php echo $email; ?>">
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
					<button type="submit" class="btn" name="reg_user">Registrieren</button>
				</div>
				<p>Bereits registriert? <a href="login.php">Login</a></p>
			</form>
		</div>
	</main>

	<script>
		// Funktion, um den gespeicherten Modus anzuwenden
		function applySavedMode() {
			const modeToggle = document.getElementById('modeToggleRegister');
			const modeToggleLabel = document.getElementById('modeToggleRegisterLabel');
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
			const modeToggle = document.getElementById('modeToggleRegister');
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
				document.getElementById('modeToggleRegisterLabel').classList.remove('no-transition');
			}, 100);
		};
	</script>
</body>
</html>