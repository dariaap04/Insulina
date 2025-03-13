<?php
session_start();

// Habilitar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
    echo '<div class="alert alert-danger">Debe iniciar sesión.</div>';
    exit();
}

// Función para conectar a la base de datos
function myConexion() {
    require_once "conexion.php"; // Asegúrate de que este archivo exista y esté correctamente configurado
    $conexion = new mysqli($servername, $username, $passworddb, $dbname);
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    return $conexion;
}

// Función para obtener el ID del usuario
function consultarIdUsu($conectada) {
    $stmt = $conectada->prepare("SELECT id_usu FROM usuario WHERE usuario = ?");
    $stmt->bind_param("s", $_SESSION["usuario"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["id_usu"];
    }
    return null;
}

// Función para consultar comidas
function consultarComidas($conectada, $id_usu) {
    if (!$conectada || !$id_usu) {
        die("❌ Error en la conexión o usuario no encontrado.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $comida = $_POST["comida"];
        $fecha = $_POST["fecha"];

        $stmt = $conectada->prepare("SELECT * FROM comida WHERE tipo_comida = ? AND fecha = ?");
        $stmt->bind_param("ss", $comida, $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        mostrarResultados($result, "comida", "Registros de Comida", "fas fa-utensils");
    } else {
        echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Método no permitido.</div>';
    }
}

// Función para consultar hipoglucemia
function consultarHipo($conectada, $id_usu) {
    if (!$conectada || !$id_usu) {
        die("❌ Error en la conexión o usuario no encontrado.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $comida = $_POST["comida"];
        $fecha = $_POST["fecha"];

        $stmt = $conectada->prepare("SELECT * FROM hipoglucemia WHERE tipo_comida = ? AND fecha = ?");
        $stmt->bind_param("ss", $comida, $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        mostrarResultados($result, "hipoglucemia", "Registros de Hipoglucemia", "fas fa-arrow-down", "bg-primary-subtle");
    } else {
        echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Método no permitido.</div>';
    }
}

// Función para consultar hiperglucemia
function consultarHiper($conectada, $id_usu) {
    if (!$conectada || !$id_usu) {
        die("❌ Error en la conexión o usuario no encontrado.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $comida = $_POST["comida"];
        $fecha = $_POST["fecha"];

        $stmt = $conectada->prepare("SELECT * FROM hiperglucemia WHERE tipo_comida = ? AND fecha = ?");
        $stmt->bind_param("ss", $comida, $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        mostrarResultados($result, "hiperglucemia", "Registros de Hiperglucemia", "fas fa-arrow-up", "bg-danger-subtle");
    } else {
        echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Método no permitido.</div>';
    }
}

// Función para mostrar resultados en una tabla
function mostrarResultados($result, $tipo, $titulo, $icono, $claseHeader = "") {
    if ($result->num_rows > 0) {
        echo '<div class="card">';
        echo '<div class="card-header ' . $claseHeader . '">';
        echo '<h2><i class="' . $icono . '"></i> ' . $titulo . '</h2>';
        echo '</div>';
        echo '<div class="card-body">';
        echo '<div class="table-responsive">';
        echo '<table class="table">';

        // Encabezados de la tabla
        $row = $result->fetch_assoc();
        echo '<thead><tr>';
        foreach ($row as $columna => $valor) {
            echo "<th>$columna</th>";
        }
        echo '<th>Acciones</th>';
        echo '</tr></thead>';

        // Cuerpo de la tabla
        echo '<tbody>';
        do {
            echo '<tr>';
            foreach ($row as $valor) {
                echo "<td>$valor</td>";
            }
            $id_registro = $row['id'] ?? $row['id_' . $tipo] ?? null;
            echo '<td class="action-buttons">';
            echo '<button type="button" class="btn btn-primary btn-sm me-1" onclick="editarRegistro(\'' . $tipo . '\', ' . $id_registro . ')"><i class="fas fa-edit"></i> Editar</button>';
            echo '<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal" data-tipo="' . $tipo . '" data-id="' . $id_registro . '"><i class="fas fa-trash-alt"></i> Eliminar</button>';
            echo '</td>';
            echo '</tr>';
        } while ($row = $result->fetch_assoc());
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-info"><i class="fas fa-info-circle"></i> No se encontraron registros.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Control de Glucemia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados */
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-top: 20px; }
        .header { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; padding: 20px; border-radius: 10px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .card { border: none; border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.05); margin-bottom: 25px; }
        .table th { background-color: #f8f9fa; color: #495057; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; border-top: none; padding: 12px 15px; }
        .table td { padding: 12px 15px; vertical-align: middle; border-color: #f2f2f2; }
        .action-buttons { white-space: nowrap; width: 1%; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabecera -->
        <div class="header">
            <h1><i class="fas fa-heartbeat"></i> Sistema de Control de Glucemia</h1>
            <p class="mb-0 mt-2">Consulta y gestión de registros glucémicos</p>
        </div>

        <!-- Formulario de búsqueda -->
        <div class="card mb-4">
            <div class="card-header">
                <h2><i class="fas fa-search"></i> Buscar Registros</h2>
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="comida" class="form-label">Tipo de Comida</label>
                        <select name="comida" id="comida" class="form-select">
                            <option value="desayuno">Desayuno</option>
                            <option value="almuerzo">Almuerzo</option>
                            <option value="cena">Cena</option>
                            <option value="merienda">Merienda</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resultados -->
        <div class="results">
            <?php
            $conectada = myConexion();
            $id_usu = consultarIdUsu($conectada);
            if ($id_usu) {
                consultarComidas($conectada, $id_usu);
                consultarHipo($conectada, $id_usu);
                consultarHiper($conectada, $id_usu);
            } else {
                echo '<div class="alert alert-danger"><i class="fas fa-user-times"></i> No se encontró el usuario. Por favor, inicie sesión nuevamente.</div>';
            }
            ?>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Sistema de Control de Glucemia. Todos los derechos reservados.</p>
        </div>
    </div>

    <!-- Modal de eliminación -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="eliminarModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas eliminar este registro?</p>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmDelete">
                        <label class="form-check-label" for="confirmDelete">Confirmo que deseo eliminar este registro</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnEliminarConfirmado" disabled><i class="fas fa-trash-alt"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuración del modal de eliminación
        document.addEventListener('DOMContentLoaded', function() {
            const eliminarModal = document.getElementById('eliminarModal');
            eliminarModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const tipo = button.getAttribute('data-tipo');
                const id = button.getAttribute('data-id');
                const btnEliminarConfirmado = document.getElementById('btnEliminarConfirmado');
                btnEliminarConfirmado.setAttribute('data-tipo', tipo);
                btnEliminarConfirmado.setAttribute('data-id', id);
            });

            const confirmDelete = document.getElementById('confirmDelete');
            const btnEliminarConfirmado = document.getElementById('btnEliminarConfirmado');
            confirmDelete.addEventListener('change', function() {
                btnEliminarConfirmado.disabled = !this.checked;
            });

            btnEliminarConfirmado.addEventListener('click', function() {
                const tipo = this.getAttribute('data-tipo');
                const id = this.getAttribute('data-id');
                window.location.href = `eliminar.php?tipo=${tipo}&id=${id}`;
            });
        });

        // Función para editar un registro
        function editarRegistro(tipo, id) {
            window.location.href = `editar.php?tipo=${tipo}&id=${id}`;
        }
    </script>
</body>
</html>