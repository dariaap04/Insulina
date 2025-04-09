<?php
    session_start(); 
    include "../ejercicio2/pintar_circulos.php";
    $usuario=$_SESSION["usuario"];    
    $combinacion = $_SESSION["combinacion"]; 

        if(!isset($_SESSION["circulos"]) ){
            $_SESSION["circulos"] = ["black", "black", "black", "black"];
        }
    
        if(!isset($_SESSION["contador"])){
            $_SESSION["contador"]=0; 
        }
        if (isset($_POST['reiniciar'])) {
            // Restablecer las variables de sesión a su estado inicial
            $_SESSION["circulos"] = ["black", "black", "black", "black"];
            $_SESSION["contador"] = 0;
            header("Location: ".$_SERVER['PHP_SELF']); // Recargar la página
            exit;
        }

        $circulos = $_SESSION["circulos"];
        $contador = $_SESSION["contador"];
        var_dump($contador);
        if(isset($_POST["color"])){
            $color = $_POST["color"]; 
            $circulos[$contador] = $color;
            $_SESSION["contador"]++;
       
            $_SESSION["circulos"] = $circulos;
            
            if($contador >=3){
                if($circulos === $combinacion){
                    header("Location: aciertos.php");
                    exit;
                }else{
                    header("Location: fallos.php");
                    exit;
                }
            }
        }
        //session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Simon</h2>
    <h3>Hola, <?php echo $usuario ?>. Pulsa los botones en el orden correspondiente </h3>
    <div>
        <?php
            pintarCirculos($circulos[0], $circulos[1], $circulos[2], $circulos[3]);

        ?>
    </div>
    <div>
        <form method="POST" action="">
            <button type="submit" name="color" value= "red" style="background-color: red;">ROJO</button>
            <button type="submit" name="color" value= "blue" style="background-color: blue;">AZUL</button>
            <button type="submit" name="color" value= "yellow" style="background-color: yellow;">AMARILLO</button>
            <button type="submit" name="color" value= "green" style="background-color: green;">VERDE</button>
        </form>
    </div>
    <form method="POST" action="">
        <button type="submit" name="reiniciar">Reiniciar Juego</button>
    </form>
</body> 
</html>