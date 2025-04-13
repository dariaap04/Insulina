<?php
session_start();
$usuario = $_SESSION["usuario"];


if(!isset($_SESSION["contador"])){
    $_SESSION["contador"] =0;
}
$contador = $_SESSION["contador"];

function myConexion(){
    include "conexion.php";
    $conexion = new mysqli($localhost, $username, $pw, $database);
    if($conexion->connect_error){
        die("Conexion fallida");
    }
    return $conexion; 
}
$conectada = myConexion();

 $imagenMostrada="";
    
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(isset($_POST["incrementar"])){
            if($_SESSION["contador"]<5){
                $_SESSION["contador"]++;
               
            }
            var_dump($_SESSION["contador"]);
        }

        
    }

    if (isset($_POST['reiniciar'])) {
        // Restablecer las variables de sesión a su estado inicial
        $_SESSION["contador"] = 0;
        header("Location: ".$_SERVER['PHP_SELF']); // Recargar la página
        exit;
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>AGENDA</h1>
    <table style="border: 1px;">
        <th style="border: 1px;">
            <ul>
                <li>Hola, <?php echo $usuario?> ¿cuantos contactos deseas guardar?</li>
                <li>Puedes grabar entre 1 y 5. Por cada pulsación en INCREMENTAR grabarás un usuario más</li>
                <li>Cuando el numero sea el deseado pulsa GRABAR</li>
            </ul>
        </th>
    </table>
    <form method="post">
    <button type="submit" name="incrementar" <?php if($_SESSION["contador"] >= 5) echo "disabled"; ?>>INCREMENTAR</button>

        <button type="submit" name="grabar"> GRABAR</button>
        <button type="submit" name="reiniciar">REINICIAR</button>
    </form>
    <p>Has pulsado <?php echo $_SESSION["contador"];  ?>veces.</p>


    <?php
    $imagenes = ["OIP0.jfif", "OIP1.jfif", "OIP2.jfif", "OIP3.jfif", "OIP4.jfif"];
    for($i = 0; $i < $_SESSION["contador"]; $i++): ?>
        <img src="<?php echo htmlspecialchars($imagenes[$i]); ?>" alt="" >
    <?php endfor; ?>


</body>
</html>