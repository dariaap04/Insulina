<?php
    $localhost = "localhost:3306"; 
    $usuario = "root"; 
    $pw = ""; 
    $database = "notas"; 

    $conexion = new mysqli($localhost, $usuario, $pw, $database); 

    if($conexion->connect_error){
        die("Error de conexion");
    }else{
        echo "conexion existosa"; 
    }

?>