<?php
    session_start(); 
    require_once "login.php"; 

        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar'])){
            if(empty($_POST["usu"]) || empty($_POST["pass"])){
                $_SESSION["error"] = 1;
                header("Location: login.php");
                exit();
            }

            $usu = $_POST["usu"]; 
            $pass = $_POST["pass"];

            $con = new mysqli($hostname, $usuario, $contrasena, $basedatos);
            if($con->connect_error){
                die("Error: ". $con->connect_error);
            }

            $consult = $con -> prepare("SELECT usuario, contra FROM usuario WHERE usuario = ?");
            $consult -> bind_param("s", $usu);
            $consult -> execute();
            $result = $consult -> get_result();

            if($result -> num_rows > 0){
                $datos = $result->fetch_assoc();

                if($pass == $datos['contra']){
                    $_SESSION["usu"] = $usu; 
                    header("Location: inicio.php");
                    exit();
                }else{
                    $_SESSION["error"] = 1;
                    header("Location: inicioSesion.php");
                    exit();
                }
            }else{
                $_SESSION["error"] = 1;
                header("Location: inicioSesion.php");
                exit();
            }
            $consult -> close();
            $con -> close();


        }

?>