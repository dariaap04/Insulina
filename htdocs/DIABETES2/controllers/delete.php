<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../auth/login.php');
    exit();
}
$usuario = $_SESSION['usuario'];

// Conexión a la base de datos
require_once "../auth/login1.php";
$con = new mysqli($localhost, $username, $pw, $database);

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

$registro = null;
$registroHiper = null;
$registroHipo = null;

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
            $registroHipo = $result_hipoglucemia->fetch_assoc();
        }
        if ($result_hiperglucemia->num_rows > 0) {
            // Si no hay hiperglucemia, obtener el primer registro
            $registroHiper = $result_hiperglucemia->fetch_assoc();
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
    echo "<p>No se ha seleccionado un registro para eliminar. Asegúrate de que los parámetros 'fecha' y 'comida' están en la URL.</p>";
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar'])){
    // Depuración antes de la ejecución de DELETE
echo "Fecha: " . $fecha . " Comida: " . $comida;
$stmt_delete = $con->prepare("DELETE FROM comida WHERE fecha = ? AND tipo_comida= ? AND id_usu=?");
$stmt_delete->bind_param("ssi", $fecha, $comida, $id_usu);

//delete from hipoglucemia 
    $stmt_hipoglucemia_delete = $con->prepare("DELETE FROM hipoglucemia WHERE tipo_comida =? AND fecha =? AND id_usu=?");
    $stmt_hipoglucemia_delete->bind_param("ssi", $comida, $fecha, $id_usu);
    

//delete from hiperglucemia
    $stmt_hiperglucemia_delete = $con->prepare("DELETE FROM hiperglucemia WHERE tipo_comida =? AND fecha =? AND id_usu=?");
    $stmt_hiperglucemia_delete->bind_param("ssi", $comida, $fecha, $id_usu);


// Verificar si la sentencia se ejecuta correctamente
if ($stmt_delete->execute() && $stmt_hipoglucemia_delete->execute() && $stmt_hiperglucemia_delete->execute()) {
    echo "<p>Los datos han sido borrados correctamente.</p>";
    header('Location: verResultados.php');
    exit();

}else {
    echo "<p>Error al eliminar los datos: " . $stmt_delete->error . "</p>";
}
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
    <h2 class="text-center">Eliminar Registro</h2>
    <form method="POST" action="delete.php">
        <?php 
        if ($registro) {
            echo "<h3>Datos encontrados:</h3>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Fecha</th><td>" . htmlspecialchars($registro['fecha']) . "</td></tr>";
            echo "<tr><th>Comida</th><td>" . htmlspecialchars($registro['tipo_comida']) . "</td></tr>";
            echo "<tr><th>Glucosa 1H:</th><td>" . htmlspecialchars($registro['gl_1h']) . "</td></tr>";
            echo "<tr><th>Raciones:</th><td>" . htmlspecialchars($registro['raciones']) . "</td></tr>";
            echo "<tr><th>Insulina:</th><td>" . htmlspecialchars($registro['insulina']) . "</td></tr>";
            echo "<tr><th>Glucosa 2H:</th><td>" . htmlspecialchars($registro['gl_2h']) . "</td></tr>";
            echo "</table>";
            echo '
                    <input type="hidden" name="fecha" value="' . htmlspecialchars($registro['fecha']) . '">
                    <input type="hidden" name="comida" value="' . htmlspecialchars($registro['tipo_comida']) . '">
            ';
        } if ($registroHiper) {
            echo "<h3>Datos de Hiperglucemia</h3>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Fecha</th><td>" . htmlspecialchars($registroHiper['fecha']) . "</td></tr>";
            echo "<tr><th>Comida</th><td>" . htmlspecialchars($registroHiper['tipo_comida']) . "</td></tr>";
            echo "<tr><th>Glucosa:</th><td>" . htmlspecialchars($registroHiper['glucosa']) . "</td></tr>";
            echo "<tr><th>Hora:</th><td>" . htmlspecialchars($registroHiper['hora']) . "</td></tr>";
            echo "<tr><th>Corrección:</th><td>" . htmlspecialchars($registroHiper['correccion']) . "</td></tr>";
            echo "</table>";
        }
        if ($registroHipo) {
            echo "<h3>Datos de Hipoglucemia</h3>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Fecha</th><td>" . htmlspecialchars($registroHipo['fecha']) . "</td></tr>";
            echo "<tr><th>Comida</th><td>" . htmlspecialchars($registroHipo['tipo_comida']) . "</td></tr>";
            echo "<tr><th>Glucosa:</th><td>" . htmlspecialchars($registroHipo['glucosa']) . "</td></tr>";
            echo "<tr><th>Hora:</th><td>" . htmlspecialchars($registroHipo['hora']) . "</td></tr>";
            echo "</table>";
        }
        ?>
        <button type="submit" class="btn btn-danger" name="eliminar" value="1">Confirmar Cambios</button>
        <a href="verResultados.php" class="btn btn-secondary">Cancelar</a>
    </form>
    
</body>
</html>