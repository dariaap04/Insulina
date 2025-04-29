<?php
    if($_SERVER["REQUEST_METHOD"] =="POST"){
        $nombre = htmlspecialchars($_POST["nombre"]);
        $correo = htmlspecialchars($_POST["correo"]);
        $contraseña = $_POST["contraseña"];
        $confirmarContraseña = $_POST["confirmarContraseña"];

            if(empty($nombre) || empty($correo) || empty($contraseña)||empty($confirmarContraseña)){
                echo "Por favor, completa todos los campos.";
            }else if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
                echo "Correo electrónico invalido.";
            }else if($contraseña !== $confirmarContraseña){
                echo "Las contraseñas no coinciden";
            }else{
                echo"Registro existoso <br>";
                echo "Nombre de Usuario: ".$nombre."<br>";
                echo "Correo Electrónico: ".$correo."<br>";
            }
    }else{
        echo '<h1>Registro</h1>
            <form method="post" action="registro.php">
                <label for="Nombre de Usuario">Nombre de Usuario:</label>
                <input type="text" id="nombre" name="nombre" required><br><br>
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required><br><br>
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required><br><br>
                <label for="confirmación de contraseña">Confirmar contraseña:</label>
                <input type="password" id="confirmarContraseña" name="confirmarContraseña" required><br><br>
                <input type="submit" value="Enviar">
            </form>';
    }
?>