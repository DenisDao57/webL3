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
include "util_bdd.php";


?>

<script type="text/javascript" src="js/sidebar.js"></script>


<body>
    <div id="Sidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

        <?php include "Arbre.php"; ?>

    </div>

    <div id="main">

    <div style="color:white">
        <form id="filtre_form" action="index.php" method="post" class="p-3 border border-primary form-inline">


                <?php

                if (isset($_SESSION['login'])) {
                    if ($_SESSION['login'] = true) {
                        echo "<div style='color:black;margin-right:1%'>Favoris</div>";
                        echo '<input style="color:black;margin-right:4%" type="checkbox" id="checkfavoris" name="favoris" value="favoris">';
                    }
                }

                ?>
            <input list="recettes"name="filtrage_nom" class="form-control" placeholder="Nom cocktail">
            <datalist id="recettes">
                    <?php
                    $liste_recette=array();
                    $liste_recette=getRecettes();
                    for ($i=0;$i<sizeof($liste_recette);$i++){
                        echo "<option value='".$liste_recette[$i]."'>";
                    }

                    ?>
            </datalist>
            <button id="btn_filtre" type="submit" class="btn btn-primary">Rechercher</button>
        </form>
    </div>


        <div class="list-group">

            <?php

            $favoris=false;

            if (isset($_POST["favoris"])){
                if ($_POST["favoris"]=="favoris"){
                    $favoris=true;
                }
            }


            if (isset($_GET["ingredientName"]))
            {
                affichage_liste_filtre_by_ingredient($_GET["ingredientName"]);
            }else{
                if (isset($_POST["filtrage_nom"])) {
                    affichage_liste_filtre($_POST["filtrage_nom"],$favoris,"index");
                } else affichage_liste_filtre("",$favoris,"index");
            }

            ?>



        </div>

    </div>
</body>

</html>
