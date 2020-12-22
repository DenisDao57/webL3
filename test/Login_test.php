<html>


<?php

session_start();

include '../bdd.php';
include '../Hashcode.php';

$log=array();
$hash_pwd=hash($HASH_CODE,$_POST["pwd"]);
foreach($db->query('SELECT * FROM personne WHERE mail=\''.$_POST["mail"].'\' AND pwd=\''.$hash_pwd.'\'') as $row){
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