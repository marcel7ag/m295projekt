<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleUser.css">
    <title>Willkommen</title>
</head>
<?php
    require 'conn.php';
    session_start();
?>
<body>
    <form method="post">
        <div class="container">
            <h1><?php echo "Hallo " . $_SESSION["name"]; ?></h1>

            <div id="auftragErfassen">
            <h2>Tools</h2>  
            <table>
                <tr>
                    <td>
                        <label for="auftragserfassung">Auftragserfassung:</label>
                    </td>
                    <td>
                        <input type="submit" id="auftragserfassung" name="action" value="Auftragserfassung">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="auftraege">Alle Aufträge anzeigen:</label>
                    </td>
                    <td>
                        <input type="submit" id="auftraege" name="action" value="Aufträge">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="oAuftraege">Offene Aufträge bearbeiten:</label>
                    </td>
                    <td>
                        <input type="submit" id="oAuftraege" name="action" value="Offene Aufträge">
                    </td>
                </tr>
            </table>
        </div>
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $action = $_POST['action'];

            if ($action == 'Auftragserfassung') {
                echo "yoooo";
                header("Location: erfassung.php");
                exit();
            }
        }

        $conn->close();
    ?>
</body>
</html>