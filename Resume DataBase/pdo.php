<?php

// this file is in a separate file from the actual file that required the variable $pdo, this is the connection to the server, and it will be saved for this example in a separate file called 'pdo.php'
$pdo = new PDO ('mysql:host=localhost;port=number;dbname=name','humanName','password');

$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
