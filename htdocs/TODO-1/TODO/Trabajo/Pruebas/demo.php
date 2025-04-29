<?php
    const API_URL = "https://whenisthenextmcufilm.com/api";
    $ch = curl_init(API_URL);
    //inidicar que queremos recibir el reusltado de la peticion y no mostrarla en pantalla
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    /*ejecutar la peticion y guardamos el resultado*/
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    curl_close($ch);
//var_dump($data);
?>

<head>
    <title>Proxima pelicula</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.min.css"
    />
</head>
<main>
   <section>
        <h2> Proxima pelicula</h2>
        <img src="<?=$data["poster_url"];?>" width="300" alt="Poster de <?=$data["title"];?>"/>
   </section>
   <hgroup>
        <h2><?= $data["title"];?> se estrena en <?= $data["days_until"];?> días </h2>
        <p>Fecha de estreno: <?=$data["release_date"];?></p>
        <p>La siguiente es: <?=$data["following_production"]["title"];?></p>
   </hgroup>
    
</main>