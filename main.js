
const monthNames = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];

const timesOfDay = ["8:00 - 10:00", "10:00 - 12:00", "12:00 - 14:00", "14:00 - 16:00", "16:00 - 18:00"];

const thWeekdays = ["Mo", "Di", "Mi", "Do", "Fr", "Sa", "So"];

//Damit unsere Woche mit Montag beginnt (Standart ist Sonntag)
const dayOfWeek = [6, 0, 1, 2, 3, 4, 5];

let year = document.querySelector(".jahr");

//let heute = new Date("1998", "10", "15");
let correctDate = new Date();
let today = new Date();

let clickedDate;
let upcomingIsVisible = true;

let monthNumbOfFirstDay, monthNumbOfLastDay;
let firstDayOfWeek, lastDayOfWeek;

let monthName1 = document.getElementById('monat1')
let monthName2 = document.getElementById('monat2')

var isLoggedIn = false;

//Berechnet die Anzahl der Tage des Monats des angegebenen Tages
function calculateAmountOfDaysOfMonth(givenDate) {
    let calculateDate = new Date(givenDate);
    calculateDate.setMonth(calculateDate.getMonth() + 1);
    
    calculateDate.setDate(0); // .setDate(0) gibt den letzten Tag des Vormonats zurück
    return calculateDate.getDate(); //Letzter Tag des Monats = Anzahl der Tage im Monat
}

