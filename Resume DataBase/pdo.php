<?php

// this file is in a separate file from the actual file that required the variable $pdo, this is the connection to the server, and it will be saved for this example in a separate file called 'pdo.php'
$pdo = new PDO ('mysql:host=localhost;port=8888;dbname=assignment','snow','933');

$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
