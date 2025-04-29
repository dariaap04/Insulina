<?php
// Inicio de sesión para acceder a la lista de emojis y al usuario logueado
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php"); // Redirige al inicio si no hay usuario logueado
    exit();
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "agenda");
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Inicializar variables
$emoji_list = $_SESSION['emoji_list'] ?? [];
$error = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valid = true;
    $contactos = [];

    foreach ($emoji_list as $index => $emoji) {
        $nombre = trim($_POST["nombre_$index"] ?? "");
        $email = trim($_POST["email_$index"] ?? "");
        $telefono = trim($_POST["telefono_$index"] ?? "");

        if (empty($nombre) || empty($email) || empty($telefono)) {
            $valid = false;
            $error = "Todos los campos deben estar rellenos.";
            break;
        }

        $contactos[] = [
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono
        ];
    }

    if ($valid) {
        // Insertar contactos en la base de datos
        foreach ($contactos as $contacto) {
            $stmt = $conn->prepare("INSERT INTO contactos (nombre, email, telefono) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $contacto['nombre'], $contacto['email'], $contacto['telefono']);

            if (!$stmt->execute()) {
                $error = "Error al guardar los datos: " . $stmt->error;
                break;
            }
        }

        if (empty($error)) {
            $_SESSION['emoji_list'] = []; // Limpiar la lista de emojis
            header("Location: confirmacion.php"); // Redirige a una pantalla de confirmación
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Añadir Contactos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        form {
            margin: 20px auto;
            width: 300px;
            text-align: left;
        }
        .contacto {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>AGENDA - Añadir Contactos</h1>
    <p>Hola <?php echo htmlspecialchars($_SESSION['usuario']); ?>, añade la información de los contactos.</p>

    <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

    <form method="post">
        <?php
        foreach ($emoji_list as $index => $emoji) {
            echo "<div class='contacto'>";
            echo "<label for='nombre_$index'>Nombre (Contacto $index):</label>";
            echo "<input type='text' name='nombre_$index' id='nombre_$index' required>";

            echo "<label for='email_$index'>Email (Contacto $index):</label>";
            echo "<input type='email' name='email_$index' id='email_$index' required>";

            echo "<label for='telefono_$index'>Teléfono (Contacto $index):</label>";
            echo "<input type='text' name='telefono_$index' id='telefono_$index' required>";

            echo "<p>Emoji asociado: <img src='images/$emoji' alt='emoji' class='emoji'></p>";
            echo "</div>";
        }
        ?>
        <button type="submit">Grabar Contactos</button>
    </form>
</body>
</html>