// Erstellt den Kalender der Woche des angeklickten Tages
function constructWeekTable(clickedDate) {

    displayCorrectMonthAndYear(clickedDate);
    
    let table = document.querySelector('#tabelle');
    let todaysWeekday;

    // KOPFZEILE TH
    let tableHeader = document.createElement("tr");
    table.appendChild(tableHeader);

    //Die Wochenansicht fängt immer mit Montag an, aber der angeklickte Tag kann ein anderer sein.
    let offset = clickedDate.getDay() - 1;
    
    // Wenn der angeklickte Tag ein Sonntag ist, setze den Offset auf 6 (Amerikanische Kalender fangen mit Sonntag an)
    if (offset === -1) {
        offset = 6; 
    }

    for (let i = 0; i < 8; i++) { //Erstellt die erste Zeile
        let newColumn = document.createElement("th");
        if (i === 0) { // Erste Spalte für Zeiträume
            newColumn.textContent = "Zeiten";
        }  else {
            let dayWithDate = new Date(clickedDate);
            dayWithDate.setDate(clickedDate.getDate() + i - 1 - offset);
            let dayText = thWeekdays[i - 1] + ' ' + dayWithDate.getDate() + '.';
            newColumn.textContent = dayText;

            //Farblich markieren, wenn die Woche den heutigen Tag enthält
            if (dayWithDate.toLocaleDateString('de-DE')  === today.toLocaleDateString('de-DE')) {
                newColumn.classList.add("heute");
                todaysWeekday = i;
            }
        }
        tableHeader.appendChild(newColumn);
    }


    //TABELLENINHALT TD
    for (let i = 0; i < 5; i++) {
        let newRow = document.createElement("tr");
        table.appendChild(newRow);

        for (let j = 0; j < 8; j++) {

            let newColumn = document.createElement("td");
            newRow.appendChild(newColumn);

            if(j === 0) { // Erste Spalte für die Zeiträume
                let times = document.createElement("span");
                times.textContent = timesOfDay[i];
                newColumn.appendChild(times);
            } else{

                //Markiert den heutigen Tag
                if(todaysWeekday === j){
                    newColumn.classList.add("heute");
                }

                let inputContainer = document.createElement("div");
                inputContainer.classList.add("input-container");

                let textField = document.createElement("input");
                textField.type = "text";
                textField.setAttribute("maxlength", "50"); // Begrenzt die Zeichenanzahl auf 50
                inputContainer.appendChild(textField);

                // Anzeige für die aktuelle Anzahl der eingegebenen Zeichen
                let charCount = document.createElement("span");
                charCount.textContent = "0"; // Startwert für die Zeichenanzahl
                inputContainer.appendChild(charCount);
                charCount.classList.add("char-count");
                charCount.style.color = 'green';
                

                let colorButtonContainer = document.createElement("div");
                colorButtonContainer.classList.add("color-button-container");

                // Setze die Datenattribute Datum und Zeitraum für jedes Textfeld
                let dayWithDate = new Date(clickedDate);
                dayWithDate.setDate(clickedDate.getDate() + j - 1 - offset);
                textField.setAttribute("data-datum", dayWithDate.toLocaleDateString('de-DE')); //toLocaleDateString(DE) für DD.MM.YYYY Format
                textField.setAttribute("data-zeitraum", timesOfDay[i]);
                colorButtonContainer.setAttribute("data-datum", dayWithDate.toLocaleDateString('de-DE'));
                colorButtonContainer.setAttribute("data-zeitraum", timesOfDay[i]);

                let redButton = document.createElement("button");
                redButton.textContent = "Rot";
                redButton.classList.add("color-button");
                redButton.setAttribute("data-color", "rot");

                let greyButton = document.createElement("button");
                greyButton.textContent = "Grau";
                greyButton.classList.add("color-button");
                greyButton.style.backgroundColor = "grey"; // Setze den grauen Button als standardmäßig ausgewählt
                textField.setAttribute("data-selected-color", "grau"); 
                greyButton.setAttribute("data-color", "grau");          

                let blueButton = document.createElement("button");
                blueButton.textContent = "Blau";
                blueButton.classList.add("color-button");
                blueButton.setAttribute("data-color", "blau");

                // Farbauswahllogik
                if(textField.value.trim() === ""){ // value.trim() === "" bedeutet leeres Textfeld oder nur Whitespace. Entfernt die Buttons bei leerem Textfeld
                    redButton.style.display = "none";
                    greyButton.style.display = "none";
                    blueButton.style.display = "none";
                }

                redButton.addEventListener("click", function() {
                    redButton.style.backgroundColor = "lightcoral";
                    greyButton.style.backgroundColor = "";
                    blueButton.style.backgroundColor = "";
                    textField.setAttribute("data-selected-color", "rot");
                    console.log('Roter Button angeklickt! '); 
                    handleColorButtonClick(redButton);
                });

                blueButton.addEventListener("click", function () {
                    blueButton.style.backgroundColor = "lightblue";
                    greyButton.style.backgroundColor = "";
                    redButton.style.backgroundColor = "";
                    textField.setAttribute("data-selected-color", "blau");
                    console.log('Blauer Button angeklickt! ');
                    handleColorButtonClick(blueButton);
                });

                greyButton.addEventListener("click", function () {
                    blueButton.style.backgroundColor = "";
                    greyButton.style.backgroundColor = "grey";
                    redButton.style.backgroundColor = "";
                    textField.setAttribute("data-selected-color", "grau");
                    console.log('Grauer Button angeklickt! ');
                    handleColorButtonClick(greyButton);
                });

                textField.addEventListener("input", function () {

                    // Aktualisiere die Anzeige für die Zeichenanzahl
                    charCount.textContent = textField.value.length;
                    updateCharCountColor(textField);

                    if (textField.value.trim() !== "") { // Zeigt die Buttons an, wenn Textfeld Inhalt hat
                        redButton.style.display = "block";
                        greyButton.style.display = "block";
                        blueButton.style.display = "block";
                    } else { // Entfernt die Buttons bei leerem Textfeld
                        redButton.style.display = "none";
                        greyButton.style.display = "none";
                        blueButton.style.display = "none";
                    }
                });

                colorButtonContainer.appendChild(redButton);
                colorButtonContainer.appendChild(greyButton);
                colorButtonContainer.appendChild(blueButton);

                newColumn.appendChild(inputContainer);
                newColumn.appendChild(colorButtonContainer);

            }
        }
    }
    addBlurEventListeners(); // blur Event tritt auf, wenn aus dem Textfeld rausgeklickt wird (Userinput ist vollständig)

    checkIfLoggedIn(displayUserInputFromDB); // Wenn ein Nutzer angemeldet ist, werden Einträge aus DB geladen
}

