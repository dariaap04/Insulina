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
$mensaje = "";
$tipoMensaje = "";

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
            $stmt_hipoglucemia = $con->prepare("SELECT * FROM hipoglucemia WHERE tipo_comida = ? AND fecha = ? AND id_usu = ?");
            $stmt_hipoglucemia->bind_param("ssi", $comida, $fecha, $id_usu);
            $stmt_hipoglucemia->execute();
            $result_hipoglucemia = $stmt_hipoglucemia->get_result();

            // CONSULTAR SI HAY HIPERGLUCEMIA
            $stmt_hiperglucemia = $con->prepare("SELECT * FROM hiperglucemia WHERE tipo_comida = ? AND fecha = ? AND id_usu = ?");
            $stmt_hiperglucemia->bind_param("ssi", $comida, $fecha, $id_usu);
            $stmt_hiperglucemia->execute();
            $result_hiperglucemia = $stmt_hiperglucemia->get_result();

            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                // Si hay resultados, obtener el primer registro
                $registro = $result->fetch_assoc();
            }
            if ($result_hipoglucemia->num_rows > 0) {
                // Si hay hipoglucemia, obtener el primer registro
                $registroHipo = $result_hipoglucemia->fetch_assoc();
            }
            if ($result_hiperglucemia->num_rows > 0) {
                // Si hay hiperglucemia, obtener el primer registro
                $registroHiper = $result_hiperglucemia->fetch_assoc();
            }
            
            if (!$registro && !$registroHipo && !$registroHiper) {
                $mensaje = "No se encontraron registros para la fecha y comida seleccionadas.";
                $tipoMensaje = "warning";
            }
        } else {
            $mensaje = "No se encontró el usuario.";
            $tipoMensaje = "danger";
        }
    } else {
        $mensaje = "ID de usuario no disponible en la sesión.";
        $tipoMensaje = "danger";
    }
} else {
    $mensaje = "No se ha seleccionado un registro para eliminar. Asegúrate de que los parámetros 'fecha' y 'comida' están en la URL.";
    $tipoMensaje = "warning";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar'])) {
    // Consultar id_usu para el usuario si no se ha hecho ya
    if (!isset($id_usu)) {
        $stmt_usuario = $con->prepare("SELECT id_usu FROM usuario WHERE usuario = ?");
        $stmt_usuario->bind_param("s", $usuario);
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();
        $usuario_data = $result_usuario->fetch_assoc();
        $id_usu = $usuario_data['id_usu'];
    }
    
    $fecha = $_POST['fecha'];
    $comida = $_POST['comida'];
    
    // Eliminar de comida
    $stmt_delete = $con->prepare("DELETE FROM comida WHERE fecha = ? AND tipo_comida = ? AND id_usu = ?");
    $stmt_delete->bind_param("ssi", $fecha, $comida, $id_usu);
    
    // Eliminar de hipoglucemia 
    $stmt_hipoglucemia_delete = $con->prepare("DELETE FROM hipoglucemia WHERE tipo_comida = ? AND fecha = ? AND id_usu = ?");
    $stmt_hipoglucemia_delete->bind_param("ssi", $comida, $fecha, $id_usu);
    
    // Eliminar de hiperglucemia
    $stmt_hiperglucemia_delete = $con->prepare("DELETE FROM hiperglucemia WHERE tipo_comida = ? AND fecha = ? AND id_usu = ?");
    $stmt_hiperglucemia_delete->bind_param("ssi", $comida, $fecha, $id_usu);
    
    // Verificar si las sentencias se ejecutan correctamente
    $success = true;
    
    if (!$stmt_delete->execute()) {
        $success = false;
    }
    
    if (!$stmt_hipoglucemia_delete->execute()) {
        $success = false;
    }
    
    if (!$stmt_hiperglucemia_delete->execute()) {
        $success = false;
    }
    
    if ($success) {
        header('Location: verResultados.php?deleted=true');
        exit();
    } else {
        $mensaje = "Error al eliminar los datos.";
        $tipoMensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            border: none;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background-color: #4e73df;
            color: white;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            padding: 15px 20px;
        }
        .card-body {
            padding: 25px;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            background-color: #f8f9fc;
            color: #5a5c69;
            font-weight: 600;
            white-space: nowrap;
            width: 40%;
        }
        .btn-danger {
            background-color: #e74a3b;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
        }
        .btn-secondary {
            background-color: #858796;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
        }
        .btn-group {
            margin-top: 20px;
        }
        .page-title {
            color: #4e73df;
            margin-bottom: 25px;
            font-weight: 700;
        }
        .alert {
            border-radius: 8px;
            padding: 15px 20px;
        }
        .data-label {
            color: #4e73df;
            margin-top: 5px;
            margin-bottom: 10px;
            font-weight: 600;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 5px;
            display: inline-block;
        }
        .alert-icon {
            margin-right: 10px;
        }
        .confirmation-title {
            font-size: 24px;
            color: #e74a3b;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .confirmation-text {
            font-size: 16px;
            margin-bottom: 25px;
            color: #5a5c69;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-center page-title">
                    <i class="fas fa-trash-alt me-2"></i>Eliminar Registro
                </h2>
                
                <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipoMensaje; ?>" role="alert">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <?php echo $mensaje; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="delete.php">
                    <?php if ($registro || $registroHiper || $registroHipo): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-exclamation-triangle me-2"></i>Confirmación de eliminación
                            </div>
                            <div class="card-body">
                                <h3 class="confirmation-title text-center">¿Estás seguro que deseas eliminar este registro?</h3>
                                <p class="confirmation-text text-center">Esta acción no se puede deshacer. Se eliminarán todos los datos relacionados con esta comida.</p>
                                
                                <?php if ($registro): ?>
                                    <h4 class="data-label"><i class="fas fa-utensils me-2"></i>Datos de comida</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th><i class="far fa-calendar-alt me-2"></i>Fecha</th>
                                                <td><?php echo htmlspecialchars($registro['fecha']); ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-hamburger me-2"></i>Comida</th>
                                                <td><?php echo htmlspecialchars($registro['tipo_comida']); ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-tachometer-alt me-2"></i>Glucosa 1H</th>
                                                <td><?php echo htmlspecialchars($registro['gl_1h']); ?> mg/dL</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-cookie-bite me-2"></i>Raciones</th>
                                                <td><?php echo htmlspecialchars($registro['raciones']); ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-syringe me-2"></i>Insulina</th>
                                                <td><?php echo htmlspecialchars($registro['insulina']); ?> U</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-tachometer-alt me-2"></i>Glucosa 2H</th>
                                                <td><?php echo htmlspecialchars($registro['gl_2h']); ?> mg/dL</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($registro['fecha']); ?>">
                                    <input type="hidden" name="comida" value="<?php echo htmlspecialchars($registro['tipo_comida']); ?>">
                                <?php endif; ?>
                                
                                <?php if ($registroHiper): ?>
                                    <h4 class="data-label mt-4"><i class="fas fa-arrow-up me-2"></i>Datos de Hiperglucemia</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th><i class="far fa-calendar-alt me-2"></i>Fecha</th>
                                                <td><?php echo htmlspecialchars($registroHiper['fecha']); ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-hamburger me-2"></i>Comida</th>
                                                <td><?php echo htmlspecialchars($registroHiper['tipo_comida']); ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-tachometer-alt me-2"></i>Glucosa</th>
                                                <td><?php echo htmlspecialchars($registroHiper['glucosa']); ?> mg/dL</td>
                                            </tr>
                                            <tr>
                                                <th><i class="far fa-clock me-2"></i>Hora</th>
                                                <td><?php echo htmlspecialchars($registroHiper['hora']); ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-syringe me-2"></i>Corrección</th>
                                                <td><?php echo htmlspecialchars($registroHiper['correccion']); ?> U</td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($registroHipo): ?>
                                    <h4 class="data-label mt-4"><i class="fas fa-arrow-down me-2"></i>Datos de Hipoglucemia</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th><i class="far fa-calendar-alt me-2"></i>Fecha</th>
                                                <td><?php echo htmlspecialchars($registroHipo['fecha']); ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-hamburger me-2"></i>Comida</th>
                                                <td><?php echo htmlspecialchars($registroHipo['tipo_comida']); ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-tachometer-alt me-2"></i>Glucosa</th>
                                                <td><?php echo htmlspecialchars($registroHipo['glucosa']); ?> mg/dL</td>
                                            </tr>
                                            <tr>
                                                <th><i class="far fa-clock me-2"></i>Hora</th>
                                                <td><?php echo htmlspecialchars($registroHipo['hora']); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="d-flex justify-content-center btn-group mt-4">
                                    <button type="submit" class="btn btn-danger me-2" name="eliminar" value="1">
                                        <i class="fas fa-trash-alt me-2"></i>Confirmar Eliminación
                                    </button>
                                    <a href="verResultados.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>