<?php

if(!defined('DB_SERVER_XAMPP'))
{
    define('DB_SERVER_XAMPP', 'localhost');
}
if(!defined('DB_USERNAME_XAMPP'))
{
    define('DB_USERNAME_XAMPP', 'root');
}
if(!defined('DB_PASSWORD_XAMPP'))
{
    define('DB_PASSWORD_XAMPP', '');
}
if(!defined('DB_NAME_XAMPP'))
{
    define('DB_NAME_XAMPP','cocktails');
}



$log=array(); 
try{
    $db = new PDO('mysql:host='.DB_SERVER_XAMPP.';dbname='.DB_NAME_XAMPP,DB_USERNAME_XAMPP,DB_PASSWORD_XAMPP);
}catch(PDOException $e){
    print "Erreur :" . $e->getMessage()."<br/>";
    die;
}


?>