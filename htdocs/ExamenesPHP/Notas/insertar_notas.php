<?php
    session_start(); 
    require_once "conexion.php"; 
    
    $login = $_SESSION["usuario"]; 
    $conexion = new mysqli($localhost, $usuario, $pw, $database); 
    if($conexion->connect_error){
        die("error de conexion"); 
    }

    if($_SERVER["REQUEST_METHOD"] =="POST"){
        if(isset($_POST["insertar"]) && isset($_POST["id_alumno"]) && isset($_POST["asignatura"]) && isset($_POST["nota"])){
            /* buscar en la tabla la existencia de tal id */
            $buscar = $conexion->prepare("SELECT alumno");


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
    <h1>INSERTAR NOTA</h1>
    <form action="" method="post">
        <label for="id_alumno">ID del Alumno</label>
        <input type="text" name="id_alumno" required>
        <label for="asignatura">Asignatura</label>
        <input type="text" name="asignatura" required>
        <label for="nota">Nota (0-10)</label>
        <input type="number" min="0" max="10" name="nota" required>
        <button type="submit" name="insertar">Insertar Nota</button>
    </form>
</body>
</html>