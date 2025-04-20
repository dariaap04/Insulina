<?php
session_start(); 

$usuario = $_SESSION["usuario"]; 
if(!$usuario){
    header("Location: entrada.php"); 
    exit; 
}

/* Si no existe session de combinacion */
if(!(isset($_SESSION["combinacion"]))){
    $cartas = ["copas_02.jpg", "copas_02.jpg",
                "copas_03.jpg", "copas_03.jpg",
                "copas_05.jpg", "copas_05.jpg"];
    shuffle($cartas); 
    shuffle($cartas);
                
    $_SESSION["combinacion"] =$cartas;  
    
}
$combinacion = $_SESSION["combinacion"];
var_dump($combinacion);



if(!isset($_SESSION["contador"])){
    $_SESSION["contador"]=0;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .img{
            height: 180px;
            width: 130px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <h1>Bienvenido <?php echo $usuario?></h1>
    <h3>Cartas Levantadas : <?php echo $_SESSION["contador"]?></h3>
    <form method="post" action="">
        <button type="submit" name="levantadas" value="0">Levantar carta 1</button>
        <button type="submit" name="levantadas" value="1">Levantar carta 2</button>
        <button type="submit"name="levantadas" value="2">Levantar carta 3</button>
        <button type="submit"name="levantadas" value="3">Levantar carta 4</button>
        <button type="submit"name="levantadas" value="4">Levantar carta 5</button>
        <button type="submit"name="levantadas" value="5">Levantar carta 6</button>
    </form>
    <hr>
    <form action="comprobar2.php" method="post">
        <h2>Pareja
            <input min="1" max="6" name="pos1">
            <input min="1" max="6" name="pos2">
            <button type="submit">Comprobar</button>
        </h2>
    </form>
    <?php
    if(isset($_POST["levantadas"])){
        $_SESSION["contador"]++;
        $posicion = $_POST["levantadas"];
        for($i=0; $i<count($combinacion); $i++){
            if($i == $posicion){
                echo "<img class='img' src='" . $combinacion[$i] . "' alt='imagen'>";
            } else {
                echo "<img class='img' src='boca_abajo.jpg' alt='bocaabajo'>";
            }
        }
    }
?>
</body>
</html>