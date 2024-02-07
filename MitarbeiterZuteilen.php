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

    $employees = getAllEmployees();

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
                <div>
                <form id="formZuteilung" style="width:auto; padding:0px;margin:0px;background-color:transparent;border:none;box-shadow:none;" method="post" action="">
                    <select name="employee_id">';

            foreach ($employees as $employee) {
                echo '<option value="' . $employee['id'] . '">' . $employee['id'] . ' - ' . $employee['username'] . '</option>';
            }

            echo '</select></div>
                <div>
                
                    <button name="submit-btn" value="'.$orderID.'" class="detail-btn" id="'.$orderID.'">Zuteilen</button>
                </form>
                </div>';
    }

    if (isset($_POST['submit-btn'])) {
        $orderID = $_POST['submit-btn'];
        $employeeID = $_POST['employee_id'];
        $zustand = "INPROGRESS";
    
        $query = "UPDATE Orders SET arbeiterID = :employeeId, zustand = :zustandd WHERE orderID = :orderId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':employeeId', $employeeID, PDO::PARAM_INT);
        $stmt->bindParam(':orderId', $orderID, PDO::PARAM_INT);
        $stmt->bindParam(':zustandd', $zustand, PDO::PARAM_STR);
        $stmt->execute();
    
        // Optionally send a response back to the client
        echo "Mitarbeiter wurde zugeteilt!";
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
?>
        </div>
        <script src="script.js" defer></script>
    </body>
</html>

