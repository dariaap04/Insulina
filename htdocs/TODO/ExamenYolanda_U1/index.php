<?php

session_start();
require 'conexion.php';

/*if(!isset($_SESSION['login'])){
header('Location: index.php');
exit();
}*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesion</title>
</head>
<body>
<h1>Inicia Sesion</h1>
<form action="validar_login.php" method="POST">
    <label for="login">Login: </label>
    <input type="text" id="login" name="login" required>
    <br>
    <label for="clave">Clave: </label>
    <input type="password" id="clave" name="clave" required>
    <br>
    <button type= "submit">Entrar</button>
    
  </form>
    
</body>
</html>


