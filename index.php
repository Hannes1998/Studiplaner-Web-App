<?php include('server.php')?>

<!DOCTYPE html>
<html lang = "de">

<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="light-mode">
    <nav>
        <div class="studiplanerLogo">
            <h3>Studiplaner</h3>
        </div>

        <?php  if (isset($_SESSION['username'])) : ?>
            <div id="signedInName"> Angemeldet als: <?php echo $_SESSION['username']; ?></div>
        <?php endif ?>
        
        <div class="nav-bar-container-right">
            <div class="toggle-container">
                <input type="checkbox" id="modeToggleIndex" class="toggle-button">
                <label for="modeToggleIndex" id="modeToggleIndexLabel" class="toggle-label"></label>
            </div>

            <?php if (isset($_SESSION['username'])) : ?>
                <form method="get" action="index.php">
                    <button class="topRightButton" type="submit" name="logout">Logout</button>
                </form>
            <?php else : ?>
                <form method="post" action="login.php">
                    <button class="topRightButton" type="submit">Login</button>
                </form>
            <?php endif ?>
        </div>

    </nav>

    <header>
        <div class = "headline2" >

            <button id="backButton">Vorheriger Monat</button>
            <div id = "monat1"></div>
            <div id = "monat2"></div>
            <button id="forwardButton">Nächster Monat</button> 
            <div class="jahr"></div>

        </div>
    </header>

    <main>
        <section>
            <div class="table-container">
                <table id = "tabelle" class = "tabelle">
                </table>
            </div>

            <script src="main.js"></script>

        </section>

        <aside id="upcomingAnzeige">
            <h1>Upcoming</h1>
            <ul>
                <li class="platzhalter">06.06.2024: Laborabschluss A</li>
                <li class="platzhalter">01.07.2024: Semesterbeitrag überweisen</li>
                <li class="platzhalter">18.07.2024: Mathe Klausur</li>
                <li class="platzhalter">23.08.2024: Flug nach Hawaii</li>
            </ul>

        </aside>
    </main>
    <footer>
        <div class="tooltip">
            <span class="tooltiptext">
                Willkommen beim Studiplaner!<br>
                Um den vollen Funktionsumfang<br>
                nutzen zu können, logge dich bitte ein.
            </span>
            <h6>i</h6>
        </div>
    </footer>

	<script>
		// Funktion, um den gespeicherten Modus anzuwenden
		function applySavedMode() {
			const modeToggle = document.getElementById('modeToggleIndex');
			const modeToggleLabel = document.getElementById('modeToggleIndexLabel');
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
			const modeToggle = document.getElementById('modeToggleIndex');
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
				document.getElementById('modeToggleIndexLabel').classList.remove('no-transition');
			}, 100);
		};
	</script>
</body>
</html>

  