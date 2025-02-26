<?php

session_start();
require 'conexion.php';

if(!isset($_SESSION['login'])){
	header('Location: index.php');
	exit();

}

$fechaActual = date("Y-m-d");

//Contar numero de aciertos
$sqlAciertos = "SELECT COUNT(*) as total FROM respuestas WHERE resultado = 1 AND DATE (fecha)= ?";
$stmt= $conn -> prepare($sqlAciertos);
$stmt-> bind_param("s", $fechaActual);
$stmt-> execute();
$result = $stmt -> get_result();
$aciertos = $result-> fetch_assoc()['total'];
$stmt->close();

//Nombre y hora de los aciertos
$sqlDetallesAciertos = "SELECT login, DATE_FORMAT(fecha,'%H:%i:%s') as hora FROM respuestas WHERE resultado = 1 AND DATE (fecha)= ?";
$stmt= $conn -> prepare($sqlDetallesAciertos);
$stmt-> bind_param("s", $fechaActual);
$stmt-> execute();
$detallesAciertos = $stmt -> get_result();

//Nombre y hora de los fallos
$sqlFallos = "SELECT login, DATE_FORMAT(fecha,'%H:%i:%s') as hora FROM respuestas WHERE resultado = 1 AND DATE (fecha)= ?";
$stmtFallados= $conn -> prepare($sqlFallos);
$stmtFallados-> bind_param("s", $fechaActual);
$stmtFallados-> execute();
$detallesFallados = $stmtFallados -> get_result();

//Sumar un punto a cada jugador
$sqlSumarPuntos = "UPDATE jugador SET puntos = puntos +1 WHERE login IN (SELECT login FROM respuestas WHERE resultado = 1 AND DATE(fecha)=?)";
$stmt= $conn -> prepare($sqlSumarPuntos);
$stmt-> bind_param("s", $fechaActual);
$stmt-> execute();
$stmt->close();


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resulatdos del dia</title>
</head>
<body>

<h1> Resultados del dia</h1>
<p>Fecha: <?php echo $fechaActual;?></P>
<p>Jugadores que han acertado: <?php echo $aciertos;?></p>

<ul>
    <?php while($fila = $detallesAciertos -> fetch_assoc()): ?>
    <li><?php htmlspecialchars($fila['login']). " - ".$fila['hora'];?></li>
    <?php endwhile;?>
</ul>

<h2>Jugadores fallados</h2>

<ul>
<?php while($fila = $detallesFallados -> fetch_assoc()): ?>
    <li><?php htmlspecialchars($fila['login']). " - ".$fila['hora'];?></li>
    <?php endwhile;?>
</ul>
</body>
</html>
    
