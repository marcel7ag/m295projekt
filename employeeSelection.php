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
?>

</body>
</html>
