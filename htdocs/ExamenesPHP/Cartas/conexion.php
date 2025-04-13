<?php
    $localhost = "localhost:3306";
    $username ="root";
    $pw = "";
    $database="cartas";

    $conexion = new mysqli($localhost, $username, $pw, $database);

    if($conexion->connect_error){
        die("Error de conexion"); 
    }else{
        echo "Conexion existosa"; 
    }

?>
