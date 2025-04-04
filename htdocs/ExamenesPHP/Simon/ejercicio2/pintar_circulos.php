<?php
    function pintarCirculos($col1, $col2, $col3, $col4){
            echo "<input style='background-color: $col1; width:50px; height:50px; border-radius:50%; margin:5px; color: transparent;' name='color1' value= ".$col1."></input>";
            echo "<input style='background-color: $col2; width:50px; height:50px; border-radius:50%; margin:5px; color: transparent;' name='color2' value= ".$col2."></input>";
            echo "<input style='background-color: $col3; width:50px; height:50px; border-radius:50%; margin:5px; color: transparent;' name='color3' value= ".$col3."></input>";
            echo "<input style='background-color: $col4; width:50px; height:50px; border-radius:50%; margin:5px; color: transparent;' name='color4'  value= ".$col4."></input>";
        
    }
?>