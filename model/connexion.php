<?php

/*define('SERVER','localhost');

//define('DATABASE','gestion_med');

define('DATABASE','promosev_ikhlas_food_code');

define('USER','root');

define('PASSWORD','');

/*

define('USER','promosev_med');

define('PASSWORD','I5Nump8UauC2');*/

class connexion{



public static function getConnexion(){

try

{

 $connexion = new PDO('mysql:host='.SERVER.';dbname='.DATABASE,USER, PASSWORD);

   $connexion->exec("SET NAMES 'utf8'");
  //  $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   $connexion->exec('SET CHARACTER SET utf8');



 }

 

catch(Exception $e)

{

        echo 'Erreur : '.$e->getMessage().'<br />';

        echo 'N° : '.$e->getCode();

}



return $connexion;

}

}

?>