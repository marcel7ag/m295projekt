<?php
// erfassung.php
session_start();
include 'header.php';

// Überprüfen, ob Benutzer angemeldet ist
if (!isset($_SESSION["name"])) {
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/auftragerfassen.css"> 
    <title>Auftragsverwaltung</title>
</head>
<body>
    <div class="container">
        <h1>Auftragsverwaltung</h1>
        <!-- Rest des HTML-Codes... -->
        <div id="auftragErfassen">
            <h2>Auftrag erfassen</h2>
            
            <table>
                <tr>
                    <td>
                        <!-- TO-DO: dd-mm--yyyy Format einbauen -->
                        <label for="auftragsDatum">Auftragsdatum:</label>
                    </td>
                    <td>
                        <input type="date" id="auftragsDatum" name="auftragsDatum" placeholder="mm-dd-yyyy" value="2023-12-31">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="zeit">Zeit:</label>
                    </td>
                    <td>
                        <input type="text" id="zeit" name="zeit" placeholder="00:00">
                    </td>
                </tr>
                
                <!-- Weitere Reihen mit je zwei Spalten -->
                <tr>
                    <td>
                        <label for="kunde">Kunde:</label>
                    </td>
                    <td>
                        <select id="kAnrede">
                            <option value="">Anrede</option>
                            <option value="m">Herr</option>  
                            <option value="f">Frau</option>  
                        </select>
                        <input type="text" id="kVorname" name="kVorname" placeholder="Vorname">
                        <input type="text" id="kNachname" name="kNachname" placeholder="Nachname">
                        <input type="text" id="kAdresse" name="kAdresse" placeholder="Adresse">
                        <input type="text" id="kOrt" name="kOrt" placeholder="Ort" style="width: 60%;"><input type="text" id="kPLZ" name="kPLZ" placeholder="PLZ" style="width: 39%; margin-left: 7px;">
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <label for="kTelefon">Telefon:</label>
                    </td>
                    <td>
                        <!-- TO-DO: richtig formattieren bitte-->
                        <input type="tel" id="kTelefon" name="kTelefon">
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <label for="adresseObj">Adresse Objekt:</label>
                    </td>
                    <td>
                        <input type="text" id="objAdresse" name="objAdresse" placeholder="Adresse">
                        <input type="text" id="objOrt" name="objOrt" placeholder="Ort" style="width: 60%;"><input type="text" id="objPLZ" name="objPLZ" placeholder="PLZ" style="width: 39%; margin-left: 7px;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="rechAdresse">Rechnungsadresse:</label>
                    </td>
                    <td>
                        <select id="rechAnrede">
                            <option value="">Anrede</option>
                            <option value="rechM">Herr</option>  
                            <option value="rechF">Frau</option>  
                        </select>
                        <input type="text" id="rechVorname" name="rechVorname" placeholder="Vorname">
                        <input type="text" id="rechNachname" name="rechNachname" placeholder="Nachname">
                        <input type="text" id="rechAdresse" name="rechAdresse" placeholder="Adresse">
                        <input type="text" id="rechOrt" name="rechOrt" placeholder="Ort" style="width: 60%;"><input type="text" id="rechPLZ" name="rechPLZ" placeholder="PLZ" style="width: 39%; margin-left: 7px;">
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="arbeiten">Auszuführende Arbeiten:</label>
                    </td>
                    <td>
                        <input type="checkbox" id="reparatur" name="reparatur">
                        <label for="reparatur">Reparatur</label>
                        <input type="checkbox" id="sanitaer" name="sanitaer">
                        <label for="sanitaer">Sanitär</label><br>

                        <input type="checkbox" id="heizung" name="heizung">
                        <label for="heizung">Heizung</label>
                        <input type="checkbox" id="garantie" name="garantie">
                        <label for="garantie">Garantie</label>

                        <textarea id="bemerkungen" name="bemerkungen" placeholder="Bemerkungen" style="height:80px;margin-top: 15px;"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="terminwunsch">Terminwunsch:</label>
                    </td>
                    <td>
                        <textarea id="terminwunsch" name="terminwunsch" placeholder="Terminwunsch" style="height:80px;margin-top: 15px;"></textarea>
                    </td>
                </tr>
            </table>
        </div>


        <script src="script.js"></script>
    </div>
</body>
</html>
