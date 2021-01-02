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

        echo '<a  name = ' . $row["id"] . ' class="list-group-item list-group-item-action flex-column align-items-start ">';
        echo '<div class="d-flex w-100 justify-content-between">';
        echo '<h2 class="mb-1">' . $row["titre"] . '</h2>'; // Affichage du titre        
        
        if (isset($_SESSION["login"])) {
            if ($_SESSION["login"])
            {
                if(isFavoris($_SESSION["id"], $row["id"],$db))
                {
                    echo '<button id="buton'. $row["id"] .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $row["id"] .'","'. $_SESSION["id"].'","buton'. $row["id"] .'",false)>Retirer des Favoris</button>';
                }
                else
                {
                    echo '<button id="buton'. $row["id"] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $row["id"] .'","'. $_SESSION["id"].'","buton'. $row["id"] .'")>Ajouter au Favoris</button>';
                }   
            }
        }
        else
        {
            if(isset($_SESSION["favoris"]))
            {
                if(isFavoristemp($row["id"]))
                {
                    echo '<button id="buton'. $row["id"] .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $row["id"] .'","'. -1 .'","buton'. $row["id"] .'",false)>Retirer des Favoris</button>';
                }
                else
                {
                    echo '<button id="buton'. $row["id"] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $row["id"] .'","'. -1 .'","buton'. $row["id"] .'")>Ajouter au Favoris</button>';
                }
            }
            else
            {
                echo '<button id="buton'. $row["id"] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $row["id"] .'","'. -1 .'","buton'. $row["id"] .'")>Ajouter au Favoris</button>';
            }
        }

        echo "</div>";

        $normalizedCocktailName = preg_replace('/[\']/', '', stripAccents($row['titre']));
        $normalizedCocktailName = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', stripAccents($normalizedCocktailName))));


        if(file_exists("image/" . $normalizedCocktailName .".jpg"))
        {
            echo "<div class='row'>";
            echo "<div class='col'>";
        }

        echo "<h4> Dosage : </h4>";

        $array_quantite = quantite_recette($row["id"], $db);

        echo "<p>   ";

        for ($i = 0; $i < sizeof($array_quantite); $i++) {
            echo $array_quantite[$i];
            if ($i < sizeof($array_quantite) - 1) echo ", ";
        }

        echo "</p>";

        echo "<h4> Préparation : </h4>";

        echo "<p>";

        echo $row["preparation"];

        echo "</p>";

        $array_ingredients = ingredient_recette($row["id"], $db);

        echo "<h4> Ingrédients : </h4>";

        echo "<ol>";

        for ($i = 0; $i < sizeof($array_ingredients); $i++) {
            echo "<li>" . $array_ingredients[$i] . "</li>";
        }

        echo "</ol>";

        if(file_exists("image/" . $normalizedCocktailName .".jpg"))
        {
            echo "</div>";
            //echo "<div class='col'>";
            echo "<div class='text-center'>";
            echo "<img src='" . "image/" . $normalizedCocktailName .".jpg" . "' alt='" . $normalizedCocktailName . "' class='rounded float-right'  width='200'>";
            //echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        echo "</a>";

        /*
        if (isset($_SESSION["login"])) { ///////////////////////// FAVORIS 
            if ($_SESSION["login"] == true) 
            {
                if (isFavoris($_SESSION["id"], $row['id'], $db) == true) { // Si il est déjà en favoris.
                    echo '<a style="color:red;" href="test/delete_favoris.php?recette=' . $row["id"] . '&personne=' . $_SESSION["id"] . ' &index=' . $index . '"> Enlever des favoris </a>';
                } 
                else
                    echo '<a href="test/add_favoris.php?recette=' . $row["id"] . '&personne=' . $_SESSION["id"] . '"> Ajouter aux favoris </a>';
            }
        } 
        else { ///// Pas connecté
            if (isset($_SESSION["favoris"])) {
                if (isFavoristemp($row["id"])) { // Si il est déjà en favoris.
                    echo '<a style="color:red;" href="test/delete_favoris.php?recette=' . $row["id"] . '&personne=' . -1 . ' &index=' . $index . '"> Enlever des favoris </a>';
                } 
                else echo '<a href="test/add_favoris.php?recette=' . $row["id"] . '&personne=' . -1 . '"> Ajouter aux favoris </a>';
            }
            else{
                echo '<a href="test/add_favoris.php?recette=' . $row["id"] . '&personne=' . -1 . '"> Ajouter aux favoris </a>';
            }
        }
        */
    }
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

