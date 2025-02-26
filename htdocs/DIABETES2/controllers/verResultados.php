<?php
session_start();

// Función para verificar si el usuario está autenticado
function verificarAutenticacion() {
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../auth/login.php');
        exit();
    }
    return $_SESSION['usuario'];
}

// Función para conectar a la base de datos
function conectarBaseDeDatos() {
    require_once "../auth/login1.php";
    $con = new mysqli($localhost, $username, $pw, $database);
    if ($con->connect_error) {
        die("Error de conexión: " . $con->connect_error);
    }
    return $con;
}

// Función para mostrar una tarjeta de día individual
function mostrarTarjetaDia($dia, $mes, $anio, $desayunoData, $comidaData, $meriendaData, $cenaData, $controlData, $hipoData, $hiperData) {
    $desayuno = isset($desayunoData[$dia]) ? $desayunoData[$dia] : [];
    $comida = isset($comidaData[$dia]) ? $comidaData[$dia] : [];
    $merienda = isset($meriendaData[$dia]) ? $meriendaData[$dia] : [];
    $cena = isset($cenaData[$dia]) ? $cenaData[$dia] : [];
    $control = isset($controlData[$dia]) ? $controlData[$dia] : [];
    $hipo = isset($hipoData[$dia]) ? $hipoData[$dia] : [];
    $hiper = isset($hiperData[$dia]) ? $hiperData[$dia] : [];
    
    // Formatear la fecha completa
    $fechaCompleta = date("d/m/Y", mktime(0, 0, 0, $mes, $dia, $anio));
    // Obtener el nombre del día de la semana
    $nombreDia = date("D", mktime(0, 0, 0, $mes, $dia, $anio));
    
    // Convertir nombre del día a español
    $diasEspanol = [
        'Mon' => 'Lun',
        'Tue' => 'Mar',
        'Wed' => 'Mié',
        'Thu' => 'Jue',
        'Fri' => 'Vie',
        'Sat' => 'Sáb',
        'Sun' => 'Dom'
    ];
    $nombreDiaEsp = $diasEspanol[$nombreDia];
    
    echo "<div class='single-day-container'>";
    echo "<div class='day-card single'>";
    // Cabecera de la tarjeta
    echo "<div class='day-header'>";
    echo "<span class='day-number'>$nombreDiaEsp $dia</span>";
    echo "<span class='date-small'>$fechaCompleta</span>";
    echo "</div>";
    
    // Sección Desayuno
    echo "<div class='meal-section'>";
    echo "<div class='meal-title'><i class='fas fa-sun desayuno-icon'></i> Desayuno</div>";
    echo "<div class='metrics-grid'>";
    echo "<div class='metric-item'><span class='metric-label'>GL/1H</span><span class='metric-value'>" . (isset($desayuno['gl_1h']) && $desayuno['gl_1h'] ? $desayuno['gl_1h'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>RAC.</span><span class='metric-value'>" . (isset($desayuno['raciones']) && $desayuno['raciones'] ? $desayuno['raciones'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>INSU.</span><span class='metric-value'>" . (isset($desayuno['insulina']) && $desayuno['insulina'] ? $desayuno['insulina'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>GL/2H</span><span class='metric-value'>" . (isset($desayuno['gl_2h']) && $desayuno['gl_2h'] ? $desayuno['gl_2h'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "</div>";
    
    // Condiciones especiales para desayuno
    echo "<div class='conditions'>";

    // Hipoglucemias
    if (isset($hipoData[$dia]['Desayuno'])) {
        echo "<div class='condition-group hipo-group'>";
        echo "<div class='condition-title'><i class='fas fa-arrow-down hipo-icon'></i> Hipoglucemias</div>";
        echo "<ul>";
        foreach ($hipoData[$dia]['Desayuno'] as $hipo) {
            echo '<li> Glucosa '. $hipo["glucosa"].'</li>';
            echo '<li>Hora '.$hipo["hora"].'</li>';
        }
        echo "</ul>";
        echo "</div>";
    }
   


    // Hiperglucemias
    if (isset($hiperData[$dia]['Desayuno'])) {
        echo "<div class='condition-group hiper-group'>";
        echo "<div class='condition-title'><i class='fas fa-arrow-up hiper-icon'></i> Hiperglucemias</div>";
        echo "<ul>";
        foreach ($hiperData[$dia]['Desayuno'] as $hiper) {
            echo '<li> Glucosa '. $hiper["glucosa"].'</li>';
            echo '<li>Hora '.$hiper["hora"].'</li>';
            echo '<li>Corrección '.$hiper["correccion"].'</li>';
        }
        echo "</ul>";
        echo "</div>";
    }

    echo "</div>"; // Cierre de conditions
    echo "</div>"; // Cierre de meal-section
    
    // Sección Comida
    echo "<div class='meal-section'>";
    echo "<div class='meal-title'><i class='fas fa-utensils comida-icon'></i> Comida</div>";
    echo "<div class='metrics-grid'>";
    echo "<div class='metric-item'><span class='metric-label'>GL/1H</span><span class='metric-value'>" . (isset($comida['gl_1h']) && $comida['gl_1h'] ? $comida['gl_1h'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>RAC.</span><span class='metric-value'>" . (isset($comida['raciones']) && $comida['raciones'] ? $comida['raciones'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>INSU.</span><span class='metric-value'>" . (isset($comida['insulina']) && $comida['insulina'] ? $comida['insulina'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>GL/2H</span><span class='metric-value'>" . (isset($comida['gl_2h']) && $comida['gl_2h'] ? $comida['gl_2h'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "</div>";
    
    // Condiciones especiales para Comida
    echo "<div class='conditions'>";

    // Hipoglucemias
    if (isset($hipoData[$dia]['Comida'])) {
        echo "<div class='condition-group hipo-group'>";
        echo "<div class='condition-title'><i class='fas fa-arrow-down hipo-icon'></i> Hipoglucemias</div>";
        echo "<ul>";
        foreach ($hipoData[$dia]['Comida'] as $hipo) {
            echo '<li> Glucosa '. $hipo["glucosa"].'</li>';
            echo '<li>Hora '.$hipo["hora"].'</li>';
        }
        echo "</ul>";
        echo "</div>";
    }

    // Hiperglucemias
    if (isset($hiperData[$dia]['Comida'])) {
        echo "<div class='condition-group hiper-group'>";
        echo "<div class='condition-title'><i class='fas fa-arrow-up hiper-icon'></i> Hiperglucemias</div>";
        echo "<ul>";
        foreach ($hiperData[$dia]['Comida'] as $hiper) {
            echo '<li> Glucosa '. $hiper["glucosa"].'</li>';
            echo '<li>Hora '.$hiper["hora"].'</li>';
            echo '<li>Corrección '.$hiper["correccion"].'</li>';
        }
        echo "</ul>";
        echo "</div>";
    }

    echo "</div>"; // Cierre de conditions
    echo "</div>"; // Cierre de meal-section
    
    // Sección Merienda
    echo "<div class='meal-section'>";
    echo "<div class='meal-title'><i class='fas fa-cookie-bite merienda-icon'></i> Merienda</div>";
    echo "<div class='metrics-grid'>";
    echo "<div class='metric-item'><span class='metric-label'>GL/1H</span><span class='metric-value'>" . (isset($merienda['gl_1h']) && $merienda['gl_1h'] ? $merienda['gl_1h'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>RAC.</span><span class='metric-value'>" . (isset($merienda['raciones']) && $merienda['raciones'] ? $merienda['raciones'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>INSU.</span><span class='metric-value'>" . (isset($merienda['insulina']) && $merienda['insulina'] ? $merienda['insulina'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>GL/2H</span><span class='metric-value'>" . (isset($merienda['gl_2h']) && $merienda['gl_2h'] ? $merienda['gl_2h'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "</div>";
    
    // Condiciones especiales para merienda
    echo "<div class='conditions'>";

    // Hipoglucemias
    if (isset($hipoData[$dia]['Merienda'])) {
        echo "<div class='condition-group hipo-group'>";
        echo "<div class='condition-title'><i class='fas fa-arrow-down hipo-icon'></i> Hipoglucemias</div>";
        echo "<ul>";
        foreach ($hipoData[$dia]['Merienda'] as $hipo) {
            echo '<li> Glucosa '. $hipo["glucosa"].'</li>';
            echo '<li>Hora '.$hipo["hora"].'</li>';
        }
        echo "</ul>";
        echo "</div>";
    }

    // Hiperglucemias
    if (isset($hiperData[$dia]['Merienda'])) {
        echo "<div class='condition-group hiper-group'>";
        echo "<div class='condition-title'><i class='fas fa-arrow-up hiper-icon'></i> Hiperglucemias</div>";
        echo "<ul>";
        foreach ($hiperData[$dia]['Merienda'] as $hiper) {
            echo '<li> Glucosa '. $hiper["glucosa"].'</li>';
            echo '<li>Hora '.$hiper["hora"].'</li>';
            echo '<li>Corrección '.$hiper["correccion"].'</li>';
        }
        echo "</ul>";
        echo "</div>";
    }

    echo "</div>"; // Cierre de conditions
    echo "</div>"; // Cierre de meal-section
    
    // Sección Cena
    echo "<div class='meal-section'>";
    echo "<div class='meal-title'><i class='fas fa-moon cena-icon'></i> Cena</div>";
    echo "<div class='metrics-grid'>";
    echo "<div class='metric-item'><span class='metric-label'>GL/1H</span><span class='metric-value'>" . (isset($cena['gl_1h']) && $cena['gl_1h'] ? $cena['gl_1h'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>RAC.</span><span class='metric-value'>" . (isset($cena['raciones']) && $cena['raciones'] ? $cena['raciones'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>INSU.</span><span class='metric-value'>" . (isset($cena['insulina']) && $cena['insulina'] ? $cena['insulina'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "<div class='metric-item'><span class='metric-label'>GL/2H</span><span class='metric-value'>" . (isset($cena['gl_2h']) && $cena['gl_2h'] ? $cena['gl_2h'] : "<span class='empty-value'>--</span>") . "</span></div>";
    echo "</div>";
    
    // Condiciones especiales para cena
    echo "<div class='conditions'>";

    // Hipoglucemias
    if (isset($hipoData[$dia]['Cena'])) {
        echo "<div class='condition-group hipo-group'>";
        echo "<div class='condition-title'><i class='fas fa-arrow-down hipo-icon'></i> Hipoglucemias</div>";
        echo "<ul>";
        foreach ($hipoData[$dia]['Cena'] as $hipo) {
            echo '<li> Glucosa '. $hipo["glucosa"].'</li>';
            echo '<li>Hora '.$hipo["hora"].'</li>';
        }
        echo "</ul>";
        echo "</div>";
    }

    // Hiperglucemias
    if (isset($hiperData[$dia]['Cena'])) {
        echo "<div class='condition-group hiper-group'>";
        echo "<div class='condition-title'><i class='fas fa-arrow-up hiper-icon'></i> Hiperglucemias</div>";
        echo "<ul>";
        foreach ($hiperData[$dia]['Cena'] as $hiper) {
            echo '<li> Glucosa '. $hiper["glucosa"].'</li>';
            echo '<li>Hora '.$hiper["hora"].'</li>';
            echo '<li>Corrección '.$hiper["correccion"].'</li>';
        }
        echo "</ul>";
        echo "</div>";
    }

    echo "</div>"; // Cierre de conditions
    echo "</div>"; // Cierre de meal-section
    
    // Sección de datos adicionales (lenta y deporte)
    echo "<div class='extra-section'>";
    echo "<div class='extra-item'>";
    echo "<div class='extra-label'><i class='fas fa-syringe'></i> Insulina Lenta</div>";
    echo "<div class='extra-value'>" . (isset($control['lenta']) && $control['lenta'] ? $control['lenta'] : "--") . "</div>";
    echo "</div>";
    
    echo "<div class='extra-item'>";
    echo "<div class='extra-label'><i class='fas fa-running'></i> Deporte</div>";
    echo "<div class='extra-value'>" . (isset($control['deporte']) && $control['deporte'] ? $control['deporte'] : "--") . "</div>";
    echo "</div>";
    echo "</div>";
    
    echo "</div>"; // Cierre de day-card
    echo "</div>"; // Cierre de single-day-container
}





// Función para obtener los datos de comidas
function obtenerDatosComidas($con, $mes, $anio, $usuario, $tipoComida) {
    $sql = "SELECT * FROM comida c INNER JOIN usuario u ON c.id_usu = u.id_usu WHERE MONTH(c.fecha) = ? AND YEAR(c.fecha) = ? AND u.usuario = ? AND c.tipo_comida = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iiss", $mes, $anio, $usuario, $tipoComida);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $fecha = new DateTime($row['fecha']);
        $dia = (int)$fecha->format('d');
        $data[$dia] = [
            'gl_1h' => $row['gl_1h'],
            'raciones' => $row['raciones'],
            'insulina' => $row['insulina'],
            'gl_2h' => $row['gl_2h']
        ];
    }
    return $data;
}

// Función para obtener los datos de hipoglucemias
function obtenerDatosHipoglucemias($con, $usuario, $mes, $anio) {
    $sql_usu = "SELECT id_usu FROM usuario WHERE usuario = ?";
    $stmt_usu = $con->prepare($sql_usu);
    $stmt_usu->bind_param("s", $usuario);
    $stmt_usu->execute();
    $result_usu = $stmt_usu->get_result();
    
    $id_usu = null;
    if ($row_usu = $result_usu->fetch_assoc()) {
        $id_usu = $row_usu['id_usu'];
    }

    $sql = "SELECT * FROM hipoglucemia WHERE id_usu = ? AND MONTH(fecha) = ? AND YEAR(fecha) = ? ORDER BY fecha, hora";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iii", $id_usu, $mes, $anio);
    $stmt->execute();
    $result = $stmt->get_result();

    $hipoData = [];
    while ($row = $result->fetch_assoc()) {
        $fecha = new DateTime($row['fecha']);
        $dia = (int)$fecha->format('d');
        if (!isset($hipoData[$dia])) {
            $hipoData[$dia] = [];
        }
        if (!isset($hipoData[$dia][$row['tipo_comida']])) {
            $hipoData[$dia][$row['tipo_comida']] = [];
        }
        $hipoData[$dia][$row['tipo_comida']][] = [
            'glucosa' => $row['glucosa'],
            'hora' => $row['hora']
        ];
    }
    return $hipoData;
}

// Función para obtener los datos de hiperglucemias
function obtenerDatosHiperglucemias($con, $usuario, $mes, $anio) {
    $sql_usu = "SELECT id_usu FROM usuario WHERE usuario = ?";
    $stmt_usu = $con->prepare($sql_usu);
    $stmt_usu->bind_param("s", $usuario);
    $stmt_usu->execute();
    $result_usu = $stmt_usu->get_result();
    
    $id_usu = null;
    if ($row_usu = $result_usu->fetch_assoc()) {
        $id_usu = $row_usu['id_usu'];
    }

    $sql = "SELECT * FROM hiperglucemia WHERE id_usu = ? AND MONTH(fecha) = ? AND YEAR(fecha) = ? ORDER BY fecha, hora";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iii", $id_usu, $mes, $anio);
    $stmt->execute();
    $result = $stmt->get_result();

    $hiperData = [];
    while ($row = $result->fetch_assoc()) {
        $fecha = new DateTime($row['fecha']);
        $dia = (int)$fecha->format('d');
        if (!isset($hiperData[$dia])) {
            $hiperData[$dia] = [];
        }
        if (!isset($hiperData[$dia][$row['tipo_comida']])) {
            $hiperData[$dia][$row['tipo_comida']] = [];
        }
        $hiperData[$dia][$row['tipo_comida']][] = [
            'glucosa' => $row['glucosa'],
            'hora' => $row['hora'],
            'correccion' => $row['correccion']
        ];
    }
    return $hiperData;
}

// Función para obtener los datos de control adicional
function obtenerDatosControlAdicional($con, $usuario, $mes, $anio) {
    $sql_usu = "SELECT id_usu FROM usuario WHERE usuario = ?";
    $stmt_usu = $con->prepare($sql_usu);
    $stmt_usu->bind_param("s", $usuario);
    $stmt_usu->execute();
    $result_usu = $stmt_usu->get_result();
    
    $id_usu = null;
    if ($row_usu = $result_usu->fetch_assoc()) {
        $id_usu = $row_usu['id_usu'];
    }

    $sql = "SELECT * FROM control_glucosa WHERE id_usu = ? AND MONTH(fecha) = ? AND YEAR(fecha) = ? ORDER BY fecha";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iii", $id_usu, $mes, $anio);
    $stmt->execute();
    $result = $stmt->get_result();

    $controlData = [];
    while ($row = $result->fetch_assoc()) {
        $fecha = new DateTime($row['fecha']);
        $dia = (int)$fecha->format('d');
        $controlData[$dia] = [
            'lenta' => $row['lenta'],
            'deporte' => $row['deporte']
        ];
    }
    return $controlData;
}

// Verificar autenticación y obtener usuario
$usuario = verificarAutenticacion();

// Conectar a la base de datos
$con = conectarBaseDeDatos();

// Definir el mes y año seleccionados
$mes = isset($_POST['mes']) ? $_POST['mes'] : date("m");
$anio = isset($_POST['anio']) ? $_POST['anio'] : date("Y");

// Obtener los datos de comidas
$desayunoData = obtenerDatosComidas($con, $mes, $anio, $usuario, 'Desayuno');
$comidaData = obtenerDatosComidas($con, $mes, $anio, $usuario, 'Comida');
$meriendaData = obtenerDatosComidas($con, $mes, $anio, $usuario, 'Merienda');
$cenaData = obtenerDatosComidas($con, $mes, $anio, $usuario, 'Cena');

// Obtener los datos de hipoglucemias e hiperglucemias
$hipoData = obtenerDatosHipoglucemias($con, $usuario, $mes, $anio);
$hiperData = obtenerDatosHiperglucemias($con, $usuario, $mes, $anio);

// Obtener los datos de control adicional
$controlData = obtenerDatosControlAdicional($con, $usuario, $mes, $anio);

// Cerrar la conexión a la base de datos
$con->close();

// El resto del código HTML permanece igual
?>



<!DOCTYPE html> 
<html lang="es"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Registro de Insulina</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="../assets/css/verResultados.css"> 
    
    <style>
        .consulta-container {
    background-color: white;
    padding: var(--space-lg);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: var(--space-xl);
}

.consulta-form {
    display: flex;
    align-items: center;
    gap: var(--space-lg);
}

.consulta-text {
    display: flex;
    align-items: center;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary-dark);
    gap: var(--space-sm);
}

.consulta-icon {
    font-size: 1.5rem;
    color: var(--info);
}

.btn-consulta {
    background-color: var(--primary);
    color: white;
    border: none;
    padding: var(--space-sm) var(--space-lg);
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    transition: all 0.3s;
    cursor: pointer;
    box-shadow: var(--shadow-sm);
}

.btn-consulta:hover {
    background-color: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-consulta i {
    font-size: 1.2rem;
}

    </style>
</head> 
<body> 
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="welcome-text">Bienvenido, <?php echo htmlspecialchars($usuario)?></h1>
        </div>
        <div class="consulta-container">
            <form method="post" action="verDatos.php" class="consulta-form">
                <div class="consulta-text">
                    <i class="fa-solid fa-magnifying-glass-chart consulta-icon"></i>
                    <span>Consulta en estas gráficas tus:</span>
                </div>
                <button type="submit" class="btn-consulta">
                    <i class="fa-solid fa-chart-bar"></i> Datos
                </button>
            </form>
        </div>
        <!-- Month & Year Selector -->
        <div class="month-selector">
            <form method="POST"> 
                <h2 class="month-title">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Registro Mensual de Insulina - 
                    <?php echo date("F", mktime(0, 0, 0, $mes, 1, $anio)) . " " . $anio; ?> 
                </h2> 

                <!-- Selección de mes --> 
                <select name="mes" class="form-select"> 
                    <?php 
                    for ($i = 1; $i <= 12; $i++) { 
                        $nombreMes = date("F", mktime(0, 0, 0, $i, 1, $anio)); 
                        $selected = ($i == $mes) ? "selected" : ""; 
                        echo "<option value='$i' $selected>$nombreMes</option>"; 
                    } 
                    ?> 
                </select> 
                
                <!-- Selección de año --> 
                <select name="anio" class="form-select"> 
                    <?php 
                    for ($i = date("Y") - 5; $i <= date("Y") + 5; $i++) { 
                        $selected = ($i == $anio) ? "selected" : ""; 
                        echo "<option value='$i' $selected>$i</option>"; 
                    } 
                    ?> 
                </select>

                <div class="date-selector">
                    <label for="dia">Seleccionar día específico (opcional):</label>
                    <input type="number" name="dia" id="dia" min="1" max="31" class="form-control mb-2" 
                           value="<?php echo isset($_POST['dia']) ? $_POST['dia'] : ''; ?>">
                    <small class="text-danger">**Deja en blanco para mostrar todos los días**</small>
                </div>
                
                <button type="submit" class="update-btn">
                    <i class="fas fa-sync-alt me-2"></i>Actualizar
                </button>

            </form> 
        </div>

        <!-- Tarjetas de días -->
        <div class="days-container">
        <?php
// Este código reemplaza la sección de "days-container" en el HTML original

// Obtener el número de días en el mes seleccionado
$diasDelMes = date("t", mktime(0, 0, 0, $mes, 1, $anio));

// Obtener el primer día de la semana del mes seleccionado (0 = domingo, 1 = lunes, ...)
$primerDia = date("w", mktime(0, 0, 0, $mes, 1, $anio));
// Ajustar para que la semana comience en lunes (0 = lunes, ..., 6 = domingo)
$primerDia = ($primerDia == 0) ? 6 : $primerDia - 1;

// Calcular el número de semanas en el mes
$numSemanas = ceil(($diasDelMes + $primerDia) / 7);
?>

<!-- Tarjetas de días organizadas por semanas -->
<div class="weeks-container">
    <?php
   
    // Para cada semana
    for ($semana = 0; $semana < $numSemanas; $semana++) {
        $semanaId = $semana+1;
        echo "<div class='week-section'>";
        echo "<button class='week-title' type='button' data-bs-toggle='collapse' data-bs-target='#" . $semanaId . "' aria-expanded='false' aria-controls='" . $semanaId . "'>";
        ECHO "Semana " .$semanaId;
        echo "</button>";
        echo "<div class='collapse' id='" . $semanaId . "'>";
        echo "<div class='days-row'>";
        
        // Para cada día de la semana (0 = lunes, ..., 6 = domingo)
        for ($diaSemana = 0; $diaSemana < 7; $diaSemana++) {
            // Calcular el día del mes
            $dia = $semana * 7 + $diaSemana + 1 - $primerDia;
            
            // Verificar si el día está dentro del mes actual
            if ($dia > 0 && $dia <= $diasDelMes) {
                $diaSeleccionado = isset($_POST['dia']) && !empty($_POST['dia']) ? (int)$_POST['dia'] : null;

                if($diaSeleccionado !== null && $dia != $diaSeleccionado){
                    continue;
                }



                $desayuno = isset($desayunoData[$dia]) ? $desayunoData[$dia] : [];
                $comida = isset($comidaData[$dia]) ? $comidaData[$dia] : [];
                $merienda = isset($meriendaData[$dia]) ? $meriendaData[$dia] : [];
                $cena = isset($cenaData[$dia]) ? $cenaData[$dia] : [];
                $control = isset($controlData[$dia]) ? $controlData[$dia] : [];
                $hipo = isset($hipoData[$dia]) ? $hipoData[$dia] : [];
                $hiper = isset($hiperData[$dia]) ? $hiperData[$dia]:[];
                
                // Formatear la fecha completa
                $fechaCompleta = date("d/m/Y", mktime(0, 0, 0, $mes, $dia, $anio));
                // Obtener el nombre del día de la semana
                $nombreDia = date("D", mktime(0, 0, 0, $mes, $dia, $anio));
                
                // Convertir nombre del día a español
                $diasEspanol = [
                    'Mon' => 'Lun',
                    'Tue' => 'Mar',
                    'Wed' => 'Mié',
                    'Thu' => 'Jue',
                    'Fri' => 'Vie',
                    'Sat' => 'Sáb',
                    'Sun' => 'Dom'
                ];
                $nombreDiaEsp = $diasEspanol[$nombreDia];
                
                echo "<div class='day-card'>";
                // Cabecera de la tarjeta
                echo "<div class='day-header'>";
                echo "<span class='day-number'>$nombreDiaEsp $dia</span>";
                echo "<span class='date-small'>$fechaCompleta</span>";
                echo "</div>";
                
                // Sección Desayuno
                echo "<div class='meal-section'>";
                echo "<div class='meal-title'><i class='fas fa-sun desayuno-icon'></i> Desayuno</div>";
                echo "<div class='metrics-grid'>";
                echo "<div class='metric-item'><span class='metric-label'>GL/1H</span><span class='metric-value'>" . (isset($desayuno['gl_1h']) && $desayuno['gl_1h'] ? $desayuno['gl_1h'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>RAC.</span><span class='metric-value'>" . (isset($desayuno['raciones']) && $desayuno['raciones'] ? $desayuno['raciones'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>INSU.</span><span class='metric-value'>" . (isset($desayuno['insulina']) && $desayuno['insulina'] ? $desayuno['insulina'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>GL/2H</span><span class='metric-value'>" . (isset($desayuno['gl_2h']) && $desayuno['gl_2h'] ? $desayuno['gl_2h'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "</div>";
                
                // Condiciones especiales para desayuno
                echo "<div class='conditions'>";

                // Hipoglucemias
                if (isset($hipoData[$dia]['Desayuno'])) {
                    echo "<div class='condition-group hipo-group'>";
                    echo "<div class='condition-title'><i class='fas fa-arrow-down hipo-icon'></i> Hipoglucemias</div>";
                    echo "<ul>";
                    foreach ($hipoData[$dia]['Desayuno'] as $hipo) {
                        echo '<li> Glucosa '. $hipo["glucosa"].'</li>';
                        echo '<li>Hora '.$hipo["hora"].'</li>';
                    }
                    echo "</ul>";
                    echo "</div>";
                }

                // Hiperglucemias
                if (isset($hiperData[$dia]['Desayuno'])) {
                    echo "<div class='condition-group hiper-group'>";
                    echo "<div class='condition-title'><i class='fas fa-arrow-up hiper-icon'></i> Hiperglucemias</div>";
                    echo "<ul>";
                    foreach ($hiperData[$dia]['Desayuno'] as $hiper) {
                        echo '<li> Glucosa '. $hiper["glucosa"].'</li>';
                        echo '<li>Hora '.$hiper["hora"].'</li>';
                        echo '<li>Corrección '.$hiper["correccion"].'</li>';
                    }
                    echo "</ul>";
                    echo "</div>";
            }

                echo "</div>"; // Cierre de conditions
                echo "</div>"; // Cierre de meal-section
                
                // Sección Comida
                echo "<div class='meal-section'>";
                echo "<div class='meal-title'><i class='fas fa-utensils comida-icon'></i> Comida</div>";
                echo "<div class='metrics-grid'>";
                echo "<div class='metric-item'><span class='metric-label'>GL/1H</span><span class='metric-value'>" . (isset($comida['gl_1h']) && $comida['gl_1h'] ? $comida['gl_1h'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>RAC.</span><span class='metric-value'>" . (isset($comida['raciones']) && $comida['raciones'] ? $comida['raciones'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>INSU.</span><span class='metric-value'>" . (isset($comida['insulina']) && $comida['insulina'] ? $comida['insulina'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>GL/2H</span><span class='metric-value'>" . (isset($comida['gl_2h']) && $comida['gl_2h'] ? $comida['gl_2h'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "</div>";
                
                // Condiciones especiales para Comida
                echo "<div class='conditions'>";

                // Hipoglucemias
                if (isset($hipoData[$dia]['Comida'])) {
                    echo "<div class='condition-group hipo-group'>";
                    echo "<div class='condition-title'><i class='fas fa-arrow-down hipo-icon'></i> Hipoglucemias</div>";
                    echo "<ul>";
                    foreach ($hipoData[$dia]['Comida'] as $hipo) {
                        echo '<li> Glucosa '. $hipo["glucosa"].'</li>';
                        echo '<li>Hora '.$hipo["hora"].'</li>';
                    }
                    echo "</ul>";
                    echo "</div>";
                }

                // Hiperglucemias
                if (isset($hiperData[$dia]['Comida'])) {
                    echo "<div class='condition-group hiper-group'>";
                    echo "<div class='condition-title'><i class='fas fa-arrow-up hiper-icon'></i> Hiperglucemias</div>";
                    echo "<ul>";
                    foreach ($hiperData[$dia]['Comida'] as $hiper) {
                        echo '<li> Glucosa '. $hiper["glucosa"].'</li>';
                        echo '<li>Hora '.$hiper["hora"].'</li>';
                        echo '<li>Corrección '.$hiper["correccion"].'</li>';
                    }
                    echo "</ul>";
                    echo "</div>";
            }

                echo "</div>"; // Cierre de conditions
                echo "</div>"; // Cierre de meal-section
                
                // Sección Merienda
                echo "<div class='meal-section'>";
                echo "<div class='meal-title'><i class='fas fa-cookie-bite merienda-icon'></i> Merienda</div>";
                echo "<div class='metrics-grid'>";
                echo "<div class='metric-item'><span class='metric-label'>GL/1H</span><span class='metric-value'>" . (isset($merienda['gl_1h']) && $merienda['gl_1h'] ? $merienda['gl_1h'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>RAC.</span><span class='metric-value'>" . (isset($merienda['raciones']) && $merienda['raciones'] ? $merienda['raciones'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>INSU.</span><span class='metric-value'>" . (isset($merienda['insulina']) && $merienda['insulina'] ? $merienda['insulina'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>GL/2H</span><span class='metric-value'>" . (isset($merienda['gl_2h']) && $merienda['gl_2h'] ? $merienda['gl_2h'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "</div>";
                
                 // Condiciones especiales para merienda
                 echo "<div class='conditions'>";

                 // Hipoglucemias
                 if (isset($hipoData[$dia]['Merienda'])) {
                     echo "<div class='condition-group hipo-group'>";
                     echo "<div class='condition-title'><i class='fas fa-arrow-down hipo-icon'></i> Hipoglucemias</div>";
                     echo "<ul>";
                     foreach ($hipoData[$dia]['Merienda'] as $hipo) {
                         echo '<li> Glucosa '. $hipo["glucosa"].'</li>';
                         echo '<li>Hora '.$hipo["hora"].'</li>';
                     }
                     echo "</ul>";
                     echo "</div>";
                 }
 
                 // Hiperglucemias
                 if (isset($hiperData[$dia]['Merienda'])) {
                     echo "<div class='condition-group hiper-group'>";
                     echo "<div class='condition-title'><i class='fas fa-arrow-up hiper-icon'></i> Hiperglucemias</div>";
                     echo "<ul>";
                     foreach ($hiperData[$dia]['Merienda'] as $hiper) {
                         echo '<li> Glucosa '. $hiper["glucosa"].'</li>';
                         echo '<li>Hora '.$hiper["hora"].'</li>';
                         echo '<li>Corrección '.$hiper["correccion"].'</li>';
                     }
                     echo "</ul>";
                     echo "</div>";
             }
 
                 echo "</div>"; // Cierre de conditions
                 echo "</div>"; // Cierre de meal-section
                
                // Sección Cena
                echo "<div class='meal-section'>";
                echo "<div class='meal-title'><i class='fas fa-moon cena-icon'></i> Cena</div>";
                echo "<div class='metrics-grid'>";
                echo "<div class='metric-item'><span class='metric-label'>GL/1H</span><span class='metric-value'>" . (isset($cena['gl_1h']) && $cena['gl_1h'] ? $cena['gl_1h'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>RAC.</span><span class='metric-value'>" . (isset($cena['raciones']) && $cena['raciones'] ? $cena['raciones'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>INSU.</span><span class='metric-value'>" . (isset($cena['insulina']) && $cena['insulina'] ? $cena['insulina'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "<div class='metric-item'><span class='metric-label'>GL/2H</span><span class='metric-value'>" . (isset($cena['gl_2h']) && $cena['gl_2h'] ? $cena['gl_2h'] : "<span class='empty-value'>--</span>") . "</span></div>";
                echo "</div>";
                
                 // Condiciones especiales para cena
                 echo "<div class='conditions'>";

                 // Hipoglucemias
                 if (isset($hipoData[$dia]['Cena'])) {
                     echo "<div class='condition-group hipo-group'>";
                     echo "<div class='condition-title'><i class='fas fa-arrow-down hipo-icon'></i> Hipoglucemias</div>";
                     echo "<ul>";
                     foreach ($hipoData[$dia]['Cena'] as $hipo) {
                         echo '<li> Glucosa '. $hipo["glucosa"].'</li>';
                         echo '<li>Hora '.$hipo["hora"].'</li>';
                     }
                     echo "</ul>";
                     echo "</div>";
                 }
 
                 // Hiperglucemias
                 if (isset($hiperData[$dia]['Cena'])) {
                     echo "<div class='condition-group hiper-group'>";
                     echo "<div class='condition-title'><i class='fas fa-arrow-up hiper-icon'></i> Hiperglucemias</div>";
                     echo "<ul>";
                     foreach ($hiperData[$dia]['Cena'] as $hiper) {
                         echo '<li> Glucosa '. $hiper["glucosa"].'</li>';
                         echo '<li>Hora '.$hiper["hora"].'</li>';
                         echo '<li>Corrección '.$hiper["correccion"].'</li>';
                     }
                     echo "</ul>";
                     echo "</div>";
             }
 
                 echo "</div>"; // Cierre de conditions
                 echo "</div>"; // Cierre de meal-section
                
                // Sección de datos adicionales (lenta y deporte)
                echo "<div class='extra-section'>";
                echo "<div class='extra-item'>";
                echo "<div class='extra-label'><i class='fas fa-syringe'></i> Insulina Lenta</div>";
                echo "<div class='extra-value'>" . (isset($control['lenta']) && $control['lenta'] ? $control['lenta'] : "--") . "</div>";
                echo "</div>";
                
                echo "<div class='extra-item'>";
                echo "<div class='extra-label'><i class='fas fa-running'></i> Deporte</div>";
                echo "<div class='extra-value'>" . (isset($control['deporte']) && $control['deporte'] ? $control['deporte'] : "--") . "</div>";
                echo "</div>";
                echo "</div>";
                
                echo "</div>"; // Cierre de day-card
            } else {
                // Día fuera del mes, mostrar una tarjeta vacía
                echo "<div class='day-card empty'></div>";
            }
        }
        
        echo "</div>"; // Cierre de days-row
        echo "</div>"; // Cierre de collapse
        echo "</div>"; // Cierre de week-section
    }
    ?>
</div>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" class="btn-action btn-add" onclick="window.location.href='../views/formularios.html'">
                <i class="fas fa-plus"></i> Añadir
            </button> 
            <button type="button" class="btn-action btn-edit" onclick="window.location.href='../views/edit.html'">
                <i class="fas fa-edit"></i> Modificar
            </button> 
            <button type="button" class="btn-action btn-delete" onclick="window.location.href='../views/delete.html'">
                <i class="fas fa-trash"></i> Borrar
            </button> 
            <button type="button" class="btn-action btn-logout" onclick="window.location.href='../views/index.html'">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button> 
        </div>
    </div>

    <!-- Bootstrap and other scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html>