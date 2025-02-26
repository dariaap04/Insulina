<?php
session_start();
require('conexion.php');



function autenticarUsuario($login, $clave) {
    global $conn;

    // Evitar inyección SQL usando consultas preparadas
    $stmt = $conn->prepare("SELECT * FROM jugador WHERE login=? AND clave=?");
    $stmt->bind_param("ss", $login, $clave);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontró un usuario
    if ($result->num_rows > 0) {
        // Usuario autenticado
        $_SESSION['login'] = $login;
        header('Location: inicio.php');
    } else {
        // Usuario no autenticado
        echo "Login o clave incorrectos intentar de nuevo";
    }
    // Cerrar la consulta y la conexión
    $stmt->close();
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $login = $_POST['login'];
    $clave = $_POST['clave'];

    if(autenticarUsuario($login, $clave)){
        $_SESSION['login']=$login;
        header('Location: inicio.php');
        exit();
    }else{
        echo "Usuario o contraseña incorrectos. Por favor, intentalo de nuevo.";
    }
}
    $conn->close();
?>