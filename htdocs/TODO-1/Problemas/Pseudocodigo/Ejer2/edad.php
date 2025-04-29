<?php
session_start(); 
/* if(!isset($_SESSION["edad"])){
    $_SESSION["edad"];
} */

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST["enviar"])){
        if(isset($_POST["edad"])>=1 && $_POST["edad"] <=120){
            $_SESSION["edad"] = $_POST["edad"]; 
            if($_POST["edad"] <1 && $_POST["edad"] >120){
                echo "error, inserte la edad correspondida"; 
                $_SESSION["edad"];
            }
        }
        $_SESSION["edad"];
        echo "Edad registrada: ".$_SESSION['edad']." años";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Edad: 1 y 120</h1>
    <form method="post" action="edad.php">
        <label for="edad">Introduce la edad</label>
        <input type="number" name="edad" min="1" max="120" required>
        <button type="submit" name="enviar">Enviar</button>
    </form>
</body>
</html>