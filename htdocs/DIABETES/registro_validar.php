<?php
session_start();
require_once "login.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que los campos existen antes de usarlos
    if (!isset($_POST["nombre"], $_POST["apellidos"], $_POST["fechaNac"], $_POST["usuario"], $_POST["contrasena"], $_POST["contrasena_rep"])) {
        echo "<p class='text-danger'>Todos los campos son obligatorios.</p>";
        exit();
    }

    /* Obtener los valores del formulario */
    $nombre = trim($_POST["nombre"]); 
    $apellidos = trim($_POST["apellidos"]);
    $fechNac = trim($_POST["fechaNac"]);
    $usu = trim($_POST["usuario"]);
    $pass = trim($_POST["contrasena"]);
    $rep_password = trim($_POST["contrasena_rep"]);

    /* Conectar a la base de datos */
    $conexion = new mysqli($hostname, $usuario, $contrasena, $basedatos);
    
    if ($conexion->connect_error) {
        die("<p class='text-danger'>Error de conexión: " . $conexion->connect_error . "</p>");
    }

    /* Validar que las contraseñas coincidan */
    if ($pass !== $rep_password) {
        echo "<p class='text-danger'>Las contraseñas no coinciden.</p>";
        exit();
    }

    /* Verificar si el usuario ya existe */
    $consulta = $conexion->prepare("SELECT usuario FROM usuario WHERE usuario = ?");
    $consulta->bind_param("s", $usu);
    $consulta->execute();
    $result = $consulta->get_result();

    if ($result->num_rows > 0) {
        echo "<p class='text-danger'>El nombre de usuario ya está en uso.</p>";
        exit();
    }

    /* Validación de la fecha de nacimiento */
    $fechaFormateada = DateTime::createFromFormat('Y-m-d', $fechNac);
    if (!$fechaFormateada || $fechaFormateada->format('Y-m-d') !== $fechNac) {
        echo "<p class='text-danger'>La fecha de nacimiento no es válida.</p>";
        exit();
    }

    /* Encriptar la contraseña antes de guardarla */
    //$contra1 = password_hash($pass, PASSWORD_DEFAULT);

    /* Insertar el usuario en la base de datos con prepared statements */
    $consulta1 = $conexion->prepare("INSERT INTO usuario (fecha_nacimiento, nombre, apellidos, usuario, contra) VALUES (?, ?, ?, ?, ?)");
    $consulta1->bind_param("sssss", $fechNac, $nombre, $apellidos, $usu, $pass);

    if ($consulta1->execute()) {
        echo "<p class='text-success'>El registro se ha realizado correctamente.</p>";
        header("Location: inicioSesion.php");
        exit();
    } else {
        echo "<p class='text-danger'>Error al registrar el usuario.</p>";
    }

    /* Cerrar conexiones */
    $consulta->close();
    $consulta1->close();
    $conexion->close();
}
?>
