<?php


session_start();
require_once "pdo.php";

###################################### MODEL ######################################

// variables
$email = '';
$userPass = '';
$message = false;
$emailCheck = false;

$salt = 'XyZzy12*_';

################################ Validation #####################################

if (isset($_POST['email']) && isset($_POST['pass'])) {

  # insert POST DATA into Session variables
  $email = $_POST['email'];
  $_SESSION['email'] = $email;

  $userPass = $_POST['pass'];
  $_SESSION['pass'] = $userPass;

  ## here we check for the email validation ##
  $emailCheck = strpos($email, '@');

################################ Validation #####################################

    // # validation of the data using nested series of if/elseif statements
    // if (strlen($email) < 1 || strlen($userPass) < 1) {
    //   $_SESSION['error'] = 'User Name and Password are required.';
    //
    // } elseif (($passCheck === $stored_hash) && ($emailCheck === false)) {
    //
    //   error_log("Login fail ".$_POST['email']);
    //   $_SESSION["error"] = "Email must have an at-sign (@)";
    //   header("Location: login.php");
    //   return;
    //
    // } elseif (($passCheck === $stored_hash) && ($emailCheck != false)) {
    //
    //   header("Location: index.php?email=".urlencode($email));
    //   error_log("Login success ".$_POST['email']);
    //   return;
    // }


  ## here we cross reference the password stored in the DB
  ## we gather the data
  $check = hash('md5', $salt.$_POST['pass']);
  $stmt = $pdo -> prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');

  ## we display the data
  $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  ## we check to see if the data maches the DB
  if ( $row !== false ) {

         $_SESSION['name'] = $row['name'];
         $_SESSION['user_id'] = $row['user_id'];

         // Redirect the browser to index.php
         header("Location: index.php");
         return;

       } else {

         // redirect with error message and no row display
         $_SESSION["error"] = "User or Password incorrect, try again.";
         header("Location: login.php");
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

    <h1>Please Log In</h1>

    <p>
      <?php

      // here we set the error flash message
      if (isset($_SESSION["error"])) {
        echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
        unset($_SESSION["error"]);
      }

      ?>
    </p>


    <form class="" method="post">
      <label> <b>User Name</b><input type="text" name="email" id="email"> </label> <br>
      <label> <b>Password</b> <input type="password" name="pass" id="id_1723"> </label> <br>
      <br>
      <input id="loginBt" type="submit" onclick="return doValidate();" value="Log In">
      <a href="index.php">Cancel</a>
    </form>

    <p id="hint">
      For a password hint, view source and find an account and password hint
      in the HTML comments.
      <!-- Hint:
      The password is the three character name of the
      programming language used in this class (all lower case)
      followed by 123. -->
    </p>


<script>

// this function validates user input, by sending an alert message the execution is alted, returning false will also prevent the data to be sent as POST data

function doValidate() {
    console.log('Validating...');

    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('id_1723').value;

        console.log("Validating addr="+addr+" pw="+pw);

        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        console.log("Validation failed");
        return false;
    }
    return false;
}
</script>


  </body>
</html>
