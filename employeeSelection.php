<?php
include 'db/conn.php';

if (isset($_POST['data_id'])) {
    $query = "SELECT id FROM Users";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <!-- Your head tags here -->
</head>
<body>
    <h1>Select an Employee</h1>
    <form method="POST">
        <input type="hidden" name="order_id" value="<?php echo $_POST['data_id']; ?>">
        <select name="employee_id">
            <?php foreach ($employees as $employee): ?>
                <option value="<?php echo $employee['employeeID']; ?>"><?php echo $employee['employeeID']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Assign">
    </form>

<?php

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
        $stmt = $conn->prepare("SELECT * FROM Users WHERE role = :role");
        $stmt->bindValue(':role', 'ARBEITER', PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Fetch all rows as an associative array
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Close the database connection
        $conn = null;

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
</body>
</html>
