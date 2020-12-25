<?php

include "../bdd.php";

session_start();

if (isset($_GET["personne"])){
    $id_personne=$_GET["personne"];
}
if (isset($_GET["recette"])){
    $id_recette=$_GET["recette"];
}


if ($id_personne!=-1){ // Cas login
        $stmt = $db->prepare("INSERT INTO favoris (id_utilisateur,id_recette) VALUES (:id_personne,:id_recette)");
        $stmt->bindParam(":id_personne",$id_personne);
        $stmt->bindParam(":id_recette",$id_recette);
        $stmt->execute();
        header('location:../index.php#'.$id_recette);
}else{ // Cas stock temporaire
    $array_temp=array();
    array_push($array_temp,$id_recette); 
    if (isset($_SESSION["favoris"]))  array_push($_SESSION["favoris"],$id_recette);
    else{
        $_SESSION["favoris"]=$array_temp;
    }
    header('location:../index.php#'.$id_recette);

}

?>