<?php
    $hostname = "localhost";
    $usuario = "root";
    $contrasena = "";
    $basedatos = "diabetesdb";
    $conexion = new mysqli($hostname, $usuario, $contrasena, $basedatos);
    if($conexion -> connect_error){
        die("Error de conexión: ". $conexion -> connect_error);
    }else{
        //echo "Conexión exitosa";
    }

?>