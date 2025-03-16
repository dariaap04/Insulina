<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "insulinadb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = $conn->query("SELECT * FROM usuario");
    $users = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($users);
} elseif ($method === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'] ?? null;

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM usuario WHERE id_usu = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Error al eliminar usuario"]);
        }
        $stmt->close();
    }
}elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("INSERT INTO usuario (nombre, apellidos, usuario, contra, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $data['nombre'], $data['apellidos'], $data['usuario'], password_hash($data['contra'], PASSWORD_BCRYPT), $data['fecha_nacimiento']);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error al agregar usuario"]);
    }
    $stmt->close();
}

$conn->close();
?>
