<?php
session_start();
$usuario = $_SESSION["usuario"];

function myConexion(){
    include "conexion.php";
    $conexion = new mysqli($localhost, $username, $pw, $database);
    if($conexion->connect_error){
        die("Conexion fallida");
    }
    return $conexion; 
}
$conectada = myConexion();

function typeForm(){
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if($_POST["incrementar"]){
            $incrementar = $_POST["incrementar"]; 
            $_SESSION["contador"] =0; 
            
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>AGENDA</h1>
    <table style="border: 1px;">
        <th style="border: 1px;">
            <ul>
                <li>Hola, <?php echo $usuario?> ¿cuantos contactos deseas guardar?</li>
                <li>Puedes grabar entre 1 y 5. Por cada pulsación en INCREMENTAR grabarás un usuario más</li>
                <li>Cuando el numero sea el deseado pulsa GRABAR</li>
            </ul>
        </th>
    </table>
    <form method="post" action="">
        <button type="submit" name="incrementar">INCREMENTAR</button>
        <button type="submit" name="grabar"> GRABAR</button>
    </form>
</body>
</html>