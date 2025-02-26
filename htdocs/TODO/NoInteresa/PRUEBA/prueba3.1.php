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
   $suma2+= $v[3-$i][$i];
};

echo "Diagonal principal: $suma1";
echo "<br>Diagonal secundaria: $suma2";

for ($i=0; $i < ; $i++) { 
    # code...
}
//calculo de la media

$suma=0;
$contador=0; 
for($i=0; $i<=4; $i++){
    $V[$i] = $contador += 10;
    $suma = $suma+$V[$i];
}

//hora de la media

$media = $suma/$i;
echo "<br>Media: ".$media."<br>";
var_dump($V);
 //calculo de la moda
?>