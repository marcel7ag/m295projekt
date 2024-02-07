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
    <title>Ihre Aufträge</title>
</head>
<body>
    <h1 style="text-align: center; margin:  10px;">Ihre offenen Aufträge</h1>
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

        $zustandd = "COMPLETED";
        $query = "SELECT * FROM orders WHERE arbeiterID = :id AND zustand != :zustandd";
        $stmt = $pdo->prepare($query);
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':zustandd', $zustandd, PDO::PARAM_STR);
        $stmt->execute();
        
        // rowcount
        $numRows = $stmt->rowCount();

        // if there are rows, do while loop
        if ($numRows >  0) {
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
                    <div>
                    <form id="formZuteilung" style="width:auto; padding:0px;margin:0px;background-color:transparent;border:none;box-shadow:none;" method="post" action="rapporte.php">
                        <button class="detail-btn" name="btn-id" id="btn-id" value="'.$orderID.'" data-id="'.$orderID.'">Rapport</button>
                    </form>
                    </div>
                </div>';
            }
        } else {echo "keine offenen Auträge!";}
    ?>
        </div>
        <script src="script.js" defer></script>
    </body>
</html>