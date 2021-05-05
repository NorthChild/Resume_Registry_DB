<?php

session_start();
require_once "pdo.php";
require "utilities.php";

// if we attempt to access add.php without loggin in
if (! isset($_SESSION['user_id'])) {
  die("ACCESS DENIED");
}

// if we want to cancel the Action and return to index
if (isset($_POST['cancel'])) {
  header("Location: index.php");
  return;

}

// transfer the GET data for the SQL search while checking if get data is present
if (! isset($_REQUEST['profile_id'])) {

  $_SESSION['error'] = 'Missing profile_id';
  header("Location: index.php");

} else {
  $profile_id = $_REQUEST['profile_id'];
}


// load profile
$stmt = $pdo -> prepare( 'SELECT * FROM Profile WHERE profile_id = :prof AND user_id = :uid');
$stmt -> execute ( array (
  ':prof' => $_REQUEST['profile_id'],
  ':uid' => $_SESSION['user_id'] ));

$profile = $stmt -> fetch (PDO::FETCH_ASSOC);

if ($profile === false ) {

  $_SESSION['error'] = 'Could not load profile';
  header("Location: index.php");
  return;
}

##       we display the data      ##

$stmtI = $pdo -> prepare('SELECT * FROM Profile WHERE profile_id LIKE :userId');
$stmtI->execute(array(':userId' => $_GET['profile_id']));

// here we create the table for the DB

while ( $row = $stmtI -> fetch(PDO::FETCH_ASSOC)) {

  $FN = $_SESSION['firstName'] = $row['first_name'];
  $LN = $_SESSION['lastName'] = $row['last_name'];
  $EM = $_SESSION['email'] = $row['email'];
  $HD = $_SESSION['headline'] = $row['headline'];
  $SUM = $_SESSION['summary'] = $row['summary'];
}




// ---------------------------------------------------------------------------------------------------------
//                                                                                                          |
//                                            POST DATA IS PRESENT                                          |
//                                                                                                          |



if ( (isset($_POST['first_name'])) && (isset($_POST['last_name'])) && (isset($_POST['email'])) && (isset($_POST['headline'])) && (isset($_POST['summary'])) ) {

  // validate input data
  $msg = validateProfile();
  if ( is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header('Location: edit.php?profile_id='.$profile_id.'');
    return;
  }

  // validate pos input data
  $msg = validatePos();
  if ( is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header('Location: edit.php?profile_id='.$profile_id.'');
    return;
  }

  // success
  $finFN = $_SESSION['first_name'] = htmlentities($_POST['first_name']);
  $finLN = $_SESSION['last_name'] = htmlentities($_POST['last_name']);
  $finEM = $_SESSION['email'] = htmlentities($_POST['email']);
  $finHD = $_SESSION['headline'] = htmlentities($_POST['headline']);
  $finSUM = $_SESSION['summary'] = htmlentities($_POST['summary']);


  // we insert this if all data is valid
  $sql = ("UPDATE Profile SET first_name = :first_name, last_name = :last_name, email = :email, headline = :headline, summary = :summary WHERE profile_id = :profile_id");
  $stmt = $pdo -> prepare($sql);
  $stmt -> execute(array(
    ':first_name' => $finFN,
    ':last_name' => $finLN,
    ':email' => $finEM,
    ':headline' => $finHD,
    ':summary' => $finSUM,
    ':profile_id' => $profile_id,
  ));

  $_SESSION['success'] = "Profile updated";
  header("Location: index.php");
  return;

}

//                                                                                                          |
//                                            POST DATA IS PRESENT                                          |
//                                                                                                          |
// ---------------------------------------------------------------------------------------------------------


?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Assignment - Michael John Carini</title>

    <link rel="stylesheet" href="CSS/assignment.css">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

  </head>
  <body>

    <h1>Editing Profile for <?= htmlentities($_SESSION['name']);  ?></h1>

    <p>
      <?php
      flashMessages();
      ?>
    </p>

    <form method="post">

    <p>First Name:  <br>
    <input type="text" name="first_name" size="40" value="<?= $FN ?>"/></p>
    <p>Last Name:  <br>
    <input type="text" name="last_name" size="40" value="<?= $LN ?>"/></p>
    <p>Email:  <br>
    <input type="text" name="email" size="40" value="<?= $EM ?>"/></p>
    <p>Headline:<br/>
    <input type="text" name="headline" size="40" value="<?= $HD ?>"/></p>
    <p>Summary:<br/>
    <textarea name="summary" rows="8" cols="80"><?= $SUM ?></textarea>

    <p>
    Position: <input type="submit" id="addPos" value="+">
    <div id="position_fields">
    </div>
    </p>

    <p>
    <input type="submit" name="save" value="Save">
    <input type="submit" name="cancel" value="Cancel">
    </p>

    </form>

  </body>
</html>
