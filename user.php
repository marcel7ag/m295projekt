<?php // user.php
    session_start();
    include 'db/conn.php';
    include 'header.php';
    // debug
    // var_dump($_SESSION);
    // check if user is logged in
    if (!isset($_SESSION["name"])) {
        header("Location: index.php");
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style/style.css">
        <title>Willkommen</title>
    </head>
    <body>
        <form method="post">
            <h1 id="hellouser"><?php echo "Hello " . $_SESSION["name"]; ?></h1>
            <input class="logout" type="submit" name="action" value="Logout">
            <div id="auftragErfassen">
                <h2>Tools</h2>
                <?php
                    if ($_SESSION["role"] == 'ADMIN') {
                        // Admin tools
                        echo '<div class="tool-option">
                                <span>Auftragserfassung:</span>
                                <input type="submit" id="auftragserfassung" name="action" value="Order Entry">
                            </div>
                            <div class="tool-option">
                                <span>Aufträge verrechnen:</span>
                                <input type="submit" id="verrechnung" name="action" value="Verrechnung">
                            </div>
                            <div class="tool-option">
                                <span>Alle Aufträge sehen:</span>
                                <input type="submit" id="oAuftraege" name="action" value="All Orders">
                            </div>
                            <div class="tool-option">
                                <span>Aufträge löschen:</span>
                                <input type="submit" id="oAuftraege" name="action" value="Delete Orders">
                            </div>';
                    } else if ($_SESSION["role"] == 'MANAGER') {
                        // Manager tools
                        echo '<div class="tool-option">
                                <span>Aufträge zuteilen:</span>
                                <input type="submit" id="auftraege" name="action" value="Dispose Order">
                            </div>
                            <div class="tool-option">
                                <span>Alle Aufträge sehen:</span>
                                <input type="submit" id="oAuftraege" name="action" value="All Orders">
                            </div>
                            <div class="tool-option">
                                <span>Rapporte prüfen:</span>
                                <input type="submit" id="oAuftraege" name="action" value="Rapporte prüfen">
                            </div>';
                    } else if ($_SESSION["role"] == 'ARBEITER') {
                        // Arbeiter tools
                        echo '<div class="tool-option">
                                <span>Ihre offenen Aufträge:</span>
                                <input type="submit" id="oAuftraege" name="action" value="Open Orders">
                            </div>';
                    }
                ?>
            </div>
        </form>
    </body>
</html>


<?php // for user.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    // bt Order Entry
    if ($action == 'Order Entry') {
        echo "Order Entry";
        // PROBLEM: Session data is not passed to the next page
        header("Location: erfassung.php");
        exit();
    } // bt Orders
    else if ($action == 'Dispose Order') {
        echo "Dispose Order";
        header("Location: auftragZuteilung.php");
        exit();
    } // bt Rapporte
    else if ($action == 'Rapporte') {
        echo "Rapporte";
        header("Location: rapporte.php");
        exit();
    }  // bt Orders
    else if ($action == 'Open Orders') {
        echo "Open Orders";
        header("Location: oAuftraege.php");
        exit();
    } // bt offene Orders
    else if ($action == 'All Orders') {
        echo "Open Orders";
        header("Location: auftraege.php");
        exit();
    }// bt Logout
    else if ($action == 'Logout') {
        echo "Logout";
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    } // bt Verrechnung
    else if ($action == 'Verrechnung') {
        echo "Verrechnung";
        header("Location: verrechnung.php");
        exit();
    } // bt Delete
    else if ($action == 'Delete Orders') {
        echo "Delete Orders";
        header("Location: deleteOrders.php");
        exit();
    } // bt Rapporte
    else if ($action == 'Rapporte prüfen') {
        echo "Rapporte prüfen";
        header("Location: rapportCheck.php");
        exit();
    }
    
}


$conn = null;
?>
