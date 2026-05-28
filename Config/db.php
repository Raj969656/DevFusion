<?php

$host = "127.0.0.1";
$user = "root";
$pass = "";
$dbname = "auth_system";
$port = 3307;

$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $dbname,
    $port
);

if(!$conn){

    die(
        "Database Connection Failed: "
        . mysqli_connect_error()
    );

}

?>