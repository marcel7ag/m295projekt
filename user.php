<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Willkommen</title>
</head>
<?php
    session_start();
?>
<body>
        <h1 id="welcome"><?php echo "Willkommen " . $_SESSION["name"]; ?></h1>
</body>
</html>