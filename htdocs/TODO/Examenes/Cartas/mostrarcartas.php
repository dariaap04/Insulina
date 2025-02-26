<?php
session_start();

// Inicializa las cartas y el estado inicial
if (!isset($_SESSION["cartas"])) {
    $cartas = array("copas_02.jpg", "copas_03.jpg", "copas_05.jpg", "copas_02.jpg", "copas_03.jpg", "copas_05.jpg");
    shuffle($cartas);
    $_SESSION["cartas"] = $cartas;
    $_SESSION["negro"] = array_fill(0, count($cartas), "boca_abajo.jpg"); // Todas las cartas comienzan boca abajo
    $_SESSION["contador"] = 0; // Contador de cartas levantadas
    $_SESSION["ultimaCartaLevantada"] = -1; // Ninguna carta levantada al inicio
}

// Procesa el botón presionado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Todas las cartas vuelven a estar boca abajo
    $_SESSION["negro"] = array_fill(0, count($_SESSION["cartas"]), "boca_abajo.jpg");

    // Verifica qué carta se levantó
    foreach ($_SESSION["negro"] as $key => $value) {
        if (isset($_POST["carta" . ($key +1)])) {
            $_SESSION["negro"][$key] = $_SESSION["cartas"][$key]; // Levanta la carta seleccionada
            $_SESSION["ultimaCartaLevantada"] = $key; // Actualiza la última carta levantada
            $_SESSION["contador"]++; // Incrementa el contador
            break;
        }
    }
}

function pintarCartas() {
    foreach ($_SESSION["negro"] as $imagen) {
        echo '<img src="' . $imagen . '" alt="Carta">';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de Cartas</title>
    <style>
        img {
            width: 150px;
            height: 200px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <h1>Bienvenid@, <?php echo $_SESSION["login"] ?? "Jugador"; ?></h1>
    <h3>Cartas levantadas: <?php echo $_SESSION["contador"]; ?></h3>
    <form method="POST" action="#">
        <?php for ($i = 1; $i <= count($_SESSION["cartas"]); $i++): ?>
            <button type="submit" name="carta<?php echo $i; ?>">Levantar Carta <?php echo $i; ?></button>
        <?php endfor; ?>
    </form>
    <h1>Cartas</h1>
    <?php pintarCartas(); ?>
    <form method="POST" action="resultado.php">
        <h1>Pareja:</h1>
        <input type="number" name="pareja1"></input>
        <input type="number" name="pareja2"></input>
        <button type="submit">Comprobar</button>
    </form>
   
</body>
</html>
