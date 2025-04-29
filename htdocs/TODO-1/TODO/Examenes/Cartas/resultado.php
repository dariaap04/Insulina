<?php
session_start();
include 'conexion.php'; // Archivo de conexión a la base de datos

// Recuperar datos enviados por el formulario
$pareja1 = (int)$_POST["pareja1"] - 1; // Posición ingresada por el usuario, ajustada al índice del array
$pareja2 = (int)$_POST["pareja2"] - 1;

// Validar que las posiciones son válidas
if ($pareja1 < 0 || $pareja2 < 0 || $pareja1 >= count($_SESSION["cartas"]) || $pareja2 >= count($_SESSION["cartas"])) {
    die("Error: posiciones inválidas.");
}

// Inicializar variables
$mensaje = "";
$detalle = "";
$puntos = 0;
$intentos = $_SESSION["contador"]; // Número de intentos

// Comprobar si las posiciones corresponden a una pareja
if ($_SESSION["cartas"][$pareja1] === $_SESSION["cartas"][$pareja2]) {
    $mensaje = "Acierto posiciones " . ($pareja1 + 1) . " y " . ($pareja2 + 1) . " después de $intentos intentos.";
    $detalle = "Se sumará 1 punto, así como $intentos intentos";
    $puntos = 1; // Acierto, sumar 1 punto
} else {
    $mensaje = "Fallo posiciones " . ($pareja1 + 1) . " y " . ($pareja2 + 1) . " después de $intentos intentos.";
    $detalle = "Se restará 1 punto, así como $intentos intentos";
    $puntos = -1; // Fallo, restar 1 punto
}

// Actualizar la base de datos
$usuario = $_SESSION["login"];
$extra = $intentos;

$sql = "UPDATE jugador SET puntos = puntos + ?, extra = extra + ? WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $puntos, $extra, $usuario);
$stmt->execute();

// Obtener la tabla de jugadores actualizada
$result = $conn->query("SELECT nombre, puntos, extra FROM jugador ORDER BY puntos DESC");
$jugadores = $result->fetch_all(MYSQLI_ASSOC);

// Cerrar conexión
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h1, p {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Bienvenid@, <?php echo htmlspecialchars($_SESSION["login"]); ?></h1>
    <p><?php echo $mensaje; ?></p>
    <p><?php echo $detalle; ?></p>
    <h2 style="text-align: center;">Puntos por jugador</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Puntos</th>
                <th>Extra</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jugadores as $jugador): ?>
                <tr>
                    <td><?php echo htmlspecialchars($jugador['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($jugador['puntos']); ?></td>
                    <td><?php echo htmlspecialchars($jugador['extra']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p style="text-align: center;"><a href="entrada.php">Volver al juego</a></p>
</body>
</html>
