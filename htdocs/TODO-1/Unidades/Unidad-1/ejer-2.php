<?php 
    $n1 = 1;
    $n2 = 9; 
    $n3 = 4; 

    if($n1 > $n2 && $n1 >$n3){
        echo $n1; 
    }elseif($n2>$n1 && $n2 > $n3){
        echo $n2;
    }elseif($n3>$n2 && $n3>$n1){
        echo $n3;
    }


?>