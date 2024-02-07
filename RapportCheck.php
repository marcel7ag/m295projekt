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
        <title>Rapporte prüfen</title>
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
    $query = "SELECT * FROM Orders";
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

        $rapport = htmlspecialchars($row["rapport"], ENT_QUOTES, 'UTF-8');

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
        $uniqueButtonIdV = 'btn-idV-' . $orderID;
        $uniqueButtonIdR = 'btn-idR-' . $orderID;
        echo '<form id="formZuteilung" style="width:auto; padding:0px;margin:0px;background-color:transparent;border:none;box-shadow:none;" method="post" action="">
                <div style="margin:15px;">
                    <div>
                        <label style="display:block;" for="report">Rapport:</label>
                        <textarea style="width:600px; height:225px;" id="report" name="report" disabled="true">' . $rapport . '</textarea>
                    </div>
                    <div style="display:flex;">
                    <button style="width:auto;margin:auto auto;" type="submit" class="detail-btn" name="' . $uniqueButtonIdV . '">Verrechnung bereitstellen</button>
                    <button style="width:auto;margin:auto auto;" type="submit" class="detail-btn" name="' . $uniqueButtonIdR . '">Rapport ablehnen</button>
                    </div>
                </div><hr style="border: none;/* width: 50%; */height: 10px;border-top: solid white 2px;">
                ';
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Iterate over the $_POST array to find the clicked button
        foreach ($_POST as $buttonName => $value) {
            // Check if the button name starts with 'btn-idV-' or 'btn-idR-'
            if (strpos($buttonName, 'btn-idV-') ===  0 || strpos($buttonName, 'btn-idR-') ===  0) {
                // Now you have the button name, you can perform actions based on it
                $buttonClicked = $buttonName;
                if (strpos($buttonName, 'btn-idV-') ===  0) {
                    $orderID = substr($buttonName, strlen('btn-idV-'));
                    $orderID = substr($buttonName, strlen('btn-idR-'));
                    $zustand = "COMPLETED";
                    $status = "TRUE";
                
                    $query = "UPDATE Orders SET completed = :statuss, zustand = :zustand WHERE orderID = :orderId";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':orderId', $orderID, PDO::PARAM_INT);
                    $stmt->bindParam(':zustand', $zustand, PDO::PARAM_STR);
                    $stmt->bindParam(':statuss', $status, PDO::PARAM_STR);
                    $stmt->execute();
                
                    // Optionally send a response back to the client
                    echo "Rapport wurde akzeptiert und ist zur Verrechnung bereit!";

                } elseif (strpos($buttonName, 'btn-idR-') ===  0) {
                    $orderID = substr($buttonName, strlen('btn-idR-'));
                    $zustand = "INPROGRESS";
                    $rapport = null;
                
                    $query = "UPDATE Orders SET rapport = :rapport , zustand = :zustand WHERE orderID = :orderId";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':orderId', $orderID, PDO::PARAM_INT);
                    $stmt->bindParam(':zustand', $zustand, PDO::PARAM_STR);
                    $stmt->bindParam(':rapport', $rapport, PDO::PARAM_STR);
                    $stmt->execute();
                
                    // Optionally send a response back to the client
                    echo "Rapport wurde abgelehnt!";
                }
                break; // Exit the loop once the clicked button is found
            }
        }
    }
    
    
                    
    
    
?>
        </div>
        <script src="script.js" defer></script>
    </body>
</html>

