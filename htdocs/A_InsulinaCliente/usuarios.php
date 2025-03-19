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

     function getAllUsuarios($conectada){
        $sqlUsuario = "SELECT * FROM usuario";
        $resultUsuario = $conectada ->query($sqlUsuario);

        $listaUsuario = $resultUsuario->fetch_all(MYSQLI_ASSOC);
        return $listaUsuario;
     }       


     function getUsuById($conectada, $id_usu){
        $sqlUsuario = "SELECT * FROM usuario WHERE id_usu = $id_usu";
        $stmt = $conectada ->query($sqlUsuario); 
        $stmt->bind_param("i", $id_usu);
        $stmt->execute();
   
       $resultado = $stmt->get_result();
       $usuario = $resultado->fetch_assoc();
   
       $stmt->close();
       
       return $usuario ?: null; 
        
     }

     function createUsu($conectada /*añadir parametros despues*/){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            if(isset($data["id_usu"]) && isset($data["usuario"]) && isset($data["fecha_nacimiento"]) && isset($data["nombre"])
            && isset($data["apellidos"]) && isset($data["contra"])) {
                $id_usu = $data["id_usu"];
                $fecha_nacimiento = $data["fecha_nacimiento"];
                $nombre = $data["nombre"];
                $apellidos = $data["apellidos"];
                $usuario = $data["usuario"];
                $contra = password_hash($data["contra"], PASSWORD_DEFAULT);
                
                $usuarios = getAllUsuarios($conectada);
                foreach($usuarios as $usu){
                    if($usu["usuario"] == $usuario){
                        return "Ya hay un usuario creado con el mismo nombre.";
                    }
                }
                
                $stmt = $conectada->prepare("INSERT INTO usuario (id_usu, fecha_nacimiento, nombre, apellidos, usuario, contra) VALUES (?,?,?,?,?,?)");
                $stmt->bind_param("isssss", $id_usu, $fecha_nacimiento, $nombre, $apellidos, $usuario, $contra);
                $stmt->execute();
                $stmt->close();
                
                return "Usuario creado correctamente";
            } else {
                return "Faltan parámetros requeridos";
            }
        }
        return "Método no permitido";
    }

    function updateUsuById($conectada, $id_usu){
        if($_SERVER["REQUEST_METHOD"] == "PUT"){
            // Get JSON data
            $json = file_get_contents('php://input');
            $datos = json_decode($json, true);
            
            $fecha_nacimiento = $datos["fecha_nacimiento"];
            $nombre = $datos["nombre"];
            $apellidos = $datos["apellidos"];
            $usuario = $datos["usuario"];
            
            $stmt = $conectada->prepare("UPDATE usuario SET fecha_nacimiento=?, nombre=?, apellidos=?, usuario=? WHERE id_usu=?");
            $stmt->bind_param("ssssi", $fecha_nacimiento, $nombre, $apellidos, $usuario, $id_usu);
            $stmt->execute();
            $stmt->close();
        } else {
            return "Peticion incorrecta";
        }
        return "Usuario actualizado correctamente";
    }
    function deleteUsuById($conectada, $id_usu){
        if (!$id_usu) {
            return "Error: ID de usuario no proporcionado";
        }
        
        $stmt = $conectada->prepare("DELETE FROM usuario WHERE id_usu=?");
        $stmt->bind_param("i", $id_usu);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return "Usuario eliminado correctamente";
        } else {
            $stmt->close();
            return "No se encontró el usuario con ID: " . $id_usu;
        }
    }

        $conectada = myConexion();

        switch($_SERVER["REQUEST_METHOD"]){
            case 'GET':
                if(isset($_GET["id_usu"])){
                    $id_usu = $_GET["id_usu"];
                    $datosUsuario = getUsuById($conectada, $id_usu);
                    echo json_encode($datosUsuario);
                }else{
                    $usuarios = getAllUsuarios($conectada); 
                    echo json_encode($usuarios);
                }
                break;

            case 'POST':
                $datosUsuario = createUsu($conectada);
                echo json_encode($datosUsuario);
                break;    
                case 'PUT':
                    $json = file_get_contents('php://input');
                    $datos = json_decode($json, true);
                    
                    if (!isset($datos["id_usu"])) {
                        echo json_encode("Error: ID de usuario no proporcionado");
                        break;
                    }
                    
                    $id_usu = $datos["id_usu"];
                    $datosUsuario = updateUsuById($conectada, $id_usu);
                    echo json_encode($datosUsuario);
                    break;
            case 'DELETE':
                $json = file_get_contents('php://input');
                $datos = json_decode($json, true);
                
                if (!isset($datos["id_usu"])) {
                    echo json_encode("Error: ID de usuario no proporcionado");
                    break;
                }
                
                $id_usu = $datos["id_usu"];
                $datosUsuario = deleteUsuById($conectada, $id_usu);
                echo json_encode($datosUsuario);
                break;
            default:
                header("HTTP/1.1 405 Method Not Allowed");
                break;    
        }
?>