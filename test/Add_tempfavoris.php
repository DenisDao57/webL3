<?php

include "../bdd.php";

session_start();

for ($i=0;$i<sizeof($_SESSION["favoris"]);$i++){
    $stmt = $db->prepare("INSERT INTO favoris (id_utilisateur,id_recette) VALUES (:id_personne,:id_recette)");
    $stmt->bindParam(":id_personne",$_SESSION["id"]);
    $stmt->bindParam(":id_recette",$_SESSION["favoris"][$i]);
    $stmt->execute();
}
header('location:../index.php');

?>