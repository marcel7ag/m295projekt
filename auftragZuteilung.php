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
        <title>AlleAufträge</title>
    </head>
    <body>
        <?php
        $query = "SELECT * FROM orders WHERE arbeiterID = null";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        ?>
            <?php
            if ($stmt->rowCount() == 0){ echo '<p style="text-align: center; font-weight: bold;">Alle Aufträge sind zugteilt worden!</p>'; }
            else {
                echo '
                <table class="oTable" border="0" cellspacing="2" cellpadding="2"> 
                    <tr class="tableT"> 
                        <td style="width: min-content;">ID</td>
                        <td style="width: min-content;" >KundenID</td>  
                        <td style="width: min-content;">KundenName</td> 
                        <td>Reparatur</td> 
                        <td>Sanitär</td>
                        <td>Heizung</td>
                        <td>Garantie</td>
                        <td>Zustand</td>
                        <td>Auftragdetails</td>
                    </tr>';

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
            }
        ?>
        </table>
        <script src="script.js" defer></script>
    </body>
</html>
