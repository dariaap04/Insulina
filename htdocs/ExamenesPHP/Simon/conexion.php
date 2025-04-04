<?php
    $servename = "localhost"; 
    $username = "root"; 
    $pw = ""; 
    $database = "simon"; 
    
    $conexion = new mysqli($servename, $username, $pw, $database); 

    if($conexion->connect_error){
        die("conexion fallida a la base de datos".$conexion->connect_error);
    }else{
        echo "conexion existosa";
    }

?>