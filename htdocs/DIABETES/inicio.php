<?php
    session_start(); 
    require_once "login.php"; 
    $usu = $_SESSION['usu'];


// Definir el mes y año seleccionados (por defecto, el mes y año actuales)
$mes = isset($_POST['mes']) ? $_POST['mes'] : date("m");
$anio = isset($_POST['anio']) ? $_POST['anio'] : date("Y");

// Obtener el número de días del mes seleccionado
$diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
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
    </style>
</head>
<body>
        <h1 class="display-6 text-center">Bienvenido, <?php echo htmlspecialchars($usu)?></h1>
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
            <th colspan="9">Cena</th>
            <th rowspan="3">Lenta</th>
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
        </tr>
    </thead>
    <tbody>
        <?php
        for ($dia = 1; $dia <= $diasDelMes; $dia++) {
            echo "<tr>";
            echo "<td>$dia</td>";
            for ($i = 0; $i < 28; $i++) { // 28 columnas por fila
                echo "<td><input type='text' name='data[$dia][$i]'></td>";
            }
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
        <div class="text-end">
            <a href="inicioSesion.php">Cerrar Sesion</a>
        </div>
</body>
</html>
