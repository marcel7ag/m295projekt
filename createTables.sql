CREATE DATABASE IF NOT EXISTS projekt5;
use projekt5;

CREATE TABLE Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(60) NOT NULL,
    role ENUM('ADMIN', 'MANAGER', 'ARBEITER')
);

CREATE TABLE Kunden (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kName VARCHAR(30) NOT NULL,
    kVorname VARCHAR(30) NOT NULL,
    kAdresse VARCHAR(30) NOT NULL,
    kOrt VARCHAR(30) NOT NULL,
    kPLZ INT NOT NULL,
    kTelefon VARCHAR(30),
    kGender ENUM('FEMALE', 'MALE')
);

CREATE TABLE Rechnung (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    rAnrede ENUM('Herr', 'Frau') NOT NULL,
    rName VARCHAR(30) NOT NULL,
    rVorname VARCHAR(30) NOT NULL,
    rAdresse VARCHAR(30) NOT NULL,
    rOrt VARCHAR(30) NOT NULL,
    rPLZ INT NOT NULL
);

CREATE TABLE Orders (
    orderID INT PRIMARY KEY AUTO_INCREMENT,
    kundenID INT NOT NULL,
    kundenName VARCHAR(30) NOT NULL,
    objAdresse VARCHAR(30) NOT NULL,
    objOrt VARCHAR(30) NOT NULL,
    objPLZ INT NOT NULL,
    rechID INT NOT NULL,
    orderDate DATETIME NOT NULL,
    orderTime DATETIME NOT NULL,
    reparatur BOOLEAN,
    sanitaer BOOLEAN,
    heizung BOOLEAN,
    garantie BOOLEAN,
    bemerkung VARCHAR(255),
    terminwunsch VARCHAR(30),
    zustand ENUM('COMPLETED', 'INPROGRESS', 'TODO') NOT NULL,
    arbeiterID INT,
    completed BOOLEAN NOT NULL,
    completedDate DATETIME NOT NULL,
    FOREIGN KEY (kundenID) REFERENCES Kunden(id),
    FOREIGN KEY (rechID) REFERENCES Rechnung(id),
    FOREIGN KEY (arbeiterID) REFERENCES Users(id)
);

INSERT INTO Users (username, password, role) VALUES 
('admin', '1234', 'ADMIN'),
('manager', '1234', 'MANAGER'),
('arbeiter', '1234', 'ARBEITER');

INSERT INTO Kunden (kName, kVorname, kAdresse, kOrt, kPLZ, kTelefon, kGender) VALUES 
('Mustermann', 'Max', 'Am Hauptplatz', 'Berlin', 8931, '0768881212', 'MALE'),
('Schmidt', 'Anna', 'Im Park', 'München', 123456, '0791235541', 'FEMALE'),
('Fischer', 'Lisa', 'An der Kirche', 'Hamburg', 8048, '0767773214', 'FEMALE');

INSERT INTO Rechnung (rAnrede, rName, rVorname, rAdresse, rOrt, rPLZ) VALUES 
('Herr', 'Mustermann', 'Max', 'Am Hauptplatz', 'Berlin', 8931),
('Frau', 'Schmidt', 'Anna', 'Im Park', 'München', 123456),
('Frau', 'Fischer', 'Lisa', 'An der Kirche', 'Hamburg', 8048);

INSERT INTO Orders (kundenID, kundenName, objAdresse, objOrt, objPLZ, rechID, orderDate, orderTime, reparatur, sanitaer, heizung, garantie, bemerkung, terminwunsch, zustand, arbeiterID, completed) VALUES 
(1, 'Mustermann Max', 'Am Dom', 'Bern', 12324, 1, NOW(), NOW(), FALSE, TRUE, FALSE, FALSE, '', '', 'INPROGRESS', 1, FALSE),
(2, 'Schmidt Anna', 'Pennymarkt', 'Tokyo', 32413, 2, NOW(), NOW(), TRUE, FALSE, TRUE, FALSE, '', '', 'TODO', 2, FALSE),
(3, 'Fischer Lisa', 'Kebabladen', 'Köln', 32142, 3, NOW(), NOW(), FALSE, TRUE, FALSE, TRUE, '', '', 'TODO', 3, FALSE);
