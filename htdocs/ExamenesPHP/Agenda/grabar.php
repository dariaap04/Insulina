<?php
    session_start();
    $usuario = $_SESSION["usuario"];
    $contador = $_SESSION["contador"];

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(isset($_POST["nombre"])&& isset($_POST["email"]) && isset($_POST["telefono"])){
            $nombre = $_POST["nombre"]; 
            $email = $_POST["email"]; 
            $telefono = $_POST["telefono"]; 

            
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
    <h1>AGENDA</h1>
    <h3>Hola <?php echo $usuario?></h3>
</body>
</html>