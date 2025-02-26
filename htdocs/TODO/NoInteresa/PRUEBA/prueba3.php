<?php

    $v = array(array(15,10,25,8),
                array(3,2,1,7),
                array(19,25,10,8),
                array(9,15,25,13)
            );

            $suma1=0;
            $suma2=0;
       for($i=0; $i<=3; $i++){
        
           $suma1+=$v[$i][$i];
       };
           for($j=0; $j<=3; $j++){
            $suma2+= $v[$j][3-$j];
           };
   
       echo "Diagonal principal: $suma1";
         echo "<br>Diagonal secundaria: $suma2";
    

?>