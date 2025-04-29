<?php
    session_start();
    require_once "conexion.php"; 
    $usuario = $_SESSION["usuario"];
    $contador = $_SESSION["contador"];

    $conexion = new mysqli($localhost, $username, $pw, $database);

    if($conexion->connect_error){
        die("Error de conexion");
    } 

    /* Buscamos los datos de la tabla de contactos */
    $sql = $conexion->prepare("SELECT COUNT(*) AS total_contactos
    FROM contactos
    WHERE codusuario = (
        SELECT Codigo FROM usuarios WHERE Nombre = ?
    )
");
    $sql ->bind_param("s", $usuario);
    $sql->execute();
    $result=$sql->get_result(); 
    $total_contactos = 0;
    if($row= $result->num_rows>0){
       $total_contactos = $row["total_contactos"];
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
    <h3>Hola <?php echo $usuario ?></h3>
    <table>
       <thead>
            <tr>
                <th>Código Usuario </th>
                <th> Nombre</th>
                <th>Nº contactos</th>
                <th>Gráfica</th>
            </tr>
       </thead>
       <tbody>
            <tr>
                <td>?</td>
                <td><?php
                    $usuario

                
                ?></td>
            </tr>
       </tbody>
    </table>
</body>
</html>