<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Estadísticos</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --bg-color: #eef1f7;
            --shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--dark-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        header {
            background: white;
            padding: 15px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .logo h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
        }

        .charts-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .chart-card {
            flex: 1;
            min-width: 320px;
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-5px);
        }

        .chart-title {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: var(--dark-color);
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-action {
            padding: 12px 24px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add { background-color: var(--secondary-color); color: white; }
        .btn-edit { background-color: var(--primary-color); color: white; }
        .btn-delete { background-color: var(--danger-color); color: white; }
        .btn-logout { background-color: var(--dark-color); color: white; }
        .btn-action:hover { opacity: 0.8; transform: scale(1.05); }

        footer {
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            background: white;
            box-shadow: var(--shadow);
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .charts-row { flex-direction: column; }
            .chart-card { width: 100%; }
            .action-buttons { flex-direction: column; }
            .btn-action { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <h1>Datos Estadísticos</h1>
            </div>
        </header>

        <div class="charts-row">
            <div class="chart-card">
                <div class="chart-title">Consumo de Comida</div>
                <div id="chart_div"></div>
            </div>
            <div class="chart-card">
                <div class="chart-title">Hipoglucemia</div>
                <div id="hipo_div"></div>
            </div>
            <div class="chart-card">
                <div class="chart-title">Hiperglucemia</div>
                <div id="hiper_div"></div>
            </div>
        </div>

        <div class="action-buttons">
            <button class="btn-action btn-add" onclick="location.href='../views/datos.html'"><i class="fas fa-plus"></i> Consultar más Datos</button>
            <button class="btn-action btn-logout" onclick="location.href='../views/index.html'"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Dashboard Mejorado. Todos los derechos reservados.</p>
    </footer>

    <script>
        google.charts.load('current', {'packages':['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            fetch('datos.php')
                .then(response => response.json())
                .then(data => {
                    drawChart('chart_div', data.comida, 'Consumo de comida', 'PieChart');
                    drawChart('hipo_div', data.hipoglucemia, 'Hipoglucemia', 'ColumnChart');
                    drawChart('hiper_div', data.hiperglucemia, 'Hiperglucemia', 'ColumnChart');
                })
                .catch(error => console.error('Error cargando datos:', error));
        }

        function drawChart(elementId, chartData, title, chartType) {
            var dataTable = google.visualization.arrayToDataTable(chartData);
            var options = { title, width: '100%', height: 350 };
            var chart = new google.visualization[chartType](document.getElementById(elementId));
            chart.draw(dataTable, options);
        }
    </script>
</body>
</html>
