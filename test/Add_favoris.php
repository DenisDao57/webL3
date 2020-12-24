<?php

include "../bdd.php";

if (isset($_GET["personne"])){
    $id_personne=$_GET["personne"];
}
if (isset($_GET["recette"])){
    $id_recette=$_GET["recette"];
}




$recettes=array();
foreach($db->query('SELECT * FROM favoris WHERE id_utilisateur = '.$id_personne.' AND id_recette='.$id_recette) as $row){
    array_push($recettes,$row["id_recette"]);
}


if (!(sizeof($recettes)>0)){ 
    $stmt = $db->prepare("INSERT INTO favoris (id_utilisateur,id_recette) VALUES (:id_personne,:id_recette)");
    $stmt->bindParam(":id_personne",$id_personne);
    $stmt->bindParam(":id_recette",$id_recette);
    $stmt->execute();
    header('location:../index.php');
}

?>