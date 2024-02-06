<?php // index.php
session_start();
require_once 'db/createTables.php';
$action = $_POST['action'] ?? null;
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

// wait for user input -> login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($action == 'Login') {
        manageLoginAttempts();
        authenticateUser();
    } elseif ($action == 'Registrieren') {
        validateAndRegisterUser();
    }
}
#TODO: fix 3 times attempt!!!
function manageLoginAttempts() { 
    global $message;
    if (!isset($_SESSION['attempt'])) {
        $_SESSION['attempt'] = 0;
    }
    if ($_SESSION['attempt'] >= 3) {
        $message = "Sie haben schon 3 Fehlversuche erreicht. Ihr Konto ist gesperrt.";
    }
}

function authenticateUser() {
    global $username, $password, $message;
    $stmt = $GLOBALS['pdo']->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bindValue(1, $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION["id"] = $row['id'];
        $_SESSION["name"] = $username;
        $_SESSION["role"] = $row['role'];
        header("Location: user.php");
        exit();
    } else {
        $_SESSION['attempt']++;
        $message = "Name oder Passwort ungültig.";
    }
    
    $stmt = null;
}

function validateAndRegisterUser() {
    global $password, $username, $message;

    // check if username is already taken
    $stmt = $GLOBALS['pdo']->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bindValue(1, $username);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $message = "Name bereits vergeben.";
        return;
    }
    // create password rules: 10char, 1 upper, 1 lower, 1 number, 1 special char
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+}{":;\'?\/>.<,])(?=.{10,})/', $password)) {
        $message = "Passwort entspricht nicht den Anforderungen.";
        return;
    }
    

    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $GLOBALS['pdo']->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->execute();            

        if ($stmt->rowCount() > 0) {
            $message = "Konto erfolgreich erstellt.";
        } else {
            throw new Exception("Fehler beim Erstellen des Kontos.");
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="style/style.css">
        <title>Login und Registration</title>
    </head>
    <body>

            <!-- USER INTERFACE HERE FROM template.php-->
            <?php
            include 'header.php'; // only header tag with navigation & logout button
            ?>
        <form method="post">
            <h1>Registrieren oder Login</h1>
            <div class="name-field">
                Name: <input type="text" name="username"><br><br>
            </div>
            <div class="password-field">
                Passwort: <input type="password" name="password" id="password">
                <label>
                    <input type="checkbox" id="showPassword" onclick="toggleVisibility()"><label for="showPassword"></label>
                    Passwort anzeigen
                </label>
            </div>
            <br><br>
            <input type="submit" name="action" value="Login">
            <input type="submit" name="action" value="Registrieren">
            
        </form>
        <div id="msgDisplay"></div>
    </body>
</html>


<script>
    // function to toggle password visibility btn
    function toggleVisibility() {
        var passwordInput = document.getElementById("password");
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    }
    $(document).ready(function(){
        $(".windowpopup").click(function(){
            $(this).hide();
        });
    });

    // Added code to handle popup messages in the msgDisplay div
    <?php if(isset($message)): ?>
    $(document).ready(function(){
        $("#msgDisplay").html("<?php echo $message; ?>");
        $("#msgDisplay").show();
        setTimeout(function() {
            $("#msgDisplay").addClass("faded-out");
        }, 5000);
    });
    <?php endif; ?>
</script>

