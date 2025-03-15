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


     function getUsuarioPorId($conectada, $id_usu){
        $sqlUsuario = "SELECT * FROM usuario WHERE id_usu = $id_usu";
        $stmt = $conectada ->query($sqlUsuario); 
        $stmt->bind_param("i", $id_usu);
        $stmt->execute();
   
       $resultado = $stmt->get_result();
       $usuario = $resultado->fetch_assoc();
   
       $stmt->close();
       
       return $usuario ?: null; 
        
     }

        function createUsuarios($conectada /*añadir parametros despues*/){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $fecha_nacimiento = $_POST["fecha_nacimiento"];
                $nombre = $_POST["nombre"];
                $apellidos = $_POST["apellidos"];
                $usuario = $_POST["usuario"];
                $contra = password_hash($_POST["contra"], PASSWORD_DEFAULT);
                
                $usuarios = getAllUsuarios($conectada);
                foreach($usuarios as $usuario){
                    if($usuario["usuario"] == $usuario){
                        return "Ya hay un usuario creado con el mismo nombre.";
                    }
                }
            }
            $stmt = $conectada->prepare("INSERT INTO usuario (fecha_nacimiento, nombre, apellidos, usuario, contra) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $fecha_nacimiento, $nombre, $apellidos, $usuario, $contra);
            $stmt->execute();
            $stmt->close();



            return "Usuario creado correctamente";
        }

        function updateUsuario($conectada, $id_usu /*añadir parametros despues*/){
            if($_SERVER["REQUEST_METHOD"] == "PUT"){
                parse_str(file_get_contents("php://input"), $datos);
                $fecha_nacimiento = $datos["fecha_nacimiento"];
                $nombre = $datos["nombre"];
                $apellidos = $datos["apellidos"];
                $usuario = $datos["usuario"];
                
                $stmt = $conectada->prepare("UPDATE usuario SET fecha_nacimiento=?, nombre=?, apellidos=?, usuario=? WHERE id_usu=?");
                $stmt->bind_param("ssssii", $fecha_nacimiento, $nombre, $apellidos, $usuario, $id_usu);
                $stmt->execute();
                $stmt->close();
            }else{
            return "Peticion incorrecta";
            }
            return "Usuario actualizado correctamente";
        }
        function deleteUsuario($conectada, $id_usu){
            $stmt = $conectada->prepare("DELETE FROM usuario WHERE id_usu=?");
            $stmt->bind_param("i", $id_usu);
            $stmt->execute();
            $stmt->close();
            return "Usuario eliminado correctamente";
        }

        $conectada = myConexion();

        switch($_SERVER["REQUEST_METHOD"]){
            case 'GET':
                if(isset($_GET["id_usu"])){
                    $id_usu = $_GET["id_usu"];
                    $datosUsuario = getUsuarioPorId($conectada, $id_usu);
                    echo json_encode($datosUsuario);
                }else{
                    $usuarios = getAllUsuarios($conectada); 
                    echo json_encode($usuarios);
                }
                break;

            case 'POST':
                $datosUsuario = createUsuarios($conectada);
                echo json_encode($datosUsuario);
                break;    
            case 'PUT':
                $id_usu = $_GET["id_usu"];
                $datosUsuario = updateUsuario($conectada, $id_usu);
                echo json_encode($datosUsuario);
                break;
            case 'DELETE':
                $id_usu = $_GET["id_usu"];
                $datosUsuario = deleteUsuario($conectada, $id_usu);
                echo json_encode($datosUsuario);
                break;
            default:
                header("HTTP/1.1 405 Method Not Allowed");
                break;    
        }
?>