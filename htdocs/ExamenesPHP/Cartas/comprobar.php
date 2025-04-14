<?php
    session_start(); 
    for($i=0; $i<6; $i++){
        $_SESSION["ocultas"][$i]="boca_abajo.jpg";
    }
    $index = $_POST["levantar"]; 
    $_SESSION["contador"]++; 
    echo$_SESSION["ocultas"][$index]=$_SESSION['cartas'][$index];
    header('Location: mostrarcartas.php');
    exit;

?>