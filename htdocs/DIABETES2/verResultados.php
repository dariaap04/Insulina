<?php
session_start();
/* Verificar si estamos conectados con el usuario */
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$usuario = $_SESSION['usuario'];

/* Hacemos la conexión */
require_once "login1.php";
$con = new mysqli($localhost, $username, $pw, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Definir el mes y año seleccionados (por defecto, el mes y año actuales)
$mes = isset($_POST['mes']) ? $_POST['mes'] : date("m");
$anio = isset($_POST['anio']) ? $_POST['anio'] : date("Y");

// Obtener el número de días del mes seleccionado
$diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

// Crear arrays para almacenar los datos
$desayunoData = [];
$comidaData = [];
$meriendaData = [];
$cenaData = [];
$hipoData = [];
$hiperData = [];

// Obtener los registros de la comida (desayuno, comida, cena)
$sqlComida = "SELECT * FROM comida WHERE MONTH(fecha) = ? AND YEAR(fecha) = ? AND id_usu = (SELECT id_usu FROM usuario WHERE usuario = ?) AND tipo_comida = ?";
$stmtComida = $con->prepare($sqlComida);
$stmtComida->bind_param("iiss", $mes, $anio, $usuario, $comidas);

// Obtener los registros de hipoglucemia
$sqlHipo = "SELECT * FROM hipoglucemia WHERE MONTH(fecha) = ? AND YEAR(fecha) =?";
$stmtHipo = $con->prepare($sqlHipo);
$stmtHipo->bind_param("ii", $mes, $anio);
$stmtHipo->execute();
$resultHipo = $stmtHipo->get_result();
while ($row = $resultHipo->fetch_assoc()) {
    $dia = date('j', strtotime($row['fecha']));
    $comida = $row['tipo_comida']; // Obtener el tipo de comida de la fila */
    $hipoData[$dia][$comida][] = $row;
}

// Obtener los registros de hiperglucemia
$sqlHiper = "SELECT * FROM hiperglucemia WHERE MONTH(fecha) = ? AND YEAR(fecha) = ? AND id_usu = (SELECT id_usu FROM usuario WHERE usuario = ?)";
$stmtHiper = $con->prepare($sqlHiper);
$stmtHiper->bind_param("iis", $mes, $anio, $usuario);
$stmtHiper->execute();
$resultHiper = $stmtHiper->get_result();
while ($row = $resultHiper->fetch_assoc()) {
    $dia = date('j', strtotime($row['fecha']));
    $comida = $row['tipo_comida']; // Obtener el tipo de comida de la fila
    $hiperData[$dia][$comida][] = $row;
}

// Obtener los registros de desayuno
$comidas = 'desayuno';
$stmtComida->execute();
$resultComida = $stmtComida->get_result();
while ($row = $resultComida->fetch_assoc()) {
    $dia = date('j', strtotime($row['fecha']));
    $desayunoData[$dia] = $row;
}

// Obtener los registros de comida
$comidas = 'comida';
$stmtComida->execute();
$resultComida = $stmtComida->get_result();
while ($row = $resultComida->fetch_assoc()) {
    $dia = date('j', strtotime($row['fecha']));
    $comidaData[$dia] = $row;
}

// Obtener los registros de merienda
$comidas = 'merienda';
$stmtComida->execute();
$resultComida = $stmtComida->get_result();
while ($row = $resultComida->fetch_assoc()) {
    $dia = date('j', strtotime($row['fecha']));
    $meriendaData[$dia] = $row;
}

// Obtener los registros de cena
$comidas = 'cena';
$stmtComida->execute();
$resultComida = $stmtComida->get_result();
while ($row = $resultComida->fetch_assoc()) {
    $dia = date('j', strtotime($row['fecha']));
    $cenaData[$dia] = $row;
}
?>
<!DOCTYPE html> 
<html lang="es"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Registro de Insulina</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/> 
    <style> 
        table { 
            margin-left: 60px; 
            width: 90%; 
            border-collapse: collapse; 
            font-size: 12px; 
        } 
        th, td { 
             border: 1px solid black; 
            text-align: center; 
            padding: 2px; 
        } 
        th { 
            background-color: #d3e6c0; 
        } 
        .hipo { 
            background-color: #b0c4de; 
        } 

        .hiper { 
            background-color: #f7e1a0; 
        } 

        input { 
            width: 30px; 
            font-size: 10px; 
        } 
        h2{ 
            text-align: center; 
        } 
        .selector { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
        } 

        select { 
            font-size: 14px; 
            padding: 2px; 
        } 

        .buttons { 
            display: flex; 
            gap: 10px; 
            justify-content: center; 
            margin-top: 20px; 
        } 

        .buttons button { 
            padding: 12px 20px; 
            font-size: 16px; 
            font-weight: bold; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            transition: all 0.3s ease-in-out; 

        } 
        .buttons button:nth-child(1) { background-color: #28a745; color: white; } /* Agregar */ 
        .buttons button:nth-child(2) { background-color: #ffc107; color: black; } /* Modificar */ 
        .buttons button:nth-child(3) { background-color: #dc3545; color: white; } /* Borrar */ 
        .buttons button:nth-child(4) { background-color:rgb(79, 108, 240); color: white; } /* Cerrar Sesion */ 
        .buttons button:hover { 
            opacity: 0.8; 
            transform: scale(1.05); 
        } 
        .buttons button:active { 
            transform: scale(0.95); 
        } 
    </style> 
</head> 
<body> 
    <h1 class="display-6 text-center">Bienvenido, <?php echo htmlspecialchars($usuario)?></h1> 
    <hr> 
    <!-- FORMULARIO PARA SELECCIONAR MES Y AÑO --> 

    <form method="POST"> 
        <div class="selector"> 
            <h2>Registro Mensual de Insulina -  
                <?php echo date("F", mktime(0, 0, 0, $mes, 1, $anio)) . " " . $anio; ?> 
            </h2> 

            <!-- Selección de mes --> 
            <select name="mes"> 
                <?php 
                for ($i = 1; $i <= 12; $i++) { 
                    $nombreMes = date("F", mktime(0, 0, 0, $i, 1, $anio)); 
                    $selected = ($i == $mes) ? "selected" : ""; 
                    echo "<option value='$i' $selected>$nombreMes</option>"; 
                } 
                ?> 
            </select> 
            <!-- Selección de año --> 

            <select name="anio"> 
                <?php 
                for ($i = date("Y") - 5; $i <= date("Y") + 5; $i++) { 
                    $selected = ($i == $anio) ? "selected" : ""; 
                    echo "<option value='$i' $selected>$i</option>"; 
                } 
                ?> 
            </select>
         <button type="submit">Actualizar</button> 
        </div> 
    </form> 

    <!-- TABLA DE REGISTRO --> 

    <table> 
        <thead> 
            <tr> 
                <th rowspan="3">Día</th> 
                <th colspan="9">Desayuno</th> 
                <th colspan="9">Comida</th> 
                <th colspan="9">Merienda</th>
                <th colspan="9">Cena</th> 
                <th rowspan="3">Lenta</th> 
                <th rowspan="3">Deporte</th> 
            </tr> 
            <tr> 
                <th colspan="4">Medición</th> 
                <th colspan="2" class="hipo">HIPO</th> 
                <th colspan="3" class="hiper">HIPER</th> 
                <th colspan="4">Medición</th> 
                <th colspan="2" class="hipo">HIPO</th> 
                <th colspan="3" class="hiper">HIPER</th> 
                <th colspan="4">Medición</th> 
                <th colspan="2" class="hipo">HIPO</th> 
                <th colspan="3" class="hiper">HIPER</th> 
                <th colspan="4">Medición</th> 
                <th colspan="2" class="hipo">HIPO</th> 
                <th colspan="3" class="hiper">HIPER</th>
            </tr> 
            <tr>

                <th>GL/1H</th><th>RAC.</th><th>INSU.</th><th>GL/2H</th> 
                <th class="hipo">GLU.</th><th class="hipo">HORA</th> 
                <th class="hiper">GLU.</th><th class="hiper">HORA</th><th class="hiper">CORR.</th> 

                <th>GL/1H</th><th>RAC.</th><th>INSU.</th><th>GL/2H</th> 
                <th class="hipo">GLU.</th><th class="hipo">HORA</th> 
                <th class="hiper">GLU.</th><th class="hiper">HORA</th><th class="hiper">CORR.</th>

                <th>GL/1H</th><th>RAC.</th><th>INSU.</th><th>GL/2H</th> 
                <th class="hipo">GLU.</th><th class="hipo">HORA</th> 
                <th class="hiper">GLU.</th><th class="hiper">HORA</th><th class="hiper">CORR.</th>
                
                <th>GL/1H</th><th>RAC.</th><th>INSU.</th><th>GL/2H</th> 
                <th class="hipo">GLU.</th><th class="hipo">HORA</th> 
                <th class="hiper">GLU.</th><th class="hiper">HORA</th><th class="hiper">CORR.</th>
                
                
            </tr> 
        </thead> 
        <tbody> 
        <?php
             // Función para mostrar datos de hipo/hiper
             function mostrarHipoHiper($data, $dia, $tipoComida, $tipo) {
                if (isset($data[$dia][$tipoComida])) {
                    foreach ($data[$dia][$tipoComida] as $registro) {
                        echo "<td class='" . ($tipo === 'hipo' ? 'hipo' : 'hiper') . "'>" . (isset($registro['glucosa']) ? $registro['glucosa'] : '') . "</td>";
                        echo "<td class='" . ($tipo === 'hipo' ? 'hipo' : 'hiper') . "'>" . (isset($registro['hora']) ? $registro['hora'] : '') . "</td>";
                        if ($tipo === 'hiper') {
                            echo "<td class='hiper'>" . (isset($registro['correccion']) ? $registro['correccion'] : '') . "</td>";
                        }
                    }
                } else {
                    echo "<td class='" . ($tipo === 'hipo' ? 'hipo' : 'hiper') . "'></td>";
                    echo "<td class='" . ($tipo === 'hipo' ? 'hipo' : 'hiper') . "'></td>";
                    if ($tipo === 'hiper') {
                        echo "<td class='hiper'></td>";
                    }
                }
            }
            for ($dia = 1; $dia <= $diasDelMes; $dia++) {
                $desayuno = isset($desayunoData[$dia]) ? $desayunoData[$dia] : [];
                $comida = isset($comidaData[$dia]) ? $comidaData[$dia] : [];
                $merienda = isset($meriendaData[$dia]) ? $meriendaData[$dia] : [];
                $cena = isset($cenaData[$dia]) ? $cenaData[$dia] : [];

                echo "<tr>";
                echo "<td>$dia</td>";

           

                // Desayuno
                echo "<td>" . (isset($desayuno['gl_1h']) ? $desayuno['gl_1h'] : '') . "</td>";
                echo "<td>" . (isset($desayuno['raciones']) ? $desayuno['raciones'] : '') . "</td>";
                echo "<td>" . (isset($desayuno['insulina']) ? $desayuno['insulina'] : '') . "</td>";
                echo "<td>" . (isset($desayuno['gl_2h']) ? $desayuno['gl_2h'] : '') . "</td>";
                mostrarHipoHiper($hipoData, $dia, 'Desayuno', 'hipo');
                mostrarHipoHiper($hiperData, $dia, 'Desayuno', 'hiper');

                // Comida
                echo "<td>" . (isset($comida['gl_1h']) ? $comida['gl_1h'] : '') . "</td>";
                echo "<td>" . (isset($comida['raciones']) ? $comida['raciones'] : '') . "</td>";
                echo "<td>" . (isset($comida['insulina']) ? $comida['insulina'] : '') . "</td>";
                echo "<td>" . (isset($comida['gl_2h']) ? $comida['gl_2h'] : '') . "</td>";
                mostrarHipoHiper($hipoData, $dia, 'Comida', 'hipo');
                mostrarHipoHiper($hiperData, $dia, 'Comida', 'hiper');

                // Merienda
                echo "<td>" . (isset($merienda['gl_1h']) ? $merienda['gl_1h'] : '') . "</td>";
                echo "<td>" . (isset($merienda['raciones']) ? $merienda['raciones'] : '') . "</td>";
                echo "<td>" . (isset($merienda['insulina']) ? $merienda['insulina'] : '') . "</td>";
                echo "<td>" . (isset($merienda['gl_2h']) ? $merienda['gl_2h'] : '') . "</td>";
                mostrarHipoHiper($hipoData, $dia, 'Merienda', 'hipo');
                mostrarHipoHiper($hiperData, $dia, 'Merienda', 'hiper');

                // Cena
                echo "<td>" . (isset($cena['gl_1h']) ? $cena['gl_1h'] : '') . "</td>";
                echo "<td>" . (isset($cena['raciones']) ? $cena['raciones'] : '') . "</td>";
                echo "<td>" . (isset($cena['insulina']) ? $cena['insulina'] : '') . "</td>";
                echo "<td>" . (isset($cena['gl_2h']) ? $cena['gl_2h'] : '') . "</td>";
                mostrarHipoHiper($hipoData, $dia, 'Cena', 'hipo');
                mostrarHipoHiper($hiperData, $dia, 'Cena', 'hiper');

                // Lenta y Deporte
                /* $sqlControl = "SELECT lenta, deporte FROM control_glucosa WHERE fecha = ? AND id_usu = (SELECT id_usu FROM usuario WHERE usuario = ?)";
                $stmtControl = $con->prepare($sqlControl);
                $fechaDia = date('Y-m-d', mktime(0, 0, 0, $mes, $dia, $anio));
                $stmtControl->bind_param("ss", $fechaDia, $usuario);
                $stmtControl->execute();
                $resultControl = $stmtControl->get_result();
                $controlData = $resultControl->fetch_assoc(); */

                echo "<td>" . (isset($controlData['lenta']) ? $controlData['lenta'] : '') . "</td>";
                echo "<td>" . (isset($controlData['deporte']) ? $controlData['deporte'] : '') . "</td>";

                echo "</tr>";
            }
            ?>

    

</tbody> 
    </table> 
    <!-- Boton de añadir, modificar, borrar --> 
     <div class="buttons"> 
        <button type="button" onclick="location.href='formularios.html'">Añadir</button> 
        <button type="button" onclick="location.href='edit.html'">Modificar</button> 
        <button type="button" onclick="location.href='edit.html'">Borrar</button> 
        <button type="button" onclick="location.href='index.html'">Cerrar Sesión</button> 
    </div> 

</body> 
</html> 