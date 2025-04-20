
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="inicioSesion.php">
        <label for="usuario">Usuario</label>
        <input type="text" name="usuario">
        <label for="contraseña">Contraseña</label>
        <input type="password" name="contrasenia">
        <button type="submit" name="enviar">Enviar</button>
    </form>    
</body>
</html>

<?php
session_start();
    $usuario = "admin"; 
    $contra = "1234"; 
    if(!isset($_SESSION["intentos"])){
        $_SESSION["intentos"] =0; 
    }
    /* Aqui falta si se envia el formulario hacer
        usuario ingresado es igual al name de usuario 
        contraseña ingresada es igual al name de contrasenia

        y meter dentro el condicional if que tienes debajo. 
    
    */

    if(isset($_POST["enviar"])){
        $usuario_ingresado=$_POST["usuario"]; 
        $contra_ingresada=$_POST["contrasenia"]; 

        if ($_SESSION["intentos"] <= 3){
            var_dump(($_SESSION["intentos"]));
            if($usuario ==  $usuario_ingresado && $contra ==  $contra_ingresada){
                echo "Acceso concedido";
                $_SESSION["intentos"] = 0;
            }else{
                $_SESSION["intentos"]++;
                if( $_SESSION["intentos"] ==3){
                    echo "cuenta bloqueada";
                }
               
            }
        }
    }




   

?>