<?php


define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME','cocktails');



$log=array(); 
try{
    $db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME,DB_USERNAME,DB_PASSWORD);
}catch(PDOException $e){
    print "Erreur :" . $e->getMessage()."<br/>";
    die;
}


?>