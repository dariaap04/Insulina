<?php
    function pintarCirculos($col1, $col2, $col3, $col4){
            echo "<input class='circulo' style='background-color: $col1; width:50px; height:50px; border-radius:50%; margin:5px; ' name='color1'></input>";
            echo "<input class='circulo' style='background-color: $col2; width:50px; height:50px; border-radius:50%; margin:5px; ' name='color2'></input>";
            echo "<input class='circulo' style='background-color: $col3; width:50px; height:50px; border-radius:50%; margin:5px; ' name='color3'></input>";
            echo "<input class='circulo' style='background-color: $col4; width:50px; height:50px; border-radius:50%; margin:5px; ' name='color4'></input>";
        
    }
?>