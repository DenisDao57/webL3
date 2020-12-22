<html>

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/style.css">
<?php


session_start();

if (isset($_SESSION['login'])) { // Gestion du header
    if ($_SESSION['login'] = true) {
        include 'navbar/Header_logged.php';
    } else include 'navbar/Header_nolog.php';
} else include 'navbar/Header_nolog.php';

include "donnees/Donnees.inc.php";


?>

<script type="text/javascript" src="js/sidebar.js"></script>

<body>

    <div id="Sidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

        <?php include "Arbre.php"; ?>

    </div>

    <div id="main">

        <div class="list-group">

            <?php

            for ($i = 0; $i < sizeof($Recettes); $i++) { // Pour chaque recette
                echo '<a href="#" class="list-group-item list-group-item-action flex-column align-items-start ">';
                for ($a = 0; $a < sizeof(array_keys($Recettes[$i])); $a++) { // Pour une recette en particulier (pour chaque clé de l'array d'une recette)
                    $recette_key = array_keys($Recettes[$i])[$a];
                    if ($recette_key != "index") {
                        if ($recette_key == "titre") { // Traitement du titre
                            
                            echo '<div class="d-flex w-100 justify-content-between">';
                            echo '<h2 class="mb-1">' . $Recettes[$i][$recette_key] . '</h2>'; // Affichage du titre
                     
                            if (isset($_SESSION["login"])) {
                                if ($_SESSION["login"]) {
                                    echo '<button type="button" class="btn btn-danger">Favoris</button>';
                                }
                            };
                            echo "</div>";
                        } else { // Traitement des autres champs (texte)
                            echo "<h4>" . ucfirst($recette_key) . " : </h4>"; // Affichage sous titre
                            echo "<p>".$Recettes[$i][$recette_key]."</p>"; // Affichage texte
                        }
                    } else { // Traitement de INDEX (ingrédients)
                        $index = $Recettes[$i][$recette_key];
                        echo "<ol>";
                        for ($s = 0; $s < sizeof($index); $s++) { // Pour chaque item de l'array index
                            echo "<li>" . $index[$s] . "</li>"; // Affichage ingrédients
                        }
                        echo "</ol>";
                    }
                }
                echo "</a>";
            }

            ?>



        </div>

    </div>
</body>

</html>