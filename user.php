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
                <?php
                    if ($_SESSION["role"] == 'ADMIN') {
                        echo   '
                                <tr>
                                    <td>
                                        Auftragserfassung:
                                    </td>
                                    <td>
                                        <input type="submit" id="auftragserfassung" name="action" value="Auftragserfassung">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Alle Aufträge anzeigen:
                                    </td>
                                    <td>
                                        <input type="submit" id="auftraege" name="action" value="Aufträge">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Ihre offenen Aufträge bearbeiten:
                                    </td>
                                    <td>
                                        <input type="submit" id="oAuftraege" name="action" value="Offene Aufträge">
                                    </td>
                                </tr>';
                    }
                    else if ($_SESSION["role"] == 'MANAGER') {
                        echo   '
                                <tr>
                                    <td>
                                        Auftragsplanung:
                                    </td>
                                    <td>
                                        <input type="submit" id="auftraege" name="action" value="Aufträge">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Offene Aufträge bearbeiten:
                                    </td>
                                    <td>
                                        <input type="submit" id="oAuftraege" name="action" value="Offene Aufträge">
                                    </td>
                                </tr>';
                    }
                    else if ($_SESSION["role"] == 'ARBEITER') {
                        echo   '
                                <tr>
                                    <td>
                                        Ihre offenen Aufträge bearbeiten:
                                    </td>
                                    <td>
                                        <input type="submit" id="oAuftraege" name="action" value="Offene Aufträge">
                                    </td>
                                </tr>';
                    }
                ?>
                
            </table>
        </div>
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $action = $_POST['action'];

            if ($action == 'Auftragserfassung') {
                header("Location: erfassung.php");
                exit();
            }
            else if ($action == 'Aufträge') {
                header("Location: auftraege.php");
                exit();
            }
            else if ($action == 'Offene Aufträge') {
                header("Location: oAuftraege.php");
                exit();
            }
        }

        $conn = null;
    ?>
</body>
</html>