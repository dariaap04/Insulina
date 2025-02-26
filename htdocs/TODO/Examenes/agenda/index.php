<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>GENDA DE CONTACTOS</h1>
    <form method="POST" action="validar.php">
        <?php
        session_start();
            if(isset($_SESSION["error"])){
                if($_SESSION["error"]=1){
                    echo "<span style='color:red;'>Usuario o contraseña incorrectos</span><br>";
                }
            }
        ?>
        <label for="usuario">Usuario:</label><br>
        <input type="text" name="usu"/><br>
        <label for="password">Contraseña:</label><br>
        <input type="password" name="pass"/><br>
        <input type="submit" name="enviar" value="Enviar"/>
    </form>
</body>
</html>