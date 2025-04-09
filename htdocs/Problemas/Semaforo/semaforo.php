<?php
 
function cambiar_color($col1="grey", $mensaje = "", $col2="grey", $col3="grey"){
    echo "<div style='background-color: $col1; width:100px; height:100px; border-radius:50%; margin:20px auto;'></div>";
    echo "<div style='background-color: $col2; width:100px; height:100px; border-radius:50%; margin:20px auto;'></div>";
    echo "<div style='background-color: $col3; width:100px; height:100px; border-radius:50%; margin:20px auto;'></div>";
    if($mensaje) {
        echo "<h2 style='text-align:center;'>$mensaje</h2>";
    }
}

/* metemos en un array los 3 colores */
$colores = ["rojo", "amarillo", "verde"]; 
$mensajes = ["¡Detente!", "¡Precaución!", "¡Avanza!"];
$color_css = ["red", "yellow", "green"];

$color_circulo1 = "grey";
$color_circulo2="grey"; 
$color_circulo3="grey"; 
$mensaje = "";

if(isset($_POST["buton"])){
    $boton = $_POST["buton"];
    if($boton == "rojo") {
        $color_circulo1 = "red";    // Primer círculo rojo
        $color_circulo2 = "grey";  // Segundo círculo verde
        $color_circulo3 = "grey"; // Tercer círculo amarillo
        $mensaje = "¡Has pulsado ROJO!";
    }
    else if($boton == "amarillo") {
        $color_circulo1 = "grey";
        $color_circulo2 = "yellow";
        $color_circulo3 = "grey";
        $mensaje = "¡Has pulsado AMARILLO!";
    }
    else if($boton == "verde") {
        $color_circulo1 = "grey";
        $color_circulo2 = "grey";
        $color_circulo3 = "green";
        $mensaje = "¡Has pulsado VERDE!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semáforo Interactivo</title>
    <style>
       /*  body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        button {
            padding: 10px 15px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        } */
    </style>
</head>
<body>
    <h1>Semáforo Interactivo</h1>
    <p>Haz clic en un botón para cambiar el color del semáforo</p>
    
    <?php cambiar_color($color_circulo1, $mensaje, $color_circulo2, $color_circulo3); ?>
    
    <div>
        <form action="semaforo.php" method="POST">
            <button type="submit" name="buton" value="rojo" style="background-color:red; color:white;">ROJO</button>
            <button type="submit" name="buton" value="amarillo" style="background-color:yellow;">AMARILLO</button>
            <button type="submit" name="buton" value="verde" style="background-color:green; color:white;">VERDE</button>
        </form>
    </div>
</body>
</html>