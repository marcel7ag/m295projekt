<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styleIndex.css">
    <title>Login und Registration</title>
</head>
<body>
    <form method="post">
        <h1>Registrieren oder Login</h1>
        <div class="name-field">
            Name: <input type="text" name="username"><br><br>
        </div>
        <div class="password-field">
            Passwort: <input type="password" name="password" id="password"">
            <label>
                <input type="checkbox" id="showPassword" onclick="toggleVisibility()"><label for="showPassword"></label>
                Passwort anzeigen
            </label>
        </div>
        <br><br>
        <input type="submit" name="action" value="Login">
        <input type="submit" name="action" value="Registrieren">
    </form>
    <div id="windowpopup" class="windowpopup"></div>

    <?php
    require "conn.php";
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $action = $_POST['action'];

        if ($action == 'Login') {
            if (!isset($_SESSION['attempt'])) {
                $_SESSION['attempt'] = 0;
            }
            if ($_SESSION['attempt'] >= 3) {
                $message = "Sie haben schon 3 Fehlversuche erreicht. Ihr Konto ist gesperrt.";
                        echo '<script type="text/javascript">
                            $("#windowpopup").html("' . $message . '");
                            $("#windowpopup").show();
                            setTimeout(function() {
                                $("#windowpopup").addClass("faded-out");
                            }, 5000);
                        </script>';
            } else {
                $username = $_POST['username'];
                $password = $_POST['password'];

                        // Prepare a SELECT statement
                $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
                $stmt->bindValue(1, $username);
                // Execute the statement
                $stmt->execute();
                // Bind the result to variables
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Fetch the result
                if ($row) {
                    // Check if the password is correct
                    if (strcmp($password, $row['password']) == 0) {

                        // Assign the fetched ID to the session ID
                        $_SESSION["id"] = $row['id'];
                        $_SESSION["name"] = $username;

                        if($row['role'] = 'admin') {
                            header("Location: user.php");
                            exit();
                        }
                        $_SESSION["attempt"] = 0;
                    } else {
                        $_SESSION["attempt"]++;
                        $message = "Name oder Passwort ungültig.";
                        echo '<script type="text/javascript">
                            $("#windowpopup").html("' . $message . '");
                            $("#windowpopup").show();
                            setTimeout(function() {
                                $("#windowpopup").addClass("faded-out");
                            }, 5000);
                        </script>';
                    }
                }
                // Close the statement
                $stmt = null;
            }
        }
        elseif ($action == 'Registrieren') {

            $password = $_POST['password'];
            $username = $_POST['username'];

            if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{10,}$/", $password)) {
                $errorMessage = "Passwort ungültig.<br>Das Passwort muss mindestens 10 Zeichen lang sein, 1 Gross- und Kleinbuchstaben, sowie 1 Sonderzeichen und 1 Zahl beinhalten.";
                echo '<script type="text/javascript">
                    $("#windowpopup").html("' . $errorMessage . '");
                    $("#windowpopup").show();
                    setTimeout(function() {
                        $("#windowpopup").addClass("faded-out");
                    }, 5000);
                </script>';
                exit();
            }

            $stmt = $pdo->prepare("INSERT INTO users (username,password) VALUES (:username, :password)");
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', $password);
            $stmt->execute();            

            if ($stmt->rowCount() > 0) {
                $message = "Konto erfolgreich erstellt.";
                echo '<script type="text/javascript">
                    $("#windowpopup").html("' . $message . '");
                    $("#windowpopup").show();
                    setTimeout(function() {
                        $("#windowpopup").addClass("faded-out");
                    }, 5000);
                </script>';
            } else {
                echo "Error: " . $pdo->errorInfo();
            }      
        }
    }
?>


<script>
    //Funktion für die Sichtoption beim Passwort-Eingabefeld
    function toggleVisibility() {
        var passwordInput = document.getElementById("password");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "password";
        }
    }
    $(document).ready(function(){
        $(".windowpopup").click(function(){
            $(this).hide();
        });
    });
</script>
</body>
</html>