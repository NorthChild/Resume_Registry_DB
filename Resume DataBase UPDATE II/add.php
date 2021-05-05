<?php

require_once "pdo.php";
require "utilities.php";

session_start();

// if we attempt to access add.php without loggin in
if (! isset($_SESSION['user_id'])) {
  die("ACCESS DENIED");
}

// in case user wants to cancel the Action
if ( isset($_POST['cancel'])) {
  unset($_SESSION['error']);
  header("Location: index.php");
  return;
}

// ---------------------------------------------------------------------------------------------------------
//                                                                                                          |
//                                            POST DATA IS PRESENT                                          |
//                                                                                                          |

// check to see if form data is present
if ( (isset($_POST['first_name'])) && (isset($_POST['last_name'])) && (isset($_POST['email'])) && (isset($_POST['headline'])) && (isset($_POST['summary'])) ) {

  // validate input data
  $msg = validateProfile();
  if ( is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header("Location: add.php");
    return;
  }

  // validate pos input data
  $msg = validatePos();
  if ( is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header("Location: add.php");
    return;
  }

  // success
  // we insert this if all data is valid
  $stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)');

  $stmt->execute(array(
    ':uid' => htmlentities($_SESSION['user_id']),
    ':fn' => htmlentities($_POST['first_name']),
    ':ln' => htmlentities($_POST['last_name']),
    ':em' => htmlentities($_POST['email']),
    ':he' => htmlentities($_POST['headline']),
    ':su' => htmlentities($_POST['summary']))
  );

  $profile_id = $pdo->lastInsertId();

  $rank = 1;

  for($i=1; $i<=9; $i++) {

    if ( ! isset($_POST['year'.$i]) ) continue;
    if ( ! isset($_POST['desc'.$i]) ) continue;

    $year = htmlentities($_POST['year'.$i]);
    $desc = htmlentities($_POST['desc'.$i]);


  $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');

  $stmt->execute(array(
    ':pid' => $profile_id,
    ':rank' => $rank,
    ':year' => $year,
    ':desc' => $desc)
  );

  $rank++;
  // error_log("- rank count ".$rank.PHP_EOL, 3, "Errors/errorLog.log");
}


  header("Location: index.php");
  $_SESSION['success'] = "Profile added";
  return;

} else {
  // no data found, nothing happens
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

<div class="container">

<h1>Adding Profile for <?= htmlentities($_SESSION['name'])  ?></h1>

    <p>
      <?php
      flashMessages();
      ?>
    </p>


<form method="post">

<p>First Name: </p> <br>
<input type="text" name="first_name" size="40"/></p>
<p>Last Name:</p>  <br>
<input type="text" name="last_name" size="40"/></p>
<p>Email:</p>  <br>
<input type="text" name="email" size="40"/></p>
<p>Headline:</p> <br/>
<input type="text" name="headline" size="40"/></p>
<p>Summary: </p> <br/>
<textarea name="summary" rows="8" cols="80"></textarea>

<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
</div>
</p>

<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>

</form>

<script type="text/javascript">

countPos = 0;

$(document).ready(function(){

    window.console && console.log('Document ready called');

    $('#addPos').click(function(event){

        event.preventDefault();

        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;

        window.console && console.log("Adding position "+countPos);

        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove(); countPos --; return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
});

</script>

</div>

  </body>
</html>
