<?php
   /*primer array facil*/ 
   $contador =1; 
   for($i=0; $i <=1; $i++){
    for($j=0; $j<=2; $j++){ //se crean a medida que se va desarrollando
        $M[$i][$j] =$contador;
        $contador++;
    }
   }
   echo $M[1][2]."<br>"; 
    
   //padre nuestro; muestra el contenido del array 
   var_dump($M);
?>