// Erstellt den Kalender des Monats
function constructMonthTable() {
    let indexDate = new Date(correctDate);

    monthName1.textContent = monthNames[correctDate.getMonth()];
    monthName2.style.display = "none"
    year.textContent = correctDate.getFullYear();



    let table = document.querySelector('#tabelle');
    table.style.display = "inline-block";
    let indexDay = 1;


    let tableHeader = document.createElement("tr");
    table.appendChild(tableHeader);

    for (let i = 0; i < 7; i++) { // Erstellt die erste Zeile
        let newColumn = document.createElement("th");
        newColumn.textContent = thWeekdays[i];
        tableHeader.appendChild(newColumn);
    }


    //TABELLENINHALT TD
    for (let i = 0; ; i++) {
        let newRow = document.createElement("tr");
        table.appendChild(newRow);

        for (let j = 0; j < 7; j++) {

            
            let newColumn = document.createElement("td");
            newRow.appendChild(newColumn);
            indexDate.setDate(indexDay);

            if (indexDay <= calculateAmountOfDaysOfMonth(correctDate)) {
                if (j === dayOfWeek[indexDate.getDay()]) {

                    let container = document.createElement("div");
                    container.classList.add("tagesinhalt");

                    // Farbige Punkte für anstehende Termine
                    let circleElements = document.createElement("div");
                    circleElements.classList.add("punktElementeContainer");
                    circleElements.setAttribute("data-datum", indexDate.toLocaleDateString('de-DE')); //toLocaleDateString(DE) für DD.MM.YYYY Format

                    let leftCircleElement = document.createElement("div");
                    leftCircleElement.classList.add("punkt-element");
                    leftCircleElement.style.backgroundColor = "lightcoral";
                    leftCircleElement.setAttribute("data-color", "rot");

                    let centerCircleElement = document.createElement("div");
                    centerCircleElement.classList.add("punkt-element");
                    centerCircleElement.style.backgroundColor = "grey";
                    centerCircleElement.setAttribute("data-color", "grau");
                
                    let rightCircleElement = document.createElement("div");
                    rightCircleElement.classList.add("punkt-element");
                    rightCircleElement.style.backgroundColor = "lightblue";
                    rightCircleElement.setAttribute("data-color", "blau");


                    circleElements.appendChild(leftCircleElement);
                    circleElements.appendChild(centerCircleElement);
                    circleElements.appendChild(rightCircleElement);

                    leftCircleElement.style.display = "none";
                    centerCircleElement.style.display = "none";
                    rightCircleElement.style.display = "none";

                    let dayText = document.createElement("span");
                    dayText.textContent = indexDay;

                    container.appendChild(circleElements);
                    
                    container.appendChild(dayText);
                    newColumn.appendChild(container);

                    indexDay++;
                }
            }
            
            if (indexDay - 1 === today.getDate() && today.getMonth() ===  correctDate.getMonth() && today.getFullYear() === correctDate.getFullYear()) { // Markiert den heutigen Tag
                newColumn.classList.add("heute");
            }
        }

        if (indexDay > calculateAmountOfDaysOfMonth(correctDate)) {
            break; 
        }
    }

    document.querySelectorAll('#tabelle .tagesinhalt').forEach(calendarDay => {
        calendarDay.addEventListener('click', function() {

            clickedDate = new Date(correctDate.getFullYear(), correctDate.getMonth(), calendarDay.textContent);
            //Der Funktion das volle Datum mitgeben, das angeklickt wurde
            switchToWeeklyView(clickedDate);
        });
    });

    checkIfLoggedIn(displayCorrectCircleElements);// Wenn ein Nutzer angemeldet ist, lädt die CircleElements an die korrekten Stellen
}

function addBlurEventListeners() {
    
    document.querySelectorAll('input[data-datum]').forEach(textField => { // 'input[data-datum]' = alle Wochenansicht-Textfelder
        
        textField.addEventListener('blur', saveUserInputToDB) // blur Event tritt auf, wenn aus dem Textfeld rausgeklickt wird (Userinput ist vollständig)
    });    
}

function removeBlurEventListeners() {
    
    document.querySelectorAll('input[data-datum]').forEach(textField => { // 'input[data-datum]' = alle Wochenansicht-Textfelder
        
        textField.removeEventListener('blur', saveUserInputToDB) // blur Event tritt auf, wenn aus dem Textfeld rausgeklickt wird (Userinput ist vollständig)
    });    
}

