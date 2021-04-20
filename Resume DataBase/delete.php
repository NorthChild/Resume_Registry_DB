<?php

session_start();
require_once "pdo.php";

// if we attempt to access add.php without loggin in
if (! isset($_SESSION['name'])) {

  die("ACCESS DENIED");
}

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


## if we decide to cancel and go back

if (isset($_POST['cancel'])) {
  header('Location: index.php');
}

## if we decide to delete the profile

if ( (isset($_POST['delete'])) && (isset($_SESSION['name'])) ) {


  $stmt = $pdo->prepare("DELETE FROM Profile WHERE profile_id = :profile_id");
  $stmt->execute(array(":profile_id" => $_GET['profile_id']));

  $_SESSION['success'] = 'Profile deleted';
  header('Location: index.php');
  return;

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

    <h1>Deleting Profile</h1>

    <table>
 <tr>
   <th>First name</th> <td><?= $FN ?></td>
 </tr>
 <tr>
   <th>Last name</th> <td><?= $LN ?></td>
 </tr>
 </tr>
    </table>

    <br>

    <form method="post">
      <input type="submit" name="delete" value="Delete">
      <input type="submit" name="cancel" value="Cancel">
    </form>

  </body>
</html>
