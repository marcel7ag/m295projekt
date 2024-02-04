<?php // user.php
    session_start();
    include 'db/conn.php';
    include 'header.php';
    // debug
    var_dump($_SESSION);
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
        <title>Welcome</title>
    </head>

    <body>
        <form method="post">
            <div class="container">
                <h1 id="hellouser"><?php echo "Hello " . $_SESSION["name"]; ?></h1>
                <input type="submit" name="action" value="Logout">
                <div id="auftragErfassen">
                    <h2>Tools</h2>  
                    <?php
                        if ($_SESSION["role"] == 'ADMIN') {
                            echo   '<div class="tool-option">
                                        <span>Order Entry:</span>
                                        <input type="submit" id="auftragserfassung" name="action" value="Order Entry">
                                    </div>
                                    <div class="tool-option">
                                        <span>Display All Orders:</span>
                                        <input type="submit" id="auftraege" name="action" value="Orders">
                                    </div>
                                    <div class="tool-option">
                                        <span>Edit Your Open Orders:</span>
                                        <input type="submit" id="oAuftraege" name="action" value="Open Orders">
                                    </div>';
                        }
                        else if ($_SESSION["role"] == 'MANAGER') {
                            echo   '<div class="tool-option">
                                        <span>Order Planning:</span>
                                        <input type="submit" id="auftraege" name="action" value="Orders">
                                    </div>
                                    <div class="tool-option">
                                        <span>Edit Your Open Orders:</span>
                                        <input type="submit" id="oAuftraege" name="action" value="Open Orders">
                                    </div>';
                        }
                        else if ($_SESSION["role"] == 'WORKER') {
                            echo   '<div class="tool-option">
                                        <span>Edit Your Open Orders:</span>
                                        <input type="submit" id="oAuftraege" name="action" value="Open Orders">
                                    </div>';
                        }
                    ?>
                </div>
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
    else if ($action == 'Orders') {
        echo "Orders";
        header("Location: auftraege.php");
        exit();
    } // bt Open Orders
    else if ($action == 'Open Orders') {
        echo "Open Orders";
        header("Location: oAuftraege.php");
        exit();
    } // bt Logout
    else if ($action == 'Logout') {
        echo "Logout";
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
    
}


$conn = null;
?>
