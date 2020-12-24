<?php

include "../bdd.php";

if (isset($_GET["personne"])) {
    $id_personne = $_GET["personne"];
}
if (isset($_GET["recette"])) {
    $id_recette = $_GET["recette"];
}




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
    
    header('location:../'.$_GET["index"].'.php');
}

?>
