<?php // erfassung.php
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
            <h1>Auftragserfassung</h1>
            <!-- Rest des HTML-Codes... -->
            <div id="auftragErfassen">
                    <form method="post">

                    <div class="form-group">
                        <label for="auftragsDatum">Auftragsdatum:</label>
                        <input type="date" id="auftragsDatum" name="auftragsDatum" placeholder="mm-dd-yyyy" value="2023-12-31">
                    </div>

                    <div class="form-group">
                        <label for="zeit">Zeit:</label>
                        <input type="text" id="zeit" name="zeit" placeholder="00:00">
                    </div>

                    <div class="form-group">
                        <label for="kunde">Kunde:</label>
                        <div class="customer-info">
                            <select id="kAnrede" name="kAnrede">
                                <option value="">Anrede</option>
                                <option value="m">Herr</option>  
                                <option value="f">Frau</option>  
                            </select>
                        </div>
                        <input type="text" id="kVorname" name="kVorname" placeholder="Vorname">
                        <input type="text" id="kNachname" name="kNachname" placeholder="Nachname">
                        <input type="text" id="kAdresse" name="kAdresse" placeholder="Adresse">
                        <input type="text" id="kOrt" name="kOrt" placeholder="Ort" style="width: 60%;"><input type="text" id="kPLZ" name="kPLZ" placeholder="PLZ" style="width: 39%; margin-left: 7px;">
                    </div>

                    <div class="form-group">
                        <label for="kTelefon">Telefon:</label>
                        <input type="tel" id="kTelefon" name="kTelefon">
                    </div>

                    <div class="form-group">
                        <label for="adresseObj">Adresse Objekt:</label>
                        <input type="text" id="objAdresse" name="objAdresse" placeholder="Adresse">
                        <input type="text" id="objOrt" name="objOrt" placeholder="Ort" style="width: 60%;"><input type="text" id="objPLZ" name="objPLZ" placeholder="PLZ" style="width: 39%; margin-left: 7px;">
                    </div>

                    <div class="form-group">
                        <label for="rechAdresse">Rechnungsadresse:</label>
                        <select id="rechAnrede">
                            <option value="">Anrede</option>
                            <option value="rechM">Herr</option>  
                            <option value="rechF">Frau</option>  
                        </select>
                        <input type="text" id="rechVorname" name="rechVorname" placeholder="Vorname">
                        <input type="text" id="rechNachname" name="rechNachname" placeholder="Nachname">
                        <input type="text" id="rechAdresse" name="rechAdresse" placeholder="Adresse">
                        <input type="text" id="rechOrt" name="rechOrt" placeholder="Ort" style="width: 60%;"><input type="text" id="rechPLZ" name="rechPLZ" placeholder="PLZ" style="width: 39%; margin-left: 7px;">
                    </div>
                    <div class="form-group work-section">
                        <label for="arbeiten">Auszuführende Arbeiten:</label>
                        <div class="checkbox-grid">
                            <div class="checkbox-group">
                                <input type="checkbox" id="reparatur" name="reparatur">
                                <label for="reparatur">Reparatur</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="sanitaer" name="sanitaer">
                                <label for="sanitaer">Sanitär</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="heizung" name="heizung">
                                <label for="heizung">Heizung</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="garantie" name="garantie">
                                <label for="garantie">Garantie</label>
                            </div>
                        </div>
                        <textarea id="bemerkungen" name="bemerkungen" placeholder="Bemerkungen" style="height:80px;margin-top: 15px;"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="terminwunsch">Terminwunsch:</label>
                        <textarea id="terminwunsch" name="terminwunsch" placeholder="Terminwunsch" style="height:80px;margin-top: 15px;"></textarea>
                    </div>
                    <div class="submit-btns">
                        <!-- Modified button to submit the form -->
                        <button type="submit" id="submit" name="submit">Auftrag erfassen</button>
                        <!-- btn to "print" the form with function printDocument() -->
                        <button type="button" id="print" name="print" onclick="printdiv('container')">Drucken</button>
                    </div>
                </form>
            </div><!-- end auftragErfassen -->
            <script src="script.js"></script>
        </div>
    </body>