//Schickt bei einem Klick auf die ColorButtons die Informationen an die DB
function handleColorButtonClick(button) {
    const date = button.parentElement.getAttribute("data-datum");
    const timeslot = button.parentElement.getAttribute("data-zeitraum");
    const color = button.getAttribute("data-color");
    const content = document.querySelector(`input[data-datum="${date}"][data-zeitraum="${timeslot}"]`).value.trim(); // .trim() entfernt vorangehenden oder anhängenden Whitespace

    const xhr = new XMLHttpRequest(); // Erstellen eines neuen XMLHttpRequest-Objekts, um eine HTTP-Anfrage zu senden
    xhr.open('POST', 'server.php', true); // Initialisieren mit URL 'server.php' und asynchron = true
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.send(`save_input=true&datum=${date}&zeitraum=${timeslot}&farbe=${color}&inhalt=${content}`);
}

// Markiert farblich, wie viel Platz noch im Textfeld ist
function updateCharCountColor(textField) {
    const maxLength = parseInt(textField.getAttribute('maxlength')); // Übernimmt die Zahl, die bei maxlength angegeben wurde
    const currentLength = textField.value.length;
    const charCountElement = textField.parentNode.querySelector('.char-count');

    if (charCountElement) {
        if (currentLength >= maxLength - 5) { // Wenig Platz
            charCountElement.style.color = 'red';
        } else if (currentLength >= maxLength - 10) { // Mittelviel Platz
            charCountElement.style.color = 'yellow';
        } else { // Viel Platz
            charCountElement.style.color = 'green';
        }
    }
}

// Speichert den Inhalt des Textfeldes in die DB
function saveUserInputToDB(){
    // Informationen des Textfelds
    const textField = event.target;
    const content = textField.value.trim();
    const date = textField.getAttribute('data-datum');
    const timeslot = textField.getAttribute('data-zeitraum');
    const color = textField.getAttribute('data-selected-color');

    if (isLoggedIn) { // Wenn ein Nutzer angemeldet ist

        
        const xhr = new XMLHttpRequest(); // Erstellen eines neuen XMLHttpRequest-Objekts, um eine HTTP-Anfrage zu senden
        xhr.open('POST', 'server.php', true); // Initialisieren mit URL 'server.php' und asynchron = true
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        //encodeURIComponent für korrekte Übermittlung von Sonderzeichen
        xhr.send(`save_input=true&inhalt=${encodeURIComponent(content)}&datum=${date}&zeitraum=${timeslot}&farbe=${color}`); // Daten an den Server senden, um sie in die Datenbank einzufügen

    } else {
        console.log('Nicht angemeldet.');
    }
}

// Funktion für Datenbankabfrage, um gespeicherte Einträge des Benutzers abzurufen
function displayUserInputFromDB(){

    const xhr = new XMLHttpRequest(); // Erstellen eines neuen XMLHttpRequest-Objekts, um eine HTTP-Anfrage zu senden
    xhr.open('POST', 'getEntries.php', true); // Initialisieren mit URL 'getEntries.php' und asynchron = true
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function () { // Definieren einer Callback-Funktion, die ausgeführt wird, wenn die Anfrage abgeschlossen ist
        if (xhr.readyState === 4 && xhr.status === 200) { // readyState = 4 bedeutet "DONE", status = 200 beudeutet "OK"
            const entries = JSON.parse(xhr.responseText); // Konvertieren der JSON-Antwort in ein JavaScript-Objekt

            entries.forEach(entry => {
                // Jedes Textfeld auf Übereinstimmung überprüfen
                document.querySelectorAll('input[data-datum][data-zeitraum]').forEach(textField => {
                    const textFieldDate = textField.getAttribute('data-datum');
                    const textFieldTimeslot = textField.getAttribute('data-zeitraum');
                    if (entry.Datum === textFieldDate && entry.Zeitraum === textFieldTimeslot) {
                        // Übereinstimmung gefunden: Setze den Inhalt des Textfelds
                        textField.value = entry.Inhalt;

                        // Das entsprechende Zeichenanzahl-Element finden und aktualisieren
                        const textLength = textField.value.length;
                        const charCountElement = textField.parentNode.querySelector('.char-count');

                        if (charCountElement) {
                            charCountElement.textContent = textLength;
                            updateCharCountColor(textField);
                        }
                        
                        //Die Farbigen Knöpfe richtig anzeigen
                        const colorButtonContainer = document.querySelector(`.color-button-container[data-datum="${textFieldDate}"][data-zeitraum="${textFieldTimeslot}"]`);//Entsprechenden ButtonContainer finden
                        if(colorButtonContainer){
                            colorButtonContainer.querySelectorAll('.color-button').forEach(button => {
                                button.style.display = "block";
                                if(entry.Farbe === button.getAttribute("data-color")){
                                    textField.setAttribute("data-selected-color", entry.Farbe);

                                    if(entry.Farbe === "grau"){
                                        button.style.backgroundColor = "grey";
                                    } else if(entry.Farbe === "blau"){
                                        button.style.backgroundColor = "lightblue";
                                    }else if(entry.Farbe === "rot"){
                                        button.style.backgroundColor = "lightcoral";
                                    }
                                }else{
                                    button.style.backgroundColor = "";
                                }
                            });
                        }
                    }
                });
            });
        }
    };
    xhr.send(`display_input=true`);
}

