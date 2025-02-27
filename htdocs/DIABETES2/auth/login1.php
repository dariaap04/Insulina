<?php
// Variables de conexión
$servername = "fdb1028.awardspace.net"; // Host de la Base de Datos
$username = "4596854_diabetesdb"; // Usuario de la Base de Datos
$password = "D%j}9C2G7[B2BGaa"; // Contraseña de la Base de Datos (¡Cambia esto!)
$dbname = "4596854_diabetesdb"; // Nombre de la Base de Datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conexión exitosa";

// ... tu código para interactuar con la base de datos ...

$conn->close(); // Cerrar la conexión cuando hayas terminado
?>