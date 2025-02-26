<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva tu Mesa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Haz tu reserva</h2>
    <form action="procesar_reserva.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono">

        <label for="fecha">Fecha de la reserva:</label>
        <input type="date" id="fecha" name="fecha" required>

        <label for="hora">Hora:</label>
        <input type="time" id="hora" name="hora" required>

        <label for="personas">Número de personas:</label>
        <input type="number" id="personas" name="personas" min="1" required>

        <button type="submit">Reservar</button>
    </form>
</body>
</html>
