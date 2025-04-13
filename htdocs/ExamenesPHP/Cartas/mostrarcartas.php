<?php
session_start(); 

$usuario = $_SESSION["usuario"]; 

if(isset($_POST["carta"])){
   if($_SESSION["contador"]>=0){
    $_SESSION["contador"]++; 
   }; 
    
}
if (isset($_POST['reiniciar'])) {
    // Restablecer las variables de sesión a su estado inicial
    $_SESSION["contador"] = 0;
    header("Location: ".$_SERVER['PHP_SELF']); // Recargar la página
    exit;
}

/* meter en un array las cartas */
$cartas = ["copas_02.jpg", "copas_02.jpg",
            "copas_03.jpg", "copas_03.jpg", 
            "copas_05.jpg", "copas_05.jpg"]; 
 shuffle($cartas); 

 for($i=0; $i<count($cartas); $i++){
    echo "<img src='$cartas[$i]'>";
 }
 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        img{
            width: 130px;
            height: 180px;
            margin: 5px;
        }
    </style>
</head>
<body>
   <h1>Bienvenid@, <?php echo $usuario?>.</h1>
   <h2>Cartas Levantadas: <?php echo $_SESSION["contador"]?></h2>
   <form action="" method="post">
        <button type="submit" name="carta">Levantar carta 1</button>
        <button type="submit" name="carta">Levantar carta 2</button>
        <button type="submit" name="carta">Levantar carta 3</button>
        <button type="submit" name="carta">Levantar carta 4</button>
        <button type="submit" name="carta">Levantar carta 5</button>
        <button type="submit" name="carta">Levantar carta 6</button>

        <h3>Pareja: </h3>
        <input type="text">
        <input type="text">
        <button type="submit" name="comprobar">Comprobar</button>
        <button type="submit" name="reiniciar">Reiniciar</button>
   </form> 
   <?php
    $cartasNegras = ["boca_abajo.jpg", "boca_abajo.jpg", "boca_abajo.jpg", 
                    "boca_abajo.jpg", "boca_abajo.jpg", "boca_abajo.jpg"];
    for($i=0; $i<count($cartasNegras); $i++){
         echo "<img src='{$cartasNegras[$i]}'>";
    }

   ?>
</body>
</html>