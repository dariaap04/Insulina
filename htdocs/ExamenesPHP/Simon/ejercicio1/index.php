<?php
    session_start(); 

    function myConexion(){
        require_once "../conexion.php";
        $conexion = new mysqli($servename, $username, $pw, $database);
        if($conexion->connect_error){
            die("error a la conexion de base de datos");
        }
        return $conexion;
    }

    $conectada = myConexion();

    function iniciarSesion($conectada){
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            if(!empty($_POST["usuario"]) && !empty($_POST["contra"])){

                $usuario = $_POST["usuario"]; 
                $contra = $_POST["contra"]; 
                
                /* Hacer una consulta para vereficar estos parametros */
                $sql = $conectada->prepare("SELECT * FROM usuarios WHERE Nombre = ? AND Clave = ?");
                $sql->bind_param("ss", $usuario, $contra);  
                $sql->execute(); 
                $result = $sql->get_result();
                if($result->num_rows>0){
                    echo" Has iniciado sesion correctamente";
                    $_SESSION["usuario"] = $usuario; 
                    header("Location: jugar.php"); 
                }else{
                    echo "Usuario o contraseña incorrectos";
                }
            }
        }
    }

    $sesion = iniciarSesion($conectada);

?>

<!-- Vamos hacer aqui el formulario... -->
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
 </head>
 <body>
    <h1>¡¡VAMOS A JUGAR AL SIMON!!</h1>
    <form method="POST" action="<?php $sesion ?>">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required><br>
    
        <label for="clave">Contraseña:</label>
        <input type="password" id="contra" name="contra" required><br>

        <input type="submit" value="Entrar" name="entrar">
    </form>
 </body>
 </html>