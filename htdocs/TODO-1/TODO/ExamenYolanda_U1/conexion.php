<?php

$host = 'localhost:3306';
$user= 'root';
$password= '';
$dbname= 'bdjero';

$conn = new mysqli($host, $user, $password, $dbname);

if($conn -> connect_error){
	die("Error de conexion: ".$conn-> connect_error);
}
?>