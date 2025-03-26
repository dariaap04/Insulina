<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";  
$password = "";  
$database = "reservas_db";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener todas las reservas
$sql = "SELECT * FROM reservas ORDER BY creada_en DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Reservas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Panel de Administración de Reservas</h2>
    <table style="border:1px">
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Personas</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['nombre_cliente']}</td> 
                        <td>{$row['email_cliente']}</td>
                        <td>{$row['telefono_cliente']}</td>
                        <td>{$row['fecha']}</td>
                        <td>{$row['hora']}</td>
                        <td>{$row['personas']}</td>
                        <td>{$row['estado']}</td>
                        <td>
                            <form action='gestionar_reservas.php' method='POST'>
                                <input type='hidden' name='nombreCliente' value='{$row['nombre_cliente']}'> 
                                <input type='hidden' name='emailCliente' value='{$row['email_cliente']}'> <!-- Se pasa el email -->
                                <input type='hidden' name='id' value='{$row['id']}'> 
                                <input type='hidden' name='accion' value='aceptar'>
                                <button type='submit'>Aceptar</button>
                            </form>
                            <form action='gestionar_reservas.php' method='POST'>
                                <input type='hidden' name='nombreCliente' value='{$row['nombre_cliente']}'> 
                                <input type='hidden' name='emailCliente' value='{$row['email_cliente']}'> <!-- Se pasa el email -->
                                <input type='hidden' name='id' value='{$row['id']}'> 
                                <input type='hidden' name='accion' value='rechazar'>
                                <button type='submit'>Rechazar</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No hay reservas pendientes.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
