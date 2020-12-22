<html>


<?php

session_start();

include '../bdd.php';
include '../Hashcode.php';

//// DEFINITION ID
$array_id=array();
foreach($db->query('SELECT MAX(id) as id from personne') as $row){
    array_push($array_id,$row["id"]);
}
if (sizeof($array_id)>0){
   $id=$row["id"]+1;
}else{
    $id=1;
}
////
//// TEST MAIL & INSCRIPTION
$array_mail=array();
foreach($db->query('SELECT * FROM personne WHERE mail = "'.$_POST["mail"].'"') as $row){
    array_push($array_mail,$row["mail"]);
}
if (sizeof($array_mail)>0){ 
    header('location:../Register.php?mail=f');
}else{
    $stmt = $db->prepare("INSERT INTO personne (id,mail,pwd) VALUES (:id,:mail,:pwd)");
    $stmt->bindParam(":id",$id);
    $stmt->bindParam(":mail",$_POST["mail"]);
    $stmt->bindParam(":pwd",hash($HASH_CODE,$_POST["pwd"]));
    $stmt->execute();
    header('location:../Login.php');

}





?>