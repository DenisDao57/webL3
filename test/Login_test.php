<html>


<?php

session_start();

include '../bdd.php';

$log=array();
foreach($db->query('SELECT * FROM personne WHERE mail=\''.$_POST["mail"].'\' AND pwd=\''.$_POST["pwd"].'\'') as $row){
    array_push($log,$row["id"]);
    array_push($log,$row["mail"]);
    array_push($log,$row["pwd"]);
}

if (sizeof($log)>0){
    $_SESSION["login"]=true;
    $_SESSION["id"]=$row["id"];
    header('location:../Accueil.php');
}else{
    header('location:../Login.php?test=f');
}

?>