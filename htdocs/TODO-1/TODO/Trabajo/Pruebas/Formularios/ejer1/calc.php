<?php
    if(isset($_POST['Enviar'])){
        $suma = $_POST["num1"]+$_POST["num2"];
        echo "La suma de los dos números es: $suma";
    }

?>