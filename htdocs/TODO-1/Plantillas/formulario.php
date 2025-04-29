
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar sesión</title>
    </head>
    <body>
    
        <h2>Iniciar sesión</h2>
    
        <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
    
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="login">Usuario:</label>
            <input type="text" id="login" name="login" required><br>
    
            <label for="clave">Contraseña:</label>
            <input type="password" id="clave" name="clave" required><br>
    
            <input type="submit" value="Entrar">
        </form>
    
    </body>
    </html>

