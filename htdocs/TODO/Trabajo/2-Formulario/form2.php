<?php
    // Inicializamos las variables
    $nombre = $email = $website = $comment = $gender = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitizamos los datos del formulario
        $nombre = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $website = htmlspecialchars($_POST["web"]);
        $comment = htmlspecialchars($_POST["comment"]);
        $gender = $_POST["gender"];

        // Validar nombre: solo letras y espacios
        if (!preg_match('/^[a-zA-Z\s]+$/', $nombre)) {
            $error .= "El nombre solo debe contener letras y espacios.<br>";
        }

        // Validar correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error .= "El correo electrónico no es válido.<br>";
        }

        // Validar si el sitio web es correcto (si es que se proporciona)
        if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
            $error .= "El sitio web no es válido.<br>";
        }

        // Validar campos obligatorios
        if (empty($nombre) || empty($email) || empty($gender)) {
            $error .= "Los campos con * son requeridos.<br>";
        }
    }

    // Mostrar formulario
    echo <<<END
        <form method="POST" action="">
            <label for="name">Nombre: *</label>
            <input type="text" id="name" name="name" value="$nombre" required><br><br>

            <label for="email">Correo electrónico: *</label>
            <input type="email" id="email" name="email" value="$email" required><br><br>

            <label for="web">Sitio web:</label>
            <input type="text" id="web" name="web" value="$website"><br><br>

            <label for="comment">Comentario:</label>
            <textarea id="comment" name="comment" rows="4" cols="50">$comment</textarea><br><br>

            <label for="gender">Sexo:  </label>
            <input type="radio" id="male" name="gender" value="male" required 
            <?php echo ($gender == 'male') ? 'checked' : ''; ?>>
            <label for="male">Masculino</label>
            <input type="radio" id="female" name="gender" value="female" required 
            <?php echo ($gender == 'female') ? 'checked' : ''; ?>>
            <label for="female">Femenino</label><br><br>

            <input type="submit" name="Enviar" value="Enviar">
        </form>
    END;

    // Si existen errores, los mostramos
    if (!empty($error)) {
        echo "<p style='color:red;'>$error</p>";
    }

    // Mostrar los datos introducidos si no hay errores
    if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)) {
        echo "<h3>Datos introducidos:</h3>";
        echo "Nombre: $nombre <br>";
        echo "Correo electrónico: $email <br>";
       /* echo "Sitio web: $website <br>";
        echo "Comentario: $comment <br>";
        echo "Sexo: $gender <br>";*/
    }
?>
