<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$usuario = $_SESSION['usuario'];

// Conexión a la base de datos
require_once "login1.php";
$con = new mysqli($localhost, $username, $pw, $database);

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

$registro = null;
$dataHiper = null;
$dataHipo = null;

// Verifica si se han pasado los parámetros 'fecha' y 'comida' por POST
if (isset($_POST['fecha']) && isset($_POST['comida'])) {
    $fecha = $_POST['fecha'];
    $comida = $_POST['comida'];

    // Asegurarse de que 'id_usu' está en la sesión
    if (isset($usuario)) {
        // Consultar id_usu para el usuario
        $stmt_usuario = $con->prepare("SELECT id_usu FROM usuario WHERE usuario = ?");
        $stmt_usuario->bind_param("s", $usuario);
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();

        if ($result_usuario->num_rows > 0) {
            $usuario_data = $result_usuario->fetch_assoc();
            $id_usu = $usuario_data['id_usu'];

            // Ahora consultar la comida con el id_usu
            $stmt = $con->prepare("SELECT * FROM comida WHERE fecha = ? AND tipo_comida = ? AND id_usu = ?");
            $stmt->bind_param("ssi", $fecha, $comida, $id_usu);
            $stmt->execute();
            $result = $stmt->get_result();

            // consultar si hay hipoglucemia
            $stmt_hipoglucemia = $con->prepare("SELECT * FROM hipoglucemia WHERE tipo_comida =? AND fecha =? AND id_usu =?");
            $stmt_hipoglucemia->bind_param("ssi", $comida, $fecha, $id_usu);
            $stmt_hipoglucemia->execute();
            $result_hipoglucemia = $stmt_hipoglucemia->get_result();

            // CONSULTAR SI HAY HIPERGLUCEMIA
            $stmt_hiperglucemia = $con->prepare("SELECT * FROM hiperglucemia WHERE tipo_comida =? AND fecha =? AND id_usu =?");
            $stmt_hiperglucemia->bind_param("ssi", $comida, $fecha, $id_usu);
            $stmt_hiperglucemia->execute();
            $result_hiperglucemia = $stmt_hiperglucemia->get_result();

            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                // Si hay resultados, obtener el primer registro
                $registro = $result->fetch_assoc();
            }
            if ($result_hipoglucemia->num_rows > 0) {
                // Si no hay hipoglucemia, obtener el primer registro
                $dataHipo = $result_hipoglucemia->fetch_assoc();
            }
            if ($result_hiperglucemia->num_rows > 0) {
                // Si no hay hiperglucemia, obtener el primer registro
                $dataHiper = $result_hiperglucemia->fetch_assoc();
            } else {
                echo "<p>No se encontraron registros para la fecha y comida seleccionadas.</p>";
            }
        } else {
            echo "<p>No se encontró el usuario.</p>";
        }
    } else {
        echo "<p>ID de usuario no disponible en la sesión.</p>";
    }
} else {
    echo "<p>No se ha seleccionado un registro para editar. Asegúrate de que los parámetros 'fecha' y 'comida' están en la URL.</p>";
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    // Validar datos de comida
    $comida_gl_1h = $_POST["comida_gl_1h"];
    $comida_raciones = $_POST["comida_raciones"];
    $comida_insulina = $_POST["comida_insulina"];
    $comida_gl_2h = $_POST["comida_gl_2h"];

    if (is_numeric($comida_gl_1h) && is_numeric($comida_raciones) && is_numeric($comida_insulina) && is_numeric($comida_gl_2h)) {
        // Realizar la actualización de comida
        $stmt_update = $con->prepare("UPDATE comida SET gl_1h = ?, raciones = ?, insulina = ?, gl_2h = ? WHERE fecha = ? AND tipo_comida = ? AND id_usu = ?");
        $stmt_update->bind_param("iiiisss", $comida_gl_1h, $comida_raciones, $comida_insulina, $comida_gl_2h, $fecha, $comida, $id_usu);

        if ($stmt_update->execute()) {
            echo "<p>Los datos de comida han sido actualizados correctamente.</p>";
        } else {
            echo "<p>Error al actualizar los datos de comida: " . $stmt_update->error . "</p>";
        }
    } else {
        echo "<p>Por favor, ingresa valores válidos en los campos de comida.</p>";
    }

    // Validar datos de hiperglucemia
    $hiper_glucosa = $_POST['hiper_glucosa'];
    $hiper_hora = $_POST['hiper_hora'];
    $hiper_correccion = $_POST['hiper_correccion'];

    if (is_numeric($hiper_glucosa) && is_string($hiper_hora) && is_numeric($hiper_correccion)) {
        // Realizar la actualización de hiperglucemia
        $stmt_update_hiperglucemia = $con->prepare("UPDATE hiperglucemia SET glucosa = ?, hora = ?, correccion =? WHERE tipo_comida =? AND fecha =? AND id_usu =?");
        $stmt_update_hiperglucemia->bind_param("isisss", $hiper_glucosa, $hiper_hora, $hiper_correccion, $comida, $fecha, $id_usu);

        if ($stmt_update_hiperglucemia->execute()) {
            echo "<p>Los datos de hiperglucemia han sido actualizados correctamente.</p>";
        } else {
            echo "<p>Error al actualizar los datos de hiperglucemia: " . $stmt_update_hiperglucemia->error . "</p>";
        }
    } else {
        echo "<p>Por favor, ingresa valores válidos en los campos de hiperglucemia.</p>";
    }

    // Validar datos de hipoglucemia
    $hipo_glucosa = $_POST['hipo_glucosa'];
    $hipo_hora = $_POST['hipo_hora'];

    if (is_numeric($hipo_glucosa) && is_string($hipo_hora)) {
        // Realizar la actualización de hipoglucemia
        $stmt_update_hipoglucemia = $con->prepare("UPDATE hipoglucemia SET glucosa = ?, hora = ? WHERE tipo_comida =? AND fecha =? AND id_usu =?");
        $stmt_update_hipoglucemia->bind_param("isssi", $hipo_glucosa, $hipo_hora, $comida, $fecha, $id_usu);

        if ($stmt_update_hipoglucemia->execute()) {
            echo "<p>Los datos de hipoglucemia han sido actualizados correctamente.</p>";
        } else {
            echo "<p>Error al actualizar los datos de hipoglucemia: " . $stmt_update_hipoglucemia->error . "</p>";
        }
    } else {
        echo "<p>Por favor, ingresa valores válidos en los campos de hipoglucemia.</p>";
    }
    header('Location: verResultados.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="container mt-4">
    <h2 class="text-center">Editar Registro</h2>
    <form method="POST" action="edit.php">
        <?php
        if ($registro) {
            echo "<h3>Datos de Comida</h3>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Fecha</th><td>" . htmlspecialchars($registro['fecha']) . "</td></tr>";
            echo "<tr><th>Comida</th><td>" . htmlspecialchars($registro['tipo_comida']) . "</td></tr>";
            echo "<tr><th>Glucosa 1H:</th><td>" . htmlspecialchars($registro['gl_1h']) . "</td></tr>";
            echo "<tr><th>Raciones:</th><td>" . htmlspecialchars($registro['raciones']) . "</td></tr>";
            echo "<tr><th>Insulina:</th><td>" . htmlspecialchars($registro['insulina']) . "</td></tr>";
            echo "<tr><th>Glucosa 2H:</th><td>" . htmlspecialchars($registro['gl_2h']) . "</td></tr>";
            echo "</table>";
            echo '
                <div class="mb-3">
                    <label class="form-label">Glucosa 1H:</label>
                    <input type="text" name="comida_gl_1h" class="form-control" value="' . htmlspecialchars($registro["gl_1h"]) . '" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Raciones:</label>
                    <input type="text" name="comida_raciones" class="form-control" value="' . htmlspecialchars($registro["raciones"]) . '" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Insulina:</label>
                    <input type="text" name="comida_insulina" class="form-control" value="' . htmlspecialchars($registro['insulina']) . '" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Glucosa 2H:</label>
                    <input type="text" name="comida_gl_2h" class="form-control" value="' . htmlspecialchars($registro['gl_2h']) . '" required>
                </div>
            ';
        }
        if ($dataHiper) {
            echo "<h3>Datos de Hiperglucemia</h3>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Fecha</th><td>" . htmlspecialchars($dataHiper['fecha']) . "</td></tr>";
            echo "<tr><th>Comida</th><td>" . htmlspecialchars($dataHiper['tipo_comida']) . "</td></tr>";
            echo "<tr><th>Glucosa:</th><td>" . htmlspecialchars($dataHiper['glucosa']) . "</td></tr>";
            echo "<tr><th>Hora:</th><td>" . htmlspecialchars($dataHiper['hora']) . "</td></tr>";
            echo "<tr><th>Corrección:</th><td>" . htmlspecialchars($dataHiper['correccion']) . "</td></tr>";
            echo "</table>";
            echo '
                <div class="mb-3">
                    <label class="form-label">Glucosa:</label>
                    <input type="text" name="hiper_glucosa" class="form-control" value="' . htmlspecialchars($dataHiper['glucosa']) . '" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hora:</label>
                    <input type="text" name="hiper_hora" class="form-control" value="' . htmlspecialchars($dataHiper['hora']) . '" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Corrección:</label>
                    <input type="text" name="hiper_correccion" class="form-control" value="' . htmlspecialchars($dataHiper['correccion']) . '" required>
                </div>
            ';
        }
        if ($dataHipo) {
            echo "<h3>Datos de Hipoglucemia</h3>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Fecha</th><td>" . htmlspecialchars($dataHipo['fecha']) . "</td></tr>";
            echo "<tr><th>Comida</th><td>" . htmlspecialchars($dataHipo['tipo_comida']) . "</td></tr>";
            echo "<tr><th>Glucosa:</th><td>" . htmlspecialchars($dataHipo['glucosa']) . "</td></tr>";
            echo "<tr><th>Hora:</th><td>" . htmlspecialchars($dataHipo['hora']) . "</td></tr>";
            echo "</table>";
            echo '
                <div class="mb-3">
                    <label class="form-label">Glucosa:</label>
                    <input type="text" name="hipo_glucosa" class="form-control" value="' . htmlspecialchars($dataHipo['glucosa']) . '" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hora:</label>
                    <input type="text" name="hipo_hora" class="form-control" value="' . htmlspecialchars($dataHipo['hora']) . '" required>
                </div>
            ';
        }
        ?>
        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
        <input type="hidden" name="comida" value="<?php echo htmlspecialchars($comida); ?>">
        <button type="submit" class="btn btn-success" name="actualizar" value="1">Guardar Cambios</button>
        <a href="verResultados.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>