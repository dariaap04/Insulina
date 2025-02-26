
<?php
    $age = 20; 
    $name = "Miguel";
    $isDev = true;
    echo "<h1>$name</h1>";
    $newAge  = match(true){
        $age <2=> "Eres un niño",
        $age <10 => "Eres un joven",
        $age >10 => "Eres un adulto",
        default => "Eres un adulto mayor"
    };

    $bestLanguages = ["English", "JavaScript", "CSS", "PHP"];

?>

<h2><?php echo $newAge; ?></h2>
<ul>
    <?php foreach($bestLanguages as $language) :?>
            <li><?=$language?></li>
    <?php endforeach; ?>    
</ul>



<style>
    :root{
        color-scheme: light dark;
    }
    body{
        display: grid;
        place-content: center;
    }
</style>