// Funktion für Datenbankabfrage, um Punktelemente in Monatsanzeige richtig abzubilden
function displayCorrectCircleElements(){

    const xhr = new XMLHttpRequest(); // Erstellen eines neuen XMLHttpRequest-Objekts, um eine HTTP-Anfrage zu senden
    xhr.open('POST', 'getEntries.php', true); // Initialisieren mit URL 'getEntries.php' und asynchron = true
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function () { // Definieren einer Callback-Funktion, die ausgeführt wird, wenn die Anfrage abgeschlossen ist
        if (xhr.readyState === 4 && xhr.status === 200) { // readyState = 4 bedeutet "DONE", status = 200 beudeutet "OK"
            const entries = JSON.parse(xhr.responseText); // Konvertieren der JSON-Antwort in ein JavaScript-Objekt

            const todayWithoutTime = new Date(); //Aktuelles Datum inkl. Uhrzeit
            todayWithoutTime.setHours(0, 0, 0, 0); // Setzt die Stunden, Minuten, Sekunden und Millisekunden auf null

            let upcomingEntries = [];

            entries.forEach(entry => {

                //Teilen und neuzusammensetzen, damit new Date mit DD.MM.YYYY arbeiten kann.
                const parts = entry.Datum.split('.');
                const givenDate = new Date(`${parts[1]}.${parts[0]}.${parts[2]}`);

                const differenceInMilliseconds = givenDate - todayWithoutTime;
                const differenceInDays = differenceInMilliseconds / (1000 * 60 * 60 * 24);

                //Überprüft, ob entry.Datum in den nächsten 14 Tagen liegt
                if (differenceInDays <= 14 && givenDate >= todayWithoutTime) {
                    upcomingEntries.push({
                        date: entry.Datum,
                        content: entry.Inhalt,
                        timeslot: entry.Zeitraum,
                        color: entry.Farbe
                    });

                  } else {
                    //console.log(entry.Datum, " liegt NICHT innerhalb der nächsten 14 Tage");
                  }

                // Die richtigen CircleElemente anzeigen
                document.querySelectorAll('.punktElementeContainer[data-datum]').forEach(container => {
                    const containerDate = container.getAttribute('data-datum');
                    if (entry.Datum === containerDate) {             
                        container.querySelectorAll('.punkt-element').forEach(circle => {
                            //Punkte anzeigen, wenn ein Eintrag mit der Farbe existiert
                            if(entry.Farbe === circle.getAttribute("data-color")){
                                if(entry.Farbe === "grau"){
                                    circle.style.display = "block";
                                } else if(entry.Farbe === "blau"){
                                    circle.style.display = "block";
                                }else if(entry.Farbe === "rot"){
                                    circle.style.display = "block";
                                }
                            }
                        });
                }
                });
            });

            //Sortiert die anstehenden Termine nach Datum
            upcomingEntries.sort((a, b) => a.date - b.date); // aufsteigend sortiert
            upcomingEntries.forEach( entry => {
                console.log(entry.date, " liegt innerhalb Der nächsten 14 Tage");
            });

            const placeHolderElements = document.querySelectorAll('.platzhalter');

            //Bildet die anstehenden Termine in der Upcoming anzeige ab
            for (let i = 0; i < placeHolderElements.length; i++) {

                const placeHolderElement = placeHolderElements[i];
                const foundEntry = upcomingEntries[i];

                //Wenn nicht genug upcomingEntries existieren, blende die Platzhalter aus
                if( i >= upcomingEntries.length){
                    placeHolderElement.style.display = "none";
                } else {
                    // Setzt Datum und Inhalt des Eintrags in das platzhalter-Element ein
                    placeHolderElement.textContent = (foundEntry.date + ": " + foundEntry.content);
                    placeHolderElement.style.display = "block";
                }
            }
        }
    };
    xhr.send(`display_input=true`);
}

