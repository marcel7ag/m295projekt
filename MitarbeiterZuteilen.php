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
                <div>Mitarbeiter</div>
                <div>Zuteilen</div>
            </div>
    <?php
    require 'db/conn.php';
    $query = "SELECT * FROM Orders WHERE orderID = :orderID";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':orderID', $_POST['data-btn'], PDO::PARAM_INT);
    $stmt->execute();

    getAllEmployees();

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
                <div><select name="employee_id">';

            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employeeID'] . '">' . $employee['employeeID'] . '</option>';
            }

            echo '</select></div>
                <div><button name="data-btn" value="'.$orderID.'" class="detail-btn" id="data-btn" data-id="'.$orderID.'">Zuteilen</button></div>
            </div>';
    }

    if (isset($_POST['employee_id']) && isset($_POST['order_id'])) {
        $query = "UPDATE Orders SET arbeiterID = :employeeId WHERE orderID = :orderId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':employeeId', $_POST['employee_id'], PDO::PARAM_INT);
        $stmt->bindParam(':orderId', $_POST['order_id'], PDO::PARAM_INT);
        $stmt->execute();
        // Redirect back to the original page after assignment
        header('Location:auftragZuteilung.php');
    }
    
    function getAllEmployees() {
        try {       
            include 'db/conn.php';
    
            // Prepare the SQL statement
            $stmt = $pdo->prepare("SELECT * FROM Users WHERE role = :rolee");
            $stmt->bindValue(':rolee', 'ARBEITER', PDO::PARAM_STR);
    
            // Execute the statement
            $stmt->execute();
    
            // Fetch all rows as an associative array
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Close the database connection
            $pdo = null;
    
            return $employees;
        } catch(PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    
    // Funktion zum Zuweisen eines Auftrags an einen Mitarbeiter
    function assignOrderToEmployee($order_id, $employee_id) {
        include 'db/conn.php';
        $sql = "UPDATE Orders SET arbeiterID = $employee_id WHERE id = $order_id";
        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);
            return true;
        } else {
            echo "Fehler beim Zuweisen des Auftrags: " . mysqli_error($conn);
            mysqli_close($conn);
            return false;
        }
    }
?>
        </div>
        <script src="script.js" defer></script>
    </body>
</html>

