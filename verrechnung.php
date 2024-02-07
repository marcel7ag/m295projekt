<?php
// verrechnung.php
session_start();
include 'header.php';
include 'db/conn.php';
//var_dump($_SESSION);
// Check if user is logged in
if (!isset($_SESSION["name"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Aufträge verrechnen</title>
</head>
<body>
    <?php
    require 'db/conn.php';

    $completed = "COMPLETED";
    $verrechnet = "FALSE";
    $query = "SELECT * FROM Orders WHERE zustand = :completed AND verrechnet = :verrechnet";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':completed', $completed, PDO::PARAM_STR);
    $stmt->bindParam(':verrechnet', $verrechnet, PDO::PARAM_STR);
    $stmt->execute();
    ?>
    <?php
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
                <form id="formZuteilung" style="width:auto; padding:0px;margin:0px;background-color:transparent;border:none;box-shadow:none;" method="post" action="">
                    <div><button name="data-btn" value="'.$orderID.'" class="detail-btn" id="submit-btn" data-id="'.$orderID.'">Verrechnen</button></div>
                </form>
                </div>';
        }
        echo '</div>';

        if (isset($_POST['data-btn'])) {
        $orderID = $_POST['data-btn'];
        $verrechnet = "TRUE";
    
        $query = "UPDATE Orders SET verrechnet = :verrechnet, completed = :completed WHERE orderID = :orderId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':orderId', $orderID, PDO::PARAM_INT);
        $stmt->bindParam(':completed', $completed, PDO::PARAM_STR);
        $stmt->bindParam(':verrechnet', $verrechnet, PDO::PARAM_STR);
        $stmt->execute();
    
        // Optionally send a response back to the client
        echo "Auftrag wurde verrechnet!";
        }
    ?>
            <script src="script.js" defer></script>
        </body>
        </html>