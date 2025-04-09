<?php
    session_start(); 
    $usuario = $_SESSION["usuario"];

    function myConexion(){
        require_once "../conexion.php";
        $conexion = new mysqli($servename, $username, $pw, $database);
        if($conexion->connect_error){
            die("error a la conexion de base de datos");
        }
        return $conexion;
    }

    $conectada = myConexion();

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
    <h3>Hola <?php echo htmlspecialchars($usuario) ?>, memoriza la combinación</h3>
    <form method="post" action="../ejercicio3/jugar.php">
        <?php
            include "pintar_circulos.php"; 
            $colores = ["red", "blue", "yellow", "green"]; 
            $combinacion = [
                $colores[array_rand($colores)],
                $colores[array_rand($colores)], 
                $colores[array_rand($colores)], 
                $colores[array_rand($colores)]
                
            ];
            pintarCirculos($combinacion[0], $combinacion[1], $combinacion[2], $combinacion[3]);
            $_SESSION["combinacion"] = $combinacion;
            var_dump($_SESSION["combinacion"]);
           
        ?>
        <br>
        <input type="submit" value="jugar" name="jugar"><br>
    </form>
   
    

</body>
</html>