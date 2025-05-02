<?php
    session_start(); 
    require_once "conexion.php"; 

    $login = $_SESSION["usuario"]; 

    $conexion = new mysqli($localhost, $usuario, $pw, $database); 
    if($conexion->connect_error){
        die("error de conexion"); 
    }

    
    $rol = $conexion->prepare("SELECT rol FROM usuarios WHERE usuario = ?"); 
    $rol->bind_param("s", $login); 
    $rol->execute(); 
    $result = $rol-> get_result(); 

    if($result -> num_rows>0){
       $fila = $result->fetch_assoc();
       $acceso = $fila["rol"]; 
    }else{
        echo "usuario no encontrado"; 
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
    <h1>Bienvenido, <?php echo $login?></h1>
    <?php 
        if($acceso === "alumno"):
    ?>
    <p>Tu perfil es de alumno</p>
    <form action="" method="post">
        <button type="submit"><a href="resultado_alumno.php">Ver mis notas</a></button>
        <button type="submit"><a href="index.php">Cerrar Sesión</a></button>
    </form>
    <?php elseif($acceso ==="director"):?>
        <p>Tu perfil es de director</p> 
        <form action="" method="post">
        <button type="submit"><a href="insertar_notas.php">Insertar Nota</a></button>
            <button type="submit"><a href="motrar_notas.php">Mostrar Notas</a></button>
            <button type="submit"><a href="index.php">Cerrar Sesión</a></button>
        </form>
    <?php endif;?>    
</body>
</html>