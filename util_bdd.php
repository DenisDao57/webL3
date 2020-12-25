<?php


function ingredient_recette($id_recette, $db)
{

    $ingredients = array();

    foreach ($db->query('SELECT * FROM recettes as R,ingredient as I,ingredientpourrecette as IPR WHERE R.id=IPR.idRecette
    AND IPR.idIngredient=I.id
    AND IPR.idRecette=' . $id_recette) as $row) {

        array_push($ingredients, $row["nomIngredient"]);
    }

    return $ingredients;
}


function quantite_recette($id_recette, $db)
{

    $quantite = array();

    foreach ($db->query('SELECT * FROM recettes as R,ingredient as I,ingredientpourrecette as IPR WHERE R.id=IPR.idRecette
    AND IPR.idIngredient=I.id
    AND IPR.idRecette=' . $id_recette) as $row) {

        array_push($quantite, $row["quantity"]);
    }

    return $quantite;
}

function affichage_liste_filtre($nom, $favoris, $index)
{
    include "bdd.php";
    if (!(isset($_SESSION["login"])) && $favoris==true){ // Cas pas login & favoris (pour le tri par favori)
        if (isset($_SESSION["favoris"])){
            $sqlquery= 'SELECT * FROM recettes WHERE titre like "%'.$nom.'%"'; ///// BASE
            for ($i=0;$i<sizeof($_SESSION["favoris"]);$i++){
                if ($i==0){
                    $sqlquery.=" AND";
                }
                $sqlquery.=" id=".$_SESSION["favoris"][$i];
                if ($i<sizeof($_SESSION["favoris"])-1){
                    $sqlquery.=" OR";
                }
            }
        }
    }else{
        if ($favoris) {
            $sqlquery = 'SELECT * FROM recettes,favoris WHERE titre LIKE "%' . $nom . '%"' . 'AND favoris.id_recette=recettes.id AND favoris.id_utilisateur=' . $_SESSION["id"]; ////// BASE
        } else $sqlquery = 'SELECT * FROM recettes WHERE titre LIKE "%' . $nom . '%" '; ////// BASE
    }
    foreach ($db->query($sqlquery) as $row) {

        echo '<a  name = ' . $row["id"] . ' class="list-group-item list-group-item-action d-flex align-items-start ">';
        echo "<div class = row>";

        if (file_exists("image/".$row["titre"].".jpg")){ //// Gestion de l'image
            echo "<div class= col-3>";
            echo "<img style='width:100%;' src='image/".$row["titre"].".jpg'>";
            echo "</div>";
            echo "<div class = col-9>";
        }else{
            echo "<div>";
        } //// Fin gestion image

        echo '<div class="d-flex w-100 justify-content-between">';
        echo '<h2 class="mb-1">' . $row["titre"] . '</h2>'; // Affichage du titre
        echo "</div>";

        echo "<h4> Dosage : </h4>";

        $array_quantite = quantite_recette($row["id"], $db);

        echo "<p>   ";

        for ($i = 0; $i < sizeof($array_quantite); $i++) { // Texte quantité 
            echo $array_quantite[$i];
            if ($i < sizeof($array_quantite) - 1) echo ", ";
        }

        echo "</p>";

        echo "<h4> Préparation : </h4>";

        echo "<p>";

        echo $row["preparation"]; // Texte préparation

        echo "</p>";

        $array_ingredients = ingredient_recette($row["id"], $db);

        echo "<h4> Ingrédients : </h4>";

        echo "<ol>";

        for ($i = 0; $i < sizeof($array_ingredients); $i++) { // Liste ingrédients
            echo "<li>" . $array_ingredients[$i] . "</li>";
        }

        echo "</ol>";
        
        echo "</div> </div>";

        echo "</a>";


        if (isset($_SESSION["login"])) { ///////////////////////// FAVORIS 
            if ($_SESSION["login"] == true) {
                if (isFavoris($_SESSION["id"], $row['id'], $db) == true) { // Si il est déjà en favoris.
                    echo '<a style="color:red;" href="test/delete_favoris.php?recette=' . $row["id"] . '&personne=' . $_SESSION["id"] . ' &index=' . $index . '"> Enlever des favoris </a>';
                } else echo '<a href="test/add_favoris.php?recette=' . $row["id"] . '&personne=' . $_SESSION["id"] . '"> Ajouter aux favoris </a>';
            }
        } else { ///// Pas connecté
            if (isset($_SESSION["favoris"])) {
                if (isFavoristemp($row["id"])) { // Si il est déjà en favoris.
                    echo '<a style="color:red;" href="test/delete_favoris.php?recette=' . $row["id"] . '&personne=' . -1 . ' &index=' . $index . '"> Enlever des favoris </a>';
                } else echo '<a href="test/add_favoris.php?recette=' . $row["id"] . '&personne=' . -1 . '"> Ajouter aux favoris </a>';
            }else{
                echo '<a href="test/add_favoris.php?recette=' . $row["id"] . '&personne=' . -1 . '"> Ajouter aux favoris </a>';
            }
        }
    } ///////////////////////////////////////// FIN FAVORIS
}

function getRecettes()
{
    $liste_recette = array();
    include "donnees/Donnees.inc.php";
    for ($i = 0; $i < sizeof($Recettes); $i++) { // Pour chaque recette
        array_push($liste_recette, $Recettes[$i]["titre"]);
    }

    return $liste_recette;
}



function affichage_liste_filtre_by_ingredient($ingredient)

/// Faut rajouter les images et le bouton favoris 


{
    include "bdd_xampp.php";



    $idRecettesQuery =  "SELECT idRecette FROM cocktails.recettes
               INNER JOIN cocktails.ingredientpourrecette ON cocktails.recettes.id = cocktails.ingredientpourrecette.idRecette
               INNER JOIN cocktails.ingredient ON cocktails.ingredientpourrecette.idIngredient = cocktails.ingredient.id
               WHERE cocktails.ingredient.nomIngredient = '" . $ingredient . "'";

    $recetteQuery = "SELECT titre, preparation FROM cocktails.recettes WHERE id = ";

    $quantiteIngredientRecette = "SELECT quantity FROM cocktails.ingredientpourrecette WHERE idRecette = ";

    $idRecettesQueryResult = $db->query($idRecettesQuery);

    while ($idRecette = $idRecettesQueryResult->fetch()) {
        //echo "id =".$idRecette['idRecette']."<br>";

        echo '<a name='.$idRecette['idRecette'].' href="#" class="list-group-item list-group-item-action flex-column align-items-start ">';

        $recetteResult = $db->query($recetteQuery . $idRecette['idRecette']);

        while ($recette = $recetteResult->fetch()) {
            echo '<div class="d-flex w-100 justify-content-between">';
            //echo $recette['titre']."<br>";
            echo '<h2 class="mb-1">' . $recette['titre'] . '</h2>'; // Affichage du titre
            if (isset($_SESSION["login"])) {
                if ($_SESSION["login"]) {
                    echo '<button type="button" class="btn btn-danger">Favoris</button>';
                }
            };


            echo "</div>";
            echo "<h4>Préparation : </h4>";
            echo "<p>" . $recette['preparation'] . "</p>";
        }



        echo "<h4>Ingrédients : </h4>";

        $QuantityResult = $db->query($quantiteIngredientRecette . $idRecette['idRecette']);
        echo "<ul>";
        while ($quantity = $QuantityResult->fetch()) {
            echo "<li>" . $quantity['quantity'] . "</li>";
        }
        echo "</ul>";


        echo "</a>";
    }
    $db = NULL;
    
}

function isFavoris($id_utilisateur, $id_recette, $db)
{
    $array_fav = array();
    foreach ($db->query('SELECT * FROM favoris WHERE id_utilisateur = ' . $id_utilisateur . ' AND id_recette=' . $id_recette) as $row) {
        array_push($array_fav, $row["id_recette"]);
    }
    if (sizeof($array_fav) <= 0) {
        return false;
    } else {
        return true;
    }
}

function isFavoristemp($id_recette)
{
    for ($i=0;$i<sizeof($_SESSION["favoris"]);$i++){
        if ($id_recette==$_SESSION["favoris"][$i]){
            return true;
        }
    }
    return false;
}

?>