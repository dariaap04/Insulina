<?php
session_start();
require 'conexion.php';

/*if(!isset($_SESSION['login'])){
	header('Location: index.php');
	exit();

}*/

$usuario = $_SESSION['login'];
$solucion1 = isset($_POST['solucion']) ? $_POST['solucion']: '';
$solucion = trim($solucion1);


if (!empty($solucion)){
$sql = "INSERT INTO  respuestas(login,fecha, resultado) VALUES (?,NOW(), ?)";
$resultado = verificarSolucion($solucion);
$stmt= $conn -> prepare($sql);
$stmt-> bind_param("ssi", $usuario, $solucion,$resultado);

if($stmt-> execute()){
	echo "Solucion enviada correctamente";
}else{
	echo"Error al guardar la solucion: ".$conn->error;
}


}else{
	echo "La solucion no puede estar vacia";
}
$conn->close();

function verificarSolucion($solucion){

if(!is_string($solucion)){
	return 0;
}
$solucionCorrecta= "Que sonada";
return strtolower(trim($solucion)) === strtolower($solucionCorrecta) ? 1 : 0;
}
?>