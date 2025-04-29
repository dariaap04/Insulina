<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <form method="POST" action="logueo.php">
        <label for="usuario">Usuario</label><br>
        <input type="text" id="usuario" name="usuario" required><br>
        <label for="contrasena">Contraseña</label><br>
        <input type="password" id="contrasena" name="contrasena" required><br>
        <input type="submit" value="Iniciar Sesión" name="enviar">
    </form>
</body>
</html>