function getHierarchyKeys()
{
    include "donnees/Donnees.inc.php";
    return array_keys($Hierarchie);
}

function affichage_liste_filtre_by_ingredient($ingredient)
{
    include "bdd.php";



    $idRecettesQuery =  "SELECT idRecette FROM recettes
               INNER JOIN ingredientpourrecette ON recettes.id = ingredientpourrecette.idRecette
               INNER JOIN ingredient ON ingredientpourrecette.idIngredient = ingredient.id
               WHERE ingredient.nomIngredient = '" . $ingredient . "'";

    $recetteQuery = "SELECT titre, preparation FROM recettes WHERE id = ";

    $quantiteIngredientRecette = "SELECT quantity FROM ingredientpourrecette WHERE idRecette = ";

    $idRecettesQueryResult = $db->query($idRecettesQuery);

    while ($idRecette = $idRecettesQueryResult->fetch()) {
        //echo "id =".$idRecette['idRecette']."<br>";

        

        echo '<a name='.$idRecette['idRecette'].' href="#" class="list-group-item list-group-item-action flex-column align-items-start ">';

        $recetteResult = $db->query($recetteQuery . $idRecette['idRecette']);

        while ($recette = $recetteResult->fetch()) {
            echo '<div class="d-flex w-100 justify-content-between">';


            $normalizedCocktailName = preg_replace('/[\']/', '', stripAccents($recette['titre']));
            $normalizedCocktailName = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', stripAccents($normalizedCocktailName))));

            

            

            echo '<h2 class="mb-1">' . $recette['titre'] . '</h2>'; // Affichage du titre
            if (isset($_SESSION["login"])) {
                if ($_SESSION["login"])
                {
                    if(isFavoris($_SESSION["id"], $idRecette['idRecette'],$db))
                    {
                        echo '<button id="buton'. $idRecette['idRecette'] .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette['idRecette'] .'","'. $_SESSION["id"].'","buton'. $idRecette['idRecette'] .'",false)>Retirer des Favoris</button>';
                    }
                    else
                    {
                        echo '<button id="buton'. $idRecette['idRecette'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['idRecette'] .'","'. $_SESSION["id"].'","buton'. $idRecette['idRecette'] .'")>Ajouter au Favoris</button>';
                    }   
                }
            }
            else
            {
                if(isset($_SESSION["favoris"]))
                {
                    if(isFavoristemp($idRecette['idRecette']))
                    {
                        echo '<button id="buton'. $idRecette['idRecette'] .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette['idRecette'] .'","'. -1 .'","buton'. $idRecette['idRecette'] .'",false)>Retirer des Favoris</button>';
                    }
                    else
                    {
                        echo '<button id="buton'. $idRecette['idRecette'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['idRecette'] .'","'. -1 .'","buton'. $idRecette['idRecette'] .'")>Ajouter au Favoris</button>';
                    }
                }
                else
                {
                    echo '<button id="buton'. $idRecette['idRecette'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['idRecette'] .'","'. -1 .'","buton'. $idRecette['idRecette'] .'")>Ajouter au Favoris</button>';
                }
            }

            echo "</div>";

            if(file_exists("image/" . $normalizedCocktailName .".jpg"))
            {
                echo "<div class='row'>";
                echo "<div class='col'>";
            }

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


        

        if(file_exists("image/" . $normalizedCocktailName .".jpg"))
        {
            echo "</div>";
            echo "<div class='text-center'>";
            echo "<img src='" . "image/" . $normalizedCocktailName .".jpg" . "' alt='" . $normalizedCocktailName . "' class='img-thumbnail' width='200'>";

            echo "</div>";
            echo "</div>";
        }

        echo "</a>";

        

    }


    $db = NULL;
}

function affichageFavoris()
{
    include "bdd.php";



    $idRecettesQuery = "SELECT recettes.id FROM recettes 
                        INNER JOIN favoris ON recettes.id = favoris.id_recette 
                        WHERE favoris.id_utilisateur = ".$_SESSION["id"];

    $recetteQuery = "SELECT titre, preparation FROM recettes WHERE id = ";

    $quantiteIngredientRecette = "SELECT quantity FROM ingredientpourrecette WHERE idRecette = ";

    $idRecettesQueryResult = $db->query($idRecettesQuery);

    while ($idRecette = $idRecettesQueryResult->fetch()) {
        //echo "id =".$idRecette['idRecette']."<br>";

        

        echo '<a name='.$idRecette['id'].' id="row'. $idRecette['id'] .'" href="#" class="list-group-item list-group-item-action flex-column align-items-start ">';

        $recetteResult = $db->query($recetteQuery . $idRecette['id']);

        while ($recette = $recetteResult->fetch()) {
            echo '<div class="d-flex w-100 justify-content-between">';


            $normalizedCocktailName = preg_replace('/[\']/', '', stripAccents($recette['titre']));
            $normalizedCocktailName = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', stripAccents($normalizedCocktailName))));

            

            

            echo '<h2 class="mb-1">' . $recette['titre'] . '</h2>'; // Affichage du titre
            if (isset($_SESSION["login"])) {
                if ($_SESSION["login"])
                {
                    if(isFavoris($_SESSION["id"], $idRecette['id'],$db))
                    {
                        echo '<button id="buton'. $idRecette['id'] .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette['id'] .'","'. $_SESSION["id"].'","buton'. $idRecette['id'] .'",true)>Retirer des Favoris</button>';
                    }
                    else
                    {
                        echo '<button id="buton'. $idRecette['id'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['id'] .'","'. $_SESSION["id"].'","buton'. $idRecette['id'] .'")>Ajouter au Favoris</button>';
                    }   
                }
            }
            else
            {
                if(isset($_SESSION["favoris"]))
                {
                    if(isFavoristemp($idRecette['id']))
                    {
                        echo '<button id="buton'. $idRecette['id'] .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette['id'] .'","'. -1 .'","buton'. $idRecette['id'] .'",true)>Retirer des Favoris</button>';
                    }
                    else
                    {
                        echo '<button id="buton'. $idRecette['id'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['id'] .'","'. -1 .'","buton'. $idRecette['id'] .'")>Ajouter au Favoris</button>';
                    }
                }
                else
                {
                    echo '<button id="buton'. $idRecette['id'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['id'] .'","'. -1 .'","buton'. $idRecette['id'] .'")>Ajouter au Favoris</button>';
                }
            }

            echo "</div>";

            if(file_exists("image/" . $normalizedCocktailName .".jpg"))
            {
                echo "<div class='row'>";
                echo "<div class='col'>";
            }

            echo "<h4>Préparation : </h4>";
            echo "<p>" . $recette['preparation'] . "</p>";


            

        }



        echo "<h4>Ingrédients : </h4>";

        $QuantityResult = $db->query($quantiteIngredientRecette . $idRecette['id']);
        echo "<ul>";
        while ($quantity = $QuantityResult->fetch()) {
            echo "<li>" . $quantity['quantity'] . "</li>";
        }
        echo "</ul>";




        if(file_exists("image/" . $normalizedCocktailName .".jpg"))
        {
            echo "</div>";
            echo "<div class='text-center'>";
            echo "<img src='" . "image/" . $normalizedCocktailName .".jpg" . "' alt='" . $normalizedCocktailName . "' class='img-thumbnail' width='200'>";

            echo "</div>";
            echo "</div>";
        }


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

function stripAccents($str) {
    return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

function calculePertinenceOrderedList()
{
    include "bdd.php";

    if(isset($_POST['include']))
    {
        $idIncludeListe = getIdIngredientsFromCategory($_POST['include']);
    }
    if(isset($_POST['exclude']))
    {
        $idExcludeListe = getIdIngredientsFromCategory($_POST['exclude']);
    }



    $queryPourClassementdeRecette = "SELECT recettes.id,COUNT(*) AS nbAppariton 
                    FROM recettes JOIN ingredientpourrecette ON recettes.id = ingredientpourrecette.idRecette  
                    WHERE";

    if(isset($_POST['include']))
    {
        for($i = 0; $i < sizeof($idIncludeListe) ; $i++)
        {
            if($i === 0)
            {
                $queryPourClassementdeRecette.=" ingredientpourrecette.idIngredient = ".$idIncludeListe[$i];
            }
            else
            {
                $queryPourClassementdeRecette.=" OR ingredientpourrecette.idIngredient = ".$idIncludeListe[$i];
            }  
        }
        if(isset($_POST['exclude']))
        {
            for($i = 0; $i < sizeof($idExcludeListe) ; $i++)
            {
                $queryPourClassementdeRecette.=" AND ingredientpourrecette.idIngredient != ".$idExcludeListe[$i];
            }
        }
    }
    else
    {
        if(isset($_POST['exclude']))
        {
            for($i = 0; $i < sizeof($idExcludeListe) ; $i++)
            {
                if($i === 0)
                {
                    $queryPourClassementdeRecette.=" ingredientpourrecette.idIngredient = ".$idExcludeListe[$i];
                }
                else
                {
                    $queryPourClassementdeRecette.=" AND ingredientpourrecette.idIngredient = ".$idExcludeListe[$i];
                }
            }
        }
    }


    


    $queryPourClassementdeRecette .= " GROUP BY recettes.id ORDER BY nbAppariton DESC";

    //echo $queryPourClassementdeRecette;

    $listeIdRecetteOrderedByPertinence = array();

    if(!$queryPourClassementdeRecetteResult = $db->query($queryPourClassementdeRecette))
    {
        echo "ERREUR trouver ID ingredient".$queryPourClassementdeRecette;
    }
    else
    {
        while($arrayResult = $queryPourClassementdeRecetteResult->fetch())
        {
            array_push($listeIdRecetteOrderedByPertinence, $arrayResult['id']);
        }
    }
    return $listeIdRecetteOrderedByPertinence;

    $db = NULL;
}

function getIdIngredientsFromCategory($tableauNom)
{
    $NomIngredient = array();
    $NomCategory = array();

    include "bdd.php";
    //echo "<br>";

    for($i = 0; $i < sizeof($tableauNom) ;$i++)
    {
        //echo $tableauNom[$i];
        $queryTrouverId = "SELECT id FROM ingredient where nomIngredient = \"".$tableauNom[$i]."\"";
        
        if(!$trouverIdQueryResult = $db->query($queryTrouverId))
        {
            echo "ERREUR trouver ID ingredient".$queryTrouverId;
        }
        else
        {
            $arrayResultIdQuery = $trouverIdQueryResult->fetch();
            //echo "TESTEST ".$arrayResultIdQuery['id']. "|||<br>";
            $trouverIdSousCategorieQuery = "SELECT sousCategorieId FROM ingredientsouscategorie WHERE idProduit =".$arrayResultIdQuery['id'];

            if(!$trouverIdSousCategorieQueryResult = $db->query($trouverIdSousCategorieQuery))
            {
                echo "ERREUR trouver ID sous categorie ingredient -> ".$trouverIdSousCategorieQuery;
            }
            else
            {
                if($trouverIdSousCategorieQueryResult->rowCount()===1)
                {
                    $arrayTrouverIdSousCategorieQueryResult = $trouverIdSousCategorieQueryResult->fetch();
                    if($arrayTrouverIdSousCategorieQueryResult['sousCategorieId'] === NULL)
                    {
                        array_push($NomCategory, $arrayResultIdQuery['id']);
                        //echo "ici <br>";
                        //return $NomCategory;
                    }
                    else
                    {

                        $nomSousCategorie = getNomFromId($arrayTrouverIdSousCategorieQueryResult['sousCategorieId']);
                        //echo $nomSousCategorie;
                        //array_push($NomCategory, $nomSousCategorie);
                        return getIdIngredientsFromCategory(array($nomSousCategorie));
                    }
                }
                else
                {

                    //echo "AT THE BEGINNING :<br>";
                    //print_r($NomCategory);
                    //echo "<br>";

                    while($arrayResultSousCategoriIdQuery = $trouverIdSousCategorieQueryResult->fetch())
                    {
                        $queryTrouverNom = "SELECT nomIngredient FROM ingredient where id = ".$arrayResultSousCategoriIdQuery['sousCategorieId'];
                        if(!$queryTrouverNomResult = $db->query($queryTrouverNom))
                        {
                            echo "ERREUR trouver NOM sous categorie ingredient -> ".$queryTrouverNom;
                        }
                        else
                        {
                            $arrayQueryTrouverNomResult = $queryTrouverNomResult->fetch();

                            //echo $arrayQueryTrouverNomResult['nomIngredient'];
                            
                            $test = getIdIngredientsFromCategory(array($arrayQueryTrouverNomResult['nomIngredient']));
                            //echo "<br> RECURSION RESULT --->";
                            //print_r($test);
                            //echo "<br>";

                            foreach($test as &$ingredient)
                            {
                                array_push($NomCategory, $ingredient);
                            }
                        }
                    }

                }
                
            }

        }
    }
    
    $db=NULL;
    return $NomCategory;
}

function getNomFromId($id)
{
    include "bdd.php";

    $queryTrouverNom = "SELECT nomIngredient FROM ingredient where id = ".$id;
    if(!$queryTrouverNomResult = $db->query($queryTrouverNom))
    {
        echo "ERREUR trouver ID sous categorie ingredient -> ".$queryTrouverNom;
    }
    else
    {
        $arrayQueryTrouverNomResult = $queryTrouverNomResult->fetch();
        $db = NULL;
        return $arrayQueryTrouverNomResult['nomIngredient'];
    }
    $db = NULL;
    return -1;
}

function getIdFromNom($nom)
{
    include "bdd.php";

    $queryTrouverNom = "SELECT id FROM ingredient where nomIngredient = ".$nom;
    if(!$queryTrouverNomResult = $db->query($queryTrouverNom))
    {
        echo "ERREUR trouver ID sous categorie ingredient -> ".$queryTrouverNom;
    }
    else
    {
        $arrayQueryTrouverNomResult = $queryTrouverNomResult->fetch();
        $db = NULL;
        return $arrayQueryTrouverNomResult['id'];
    }
    $db = NULL;
    return -1;
}

function affichage_by_idRecetteListe($idRecetteListe, $affichagePourFavoris)
{
    include "bdd.php";

    $recetteQuery = "SELECT titre, preparation FROM recettes WHERE id = ";

    $quantiteIngredientRecette = "SELECT quantity FROM ingredientpourrecette WHERE idRecette = ";

    foreach($idRecetteListe as &$idRecette)
    {
        //echo "id =".$idRecette['idRecette']."<br>";

        

        echo '<a name='.$idRecette.' id="row'. $idRecette .'" href="#" class="list-group-item list-group-item-action flex-column align-items-start ">';

        $recetteResult = $db->query($recetteQuery.$idRecette);

        while ($recette = $recetteResult->fetch()) {
            echo '<div class="d-flex w-100 justify-content-between">';


            $normalizedCocktailName = preg_replace('/[\']/', '', stripAccents($recette['titre']));
            $normalizedCocktailName = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', stripAccents($normalizedCocktailName))));

            

            

            echo '<h2 class="mb-1">' . $recette['titre'] . '</h2>'; // Affichage du titre
            
            if($affichagePourFavoris)//Si la fonction est utilisée pour l'affichage des favoris temporaires (obligé de différencié pour masquer la recette lors de la suprression) 
            {
                if (isset($_SESSION["login"])) {
                    if ($_SESSION["login"])
                    {
                        if(isFavoris($_SESSION["id"], $idRecette,$db))
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette .'","'. $_SESSION["id"].'","buton'. $idRecette .'",true)>Retirer des Favoris</button>';
                        }
                        else
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. $_SESSION["id"].'","buton'. $idRecette .'")>Ajouter au Favoris</button>';
                        }   
                    }
                }
                else
                {
                    if(isset($_SESSION["favoris"]))
                    {
                        if(isFavoristemp($idRecette))
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'",true)>Retirer des Favoris</button>';
                        }
                        else
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'")>Ajouter au Favoris</button>';
                        }
                    }
                    else
                    {
                        echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'")>Ajouter au Favoris</button>';
                    }
                }
            }
            else
            {    
            if (isset($_SESSION["login"])) {
                    if ($_SESSION["login"])
                    {
                        if(isFavoris($_SESSION["id"], $idRecette,$db))
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette .'","'. $_SESSION["id"].'","buton'. $idRecette .'",false)>Retirer des Favoris</button>';
                        }
                        else
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. $_SESSION["id"].'","buton'. $idRecette .'")>Ajouter au Favoris</button>';
                        }   
                    }
                }
                else
                {
                    if(isset($_SESSION["favoris"]))
                    {
                        if(isFavoristemp($idRecette))
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'",false)>Retirer des Favoris</button>';
                        }
                        else
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'")>Ajouter au Favoris</button>';
                        }
                    }
                    else
                    {
                        echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'")>Ajouter au Favoris</button>';
                    }
                }
            }

            echo "</div>";

            if(file_exists("image/" . $normalizedCocktailName .".jpg"))
            {
                echo "<div class='row'>";
                echo "<div class='col'>";
            }

            echo "<h4>Préparation : </h4>";
            echo "<p>" . $recette['preparation'] . "</p>";


            

        }



        echo "<h4>Ingrédients : </h4>";

        $QuantityResult = $db->query($quantiteIngredientRecette . $idRecette);
        echo "<ul>";
        while ($quantity = $QuantityResult->fetch()) {
            echo "<li>" . $quantity['quantity'] . "</li>";
        }
        echo "</ul>";


        

        if(file_exists("image/" . $normalizedCocktailName .".jpg"))
        {
            echo "</div>";
            echo "<div class='text-center'>";
            echo "<img src='" . "image/" . $normalizedCocktailName .".jpg" . "' alt='" . $normalizedCocktailName . "' class='img-thumbnail' width='200'>";

            echo "</div>";
            echo "</div>";
        }
        echo "</a>";

        

    }
}

?>