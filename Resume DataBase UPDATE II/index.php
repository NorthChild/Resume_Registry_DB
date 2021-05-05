<?php



session_start();
require_once "pdo.php";

// here we check if theres any data in the DB to be displayed

try {

 $sql = "SELECT * FROM Profile";
 $stmt = $pdo -> prepare($sql);
 $stmt -> execute(array());

} catch (Exception $e) {

}

//
// $sql = "SELECT * FROM Profile ORDER BY user_id";
// $stmt = $pdo -> prepare($sql);
// $stmt -> execute(array());

// here we check if table is empty or not
$count = $stmt -> rowCount();

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Assignment - Michael John Carini</title>

    <link rel="stylesheet" href="CSS/assignment.css">
  </head>
  <body>

    <h1>Michael Carini's Resume Registry</h1>

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

    <?php

    // if NOT logged in

    if (! isset($_SESSION['name'])) {

      echo('<a href="login.php">Please log in</a>'."<br>");

      // row display of profiles (if $row etc)

      if ($count === 0) {

        // table is empty

      } else {

        // here we create the table for the DB
        echo('<table border="1">'."\n");
        echo('

          <tr>
            <th> Name </th>
            <th> Headline </th>
          </tr>

          ');

        while ( $row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
          echo("<tr><td>");
          echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'])." ".htmlentities($row['last_name']).'</a>');
          echo("</td><td>");
          echo(htmlentities($row['headline']));

        }
        echo('</table>'."<br>");
      }

    } else {

      // if logged in

      // send user to logout
      echo('<a href="logout.php">Logout</a>'."<br>");

      if ($count === 0) {


        // table is empty

      } else {

        // here we create the table for the DB
        echo('<table border="1">'."\n");
        echo("<br>");
        echo('

          <tr>
            <th> Name </th>
            <th> Headline </th>
            <th> Action </th>
          </tr>

          ');

          // ORIGINAL
        // while ( $row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        //   echo("<tr><td>");
        //   echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'])." ".htmlentities($row['last_name']).'</a>');
        //   echo("</td><td>");
        //   echo(htmlentities($row['headline']));
        //   echo("</td><td>");
        //   echo("<a href='edit.php'>Edit</a>"." "."<a href='delete.php'>Delete</a>");
        //   // hidden user id
        //   echo("<input type='hidden' name='user_id' value='".$_SESSION['user_id']."'>");
          // ORIGINAL

          while ( $row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            echo("<tr><td>");
            echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'])." ".htmlentities($row['last_name']).'</a>');
            echo("</td><td>");
            echo(htmlentities($row['headline']));
            echo("</td><td>");
            echo('<a href = "edit.php?profile_id='.$row['profile_id'].'">Edit</a>'.'<a href = "delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
            // hidden user id
            echo("<input type='hidden' name='user_id' value='".$_SESSION['user_id']."'>");

        }
        echo('</table>'."<br>");
        echo("<br>");
      }

      // send user to add
      echo('<a href="add.php">Add New Entry</a>');

    }

    ?>



  </body>
</html>
