<html>


<?php

session_start();

include '../bdd.php';

//// DEFINITION ID
$array_id=array();
foreach($db->query('SELECT MAX(id) as id from PERSONNE') as $row){
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
foreach($db->query('SELECT * from PERSONNE WHERE mail = "'.$_POST["mail"].'"') as $row){
    array_push($array_mail,$row["mail"]);
}
if (sizeof($array_mail)>0){ 
    header('location:../Register.php?mail=f');
}else{
    $stmt = $db->prepare("INSERT INTO PERSONNE (id,mail,pwd) VALUES (:id,:mail,:pwd)");
    $stmt->bindParam(":id",$id);
    $stmt->bindParam(":mail",$_POST["mail"]);
    $stmt->bindParam(":pwd",$_POST["pwd"]);
    $stmt->execute();

    header('location:../Login.php');
}





?>