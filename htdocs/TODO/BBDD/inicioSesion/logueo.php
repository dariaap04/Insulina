<?php 
    session_start();
    require_once "login1.php";
   
   
    if(isset($_POST['enviar'])){
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        $conn = new mysqli($hostname, $usser, $password, $database);
       if($conn -> connect_error){
            die ("Error connecting");
       }else{
        $sql = "SELECT usu, pass FROM usuarios 
            WHERE usu = '$usuario' AND pass = '$contrasena'";
            $result = $conn->query($sql);
            $uValido = $result ->fetch_assoc()['usu'];
            $result -> data_seek(0); /**/
            $pValido = $result ->fetch_assoc()['pass'];
            if($uValido == $usuario && $pValido == $contrasena){
                echo "Has iniciado correctamente";
                
            }else{
                echo "Usuario o contraseña incorrectos";
            }
            $conn->close();
            }
    }
?>