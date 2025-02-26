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

    /* Si se han rellenado los datos, verificamos si se han enviado */ 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
        if (isset($_POST["fecha"], $_POST["comida"], $_POST["gl1h"], $_POST["rac"], $_POST["insulina"], $_POST["gl2h"])) { 

            $fecha = $_POST["fecha"]; 
            $comida = $_POST["comida"]; 
            $gl1h = $_POST["gl1h"]; 
            $rac = $_POST["rac"]; 
            $insulina = $_POST["insulina"]; 
            $gl2h = $_POST["gl2h"]; 
            $estado_glucosa = $_POST["estado_glucosa"]; 

          // Verificar el valor de glucosa (hipoglucemia o hiperglucemia)
$glucosa = null;
$hora = null;
$correccion = null;

if ($estado_glucosa === 'hipo') {
    if (isset($_POST["glucosa1"])) {
        $glucosa = (int) $_POST["glucosa1"];
    }
    if (isset($_POST["hora1"])) {
        $hora = $_POST["hora1"];
    }
} elseif ($estado_glucosa === 'hiper') {
    if (isset($_POST["glucosa2"])) {
        $glucosa = (int) $_POST["glucosa2"];
    }
    if (isset($_POST["hora2"])) {
        $hora = $_POST["hora2"];
    }
    if (isset($_POST["correccion"])) {
        $correccion = (int) $_POST["correccion"];
    }
} 

            // Consultar el ID del usuario, ya que está en todas las tablas (id_usu)
            $sql = "SELECT id_usu FROM usuario WHERE usuario = ?"; 
            $stmtUser = $con->prepare($sql);  
            $stmtUser->bind_param("s", $usuario); // s -> string  
            $stmtUser->execute(); 
            $resultUser = $stmtUser->get_result(); 
            if ($resultUser->num_rows > 0) { 
                $rowUser = $resultUser->fetch_assoc(); 
                $id_usu = $rowUser["id_usu"]; 
            }

            // Verificar si existe un registro en control_glucosa [fecha, deporte, lenta, id_usu]
            $sql = "SELECT * FROM control_glucosa WHERE fecha = ? AND deporte = ? AND lenta = ? AND id_usu = ?"; 
            $stmtControl = $con->prepare($sql);  
            $stmtControl->bind_param("ssii", $fecha, $deporte, $lenta, $id_usu);  
            $stmtControl->execute(); 
            $resultControl = $stmtControl->get_result(); 

            // Si no existe, insertamos
            if ($resultControl->num_rows == 0) { 
                $sqlInsert = "INSERT IGNORE INTO control_glucosa (fecha, deporte, lenta, id_usu) VALUES (?, 0, 0, ?)"; 
                $stmtInsert = $con->prepare($sqlInsert);  
                $stmtInsert->bind_param("si", $fecha, $id_usu);  
                $stmtInsert->execute(); 
            } 

          // Verificar si existe registro en "comida"
$sql = "SELECT * FROM comida WHERE tipo_comida = ? AND gl_1h = ? AND gl_2h = ? AND raciones = ? AND insulina = ? AND fecha = ? AND id_usu = ?"; 
$stmtComida = $con->prepare($sql);  
$stmtComida->bind_param("siiiisi", $comida, $gl1h, $gl2h, $rac, $insulina, $fecha, $id_usu);  
$stmtComida->execute(); 
$resultComida = $stmtComida->get_result(); 

// Si no existe, insertamos
if ($resultComida->num_rows == 0) { 
    $sqlInsert = "INSERT IGNORE INTO comida (tipo_comida, gl_1h, gl_2h, raciones, insulina, fecha, id_usu) VALUES (?, ?, ?, ?, ?, ?, ?)"; 
    $stmtInsert = $con->prepare($sqlInsert);  
    $stmtInsert->bind_param("siiiisi", $comida, $gl1h, $gl2h, $rac, $insulina, $fecha, $id_usu);  
    $stmtInsert->execute(); 
}

// Insertar en caso de HIPO o HIPER si se selecciona
if ($glucosa !== null && $glucosa > 0 && !empty($hora)) {
    if ($estado_glucosa === 'hiper') {
        if (isset($glucosa, $hora, $correccion, $comida, $fecha, $id_usu)) {
            var_dump($_POST); // Comprueba los datos POST.
            $sql = "SELECT * FROM hiperglucemia WHERE glucosa = ? AND hora = ? AND correccion = ? AND tipo_comida = ? AND fecha = ? AND id_usu = ?";
            $stmtHiper = $con->prepare($sql);
            $stmtHiper->bind_param("isissi", $glucosa, $hora, $correccion, $comida, $fecha, $id_usu);
            $stmtHiper->execute();
            $resultHiper = $stmtHiper->get_result();
            if ($resultHiper->num_rows == 0) {
                $sqlInsert = "INSERT INTO hiperglucemia (glucosa, hora, correccion, tipo_comida, fecha, id_usu) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtInsert = $con->prepare($sqlInsert);
                $stmtInsert->bind_param("isissi", $glucosa, $hora, $correccion, $comida, $fecha, $id_usu);
                try {
                    $stmtInsert->execute();
                    echo "hiperglucemia insertada";
                } catch (mysqli_sql_exception $e) {
                    echo "Error hiperglucemia: " . $e->getMessage() . " error mysql: ". mysqli_error($con);
                }
            }
        } else {
            echo "Error: Faltan datos necesarios para insertar el registro de hiperglucemia.";
        }
    }

    if ($estado_glucosa === 'hipo') {
        // Verificar si existe el registro de hipoglucemia
        $sql = "SELECT * FROM hipoglucemia WHERE glucosa=? AND hora=? AND tipo_comida = ? AND fecha = ? AND id_usu = ?";
        $stmtHipo = $con->prepare($sql);
        $stmtHipo->bind_param("isssi", $glucosa, $hora, $comida, $fecha, $id_usu);
        $stmtHipo->execute();
        $resultHipo = $stmtHipo->get_result();

        if ($resultHipo->num_rows == 0) {
            $sqlInsert = "INSERT IGNORE INTO hipoglucemia (glucosa, hora, tipo_comida, fecha, id_usu) VALUES (?, ?, ?, ?, ?)";
            $stmtInsert = $con->prepare($sqlInsert);
            $stmtInsert->bind_param("isssi", $glucosa, $hora, $comida, $fecha, $id_usu);
            $stmtInsert->execute();
        }
    }
}


            // Redirigir al ver resultados
            header('Location: verResultados.php'); 
            exit();
        } 
    }  
?>