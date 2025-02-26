<?php
function pintarEmojis(){
   
    /*al pulsar el boton INCREMENTAR se vea uno aleatorio*/
    shuffle($emojis);
    $randomEmoji = $emojis[array_rand($emojis)];
    echo "<img src='img/$randomEmoji' alt='emoji'>";
}

?>