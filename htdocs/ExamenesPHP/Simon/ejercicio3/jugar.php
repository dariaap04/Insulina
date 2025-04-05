<?php
session_start();
include "../ejercicio2/pintar_circulos.php"; // Incluimos la función de pintar círculos

// Si el usuario no está logueado, redirigir a index.php
/* if (!isset($_SESSION['usuario'])) {
    header("Location: ../ejercicio1/index.php");
    exit();
} */

// Si no existe la combinación generada, redirigir a inicio.php
/* if (!isset($_SESSION['combinacion'])) {
    header("Location: ../ejercicio2/inicio.php");
    exit();
} */

// Inicializar la jugada del usuario si no existe
if (!isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = array();
}

// Si se ha pulsado un color
if (isset($_GET['color'])) {
    $color = $_GET['color'];
    // Añadir el color a la jugada del usuario
    $_SESSION['usuario'][] = $color;
    
    // Si ya se han realizado las 4 pulsaciones, comprobar si es acierto o fallo
    if (count($_SESSION['usuario']) == 4) {
        // Comprobar si la jugada coincide con la combinación
        if ($_SESSION['usuario'] == $_SESSION['combinacion']) {
            header("Location: acierto.php");
        } else {
            header("Location: fallo.php");
        }
        exit();
    }
    
    // Redireccionar para evitar reenvíos de formulario
    header("Location: jugar.php");
    exit();
}

// Obtener los colores de la jugada actual para pintarlos
$colores = array('black', 'black', 'black', 'black');
for ($i = 0; $i < count($_SESSION['usuario']); $i++) {
    $colores[$i] = $_SESSION['usuario'][$i];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Juego Simon</title>
    <style>
        .boton-color {
            padding: 10px 20px;
            margin: 10px;
            font-weight: bold;
            cursor: pointer;
        }
        .contenedor {
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Juego Simon - Usuario: <?php echo $_SESSION['usuario']; ?></h2>
        
        <?php
        // Pintamos los círculos según el estado actual de la jugada
        pintarCirculos($colores[0], $colores[1], $colores[2], $colores[3]);
        ?>
        
        <div>
            <p>Selecciona los colores en orden:</p>
            <a href="jugar.php?color=red" class="boton-color" style="background-color: red; color: white;">ROJO</a>
            <a href="jugar.php?color=blue" class="boton-color" style="background-color: blue; color: white;">AZUL</a>
            <a href="jugar.php?color=yellow" class="boton-color" style="background-color: yellow; color: black;">AMARILLO</a>
            <a href="jugar.php?color=green" class="boton-color" style="background-color: green; color: white;">VERDE</a>
        </div>
        
        <div>
            <p>Llevas <?php echo count($_SESSION['jugada_usuario']); ?> de 4 colores seleccionados</p>
        </div>
    </div>
</body>
</html>