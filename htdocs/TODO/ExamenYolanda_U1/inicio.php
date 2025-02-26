<?php

session_start();
require 'conexion.php';

/*if(!isset($_SESSION['login'])){
header('Location: index.php');
exit();

}*/
$usuario = $_SESSION['login'];

$jeroglifico= '20241212.jpg';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>

<body>
<h1>Bienvenido, <?php echo htmlspecialchars($usuario);?></h1>

<div> 
    <img src="<?php echo $jeroglifico; ?>" alt="Jeroglificodel dia" style="mas-width: 300px; max-height: 300px;">
    </div>

<form action = "guardar_respuesta.php" method='POST'>
    <label for= "solucion">Solucion al jeroglifico:</label>
    <input type="text" id="solucion" name="solucion" required>
    <br>
    <button type="submit">Enviar</button>
    </form>

    <div style = "margin-top: 20px;">
        <a href="puntos.php">Ver puntos por jugador(no esta hecho)</a>
        <br>
        <a href="resultado.php">Resultados del dia</a>
    </div>
</body>
</html>