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

    <div style="color:white">
        <form action="Accueil.php" method="post" class="form-inline">
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

    <div id="Sidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

        <?php include "Arbre.php"; ?>

    </div>

    <div id="main">

        <div class="list-group">

            <?php

            if (isset($_POST["filtrage_nom"])) {
                affichage_liste_filtre($_POST["filtrage_nom"]);
            } else affichage_liste_filtre("");

            ?>



        </div>

    </div>
</body>

</html>