<?php
// oAuftraege.php
session_start();
include 'header.php';
include 'db/conn.php';
//var_dump($_SESSION);
// Check if user is logged in
if (!isset($_SESSION["name"])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];
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
        <?php
        require 'db/conn.php';

        $query = "SELECT * FROM orders WHERE completed = '1'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        if ($stmt->rowCount() ==  0){
            echo '<p style="text-align: center; font-weight: bold;">Alle Aufträge sind abgeschlossen!</p>';
        } else {
            echo '
            <div class="orders-container">
                <div class="orders-header">
                    <div>ID</div>
                    <div>KundenID</div>
                    <div>KundenName</div>
                    <div>Reparatur</div>
                    <div>Sanitär</div>
                    <div>Heizung</div>
                    <div>Garantie</div>
                    <div>Zustand</div>
                    <div>Auftragdetails</div>
                </div>';

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $orderID = $row["orderID"];
                    $kundenID = $row["kundenID"];
                    $kundenName = $row["kundenName"];
                    $reparatur = "-";
                    $sanitaer = "-";
                    $heizung = "-";
                    $garantie = "-";
                    $zustand;
                    $color;

                    if ($row["reparatur"] == '1') {
                        $reparatur = "ja";
                    }
                    if ($row["sanitaer"] == '1') {
                        $sanitaer = "ja";
                    }
                    if ($row["heizung"] == '1') {
                        $heizung = "ja";
                    }
                    if ($row["garantie"] == '1') {
                        $garantie = "ja";
                    }

                    if($row["zustand"] == "INPROGRESS"){$color = "lightblue"; $zustand = "in Bearbeitung";}
                        else if($row["zustand"] == "TODO"){$color = "orange"; $zustand = "Zu Bearbeiten";}
                        else if($row["zustand"] == "COMPLETED"){$color = "green"; $zustand = "Abgeschlossen";}

                        echo '<div class="order-row">
                        <div>'.$orderID.'</div>
                        <div>'.$kundenID.'</div>
                        <div>'.$kundenName.'</div>
                        <div>'.$reparatur.'</div>
                        <div>'.$sanitaer.'</div>
                        <div>'.$heizung.'</div>
                        <div>'.$garantie.'</div>
                        <div style="color: '.$color.';">'.$zustand.'</div>
                        <div><button class="detail-btn" data-id="'.$orderID.'">Details</button></div>
                    </div>';
                }
                echo '</div>';
            }
        ?>
        <script src="script.js" defer></script>
    </body>
</html>