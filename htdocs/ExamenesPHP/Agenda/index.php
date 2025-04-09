<?php
    session_start(); 

    function myConexion(){
        include "conexion.php";
        $conexion = new mysqli($localhost, $username, $pw, $database);
        if($conexion->connect_error){
            die("Conexion fallida");
        }
        return $conexion; 
    }
    $conectada = myConexion();

    function iniciarSesion($conectada){
        if($_SERVER["REQUEST_METHOD"]== "POST"){
            if(!empty($_POST["usuario"]) && !empty($_POST["contra"])){
                $usuario=$_POST["usuario"];
                $contra = $_POST["contra"]; 

                $sql = $conectada->prepare("SELECT * FROM usuarios WHERE Nombre = ? AND Clave =?");
                $sql->bind_param("ss", $usuario, $contra); 
                $sql->execute();
                $result = $sql->get_result();
                if($result->num_rows>0){
                    echo"Has iniciado sesion correctamente";
                    $_SESSION["usuario"] = $usuario;
                    header("Location: inicio.php"); 
                    exit; 
                }else{
                    echo "Credencianles incorrectas"; 
                    header("Location: index.php");
                    exit; 
                }
            }
        }
    }
    $sesion=iniciarSesion($conectada);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Agenda de Contactos</h2>
    <form method="POST" action="<?php $sesion ?>">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required><br>
    
        <label for="clave">Contraseña:</label>
        <input type="password" id="contra" name="contra" required><br>

        <input type="submit" value="Entrar" name="entrar">
    </form>
</body>
</html>