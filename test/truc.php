<html>

<link rel="stylesheet" href="bootstrap.css">

<?php

include "Donnees.inc.php";

echo "<h2>Recettes : </h2>";

for ($i=0 ; $i<sizeof($Recettes) ; $i++){ // Pour chaque recette
    echo "<ul>";
     for ($a=0;$a<sizeof(array_keys($Recettes[$i]));$a++){ // Pour une recette en particulier (pour chaque clé de l'array d'une recette)
        $recette_key=array_keys($Recettes[$i])[$a];
        if ($recette_key!="index"){ 
            if ($recette_key=="titre"){ // Traitement du titre
                echo "<h2>".$Recettes[$i][$recette_key]."</h2>";
            }else{ // Traitement des autres champs
                echo "<li>".$recette_key." :";
                echo $Recettes[$i][$recette_key]."</li>";
            }
        }else{ // Traitement de INDEX
            $index=$Recettes[$i][$recette_key];
            echo "<ol>";
            for($s=0;$s<sizeof($index);$s++){ // Pour chaque item de l'array index
                echo "<li>".$index[$s]."</li>";
            }
            echo "</ol>";
        }
     }
     echo "</ul>";
}

for ($i=0;$i<sizeof($Hierarchie);$i++){ //Pour chaque catégorie
    $hierarchie_keys=array_keys($Hierarchie);
    $nb_categorie=sizeof($Hierarchie[$hierarchie_keys[$i]]); // Nombre de chaque sous categorie
    echo "<h1>".array_keys($Hierarchie)[$i]."</h1>"; // Nom de chaque catégorie
    for ($a=0;$a<$nb_categorie;$a++){ // Pour chaque sous categorie
        $array_categorie=$Hierarchie[$hierarchie_keys[$i]]; // Pour chaque catégorie on a l'array des sous/super categorie
        $key_categorie = array_keys($array_categorie);
        echo "<h3>".$key_categorie[$a]."</h3>"; // nom de la sous categorie
        echo"<ol>";
        $categorie_keys=array_keys($array_categorie[$key_categorie[$a]]); //Array des clés des sous/super categorie
        for ($s=0;$s<sizeof($categorie_keys);$s++){ // Pour chaque élement de la sous catégorie
            $element = $array_categorie[$key_categorie[$a]][$categorie_keys[$s]];
            echo "<li>";
            echo ($element); // Chaque element des sous/super categorie
            echo "</li>";
        }
        echo"</ol>";
        
    }
}


?>

</html>