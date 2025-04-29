<?php 
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 204 No Content');
    exit;
    }
    function myConexion() {
        require_once "conexion.php";
        $conexion = new mysqli($servername, $username, $passworddb, $dbname);
        if ($conexion->connect_error) {
            die("Error de conexion" . $conexion->connect_error);
        }
        return $conexion;
    }
    function getLenta($conectada){
        $sqlLenta = "SELECT lenta, fecha FROM control_glucosa";
        $resultLenta = $conectada->query($sqlLenta);
        $lenta = $resultLenta->fetch_all(MYSQLI_ASSOC);
        return $lenta;
    }

    $conectada = myConexion();
    switch($_SERVER["REQUEST_METHOD"]){
        case 'GET':
            $lenta = getLenta($conectada);
            echo json_encode($lenta);
            break;
    }
?>