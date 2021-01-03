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
    //On inclue bdd.hp pour dialoguer avec la base de donnée
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
                    echo '<button id="buton'. $row["id"] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $row["id"] .'","'. $_SESSION["id"].'","buton'. $row["id"] .'")>Ajouter aux Favoris</button>';
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
                    echo '<button id="buton'. $row["id"] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $row["id"] .'","'. -1 .'","buton'. $row["id"] .'")>Ajouter aux Favoris</button>';
                }
            }
            else
            {
                echo '<button id="buton'. $row["id"] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $row["id"] .'","'. -1 .'","buton'. $row["id"] .'")>Ajouter aux Favoris</button>';
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



/**
 * génère l'affichage d'une liste de recette contenant un certain ingredient
 *
 * @param  mixed $ingredient ingredient que les recettes affichées doivent contenir
 * @return void
 */
function affichage_liste_filtre_by_ingredient($ingredient)
{
    //On inclue bdd.hp pour dialoguer avec la base de donnée
    include "bdd.php";



    $idRecettesQuery =  "SELECT idRecette FROM recettes
               INNER JOIN ingredientpourrecette ON recettes.id = ingredientpourrecette.idRecette
               INNER JOIN ingredient ON ingredientpourrecette.idIngredient = ingredient.id
               WHERE ingredient.nomIngredient = '" . $ingredient . "'";

    $recetteQuery = "SELECT titre, preparation FROM recettes WHERE id = ";

    $quantiteIngredientRecette = "SELECT quantity FROM ingredientpourrecette WHERE idRecette = ";

    $idRecettesQueryResult = $db->query($idRecettesQuery);

    //Pour chaque recette contenant l'ingredient on génère un affichage
    while ($idRecette = $idRecettesQueryResult->fetch()) {
        //echo "id =".$idRecette['idRecette']."<br>";

        

        echo '<a name='.$idRecette['idRecette'].' href="#" class="list-group-item list-group-item-action flex-column align-items-start ">';

        $recetteResult = $db->query($recetteQuery . $idRecette['idRecette']);

        //Recuperation des infos de la recette
        while ($recette = $recetteResult->fetch()) {
            echo '<div class="d-flex w-100 justify-content-between">';


            //Pour chercher si il existe une image pour la recette 
            $normalizedCocktailName = preg_replace('/[\']/', '', stripAccents($recette['titre']));
            $normalizedCocktailName = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', stripAccents($normalizedCocktailName))));

            

            

            echo '<h2 class="mb-1">' . $recette['titre'] . '</h2>'; // Affichage du titre
            
            //Affichage du bouton favoris
            if (isset($_SESSION["login"])) {        //Cas où l'utilisateur est connecté
                if ($_SESSION["login"])
                {
                    //Cas où la recette est un favoris
                    if(isFavoris($_SESSION["id"], $idRecette['idRecette'],$db))
                    {
                        echo '<button id="buton'. $idRecette['idRecette'] .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette['idRecette'] .'","'. $_SESSION["id"].'","buton'. $idRecette['idRecette'] .'",false)>Retirer des Favoris</button>';
                    }
                    //Cas où la recette n'est un pas favoris
                    else
                    {
                        echo '<button id="buton'. $idRecette['idRecette'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['idRecette'] .'","'. $_SESSION["id"].'","buton'. $idRecette['idRecette'] .'")>Ajouter aux Favoris</button>';
                    }   
                }
            }
            else
            {
                //Cas où l'utilisateur n'est pas connecté et qu'il a des favoris temporraires
                if(isset($_SESSION["favoris"]))
                {
                    //Cas où la recette est un favoris temporaire
                    if(isFavoristemp($idRecette['idRecette']))
                    {
                        echo '<button id="buton'. $idRecette['idRecette'] .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette['idRecette'] .'","'. -1 .'","buton'. $idRecette['idRecette'] .'",false)>Retirer des Favoris</button>';
                    }
                    //Cas où la recette n'est pas un favoris temporaire
                    else
                    {
                        echo '<button id="buton'. $idRecette['idRecette'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['idRecette'] .'","'. -1 .'","buton'. $idRecette['idRecette'] .'")>Ajouter aux Favoris</button>';
                    }
                }
                //Cas où l'utilisateur n'est pas connecté et qu'il n'a pas des favoris temporraires
                else
                {
                    echo '<button id="buton'. $idRecette['idRecette'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['idRecette'] .'","'. -1 .'","buton'. $idRecette['idRecette'] .'")>Ajouter aux Favoris</button>';
                }
            }

            echo "</div>";

            //Si il existe une image pour la recette on ajoute une colonne
            if(file_exists("image/" . $normalizedCocktailName .".jpg"))
            {
                echo "<div class='row'>";
                echo "<div class='col'>";
            }

            //Affichage des instruction de préparation
            echo "<h4>Préparation : </h4>";
            echo "<p>" . $recette['preparation'] . "</p>";


            

        }


        //Affichage des ingrédients de la recette
        echo "<h4>Ingrédients : </h4>";

        $QuantityResult = $db->query($quantiteIngredientRecette . $idRecette['idRecette']);
        echo "<ul>";
        while ($quantity = $QuantityResult->fetch()) {
            echo "<li>" . $quantity['quantity'] . "</li>";
        }
        echo "</ul>";


        
        //Affichage de l'image de la recette si elle existe
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

/**
 * génère l'affichage des recettes favorites d'un utilisateur connecté
 *
 * @return void
 */
function affichageFavoris()
{

    //On inclue bdd.hp pour dialoguer avec la base de donnée
    include "bdd.php";



    $idRecettesQuery = "SELECT recettes.id FROM recettes 
                        INNER JOIN favoris ON recettes.id = favoris.id_recette 
                        WHERE favoris.id_utilisateur = ".$_SESSION["id"];

    $recetteQuery = "SELECT titre, preparation FROM recettes WHERE id = ";

    $quantiteIngredientRecette = "SELECT quantity FROM ingredientpourrecette WHERE idRecette = ";

    $idRecettesQueryResult = $db->query($idRecettesQuery);


    //Pour chaque recette favorite de l'utilisateur on genère son affichage
    while ($idRecette = $idRecettesQueryResult->fetch()) {
        //echo "id =".$idRecette['idRecette']."<br>";

        

        echo '<a name='.$idRecette['id'].' id="row'. $idRecette['id'] .'" href="#" class="list-group-item list-group-item-action flex-column align-items-start ">';

        $recetteResult = $db->query($recetteQuery . $idRecette['id']);

        //Recuperation des infos de la recette
        while ($recette = $recetteResult->fetch()) {
            echo '<div class="d-flex w-100 justify-content-between">';

            //Pour chercher si il existe une image pour la recette 
            $normalizedCocktailName = preg_replace('/[\']/', '', stripAccents($recette['titre']));
            $normalizedCocktailName = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', stripAccents($normalizedCocktailName))));

            

            

            echo '<h2 class="mb-1">' . $recette['titre'] . '</h2>'; // Affichage du titre
            
            //Affichage du bouton favoris
            if (isset($_SESSION["login"])) {        //Cas où l'utilisateur est connecté
                if ($_SESSION["login"])
                {
                    //Cas où la recette est un favoris
                    if(isFavoris($_SESSION["id"], $idRecette['id'],$db))
                    {
                        echo '<button id="buton'. $idRecette['id'] .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette['id'] .'","'. $_SESSION["id"].'","buton'. $idRecette['id'] .'",true)>Retirer des Favoris</button>';
                    }
                    //Cas où la recette n'est un pas favoris
                    else
                    {
                        echo '<button id="buton'. $idRecette['id'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['id'] .'","'. $_SESSION["id"].'","buton'. $idRecette['id'] .'")>Ajouter aux Favoris</button>';
                    }   
                }
            }
            else
            {
                //Cas où l'utilisateur n'est pas connecté et qu'il a des favoris temporraires
                if(isset($_SESSION["favoris"]))
                {
                    //Cas où la recette est un favoris temporaire
                    if(isFavoristemp($idRecette['id']))
                    {
                        echo '<button id="buton'. $idRecette['id'] .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette['id'] .'","'. -1 .'","buton'. $idRecette['id'] .'",true)>Retirer des Favoris</button>';
                    }
                    //Cas où la recette n'est pas un favoris temporaire
                    else
                    {
                        echo '<button id="buton'. $idRecette['id'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['id'] .'","'. -1 .'","buton'. $idRecette['id'] .'")>Ajouter aux Favoris</button>';
                    }
                }
                //Cas où l'utilisateur n'est pas connecté et qu'il n'a pas des favoris temporraires
                else
                {
                    echo '<button id="buton'. $idRecette['id'] .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette['id'] .'","'. -1 .'","buton'. $idRecette['id'] .'")>Ajouter aux Favoris</button>';
                }
            }

            echo "</div>";

            //Si il existe une image pour la recette on ajoute une colonne
            if(file_exists("image/" . $normalizedCocktailName .".jpg"))
            {
                echo "<div class='row'>";
                echo "<div class='col'>";
            }

            //Affichage des instruction de préparation
            echo "<h4>Préparation : </h4>";
            echo "<p>" . $recette['preparation'] . "</p>";


            

        }


        //Affichage des ingrédients de la recette
        echo "<h4>Ingrédients : </h4>";

        $QuantityResult = $db->query($quantiteIngredientRecette . $idRecette['id']);
        echo "<ul>";
        while ($quantity = $QuantityResult->fetch()) {
            echo "<li>" . $quantity['quantity'] . "</li>";
        }
        echo "</ul>";



        //Affichage de l'image de la recette si elle existe
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


/**
 * isFavoris
 * 
 * verifie si une recette fais partie des favoris d'un utilisateur connecté
 *
 * @param  mixed $id_utilisateur l'id de l'utilisateur dont on verifie les favoris
 * @param  mixed $id_recette l'id de la recette qu'on cherche dans les favoris de l'utilisateur
 * @param  mixed $db PDO qu'on utilise pour dialoguer avec la base de données
 * @return true si la recette se trouve dans les favoris de l'utilisateur connecté
 * @return false si la recette ne se trouve pas dans les favoris de l'utilisateur connecté
 */
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


/**
 * isFavoristemp
 * 
 * verifie si une recette fais partie des favoris temporaires
 *
 * @param  mixed $id_recette l'id de la recette qu'on cherche dans les favoris temporaires
 * @return true si la recette se trouve dans les favoris les favoris temporaires
 * @return false si la recette ne se trouve pas dans les favoris les favoris temporaires
 */
function isFavoristemp($id_recette)
{
    for ($i=0;$i<sizeof($_SESSION["favoris"]);$i++){
        if ($id_recette==$_SESSION["favoris"][$i]){
            return true;
        }
    }
    return false;
}

/**
 * Fonction trouvée sur stackOverflow
 * Sert à convertir un caractère accentué à sa version sans accent,
 * utilisée pour la normalisation avant de chercher si il existe une image pour une recette
 */
function stripAccents($str) {
    return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}


/**
 * calcule une liste d'id triée par ordre de pertinence décroissante 
 * (on commence par ce qu'il y a de plus pertinent)
 * la liste est calculée en fonction d'ingrédient qu'on veut avoir dans les recettes
 * et d'ingrédeient qu'on ne veut pas avoir dans les recettes
 * 
 * @return void
 */
function calculePertinenceOrderedList()
{
    //On inclue bdd.hp pour dialoguer avec la base de donnée
    include "bdd.php";

    //On recupère la liste des id qu'on veut inclure et exclure
    if(isset($_POST['include']))
    {
        $idIncludeListe = getIdIngredientsFromCategory($_POST['include']);
    }
    if(isset($_POST['exclude']))
    {
        $idExcludeListe = getIdIngredientsFromCategory($_POST['exclude']);
    }

    //en fonction des listes include et exclude on construit une requete sql adaptée
    //on cherche les recettes qui incluent le + les ingrédients qu'on veut, une succession de OR 
    //on exclue les recettes qu'on veut exclure avec, une succession de AND 

    //A ce stade on a plusieurs fois la même recette, pour obtenir une liste de pertinence, 
    //on compte le nombre de fois où on voit une recette apparaitre
    //et on trie les recettes qui apparaisse par nombre d'apparation décroissante

    $queryPourClassementdeRecette = "SELECT recettes.id,COUNT(*) AS nbAppariton 
                    FROM recettes JOIN ingredientpourrecette ON recettes.id = ingredientpourrecette.idRecette  
                    WHERE";

    //Cas si on veut inclure des ingédients
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
        //Cas où veut inclure ET exclure certains ingrédient
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
        //Cas si on veut exclure des ingredients UNIQUEMENT
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


    
    
    //On ordonne la table
    $queryPourClassementdeRecette .= " GROUP BY recettes.id ORDER BY nbAppariton DESC";

    $listeIdRecetteOrderedByPertinence = array();

    //On construit un tableau représentant le resultat de la requete
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

    //On ferme la connexion
    $db = NULL;

    //On retourne le tableau
    return $listeIdRecetteOrderedByPertinence;

    
}


/**
 * getIdIngredientsFromCategory
 *
 * retourne un tableau d'ID d'ingredient à partir d'un tableau de nom de categorie et/ou de nom d'ingrédient
 * ce tableau est remplis des id d'ingredients auquel on peut arriver 
 * en ayant comme point de départ un nom de categorie/d'ingredient
 * 
 * on cherche à obtenir chaque id des feuilles de l'abre dont la racine de l'abre est le noeud.nom = nom de categorie et/ou de nom d'ingrédient
 * 
 * 
 * @param  mixed $tableauNom tableau de nom de categorie ou d'ingrédient
 * @return tab un tableau d'id d'ingredient
 */
function getIdIngredientsFromCategory($tableauNom)
{
    $NomIngredient = array();
    $NomCategory = array();

    include "bdd.php";
    //echo "<br>";

    //Pour chaque nom d'ingredient/categorie dans tableauNom
    for($i = 0; $i < sizeof($tableauNom) ;$i++)
    {
        //Trouver ID à partir du nom
        $queryTrouverId = "SELECT id FROM ingredient where nomIngredient = \"".$tableauNom[$i]."\"";
        
        if(!$trouverIdQueryResult = $db->query($queryTrouverId))
        {
            echo "ERREUR trouver ID ingredient".$queryTrouverId;
        }
        else
        {
            //trouver les sous categorie à partir de l'id
            $arrayResultIdQuery = $trouverIdQueryResult->fetch();
            $trouverIdSousCategorieQuery = "SELECT sousCategorieId FROM ingredientsouscategorie WHERE idProduit =".$arrayResultIdQuery['id'];

            if(!$trouverIdSousCategorieQueryResult = $db->query($trouverIdSousCategorieQuery))
            {
                echo "ERREUR trouver ID sous categorie ingredient -> ".$trouverIdSousCategorieQuery;
            }
            else
            {
                if($trouverIdSousCategorieQueryResult->rowCount()===1)
                {
                    //Si il n'y a pas de sous categorie, on retourne un tableau de taille 1 contenant l'ID qu'on balaye
                    $arrayTrouverIdSousCategorieQueryResult = $trouverIdSousCategorieQueryResult->fetch();
                    if($arrayTrouverIdSousCategorieQueryResult['sousCategorieId'] === NULL)
                    {
                        array_push($NomCategory, $arrayResultIdQuery['id']);
                        //echo "ici <br>";
                        //return $NomCategory;
                    }
                    //Si il y a une seule sous categorie, on fait une recursion et on retourne getIdIngredientsFromCategory[nom de la sous categorie]
                    else
                    {

                        $nomSousCategorie = getNomFromId($arrayTrouverIdSousCategorieQueryResult['sousCategorieId']);
                        //echo $nomSousCategorie;
                        //array_push($NomCategory, $nomSousCategorie);
                        return getIdIngredientsFromCategory(array($nomSousCategorie));
                    }
                }
                //Si il y a plusieurs sous categorie
                else
                {

                    //pour chaque sous categorie
                    while($arrayResultSousCategoriIdQuery = $trouverIdSousCategorieQueryResult->fetch())
                    {

                        $queryTrouverNom = "SELECT nomIngredient FROM ingredient where id = ".$arrayResultSousCategoriIdQuery['sousCategorieId'];
                        if(!$queryTrouverNomResult = $db->query($queryTrouverNom))
                        {
                            echo "ERREUR trouver NOM sous categorie ingredient -> ".$queryTrouverNom;
                        }
                        //Pour chaque sous categorie on fait une recursion avec getIdIngredientsFromCategory([nom Ingredient de la sous categorie])
                        //ce resultat est ajouté au tableau qui contient le resultat de la recursion actuel
                        else
                        {
                            $arrayQueryTrouverNomResult = $queryTrouverNomResult->fetch();


                            $test = getIdIngredientsFromCategory(array($arrayQueryTrouverNomResult['nomIngredient']));
                            //echo "<br> RECURSION RESULT --->";
                            //print_r($test);
                            //echo "<br>";

                            //Ajout au tableau du resultat de la recursion actuel
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
    
    //fermeture de la connexion à la base de donnée
    $db=NULL;

    //On retourne le tableau du resultat de la recursion actuel
    return $NomCategory;
}


/**
 * getNomFromId
 * 
 * retourne le nom d'un ingredient à partir de son id
 * sinon retourne -1
 *
 * @param  mixed $id id de l'ingredient dont on cherche le nom
 */
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

/**
 * getIdFromNom
 * 
 * retourne l'id d'un ingredient à partir de son nom
 * sinon retourne -1
 *
 * @param  mixed $nom nom de l'ingredient dont on cherche l'id'
 */
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


/**
 * génère l'affichage des recettes pour une liste d'id Recette
 *
 *
 * @param  mixed $idRecetteListe la liste des id des recettes à afficher
 * @param  mixed $affichagePourFavoris boolean  true si on la fonction est utilisée pour afficher des favoris (l'onglet favoris)
 *                                              false sinon
 * @return void
 */
function affichage_by_idRecetteListe($idRecetteListe, $affichagePourFavoris)
{
    //On inclue bdd.hp pour dialoguer avec la base de donnée
    include "bdd.php";

    $recetteQuery = "SELECT titre, preparation FROM recettes WHERE id = ";

    $quantiteIngredientRecette = "SELECT quantity FROM ingredientpourrecette WHERE idRecette = ";

    //Pour chaque recette contenant l'ingredient on génère un affichage
    foreach($idRecetteListe as &$idRecette)
    {

        echo '<a name='.$idRecette.' id="row'. $idRecette .'" href="#" class="list-group-item list-group-item-action flex-column align-items-start ">';

        $recetteResult = $db->query($recetteQuery.$idRecette);


        //Recuperation des infos de la recette
        while ($recette = $recetteResult->fetch()) {
            echo '<div class="d-flex w-100 justify-content-between">';

            //Pour chercher si il existe une image pour la recette 
            $normalizedCocktailName = preg_replace('/[\']/', '', stripAccents($recette['titre']));
            $normalizedCocktailName = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', stripAccents($normalizedCocktailName))));

            

            

            echo '<h2 class="mb-1">' . $recette['titre'] . '</h2>'; // Affichage du titre
            
            //Si la fonction est utilisée pour l'affichage des favoris temporaires (obligé de différencié pour masquer la recette lors de la suprression (fonction javascript)) 
            if($affichagePourFavoris)
            {
                //Affichage du bouton favoris
                if (isset($_SESSION["login"])) {
                    //Cas où l'utilisateur est connecté
                    if ($_SESSION["login"])
                    {
                        //Cas où la recette est un favoris
                        if(isFavoris($_SESSION["id"], $idRecette,$db))
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette .'","'. $_SESSION["id"].'","buton'. $idRecette .'",true)>Retirer des Favoris</button>';
                        }
                        //Cas où la recette n'est un pas favoris
                        else
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. $_SESSION["id"].'","buton'. $idRecette .'")>Ajouter aux Favoris</button>';
                        }   
                    }
                }
                else
                {
                    //Cas où l'utilisateur n'est pas connecté et qu'il a des favoris temporraires
                    if(isset($_SESSION["favoris"]))
                    {
                        //Cas où la recette est un favoris temporaire
                        if(isFavoristemp($idRecette))
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'",true)>Retirer des Favoris</button>';
                        }
                        //Cas où la recette n'est pas un favoris temporaire
                        else
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'")>Ajouter aux Favoris</button>';
                        }
                    }
                    //Cas où l'utilisateur n'est pas connecté et qu'il n'a pas des favoris temporraires
                    else
                    {
                        echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'")>Ajouter aux Favoris</button>';
                    }
                }
            }
            //Si la fonction n'est pas utilisée pour l'affichage des favoris temporaires (obligé de différencié pour masquer la recette lors de la suprression, fonction javascript) 
            else
            {  
                //Cas où l'utilisateur est connecté  
                if (isset($_SESSION["login"])) {
                    if ($_SESSION["login"])
                    {
                        //Cas où la recette est un favoris
                        if(isFavoris($_SESSION["id"], $idRecette,$db))
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette .'","'. $_SESSION["id"].'","buton'. $idRecette .'",false)>Retirer des Favoris</button>';
                        }
                        //Cas où la recette n'est un pas favoris
                        else
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. $_SESSION["id"].'","buton'. $idRecette .'")>Ajouter aux Favoris</button>';
                        }   
                    }
                }
                else
                {
                    //Cas où l'utilisateur n'est pas connecté et qu'il a des favoris temporraires
                    if(isset($_SESSION["favoris"]))
                    {
                        //Cas où la recette est un favoris temporaire
                        if(isFavoristemp($idRecette))
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-danger" onclick=removeFromFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'",false)>Retirer des Favoris</button>';
                        }
                        //Cas où la recette n'est pas un favoris temporaire
                        else
                        {
                            echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'")>Ajouter aux Favoris</button>';
                        }
                    }
                    //Cas où l'utilisateur n'est pas connecté et qu'il n'a pas des favoris temporraires
                    else
                    {
                        echo '<button id="buton'. $idRecette .'" type="button" class="btn btn-success" onclick=addToFavourite("'. $idRecette .'","'. -1 .'","buton'. $idRecette .'")>Ajouter aux Favoris</button>';
                    }
                }
            }

            echo "</div>";

            //Si il existe une image pour la recette on ajoute une colonne
            if(file_exists("image/" . $normalizedCocktailName .".jpg"))
            {
                echo "<div class='row'>";
                echo "<div class='col'>";
            }

            //Affichage des instruction de préparation
            echo "<h4>Préparation : </h4>";
            echo "<p>" . $recette['preparation'] . "</p>";


            

        }



        //Affichage des ingrédients de la recette
        echo "<h4>Ingrédients : </h4>";

        $QuantityResult = $db->query($quantiteIngredientRecette . $idRecette);
        echo "<ul>";
        while ($quantity = $QuantityResult->fetch()) {
            echo "<li>" . $quantity['quantity'] . "</li>";
        }
        echo "</ul>";


        

        //Affichage de l'image de la recette si elle existe
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