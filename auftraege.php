<!--------->
<!--TO DO-->
<!--------->
<?php
require 'conn.php';
session_start();

$query = "SELECT * FROM orders";
$stmt = $pdo->prepare($query);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo '<table border="0" cellspacing="2" cellpadding="2"> 
          <tr> 
              <td>ID</td>
              <td>KundenID</td>  
              <td>KundenName</td> 
              <td>Reparatur</td> 
              <td>Sanitär</td>
              <td>Heizung</td>
              <td>Garantie</td>
              <td>Zustand</td>
              <td>Auftragdetails</td>
          </tr>';

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $orderID = $row["orderID"];
        $kundenID = $row["kundenID"];
        $kundenName = $row["kundenName"];
        $reparatur = $row["reparatur"];
        $sanitaer = $row["sanitaer"];
        $heizung = $row["heizung"];
        $garantie = $row["garantie"];
        $zustand = $row["zustand"];

        echo '<tr> 
                 <td>'.$orderID.'</td> 
                 <td>'.$kundenID.'</td> 
                 <td>'.$kundenName.'</td> 
                 <td>'.$reparatur.'</td>
                 <td>'.$sanitaer.'</td>
                 <td>'.$heizung.'</td>
                 <td>'.$garantie.'</td>
                 <td>'.$zustand.'</td>
                 <td><button id="'.$orderID.'">Details</button></td> 
              </tr>';
    }
} 
?>
