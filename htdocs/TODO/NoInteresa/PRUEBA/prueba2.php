
<?php

    $numeros = [10, 20, 30, 40, 50]; 
    $suma=0; 
    $media =0;

        foreach($numeros as $numero){
            $suma += $numero; 
        }
        $media = $suma/count($numeros);
        echo "Media: $media <br>";
        echo "Suma: $suma";

        echo "<br>_______________________ <br>";
        $suma =0;
        $contador=0; 

       for($i=0; $i<=4; $i++){
            $V[$i] = $contador += 10;
            $suma = $suma+$V[$i];
            echo "suma: $suma ";
        }
        $media = $suma/$i;
        echo "Media: ".$media."<br>";
        var_dump($V);
?>