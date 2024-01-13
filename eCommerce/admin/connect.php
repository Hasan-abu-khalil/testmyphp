<?php

$dsn = 'mysql:host=localhost; dbname=shop';
$user = 'root';
$pass = '';
$option = array(
    PDO::MYSQL_ATTR_COMPRESS => 'SET NAME utf8',
);

try {
    $con = new PDO($dsn, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '';

} catch (PDOException $e) {
    echo 'failed to connect' . $e->getMessage();
}


?>

