<?php

include "../bdd.php";

session_start();

if (isset($_GET["personne"])) {
    $id_personne = $_GET["personne"];
}
if (isset($_GET["recette"])) {
    $id_recette = $_GET["recette"];
}


if ($id_personne!=-1){  // CAS LOGIN

    $recettes = array();
    foreach ($db->query('SELECT * FROM favoris WHERE id_utilisateur = ' . $id_personne . ' AND id_recette=' . $id_recette) as $row) {
        array_push($recettes, $row["id_recette"]);
    }


    if ((sizeof($recettes) > 0)) {
        $sql = "DELETE FROM favoris WHERE id_utilisateur=" . $id_personne . " AND id_recette=" . $id_recette;

        if ($db->query($sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $db->error;
        }
        
        header('location:../'.$_GET["index"].'.php#'.$id_recette);
    }

}else{ // CAS TEMPORAIRE

    if (($key = array_search($id_recette, $_SESSION["favoris"])) !== false) {
        unset($_SESSION["favoris"][$key]);
    }

}
?>
