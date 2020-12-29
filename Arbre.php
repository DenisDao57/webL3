<html>

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="test/tree.css">
<?php



include "donnees/Donnees.inc.php";

function indexCategorie(string $categorie){
    include "donnees/Donnees.inc.php";
    $hierarchie_keys=array_keys($Hierarchie);
    for ($i=0;$i<sizeof($Hierarchie);$i++){ //Pour chaque catégorie
        $nb_categorie=sizeof($Hierarchie[$hierarchie_keys[$i]]); // Nombre de chaque sous categorie
        if (strcmp(array_keys($Hierarchie)[$i],$categorie)==0){
            return $i;
        }
    }
    return -1;
}

function sousCategorie(string $categorie){
    include "donnees/Donnees.inc.php";
    $array_categorie=array();
    $hierarchie_keys=array_keys($Hierarchie);
    for ($i=0;$i<sizeof($Hierarchie);$i++){ //Pour chaque catégorie
        $nb_categorie=sizeof($Hierarchie[$hierarchie_keys[$i]]); // Nombre de chaque sous categorie
        if (strcmp(array_keys($Hierarchie)[$i],$categorie)==0){ // On filtre la bonne catégorie
            for ($a=0;$a<$nb_categorie;$a++){ // Pour chaque sous categorie
                $array_categorie=$Hierarchie[$hierarchie_keys[$i]]; // Pour chaque catégorie on a l'array des sous/super categorie
                $key_categorie = array_keys($array_categorie);
                if (strcmp($key_categorie[$a],"sous-categorie")==0){ // Si il y a une sous categorie
                    $categorie_keys=array_keys($array_categorie[$key_categorie[$a]]); //Array des clés des sous/super categorie
                    for ($s=0;$s<sizeof($categorie_keys);$s++){ // Pour chaque élement de la sous catégorie
                        $element = $array_categorie[$key_categorie[$a]][$categorie_keys[$s]];
                        array_push($array_categorie,$element);
                    }
                    return $array_categorie;
                }
                echo"</ol>";
                
            }
        }
    }
    return -1;
}

function superCategorie(string $categorie){
    include "donnees/Donnees.inc.php";
    $array_categorie=array();
    $hierarchie_keys=array_keys($Hierarchie);
    for ($i=0;$i<sizeof($Hierarchie);$i++){ //Pour chaque catégorie
        $nb_categorie=sizeof($Hierarchie[$hierarchie_keys[$i]]); // Nombre de chaque sous categorie
        if (strcmp(array_keys($Hierarchie)[$i],$categorie)==0){ // On filtre la bonne catégorie
            for ($a=0;$a<$nb_categorie;$a++){ // Pour chaque sous categorie
                $array_categorie=$Hierarchie[$hierarchie_keys[$i]]; // Pour chaque catégorie on a l'array des sous/super categorie
                $key_categorie = array_keys($array_categorie);
                if (strcmp($key_categorie[$a],"super-categorie")==0){ // Si il y a une sous categorie
                    $categorie_keys=array_keys($array_categorie[$key_categorie[$a]]); //Array des clés des sous/super categorie
                    for ($s=0;$s<sizeof($categorie_keys);$s++){ // Pour chaque élement de la sous catégorie
                        $element = $array_categorie[$key_categorie[$a]][$categorie_keys[$s]];
                        array_push($array_categorie,$element);
                    }
                    return $array_categorie;
                }
                echo"</ol>";
                
            }
        }
    }
    return -1;
}


$array_utilise = array();
$index_aliment = indexCategorie("Aliment");


echo "<ul style='color:white;' id='myUL'>";
echo "<h2 class='text-center'>Aliment</h2>";

$sous_categorie_aliment=sousCategorie("Aliment");
// Aliment est print en dur car c'est le plus haut de la hierarchie complète
for ($i=0;$i<sizeof($sous_categorie_aliment)-1;$i++){ // Pour chaque sous catégorie de aliment 
    $subcategorie1=sousCategorie((String) $sous_categorie_aliment[$i]);
    if ($subcategorie1!=-1) echo"<li> <span class ='caret'> </span>".$sous_categorie_aliment[$i];
    else echo "<li onclick=\"onClickAliment('".$sous_categorie_aliment[$i]."')\" id='aliment'>".$sous_categorie_aliment[$i]."</li>";
    echo "<ul class='nested'>";
    if($subcategorie1!=-1){
        for ($a=0;$a<sizeof($subcategorie1)-2;$a++){
            $subcategorie2=sousCategorie((String) $subcategorie1[$a]);

            if ($subcategorie2==-1)echo"<li onclick=\"onClickAliment('".$subcategorie1[$a]."')\" id='aliment'>".$subcategorie1[$a]."</li>";
            else echo"<li><span class = 'caret'></span>".$subcategorie1[$a];

            echo "<ul class='nested'>";
            if($subcategorie2!=-1){
                for ($b=0;$b<sizeof($subcategorie2)-2;$b++){
                    $subcategorie3=sousCategorie((String) $subcategorie2[$b]);

                    if($subcategorie3==-1) echo"<li onclick=\"onClickAliment('".$subcategorie2[$b]."')\" id='aliment'>".$subcategorie2[$b]."</li>";
                    else echo"<li><span class='caret'></span>".$subcategorie2[$b];

                    echo "<ul class='nested'>";
                    if($subcategorie3!=-1){
                        for ($c=0;$c<sizeof($subcategorie3)-2;$c++){
                            $subcategorie4=sousCategorie((String) $subcategorie3[$c]);

                            if ($subcategorie4==-1) echo"<li onclick=\"onClickAliment('".$subcategorie3[$c]."')\" id='aliment'>".$subcategorie3[$c]."</li>";
                            else echo"<li><span class='caret'></span>".$subcategorie3[$c];
                            
                            echo "<ul class='nested'>";
                            if($subcategorie4!=-1){
                                for ($d=0;$d<sizeof($subcategorie4)-2;$d++){
                                    echo"<li onclick=\"onClickAliment('".$subcategorie4[$d]."')\" id='aliment'> ".$subcategorie4[$d]."</li>";

    
                                }
                        
                            }
                            echo "</ul>";
                            echo "</li>";
                            
                        }
                
                    }
                    echo "</ul>";
                    echo "</li>";
                }
        
            }
            echo "</ul>";
            echo "</li>";
        }

    }
        echo "</ul>";
        echo "</li>";
}
 echo "</ul>";   


?>

<script>

var toggler = document.getElementsByClassName('caret');
var i;

for (i = 0; i < toggler.length; i++) {
  toggler[i].addEventListener("click", function() {
    this.parentElement.querySelector('.nested').classList.toggle("active");
    this.classList.toggle("caret-down");
  });
}

</script>

</html>