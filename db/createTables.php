<?php
// Create a new PDO object
$pdo = new PDO('sqlite:projekt5.db');
// Set the error mode to exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Check if tables are empty
checkTables($pdo);


function createTables($pdo) {    
    try {
        // Create Users table
        $pdo->exec("CREATE TABLE IF NOT EXISTS Users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            password TEXT NOT NULL,
            role TEXT CHECK(role IN ('ADMIN', 'MANAGER', 'ARBEITER'))
        )");

        // Create Kunden table
        $pdo->exec("CREATE TABLE IF NOT EXISTS Kunden (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            kName TEXT NOT NULL,
            kVorname TEXT NOT NULL,
            kAdresse TEXT NOT NULL,
            kOrt TEXT NOT NULL,
            kPLZ INTEGER NOT NULL,
            kTelefon TEXT,
            kGender TEXT CHECK(kGender IN ('FEMALE', 'MALE'))
        )");

        // Create Rechnung table
        $pdo->exec("CREATE TABLE IF NOT EXISTS Rechnung (
            ID INTEGER PRIMARY KEY AUTOINCREMENT,
            rAnrede TEXT NOT NULL,
            rName TEXT NOT NULL,
            rVorname TEXT NOT NULL,
            rAdresse TEXT NOT NULL,
            rOrt TEXT NOT NULL,
            rPLZ INTEGER NOT NULL
        )");

        // Create Orders table
        $pdo->exec("CREATE TABLE IF NOT EXISTS Orders (
            orderID INTEGER PRIMARY KEY AUTOINCREMENT,
            kundenID INTEGER NOT NULL, /* Problem: need to manually type ID because AUTOINCREMENT cant be used rn. */
            kundenName TEXT NOT NULL,
            objAdresse TEXT NOT NULL,
            objOrt TEXT NOT NULL,
            objPLZ INTEGER NOT NULL,
            rechID INTEGER NOT NULL,
            orderDate TEXT NOT NULL,
            reparatur BOOLEAN DEFAULT 0,
            sanitaer BOOLEAN DEFAULT 0,
            heizung BOOLEAN DEFAULT 0,
            garantie BOOLEAN DEFAULT 0,
            bemerkung TEXT,
            terminwunsch TEXT,
            zustand TEXT CHECK(zustand IN ('COMPLETED', 'INPROGRESS', 'TODO')),
            arbeiterID INTEGER,
            completed BOOLEAN DEFAULT 0,
            completedDate TEXT NOT NULL,
            verrechnet BOOLEAN DEFAULT 0,
            FOREIGN KEY (kundenID) REFERENCES Kunden(id),
            FOREIGN KEY (rechID) REFERENCES Rechnung(id),
            FOREIGN KEY (arbeiterID) REFERENCES Users(id)
        )");
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

function insertSample($pdo) {
    try {
        // insert passwords hashed
        $users = [
            ['admin', 'Bene1234!_', 'ADMIN'],
            ['manager', 'Bene1234!_', 'MANAGER'],
            ['arbeiter', 'Bene1234!_', 'ARBEITER']
        ];
        // Iterate through each user and hash their password
        foreach ($users as $user) {
            $hashedPassword = password_hash($user[1], PASSWORD_DEFAULT);

            // Prepare the INSERT statement
            $stmt = $pdo->prepare("INSERT INTO Users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$user[0], $hashedPassword, $user[2]]);
        }
        // Insert data into Kunden table
        $pdo->exec("INSERT INTO Kunden (kName, kVorname, kAdresse, kOrt, kPLZ, kTelefon, kGender) VALUES 
        ('Mustermann', 'Max', 'Am Hauptplatz', 'Berlin', 8931, '0768881212', 'MALE'),
        ('Schmidt', 'Anna', 'Im Park', 'München', 123456, '0791235541', 'FEMALE'),
        ('Fischer', 'Lisa', 'An der Kirche', 'Hamburg', 8048, '0767773214', 'FEMALE')");

        // Insert data into Rechnung table
        $pdo->exec("INSERT INTO Rechnung (rAnrede, rName, rVorname, rAdresse, rOrt, rPLZ) VALUES 
        ('Herr', 'Mustermann', 'Max', 'Am Hauptplatz', 'Berlin', 8931),
        ('Frau', 'Schmidt', 'Anna', 'Im Park', 'München', 123456),
        ('Frau', 'Fischer', 'Lisa', 'An der Kirche', 'Hamburg', 8048)");

        // create orderDate  format: 'dd-mm-yyyy hh:mm:ss'
        $date = date('d-m-Y H:i:s');
        // Insert sample data into Orders table
        $pdo->exec("INSERT INTO Orders (kundenID, kundenName, objAdresse, objOrt, objPLZ, rechID, orderDate, reparatur, sanitaer, heizung, garantie, bemerkung, terminwunsch, zustand, arbeiterID, completed, completedDate) VALUES 
        (1, 'Mustermann Max', 'Am Dom', 'Bern', 12324, 1, '$date', 0, 1, 0, 0, '', '', 'INPROGRESS', 1, 0, '$date'),
        (2, 'Schmidt Anna', 'Pennymarkt', 'Tokyo', 32413, 2, '$date', 1, 0, 1, 0, '', '', 'TODO', 2, 0, '$date'),
        (3, 'Fischer Lisa', 'Kebabladen', 'Köln', 32142, 3, '$date', 0, 1, 0, 1, '', '', 'TODO', 3, 0, '$date')");
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

function checkTables($pdo) {
    $stmt = $pdo->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name='Users'");
    $stmt->execute();
    $result = $stmt->fetch();
    if (!$result) {
        createTables($pdo);
        insertSample($pdo);
    }
    $stmt = null;
}