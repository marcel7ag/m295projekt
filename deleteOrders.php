<?php
// oAuftraege.php
session_start();
include 'header.php';
include 'db/conn.php';
//var_dump($_SESSION);
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
        <h1>Delete Orders</h1>
        <div id="ordersContainer">
            <?php
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

                echo '<div class="order-item">  
                <span class="order-id">'.$orderID.'</span>  
                <span class="kunden-id">'.$kundenID.'</span>  
                <span class="kunden-name">'.$kundenName.'</span>  
                <span class="reparatur">'.$reparatur.'</span>
                <span class="sanitaer">'.$sanitaer.'</span>
                <span class="heizung">'.$heizung.'</span>
                <span class="garantie">'.$garantie.'</span>
                <span class="zustand" style="color: '.$color.';">'.$zustand.'</span>
                <div class="order-actions">
                    <form method="POST">
                        <input type="hidden" name="data_id" value="'.$orderID.'">
                        <button type="submit" name="action" value="Delete" class="detail-btn">Delete</button>
                    </form>
                </div>
                </div>';
            }

            if (isset($_POST['action']) && $_POST['action'] === 'Delete' && isset($_POST['data_id'])) {
                disposeOrder($_POST['data_id']);
            }
            
            function disposeOrder($orderId) {
                global $pdo;
            
                // Prepare the SQL statement to delete the order
                $sql = "DELETE FROM Orders WHERE orderID = :orderId";
                $stmt = $pdo->prepare($sql);
            
                // Execute the statement with the provided order ID
                $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
                $stmt->execute();
            
                // Check if the deletion was successful
                if ($stmt->rowCount() >  0) {
                    echo "Order has been deleted.";
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit;
                } else {
                    echo "Failed to delete of order.";
                }
            }
            
            ?>
        </div>
        <script src="script.js"></script>
    </div>
</body>
</html>

