<?php

session_start();
require_once "pdo.php";

unset($_SESSION['name']);
unset($_SESSION['user_id']);
header('Location: index.php');

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Assignment - Michael John Carini</title>
  </head>
  <body>

    <p>work in progress</p>

  </body>
</html>
