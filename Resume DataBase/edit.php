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

// if we want to cancel the Action and return to index

if (isset($_POST['cancel'])) {
  header("Location: index.php");
  return;

}

// POST data validation for the editing

if (! isset($_POST['save'])) {


  // if POST data is NOT present
  // we do not display anything and we unset any error message


} else {

  // if POST data is present

  $PostFN = $_POST['first_name'];
  $PostLN = $_POST['last_name'];
  $PostEM = $_POST['email'];
  $PostHD = $_POST['headline'];
  $PostSUM = $_POST['summary'];


  // lets validate the form fields

  // email validation
  $emailCheck = strpos($PostEM, '@');

  // fields ! empty

    if ((strlen($PostFN) < 1) || (strlen($PostLN) < 1) || (strlen($PostEM) < 1) || (strlen($PostHD) < 1) || (strlen($PostSUM) < 1)) {

      $_SESSION['error'] = "All fields are required";
      header('Location: edit.php?profile_id='.$profile_id.'');
      return;

    } elseif ((strlen($PostFN) > 1) && (strlen($PostLN) > 1) && (strlen($PostEM) > 1) && (strlen($PostHD) > 1) && (strlen($PostSUM) > 1) && ($emailCheck != true)) {

      $_SESSION['error'] = "Email address must contain @";
      header('Location: edit.php?profile_id='.$profile_id.'');
      return;

    } else {

    // success
    $finFN = $_SESSION['first_name'] = htmlentities($_POST['first_name']);
    $finLN = $_SESSION['last_name'] = htmlentities($_POST['last_name']);
    $finEM = $_SESSION['email'] = htmlentities($_POST['email']);
    $finHD = $_SESSION['headline'] = htmlentities($_POST['headline']);
    $finSUM = $_SESSION['summary'] = htmlentities($_POST['summary']);


    // we insert this if all data is valid
    $sql = "UPDATE Profile SET first_name = :first_name, last_name = :last_name, email = :email, headline = :headline, summary = :summary WHERE profile_id = :profile_id";
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute(array(
      ':first_name' => $finFN,
      ':last_name' => $finLN,
      ':email' => $finEM,
      ':headline' => $finHD,
      ':summary' => $finSUM,
      ':profile_id' => $profile_id,
    ));

    header("Location: index.php");
    $_SESSION['success'] = "Profile updated";
    return;

  }

}



?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Assignment - Michael John Carini</title>

    <link rel="stylesheet" href="CSS/assignment.css">
  </head>
  <body>

    <h1>Editing Profile for <?= htmlentities($_SESSION['name'])  ?></h1>

    <p>
      <?php

      // here we set the error flash message
      if (isset($_SESSION["error"])) {
        echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
        unset($_SESSION["error"]);
      }

      if (isset($_SESSION["success"])) {
        echo('<p style = "color:green">').htmlentities($_SESSION["success"])."</p>\n";
        unset($_SESSION["success"]);
      }

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
    <input type="submit" name="save" value="Save">
    <input type="submit" name="cancel" value="Cancel">
    </p>

    </form>

  </body>
</html>