</html>
<?php // insertOrder.
include 'db/conn.php';
print("Test insertOrder.php");
// Assuming the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Prepare the SQL statement with placeholders
        $stmt = $pdo->prepare("SELECT id FROM Kunden WHERE kVorname COLLATE NOCASE = :kVorname AND kName COLLATE NOCASE = :kName AND kAdresse COLLATE NOCASE = :kAdresse");

        // Bind parameters to the placeholders
        $stmt->bindParam(':kVorname', $kVorname);
        $stmt->bindParam(':kName', $kNachname);
        $stmt->bindParam(':kAdresse', $kAdresse);

        // Execute the statement
        $stmt->execute();

        // Abfrageresultat in $row speichern
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Der Kunde existiert in der Datenbank, also speichern wir die ID in einer Variable
            $kID = $row['id'];
        }
        else {
            // Der Kunde existiert nicht in der Datenbank, also erstellen wir auch den Kunden in der Datenbanktabelle "Kunden"
            // TO DO:
            //---
            try {
                echo "Kunde wird erstellt:";
                $stmt = $pdo->prepare("INSERT INTO Kunden (kName, kVorname, kAdresse, kOrt, kPLZ, kTelefon, kGender) VALUES (:kName, :kVorname, :kAdresse, :kOrt, :kPLZ, :kTelefon, :kGender)");
                
                // Bind parameters to the placeholders
                $stmt->bindParam(':kName', $kNachname);
                $stmt->bindParam(':kVorname', $kVorname);
                $stmt->bindParam(':kAdresse', $kAdresse);
                $stmt->bindParam(':kOrt', $kOrt);
                $stmt->bindParam(':kPLZ', $kPLZ);
                $stmt->bindParam(':kTelefon', $kTelefon);
                $stmt->bindParam(':kGender', $kGender);

                $kVorname = $_POST['kVorname'] ?? null;
                $kNachname = $_POST['kNachname'] ?? null;
                $kAdresse = $_POST['kAdresse'] ?? null;
                $kOrt = $_POST['kOrt'] ?? null;
                $kPLZ = $_POST['kPLZ'] ?? null;
                $kTelefon = $_POST['kTelefon'] ?? null;
                // set Gender for db
                if ($_POST['kAnrede'] == "m") { 
                    $kGender = "MALE";} 
                else { 
                    $kGender = "FEMALE"; }

                // Execute the statement
                $stmt->execute();
                echo "Kunde wurde erstellt";

                $stmt = $pdo->prepare("SELECT id FROM Kunden WHERE kVorname COLLATE NOCASE = :kVorname AND kName COLLATE NOCASE = :kName AND kAdresse COLLATE NOCASE = :kAdresse");
                // Bind parameters to the placeholders
                $stmt->bindParam(':kVorname', $kVorname);
                $stmt->bindParam(':kName', $kNachname);
                $stmt->bindParam(':kAdresse', $kAdresse);

                // Execute the statement
                $stmt->execute();

                // Abfrageresultat in $row speichern
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    // Der Kunde existiert in der Datenbank, also speichern wir die ID in einer Variable
                    $kID = $row['id'];
                }
            } catch (\PDOException $e) { echo "An error occurred:" . $e->getMessage(); }
        }


        // Prepare the SQL statement with placeholders
        $stmt = $pdo->prepare("INSERT INTO Orders (
            kundenID, kundenName, objAdresse, objOrt, objPLZ, rechID, orderDate, orderTime,
            reparatur, sanitaer, heizung, garantie, bemerkung, terminwunsch, zustand, arbeiterID,
            completed, completedDate
        ) VALUES (
            :kundenID, :kundenName, :objAdresse, :objOrt, :objPLZ, :rechID, :orderDate, :orderTime,
            :reparatur, :sanitaer, :heizung, :garantie, :bemerkung, :terminwunsch, :zustand, :arbeiterID,
            :completed, :completedDate
        )");
        // Since you construct kundenName from other parts, you should check each part separately
        $kAnrede = $_POST['kAnrede'] ?? '';
        $kVorname = $_POST['kVorname'] ?? '';
        $kNachname = $_POST['kNachname'] ?? '';
        
        // Only construct the full name if all parts are present
        if (!empty($kAnrede) && !empty($kVorname) && !empty($kNachname)) {
            $kundenName = $kAnrede . ' ' . $kVorname . ' ' . $kNachname;
        } else {
            // Handle the error, show a message or redirect
            echo "Error: Invalid name parts in: $kAnrede, $kVorname, $kNachname";
            exit;
        }

        // $kundenName = $_POST['kundenName'] ?? '';
        echo $kGender;
        // var_dump($_SESSION);
        // Bind the form data to the placeholders
        $stmt->bindParam(':kundenID', $kID);
        $stmt->bindParam(':kundenName', $kundenName);
        $stmt->bindParam(':objAdresse', $objAdresse);
        $stmt->bindParam(':objOrt', $objOrt);
        $stmt->bindParam(':objPLZ', $objPLZ);
        $stmt->bindParam(':rechID', $rechID);
        $stmt->bindParam(':orderDate', $orderDate);
        $stmt->bindParam(':orderTime', $orderTime);
        $stmt->bindParam(':reparatur', $reparatur);
        $stmt->bindParam(':sanitaer', $sanitaer);
        $stmt->bindParam(':heizung', $heizung);
        $stmt->bindParam(':garantie', $garantie);
        $stmt->bindParam(':bemerkung', $bemerkung);
        $stmt->bindParam(':terminwunsch', $terminwunsch);
        $stmt->bindParam(':zustand', $zustand);
        // $stmt->bindParam(':arbeiterID', $arbeiterID);
        $stmt->bindParam(':completed', $completed);
        $stmt->bindParam(':completedDate', $completedDate);

        // Assign form data to variables
        $kundenName = $_POST['kNachname']; // Construct the full name from inputs -> line 170
        $objAdresse = $_POST['objAdresse'];
        $objOrt = $_POST['objOrt'];
        $objPLZ = $_POST['objPLZ'];
        $orderDate = $_POST['auftragsDatum'];
        $orderTime = $_POST['zeit'];
        $rechID = random_int(1,10000); // random rechnungsID
        $reparatur = isset($_POST['reparatur']) ? 1 : 0;
        $sanitaer = isset($_POST['sanitaer']) ? 1 : 0;
        $heizung = isset($_POST['heizung']) ? 1 : 0;
        $garantie = isset($_POST['garantie']) ? 1 : 0;
        $bemerkung = $_POST['bemerkungen'];
        $terminwunsch = $_POST['terminwunsch'];
        $zustand = 'TODO'; // Default state, change as needed
        // $arbeiterID = $_POST['arbeiterID'];
        $completed = 0; // Default completion status, change as needed
        $completedDate = ''; // Default completion date, change as needed

        // Execute the prepared statement
        $stmt->execute();
        
        exit;
    } catch (PDOException $e) {
        // Handle exception, display error message
        echo "Error: " . $e->getMessage();
    }
}

?>
