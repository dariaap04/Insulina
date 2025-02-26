<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";  // Cambia esto si usas otro usuario
$password = "";  // Si tienes contraseña, agrégala aquí
$database = "reservas_db";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$personas = $_POST['personas'];

// Insertar la reserva en la base de datos
$sql = "INSERT INTO reservas (nombre_cliente, email_cliente, telefono_cliente, fecha, hora, personas)
        VALUES ('$nombre', '$email', '$telefono', '$fecha', '$hora', '$personas')";

if ($conn->query($sql) === TRUE) {
    echo "Reserva enviada con éxito. Nos pondremos en contacto contigo.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar conexión
$conn->close();
?>
