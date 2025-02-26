<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Calculadora mas completa</h1>
    <form method="POST" action="#">
        <label for="num1">A:</label>
        <input type="number" id="num1" name="num1" required>
        <label for="num2">B:</label>
        <input type="number" id="num2" name="num2" required>
        <input type= "submit" name="calc" value="suma">
        <input type= "submit" name="calc" value="resta">
        <input type= "submit" name="calc" value="multiplicacion">
        <input type= "submit" name="calc" value="division">
    </form>
</body>
</html>

<?php
    if(isset($_POST['calc'])){
        switch($_POST['calc']){
            case'suma':
                echo "La suma es: ".($_POST['num1'] + $_POST['num2']);
                break;
            case'resta':
                echo "La resta es: ".($_POST['num1'] - $_POST['num2']);
                break;
            case'multiplicacion':
                echo "La multiplicacion es: ".($_POST['num1'] * $_POST['num2']);
                break;
            case 'division':
                if($_POST['num2']!=0){
                    echo "La division es: ".($_POST['num1'] / $_POST['num2']);
                }else{
                    echo "Error: Division por 0";
                }
                break;
            default:
                echo "Error: Seleccione una operacion";
        }
    }

?>