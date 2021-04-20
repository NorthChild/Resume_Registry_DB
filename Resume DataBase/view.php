<?php

session_start();
require_once "pdo.php";

// transfer the GET data for the SQL search
$profile_id = $_GET['profile_id'];


// here we gather from the DB all the data relevant to the requested element to be viewed

$stmt = $pdo -> prepare('SELECT * FROM Profile WHERE profile_id LIKE :userId');
$stmt->execute(array(':userId' => $_GET['profile_id']));

## we display the data

// here we create the table for the DB
while ( $row = $stmt -> fetch(PDO::FETCH_ASSOC)) {

  $FN = $_SESSION['firstName'] = $row['first_name'];
  $LN = $_SESSION['lastName'] = $row['last_name'];
  $EM = $_SESSION['email'] = $row['email'];
  $HD = $_SESSION['headline'] = $row['headline'];
  $SUM = $_SESSION['summary'] = $row['summary'];
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Assignment - Michael John Carini</title>

    <link rel="stylesheet" href="CSS/assignmentpt2.css">
  </head>
  <body>

    <h1>Profile Information</h1>

    <table>
 <tr>
   <th>First name</th> <td><?= $FN ?></td>
 </tr>
 <tr>
   <th>Last name</th> <td><?= $LN ?></td>
 </tr>
 <tr>
   <th>Email</th> <td><?= $EM ?></td>
 </tr>
 <tr>
   <th>Headline</th> <td><?= $HD ?></td>
 </tr>
 <tr>
   <th>Summary</th> <td><?= $SUM ?></td>
 </tr>
    </table>


<a href="index.php">DONE</a>



  </body>
</html>
