<?php
// oAuftraege.php
session_start();
include 'header.php';
include 'db/conn.php';
//var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style/style.css">
        <title>Dispose Orders</title>
    </head>
    <table class="oTable" border="0" cellspacing="2" cellpadding="2"> 
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
    <body>
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

            echo '<tr> 
            <td>'.$orderID.'</td> 
            <td>'.$kundenID.'</td> 
            <td>'.$kundenName.'</td> 
            <td>'.$reparatur.'</td>
            <td>'.$sanitaer.'</td>
            <td>'.$heizung.'</td>
            <td>'.$garantie.'</td>
            <td style="color: '.$color.';">'.$zustand.'</td>
            <td><form><input type="hidden" name="data_id" value="'.$orderID.'"><button type="submit" name="action" class="detail-btn">Dispose</button></form></td>
            </tr>';
        }
        echo '</table>';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $action = $_POST['action'];
            if ($action == 'Dispose') {
                $data_id = $_POST['data_id']; // This is the orderID from the hidden input field
                $query = "DELETE FROM Orders WHERE id = :orderID";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':orderID', $data_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }        
        ?>
        </table>
        <script src="script.js" defer></script>
    </body>
</html>
