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
        <title>Auftrag ausführen</title>
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
                <div>Rapport</div>
            </div>
    <?php
    require 'db/conn.php';
    $orderID = $_POST['btn-id'];
    $query = "SELECT * FROM Orders WHERE orderID = :orderID";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
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
                <div>' . $orderID . '</div>
                <div>' . $kundenID . '</div>
                <div>' . $kundenName . '</div>
                <div>' . $reparatur . '</div>
                <div>' . $sanitaer . '</div>
                <div>' . $heizung . '</div>
                <div>' . $garantie . '</div>
                <div style="color: ' . $color . ';">' . $zustand . '</div>
                </div>';
            echo '<form id="formZuteilung" style="width:auto; padding:0px;margin:0px;background-color:transparent;border:none;box-shadow:none;" method="post" action="">
                    <div style="margin:15px;">
                        <div>
                            <label style="display:block;" for="report">Rapport:</label>
                            <textarea style="width:600px; height:225px;" id="report" name="report"></textarea>
                        </div>
                        <div>
                            <button class="detail-btn" style="width:auto;display:flex;margin:auto auto;" name="btn-id" id="btn-id" value="'.$orderID.'" data-id="'.$orderID.'">abschliessen</button>
                        </div>
                    </div>
                    ';
    }

    if (isset($_POST['btn-id'])) {
        $Compldate = date('d-m-Y H:i:s', time());
        $orderID = $_POST['btn-id'];
        $zustand = "COMPLETED";
        $rapport = isset($_POST['report']) ? $_POST['report'] : '';
    
        $query = "UPDATE Orders SET zustand = :zustandd, rapport = :rapportt , CompletedDate = :Compldate WHERE orderID = :orderId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':orderId', $orderID, PDO::PARAM_INT);
        $stmt->bindParam(':zustandd', $zustand, PDO::PARAM_STR);
        $stmt->bindParam(':Compldate', $Compldate, PDO::PARAM_STR);
        $stmt->bindParam(':rapportt', $rapport, PDO::PARAM_STR);
        $stmt->execute();
    
        // Optionally send a response back to the client
        echo "Auftrag erledigt und Rapport wurde erstellt!";
    }
?>
        </div>
        <script src="script.js" defer></script>
    </body>
</html>

