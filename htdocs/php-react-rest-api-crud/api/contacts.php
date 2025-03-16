<?php
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$password = "";
$dbname = "contacts"; // Corrigiendo "constacts" a "contacts"

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["message" => "Connection failed", "error" => $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $sql = "SELECT * FROM contacts" . ($id ? " WHERE id=$id" : "");
        $result = $conn->query($sql);

        $contacts = [];
        while ($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
        echo json_encode($contacts);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            die(json_encode(["message" => "Invalid input"]));
        }

        $name = $conn->real_escape_string($data['name']);
        $email = $conn->real_escape_string($data['email']);
        $country = $conn->real_escape_string($data['country']);
        $city = $conn->real_escape_string($data['city']);
        $job = $conn->real_escape_string($data['job']);

        $sql = "INSERT INTO contacts (name, email, city, country, job) VALUES ('$name', '$email', '$city', '$country', '$job')";
        $result = $conn->query($sql);

        echo json_encode(["message" => $result ? "success" : "error", "error" => $conn->error]);
        break;
}

$conn->close();
?>
