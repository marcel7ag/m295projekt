<?php // auftragzuteilung.php
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
        <title>Auftrag zuteilen</title>
    </head>
    <body>
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
            </div>
        <?php
    require 'db/conn.php';
    
    $query = "SELECT * FROM Orders WHERE arbeiterID IS NULL";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

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
                <form id="formZuteilung" style="width:auto; padding:0px;margin:0px;background-color:transparent;border:none;box-shadow:none;" method="post" action="MitarbeiterZuteilen.php">
                    <div><button name="data-btn" value="'.$orderID.'" class="detail-btn" id="data-btn" data-id="'.$orderID.'">Zuteilen</button></div>
                    <input type="hidden" id="dataIdField" name="dataId" />
                </form>
            </div>';
        }
    
?>
        </div>
        <script src="script.js" defer></script>
    </body>
</html>

