<?php
// oAuftraege.php
session_start();
include 'header.php';
include 'db/conn.php';
var_dump($_SESSION);
// Check if user is logged in
if (!isset($_SESSION["name"])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];
$query = "SELECT * FROM orders WHERE arbeiterID = $id";
$stmt = $pdo->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getColor($status) {
    switch ($status) {
        case 'INPROGRESS':
            return 'blue';
        case 'TODO':
            return 'orange';
        case 'COMPLETED':
            return 'green';
        default:
            return 'red'; // oder eine andere Standardfarbe
    }
}

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
        <table id="tablee" border="0" cellspacing="2" cellpadding="2"> 
            <tr class="tableT"> 
                <td style="width: min-content;">OrderID</td>
                <td style="width: min-content;" >KundenID</td>  
                <td style="width: min-content;">KundenName</td> 
                <td>Reparatur</td> 
                <td>Sanitär</td>
                <td>Heizung</td>
                <td>Garantie</td>
                <td>Zustand</td>
                <td>Auftragdetails</td>
            </tr>
            <?php foreach ($orders as $order): ?>
                <?php $color = getColor($order["zustand"]); ?>
                <tr> 
                    <td><?= $order["orderID"] ?></td> 
                    <td><?= $order["kundenID"] ?></td> 
                    <td><?= $order["kundenName"] ?></td> 
                    <td><?= $order["reparatur"] ? "ja" : "nein" ?></td>
                    <td><?= $order["sanitaer"] ? "ja" : "nein" ?></td>
                    <td><?= $order["heizung"] ? "ja" : "nein" ?></td>
                    <td><?= $order["garantie"] ? "ja" : "nein" ?></td>
                    <td style="color: <?= $color ?>;"><?= ucfirst($order["zustand"]) ?></td>
                    <td><button class="detail-btn" data-id="<?= $order["orderID"] ?>">Details</button></td> 
                </tr>
                <div id="modal-<?= $order["orderID"] ?>" class="modal hidden">
                    <div class="modal-content">
                        <!-- Close Button -->
                        <span id="closeSpan" onclick="this.parentElement.parentElement.style.display='none'; document.querySelector('.overlay').style.display='none';" class="close">&times;</span>
                        <!-- Your modal content goes here -->
                    </div>
                </div>
            <?php endforeach; ?>
        </table>
        <script src="script.js" defer></script>
    </body>
</html>
