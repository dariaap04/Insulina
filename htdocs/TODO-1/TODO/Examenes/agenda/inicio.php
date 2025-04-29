<?php
    session_start();

    $emojis = ["OIP0.jpg", "OIP1.jpg", "OIP2.jpg", "OIP3.jpg", "OIP4.jpg"];

    // Inicializar la lista de emojis si no existe
    if (!isset($_SESSION['emoji_list'])) {
        $_SESSION['emoji_list'] = [];
    }

    // Procesar el formulario
    if (isset($_POST['incrementar'])) {
        if (count($_SESSION['emoji_list']) < 5) {
            $nuevoEmoji = $emojis[array_rand($emojis)]; // Selecciona un emoji aleatorio
            $_SESSION['emoji_list'][] = $nuevoEmoji; // Agrega el emoji a la lista
        }
    } elseif (isset($_POST['grabar'])) {
        header("Location: agenda.php"); // Redirige a la siguiente pantalla
        exit();
    }

    // Verificar si se debe redirigir automáticamente
    if (count($_SESSION['emoji_list']) >= 5) {
        header("Location: agenda.php"); // Redirige a la siguiente pantalla
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Generar Contactos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .emoji {
            font-size: 2rem;
            margin: 5px;
        }
        button {
            padding: 10px 20px;
            font-size: 1rem;
            margin: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>AGENDA</h1>
    <p>Hola <?php echo htmlspecialchars($_SESSION['usu']); ?>, ¿cuántos contactos deseas grabar?</p>
    <p>Puedes grabar entre 1 y 5. Por cada pulsación en INCREMENTAR grabarás un usuario más.</p>
    <p>Cuando el número sea el deseado pulsa GRABAR.</p>

    <div>
        <?php
        // Mostrar los emojis actuales
        foreach ($_SESSION['emoji_list'] as $emoji) {
            echo "<img src='{$emoji}' alt='emoji' class='emoji'></img>";
        }
        ?>
    </div>

    <form method="post">
        <button type="submit" name="incrementar">INCREMENTAR</button>
        <button type="submit" name="grabar">GRABAR</button>
    </form>
</body>
</html>
