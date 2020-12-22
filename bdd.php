<?php


define('DB_SERVER', 'bt2zdg2wq1msrihj3pq9-mysql.services.clever-cloud.com');
define('DB_USERNAME', 'uyayoi3vygszk7do');
define('DB_PASSWORD', 'd6ieT4H86o1Ei8zGFfam');
define('DB_NAME','bt2zdg2wq1msrihj3pq9');

$log=array(); 
try{
    $db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME,DB_USERNAME,DB_PASSWORD);
}catch(PDOException $e){
    print "Erreur :" . $e->getMessage()."<br/>";
    die;
}


?>

