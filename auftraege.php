<?php // auftraege.php
session_start();
include 'header.php';
include 'db/conn.php';
// check if user is logged in
if (!isset($_SESSION["name"])) {
    header("Location: index.php");
    exit();
}
// debug
// var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Alle Aufträge</title>
</head>
<body>
    <table border="0" cellspacing="2" cellpadding="2"> 
        <tr class="tableT"> 
            <td>ID</td>
            <td>KundenID</td>  
            <td>KundenName</td> 
            <td>Reparatur</td> 
            <td>Sanitär</td>
            <td>Heizung</td>
            <td>Garantie</td>
            <td>Zustand</td>
            <td>Auftragdetails</td>
        </tr>
    <?php
    require 'db/conn.php';
    
    $query = "SELECT * FROM Orders";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orderID = $row["orderID"];
            $kundenID = $row["kundenID"];
            $kundenName = $row["kundenName"];
            $reparatur = $row["reparatur"];
            $sanitaer = $row["sanitaer"];
            $heizung = $row["heizung"];
            $garantie = $row["garantie"];
            $zustand;
            $color;

            if($row["zustand"] == "INPROGRESS"){$color = "blue"; $zustand = "in Bearbeitung";}
                else if($row["zustand"] == "TODO"){$color = "orange"; $zustand = "Zu Bearbeiten";}
                else if($row["zustand"] == "COMPLETED"){$color = "green"; $zustand = "Abgeschlossen";}

            echo '<tr> 
            <td>'.$orderID.'</td> 
            <td>'.$kundenID.'</td> 
            <td>'.$kundenName.'</td> 
            <td>'.$reparatur.'</td>
            <td>'.$sanitaer.'</td>
            <td>'.$heizung.'</td>
            <td>'.$garantie.'</td>
            <td style="color: '.$color.';">'.$zustand.'</td>
            <td><button class="detail-btn" data-id="'.$orderID.'">Details</button></td> 
            </tr>';
        }
    echo '</table>';
    
?>
    
        <script src="script.js" defer></script>
    </body>
</html>