// Wechselt auf die Wochentabelle
function switchToWeeklyView(angeklicktesDatum) {
    console.log("Wechsel auf Wochenansicht");

    //In der Wochenansicht die Upcominganzeige ausblenden
    switchUpcomingVisibility();

    //Wechselt die Funktion des Buttons
    document.getElementById('backButton').removeEventListener('click', backwardsMonthWithButton);
    document.getElementById('forwardButton').removeEventListener('click', forwardMonthWithButton);
    document.getElementById('backButton').addEventListener('click', backwardsWeekWithButton);
    document.getElementById('forwardButton').addEventListener('click', forwardWeekWithButton);
    document.getElementById('forwardButton').textContent = "Nächste Woche";
    document.getElementById('backButton').textContent = "Vorherige Woche";
    document.getElementById('monat1').addEventListener('click', handleMonat1Click);
    document.getElementById('monat2').addEventListener('click', handleMonat2Click);

    document.getElementById('monat1').classList.add('monatName1');
    document.getElementById('monat2').classList.add('monatName2');

    clearTable();
    constructWeekTable(angeklicktesDatum);
}

// Wechselt auf die Monatstabelle
function switchToMonthlyView() {
    console.log("Wechsel auf Monatsansicht");

    //In der Monatsansicht die Upcominganzeige einblenden
    switchUpcomingVisibility();

    removeBlurEventListeners();

    //Wechselt die Funktion des Buttons
    document.getElementById('backButton').removeEventListener('click', backwardsWeekWithButton);
    document.getElementById('forwardButton').removeEventListener('click', forwardWeekWithButton);
    document.getElementById('backButton').addEventListener('click', backwardsMonthWithButton);
    document.getElementById('forwardButton').addEventListener('click', forwardMonthWithButton);
    document.getElementById('forwardButton').textContent = "Nächster Monat";
    document.getElementById('backButton').textContent = "Vorheriger Monat";
    document.getElementById('monat1').removeEventListener('click', handleMonat1Click);
    document.getElementById('monat2').removeEventListener('click', handleMonat2Click);

    document.getElementById('monat1').classList.remove('monatName1');
    document.getElementById('monat2').classList.remove('monatName2');

    clearTable();
    constructMonthTable();
}

function setupButtons(){
    document.getElementById('backButton').addEventListener('click', backwardsMonthWithButton);
    document.getElementById('forwardButton').addEventListener('click', forwardMonthWithButton);
}

