CREATE TABLE Users (
    id INT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(60) NOT NULL,
    role ENUM('ADMIN', 'MANAGER', 'ARBEITER'),
    description VARCHAR(50)
);

CREATE TABLE Kunden (
    id INT PRIMARY KEY,
    kName VARCHAR(30) NOT NULL,
    kVorname VARCHAR(30) NOT NULL,
    kAdresse VARCHAR(30) NOT NULL,
    kOrt VARCHAR(30) NOT NULL,
    kPLZ INT NOT NULL,
    kTelefon VARCHAR(30),
    kGender ENUM('FEMALE', 'MALE')
);

CREATE TABLE Orders (
    orderID INT PRIMARY KEY,
    kundenID INT NOT NULL,
    kundenName VARCHAR(30) NOT NULL,
    objAdresse VARCHAR(30) NOT NULL,
    objOrt VARCHAR(30) NOT NULL,
    objPLZ INT NOT NULL,
    rechAnrede ENUM('Herr', 'Frau') NOT NULL,
    rechName VARCHAR(30) NOT NULL,
    rechVorname VARCHAR(30) NOT NULL,
    rechAdresse VARCHAR(30) NOT NULL,
    rechOrt VARCHAR(30) NOT NULL,
    rechPLZ INT NOT NULL,
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
    FOREIGN KEY (arbeiterID) REFERENCES Users(id)
);
