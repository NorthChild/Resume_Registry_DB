<?php

session_start();
require_once "pdo.php";

// if we attempt to access add.php without loggin in
if (! isset($_SESSION['name'])) {

  die("ACCESS DENIED");
}


// in case user wants to cancel the Action

if ( isset($_POST['cancel'])) {

  unset($_SESSION['error']);
  header("Location: index.php");
  return;
}

// check to see if form data is present

if ( (! isset($_POST['first_name'])) || (! isset($_POST['last_name'])) || (! isset($_POST['email'])) || (! isset($_POST['headline'])) || (! isset($_POST['summary'])) ) {


  // if the form data isnt set
  // we do not display anything and we unset any error message
  // unset($_SESSION['error']);

} else {

  // POST to SESSION into variables
  $userId = $_SESSION['user_id'];
  $firstName = $_POST['first_name'];
  $lastName = $_POST['last_name'];
  $email = $_POST['email'];
  $headline = $_POST['headline'];
  $summary = $_POST['summary'];

  // lets validate the form fields
  // email validation
  $emailCheck = strpos($email, '@');

  // fields ! empty
  if ((strlen($firstName) < 1) || (strlen($lastName) < 1) || (strlen($email) < 1) || (strlen($headline) < 1) || (strlen($summary) < 1)) {

    $_SESSION['error'] = "All fields are required";
    header("Location: add.php");
    return;

  } elseif ((strlen($firstName) > 1) && (strlen($lastName) > 1) && (strlen($email) > 1) && (strlen($headline) > 1) && (strlen($summary) > 1) && ($emailCheck != true)) {

    $_SESSION['error'] = "Email address must contain @";
    header("Location: add.php");
    return;

  } else {

    // success
    $_SESSION['first_name'] = htmlentities($_POST['first_name']);
    $_SESSION['last_name'] = htmlentities($_POST['last_name']);
    $_SESSION['email'] = htmlentities($_POST['email']);
    $_SESSION['headline'] = htmlentities($_POST['headline']);
    $_SESSION['summary'] = htmlentities($_POST['summary']);



    // we insert this if all data is valid
    $stmt = $pdo->prepare('INSERT INTO Profile
      (user_id, first_name, last_name, email, headline, summary)
      VALUES ( :uid, :fn, :ln, :em, :he, :su)');

    $stmt->execute(array(
      ':uid' => $_SESSION['user_id'],
      ':fn' => $_SESSION['first_name'],
      ':ln' => $_SESSION['last_name'],
      ':em' => $_SESSION['email'],
      ':he' => $_SESSION['headline'],
      ':su' => $_SESSION['summary'])
    );

    header("Location: index.php");
    $_SESSION['success'] = "Profile added";
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

<div class="container">

<h1>Adding Profile for <?= htmlentities($_SESSION['name'])  ?></h1>


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
<input type="text" name="first_name" size="40"/></p>
<p>Last Name:  <br>
<input type="text" name="last_name" size="40"/></p>
<p>Email:  <br>
<input type="text" name="email" size="40"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="40"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>

</form>

</div>

  </body>
</html>
