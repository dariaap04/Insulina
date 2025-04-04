<?php 
    session_start(); 
    $usuario = $_SESSION["usuario"];
            include "../ejercicio2/pintar_circulos.php"; 
            $colores = ["red", "blue", "yellow", "green"]; 
            $circulos=["black", "black", "black", "black"]; 
            pintarCirculos($circulos[array_rand($circulos)] ,$circulos[array_rand($circulos)], $circulos[array_rand($circulos)], $circulos[array_rand($circulos)]);
        
         

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>SIMÓN</h1>
    <h3><?php echo htmlspecialchars($usuario)?> pulsa los botones en el orden correspondiente</h3>
    <form action="">
        
        <button type="submit" name="color" value="red" style="background-color: red;">Rojo</button>
        <button type="submit" name="color" value="blue" style="background-color: blue;">Azul</button>
        <button type="submit" name="color" value="yellow" style="background-color: yellow;">Amarillo</button>
        <button type="submit" name="color" value="green" style="background-color: green;">Verde</button>
    </form>
</body>
</html>