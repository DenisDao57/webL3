<html>


<?php

session_start();

include '../bdd.php';
include '../Hashcode.php';

//// TEST MAIL & INSCRIPTION

if (hash($HASH_CODE,$_POST["pwd_old"])==$_SESSION["pwd_old"]){

    $array_mail=array();
    foreach($db->query('SELECT * FROM personne WHERE mail = "'.$_POST["mail"].'"') as $row){
        array_push($array_mail,$row["mail"]);
    }

    if (sizeof($array_mail)>0 && !($_POST["mail"]==$_SESSION["mail"])){ 
        header('location:../Account.php?mail=f');
    }else{
        $sql = "UPDATE personne SET mail=?,pwd=? WHERE id=".$_SESSION["id"];
        $stmt = $db->prepare($sql);
        if ($_POST["pwd"] == ""){
            $stmt->execute([$_POST["mail"],$_SESSION["pwd_old"]]);
        }else{
        $stmt->execute([$_POST["mail"],hash($HASH_CODE,$_POST["pwd"])]);
        $_SESSION["pwd_old"]=hash($HASH_CODE,$_POST["pwd"]);
        }
        $_SESSION["mail"]=$_POST["mail"];
        header('location:../index.php');
 

    }

}else{
    header('location:../Account.php?pwd=f');
}
