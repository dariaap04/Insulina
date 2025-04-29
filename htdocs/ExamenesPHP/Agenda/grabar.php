<?php
require_once "conexion.php";
    session_start();
    $usuario = $_SESSION["usuario"];
    $contador = $_SESSION["contador"];

    $conexion = new mysqli($localhost, $username, $pw, $database);

    if($conexion->connect_error){
        die("Error de conexion");
    }


    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(isset($_POST["nombre"])&& isset($_POST["email"]) && isset($_POST["telefono"])){
            $nombre = $_POST["nombre"]; 
            $email = $_POST["email"]; 
            $telefono = $_POST["telefono"]; 

           /* Preparar la consulta */
           /* BUSCAMOS EL CODCONTACTO */
           $sql = $conexion->prepare("SELECT Codigo FROM usuarios WHERE Nombre = ?");
            $sql->bind_param("s", $usuario); 
            $sql->execute();
            $result = $sql->get_result(); 
            if($result->num_rows>0){
                $row = $result->fetch_assoc();
                $codusuario = $row["Codigo"];
                $sql = $conexion->prepare("INSERT IGNORE INTO contactos (nombre, email, telefono, codusuario) VALUES
                    (?, ?, ?, ?)"); 
                $sql->bind_param("sssi", $nombre, $email, $telefono, $codusuario);  
                $sql->execute();
                echo "usuario grabado";  
            }else{
                echo "usuario no encontrado";
            }
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
    <p>Se han grabado <?php echo $contador?> contactos de <?php echo $usuario?></p>
    <a href="index.php">Volver a loguearse</a>
    <a href="inicio.php">Introducir más contactos para <?php echo $usuario?></a>
    <a href="totales.php">Total de contactos guardados</a>
</body>
</html>