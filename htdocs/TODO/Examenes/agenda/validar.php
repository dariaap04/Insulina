<?php
    session_start();
    $_SESSION["error"] =0;
    require_once "login.php"; 
    if(isset($_POST['enviar'])){
        $usuario = $_POST["usu"];
        $pass = $_POST["pass"];
        $conexion = new mysqli($localhost, $username, $pw, $database);
        if($conexion -> connect_error){
            die("Error connecting to database");
        }else{
            $consulta = "SELECT Nombre, Clave 
                        FROM usuarios
                        WHERE Nombre = '$usuario' AND Clave = '$pass'";
            $mostrar = $conexion ->query($consulta);
            if($mostrar ->num_rows> 0){
                $_SESSION["usu"] = $usuario;
                header("Location: inicio.php");
                echo "Has iniciado sesion";
                die();
            }else{

                $_SESSION["error"] =1;
                header("Location: index.php");
            }
            $conexion -> close();
        }
    }
?>