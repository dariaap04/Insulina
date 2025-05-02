<?php
    session_start(); 
    require_once "conexion.php"; 

    $conexion = new mysqli($localhost, $usuario, $pw, $database); 
    if($conexion->connect_error){
        die("error de conexion"); 
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(isset($_POST["enviar"]) && isset($_POST["login"]) && isset($_POST["clave"])){
            $login =  $_POST["login"];
            $clave = $_POST["clave"];
            
            $buscar = $conexion->prepare("SELECT * FROM usuarios 
            WHERE usuario = ? AND password = ?"); 

            $buscar->bind_param("ss", $login, $clave); 
            $buscar->execute(); 
            $result = $buscar-> get_result(); 

            if($result -> num_rows>0){
                echo " Has iniciado sesion correctamente..."; 
                $_SESSION["usuario"] = $login; 
                header("Location: inicio.php"); 
                exit();
            }else{
                $_SESSION["error"] = 1; 
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
    <form action="" method="post">
        <label for="usuario">Usuario</label>
        <input type="text" name="login">
        <label for="contra">Contraseña</label>
        <input type="password" name="clave">
        <button type="submit" name="enviar">Entrar</button>
    </form>
    <?php 
        if(isset($_SESSION["error"])) {
            echo"<p>Error de credenciales</p>";
            unset($_SESSION["error"]); // Importante para que no se quede mostrando siempre
        } 
    ?>
</body>
</html>