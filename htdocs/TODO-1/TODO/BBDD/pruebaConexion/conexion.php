<?php
    require_once 'login.php';
    $conn = new mysqli($hostname, $usser, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: ". $conn->connect_error);
    }else{
        echo "Connected successfully";
    }

?>