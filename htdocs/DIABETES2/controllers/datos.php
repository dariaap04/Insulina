<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../auth/login.php');
    exit();
}
$usuario = $_SESSION['usuario'];

require_once "../auth/login1.php";
$con = new mysqli($localhost, $username, $pw, $database);

if ($con->connect_error) {
    die(json_encode(['error' => 'Error de conexión: ' . $con->connect_error]));
}

header('Content-Type: application/json');

$sqlGlucosa = "SELECT * FROM control_glucosa ORDER BY fecha";
$resultGlucosa = $con->query($sqlGlucosa);

$glucosaData = [];
$glucosaLabels = [];

while ($row = $resultGlucosa->fetch_assoc()) {
    $glucosaData[] = (float) $row['glucosa'];
    $glucosaLabels[] = $row['fecha'];
}

$hipoCount = $con->query("SELECT COUNT(*) as count FROM hipoglucemia")->fetch_assoc()['count'] ?? 0;
$hiperCount = $con->query("SELECT COUNT(*) as count FROM hiperglucemia")->fetch_assoc()['count'] ?? 0;

$con->close();

echo json_encode([
    'glucosaLabels' => $glucosaLabels,
    'glucosaData' => $glucosaData,
    'hipoCount' => $hipoCount,
    'hiperCount' => $hiperCount,
]);
?>