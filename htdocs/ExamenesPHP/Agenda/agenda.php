<?php
    session_start(); 
    $usuario = $_SESSION["usuario"];
    $contador = $_SESSION["contador"];
     var_dump($contador);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table, th{
            border: "1px solid black";
            border-collapse: collapse;
            padding: 5px;
        }


    </style>
</head>
<body>
    <h1>AGENDA</h1>
    <h3>Hola, <?php echo $usuario?></h3>
    <form method='POST' action= 'grabar.php' >
    <?php
        for($i=0; $i<$contador; $i++){
            $num = $i+1;
            echo"
                <table>
                    <th> Contacto $num
                        <br>
                            <label>Nombre $num
                                <input type='text' name='nombre'/>
                            </label>
                            <br>
                            <label>Email $num
                                <input type='email' name='email'/>
                            </label>
                            <br>
                            <label>Telefono $num
                                <input type='number' name='telefono'/>
                            </label>
                    </th>
                </table>
            
            ";
        }
    
    
    ?>
    <button type="submit" name="grabado">Grabar</button>
    </form>
    
</body>
</html>