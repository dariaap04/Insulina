<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Vamos a jugar al Simón</h1>
        <form method ="POST" action="jugar.php">
        <?php
            $colores = ["red", "blue","yellow", "green"];
            include "pintarcirculos.php";
            pintarCirculos($colores[array_rand($colores)], $colores[array_rand($colores)],$colores[array_rand($colores)],$colores[array_rand($colores)]);
        ?>
            <input type="submit" value="Jugar" name="jugar" ><br>
        </form>
        
</body>
</html>