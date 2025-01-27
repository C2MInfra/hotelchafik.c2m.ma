<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 27/02/2018
 * Time: 17:07
 */

class compte extends table {
   protected $id;
   protected $num_compte;
   protected $nom_compte;
   protected $nom_banque;
   protected $responsable;
   protected $telephone;
   protected $fax;
   protected $email;
   protected $remarque;

   public function selectAllCompte(){
      $result=connexion::getConnexion()->query("select * from compte order by id desc");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
}