function displayCorrectMonthAndYear(angeklicktesDatum) {

    console.log('Angeklicktes Datum:', angeklicktesDatum.toLocaleDateString('de-DE'));

    // Ermittle den Starttag der Woche (Montag)
    firstDayOfWeek = new Date(angeklicktesDatum);
    firstDayOfWeek.setDate(angeklicktesDatum.getDate() - (angeklicktesDatum.getDay() + 6) % 7);

    console.log('Starttag der Woche:', firstDayOfWeek.toLocaleDateString('de-DE'));

    // Überprüfe, ob der Monat des ersten Tags der Woche verschieden ist
    monthNumbOfFirstDay = firstDayOfWeek.getMonth();
    lastDayOfWeek = new Date(firstDayOfWeek);
    lastDayOfWeek.setDate(firstDayOfWeek.getDate() + 6);
    monthNumbOfLastDay = lastDayOfWeek.getMonth();

    console.log('Endtag der Woche:', lastDayOfWeek.toLocaleDateString('de-DE'));

    if (monthNumbOfFirstDay !== monthNumbOfLastDay) { // Diese Woche gehört zu verschiedenen Monaten
        console.log('Diese Woche gehört zu verschiedenen Monaten.');
        monthName2.style.display = "block";

        monthName1.textContent = monthNames[monthNumbOfFirstDay];
        monthName2.textContent = monthNames[monthNumbOfLastDay];

        if(monthNumbOfFirstDay == 11) { // Diese Woche gehört zu unterschiedlichen Jahren
            //Korrektes Jahr anzeigen
            year.textContent = firstDayOfWeek.getFullYear() + " / " + lastDayOfWeek.getFullYear();
        }
    } else { // Diese Woche gehört zu einem Monat
        monthName2.style.display = "none";
        monthName1.textContent = monthNames[angeklicktesDatum.getMonth()];
        correctDate.setMonth(angeklicktesDatum.getMonth());
        correctDate.setFullYear(angeklicktesDatum.getFullYear());

        //Korrektes Jahr anzeigen
        year.textContent = correctDate.getFullYear();
    }
}

function switchUpcomingVisibility(){
    if(upcomingIsVisible){
        document.getElementById('upcomingAnzeige').style.display = "none";
        upcomingIsVisible = false;
    } else {
        document.getElementById('upcomingAnzeige').style.display = "block";
        upcomingIsVisible = true;
    }
}

// Wenn in der Wochenanzeige auf den Monatsnamen geklickt wurde
function handleMonat1Click() {
    correctDate = new Date(firstDayOfWeek);
    switchToMonthlyView();
}

// Wenn in der Wochenanzeige auf den Monatsnamen geklickt wurde
function handleMonat2Click() {
    correctDate = new Date(lastDayOfWeek);
    switchToMonthlyView();
}

// Einen Monat zurückblättern
function backwardsMonthWithButton() {
    if (correctDate.getMonth() > 0) {
        correctDate.setMonth(correctDate.getMonth() - 1);
    }
    else {
        correctDate.setMonth(11);
        correctDate.setFullYear(correctDate.getFullYear() - 1);
    }

    clearTable();
    constructMonthTable();
    console.log('Vorheriger Monat geklickt');
}

// Einen Monat vorblättern
function forwardMonthWithButton() {
    if (correctDate.getMonth() < 11) {
        correctDate.setMonth(correctDate.getMonth() + 1);
    }
    else {
        correctDate.setMonth(0);
        correctDate.setFullYear(correctDate.getFullYear() + 1);
    }

    console.log('Nächster Monat geklickt');
    clearTable();
    constructMonthTable();
}

// Eine Woche zurückblättern
function backwardsWeekWithButton() {
    clickedDate.setDate(clickedDate.getDate() -7);

    clearTable();
    constructWeekTable(clickedDate);
    console.log('Vorherige Woche geklickt');
}

// Eine Woche vorblättern
function forwardWeekWithButton() {
    clickedDate.setDate(clickedDate.getDate() +7);

    clearTable();
    constructWeekTable(clickedDate);
    console.log('Nächste Woche geklickt');
}

// Löscht den Inhalt der Tabelle
function clearTable() {
    let table = document.getElementById('tabelle');
    table.innerHTML = '';
}

function checkIfLoggedIn(callback) {

    
    const xhr = new XMLHttpRequest(); // Erstellen eines neuen XMLHttpRequest-Objekts, um eine HTTP-Anfrage zu senden
    xhr.open('GET', 'checkUsername.php', true); // Initialisieren mit URL 'checkUsername.php' und asynchron = true
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.loggedIn) { // Prüfen, ob ein Username vorhanden ist
                console.log('Benutzer ist angemeldet');
                isLoggedIn = true;
                displayLoginNotification(true);
                callback(); // Die übergebene Funktion ausführen
            } else {
                console.log('Kein Benutzer angemeldet.');
                isLoggedIn = false;
                displayLoginNotification(false);
            }
        }
    };
    xhr.send();
}

function displayLoginNotification(status){
    var element = document.querySelector('.tooltip');
    if (status) {
        element.style.display = 'none';
    } else {
        element.style.display = 'block';
    }
}

constructMonthTable();
setupButtons();