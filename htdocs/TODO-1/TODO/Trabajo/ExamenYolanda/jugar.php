<?php
    session_start();
    $colores = ["red", "blue", "yellow", "green"];
    $circulos = ["black", "black", "black", "black"]; // Todos los círculos son inicialmente negros
    // Si se ha enviado un color y está en el array $colores, cambia el color de cada circulo cuando le das al boton
    if (isset($_POST["jugar"])) {
        $_SESSION['count'] =0; 
        for($i=1; $i<=count($colores); $i++) {
            $_SESSION['aciertos'.$i] = $_POST["color".$i];
            $_SESSION['res'.$i]="black";
        }
    }else{
        $_SESSION['count']++;
        $_SESSION['res'.$_SESSION['count']]=$_GET['color'];
    }
    function accion() {
        if ($_SESSION['count'] == 4) {  // Cuando haya 4 respuestas
            $correcto = true;
    
            // Recorrer cada círculo y verificar si coincide con el color objetivo
            for ($i = 1; $i <= 4; $i++) {
                if ($_SESSION['res'.$i] != $_SESSION['count'.$i]) {
                    $correcto = false; // Si alguna respuesta es incorrecta, marcar como falso
                   break;
            }
    
            if ($correcto) {
                echo "¡Has acertado la combinación!";
                // Opcionalmente, puedes reiniciar el juego redirigiendo a index.php
            } else {
                echo "¡Has perdido! Inténtalo de nuevo.";
                // Opcionalmente, puedes reiniciar el juego redirigiendo a index.php
               // header("Location: index.php");
                exit;
            }
        }
    }
}
    
    // Llama a la función para comprobar el estado del juego
    accion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de Colores</title>
</head>
<body>
    <h1>Elige un color para cambiar los circulos:</h1>

    <!-- Formulario para seleccionar el color -->
    <form method="GET" action="jugar.php">
        <button type="submit" name="color" value="red" style="background-color: red;">Rojo</button>
        <button type="submit" name="color" value="blue" style="background-color: blue;">Azul</button>
        <button type="submit" name="color" value="yellow" style="background-color: yellow;">Amarillo</button>
        <button type="submit" name="color" value="green" style="background-color: green;">Verde</button>
    </form>

    <!-- Mostrar los círculos con el color actualizado -->
    <div>
        <?php
            // Generar los cuatro círculos con sus colores actuales
            include "pintarcirculos.php";
            pintarCirculos($_SESSION["res1"],$_SESSION["res2"],$_SESSION["res3"],$_SESSION["res4"]);
        ?>
    </div>
</body>
</html>
