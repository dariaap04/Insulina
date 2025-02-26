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
                $mensaje = "No se encontraron registros para la fecha y comida seleccionadas.";
            }
        } else {
            $mensaje = "No se encontró el usuario.";
        }
    } else {
        $mensaje = "ID de usuario no disponible en la sesión.";
    }
} else {
    $mensaje = "No se ha seleccionado un registro para editar. Asegúrate de que los parámetros 'fecha' y 'comida' están en la URL.";
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    $mensaje = "";
    $error = false;
    
    // Validar datos de comida
    if (isset($_POST["comida_gl_1h"]) && isset($_POST["comida_raciones"]) && isset($_POST["comida_insulina"]) && isset($_POST["comida_gl_2h"])) {
        $comida_gl_1h = $_POST["comida_gl_1h"];
        $comida_raciones = $_POST["comida_raciones"];
        $comida_insulina = $_POST["comida_insulina"];
        $comida_gl_2h = $_POST["comida_gl_2h"];

        if (is_numeric($comida_gl_1h) && is_numeric($comida_raciones) && is_numeric($comida_insulina) && is_numeric($comida_gl_2h)) {
            // Realizar la actualización de comida
            $stmt_update = $con->prepare("UPDATE comida SET gl_1h = ?, raciones = ?, insulina = ?, gl_2h = ? WHERE fecha = ? AND tipo_comida = ? AND id_usu = ?");
            $stmt_update->bind_param("iiiissi", $comida_gl_1h, $comida_raciones, $comida_insulina, $comida_gl_2h, $fecha, $comida, $id_usu);

            if ($stmt_update->execute()) {
                $mensaje .= "Los datos de comida han sido actualizados correctamente. ";
            } else {
                $error = true;
                $mensaje .= "Error al actualizar los datos de comida: " . $stmt_update->error . " ";
            }
        } else {
            $error = true;
            $mensaje .= "Por favor, ingresa valores válidos en los campos de comida. ";
        }
    }

    // Validar datos de hiperglucemia
    if (isset($_POST['hiper_glucosa']) && isset($_POST['hiper_hora']) && isset($_POST['hiper_correccion'])) {
        $hiper_glucosa = $_POST['hiper_glucosa'];
        $hiper_hora = $_POST['hiper_hora'];
        $hiper_correccion = $_POST['hiper_correccion'];

        if (is_numeric($hiper_glucosa) && is_string($hiper_hora) && is_numeric($hiper_correccion)) {
            // Realizar la actualización de hiperglucemia
            $stmt_update_hiperglucemia = $con->prepare("UPDATE hiperglucemia SET glucosa = ?, hora = ?, correccion = ? WHERE tipo_comida = ? AND fecha = ? AND id_usu = ?");
            $stmt_update_hiperglucemia->bind_param("isissi", $hiper_glucosa, $hiper_hora, $hiper_correccion, $comida, $fecha, $id_usu);

            if ($stmt_update_hiperglucemia->execute()) {
                $mensaje .= "Los datos de hiperglucemia han sido actualizados correctamente. ";
            } else {
                $error = true;
                $mensaje .= "Error al actualizar los datos de hiperglucemia: " . $stmt_update_hiperglucemia->error . " ";
            }
        } else {
            $error = true;
            $mensaje .= "Por favor, ingresa valores válidos en los campos de hiperglucemia. ";
        }
    }

    // Validar datos de hipoglucemia
    if (isset($_POST['hipo_glucosa']) && isset($_POST['hipo_hora'])) {
        $hipo_glucosa = $_POST['hipo_glucosa'];
        $hipo_hora = $_POST['hipo_hora'];

        if (is_numeric($hipo_glucosa) && is_string($hipo_hora)) {
            // Realizar la actualización de hipoglucemia
            $stmt_update_hipoglucemia = $con->prepare("UPDATE hipoglucemia SET glucosa = ?, hora = ? WHERE tipo_comida = ? AND fecha = ? AND id_usu = ?");
            $stmt_update_hipoglucemia->bind_param("isssi", $hipo_glucosa, $hipo_hora, $comida, $fecha, $id_usu);

            if ($stmt_update_hipoglucemia->execute()) {
                $mensaje .= "Los datos de hipoglucemia han sido actualizados correctamente. ";
            } else {
                $error = true;
                $mensaje .= "Error al actualizar los datos de hipoglucemia: " . $stmt_update_hipoglucemia->error . " ";
            }
        } else {
            $error = true;
            $mensaje .= "Por favor, ingresa valores válidos en los campos de hipoglucemia. ";
        }
    }

    if (!$error) {
        header('Location: verResultados.php?mensaje=' . urlencode("Registro actualizado con éxito"));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --light-bg: #f8f9fa;
            --border-radius: 8px;
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border-radius: var(--border-radius);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            padding: 12px 20px;
        }
        
        .hiperglucemia .card-header {
            background-color: var(--danger-color);
        }
        
        .hipoglucemia .card-header {
            background-color: var(--warning-color);
        }
        
        .info-table {
            margin-bottom: 0;
        }
        
        .info-table th {
            width: 40%;
            background-color: var(--light-bg);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-success:hover {
            background-color: #27ae60;
            border-color: #27ae60;
        }
        
        .page-header {
            border-bottom: 2px solid var(--primary-color);
            margin-bottom: 30px;
            padding-bottom: 10px;
        }
        
        .alert {
            border-radius: var(--border-radius);
        }
        
        .input-group-text {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
                    <h2 class="m-0"><i class="fas fa-edit me-2"></i>Editar Registro</h2>
                    <a href="verResultados.php" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                </div>
                
                <?php if (isset($mensaje)): ?>
                <div class="alert <?php echo $error ? 'alert-danger' : 'alert-info'; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="edit.php">
                    <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha ?? ''); ?>">
                    <input type="hidden" name="comida" value="<?php echo htmlspecialchars($comida ?? ''); ?>">
                    
                    <?php if ($registro): ?>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="m-0"><i class="fas fa-utensils me-2"></i>Datos de Comida</h5>
                            <span class="badge bg-light text-dark">
                                <?php echo htmlspecialchars($registro['tipo_comida']); ?> - 
                                <?php echo htmlspecialchars($registro['fecha']); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <table class="table table-bordered info-table">
                                        <tr>
                                            <th>Glucosa 1H</th>
                                            <td><?php echo htmlspecialchars($registro['gl_1h']); ?> mg/dL</td>
                                        </tr>
                                        <tr>
                                            <th>Raciones</th>
                                            <td><?php echo htmlspecialchars($registro['raciones']); ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered info-table">
                                        <tr>
                                            <th>Insulina</th>
                                            <td><?php echo htmlspecialchars($registro['insulina']); ?> U</td>
                                        </tr>
                                        <tr>
                                            <th>Glucosa 2H</th>
                                            <td><?php echo htmlspecialchars($registro['gl_2h']); ?> mg/dL</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Glucosa 1H:</label>
                                    <div class="input-group">
                                        <input type="number" name="comida_gl_1h" class="form-control" value="<?php echo htmlspecialchars($registro['gl_1h']); ?>" required>
                                        <span class="input-group-text">mg/dL</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Raciones:</label>
                                    <input type="number" step="0.1" name="comida_raciones" class="form-control" value="<?php echo htmlspecialchars($registro['raciones']); ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Insulina:</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" name="comida_insulina" class="form-control" value="<?php echo htmlspecialchars($registro['insulina']); ?>" required>
                                        <span class="input-group-text">U</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Glucosa 2H:</label>
                                    <div class="input-group">
                                        <input type="number" name="comida_gl_2h" class="form-control" value="<?php echo htmlspecialchars($registro['gl_2h']); ?>" required>
                                        <span class="input-group-text">mg/dL</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($dataHiper): ?>
                    <div class="card mb-4 hiperglucemia">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="m-0"><i class="fas fa-arrow-trend-up me-2"></i>Datos de Hiperglucemia</h5>
                            <span class="badge bg-light text-dark">
                                <?php echo htmlspecialchars($dataHiper['tipo_comida']); ?> - 
                                <?php echo htmlspecialchars($dataHiper['fecha']); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <table class="table table-bordered info-table">
                                        <tr>
                                            <th>Glucosa</th>
                                            <td><?php echo htmlspecialchars($dataHiper['glucosa']); ?> mg/dL</td>
                                            <th>Hora</th>
                                            <td><?php echo htmlspecialchars($dataHiper['hora']); ?></td>
                                            <th>Corrección</th>
                                            <td><?php echo htmlspecialchars($dataHiper['correccion']); ?> U</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Glucosa:</label>
                                    <div class="input-group">
                                        <input type="number" name="hiper_glucosa" class="form-control" value="<?php echo htmlspecialchars($dataHiper['glucosa']); ?>" required>
                                        <span class="input-group-text">mg/dL</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hora:</label>
                                    <input type="time" name="hiper_hora" class="form-control" value="<?php echo htmlspecialchars($dataHiper['hora']); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Corrección:</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" name="hiper_correccion" class="form-control" value="<?php echo htmlspecialchars($dataHiper['correccion']); ?>" required>
                                        <span class="input-group-text">U</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($dataHipo): ?>
                    <div class="card mb-4 hipoglucemia">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="m-0"><i class="fas fa-arrow-trend-down me-2"></i>Datos de Hipoglucemia</h5>
                            <span class="badge bg-light text-dark">
                                <?php echo htmlspecialchars($dataHipo['tipo_comida']); ?> - 
                                <?php echo htmlspecialchars($dataHipo['fecha']); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <table class="table table-bordered info-table">
                                        <tr>
                                            <th>Glucosa</th>
                                            <td><?php echo htmlspecialchars($dataHipo['glucosa']); ?> mg/dL</td>
                                            <th>Hora</th>
                                            <td><?php echo htmlspecialchars($dataHipo['hora']); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Glucosa:</label>
                                    <div class="input-group">
                                        <input type="number" name="hipo_glucosa" class="form-control" value="<?php echo htmlspecialchars($dataHipo['glucosa']); ?>" required>
                                        <span class="input-group-text">mg/dL</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hora:</label>
                                    <input type="time" name="hipo_hora" class="form-control" value="<?php echo htmlspecialchars($dataHipo['hora']); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="verResultados.php" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success" name="actualizar" value="1">
                            <i class="fas fa-save me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>