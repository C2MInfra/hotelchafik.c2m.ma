<?php

class reg_vendeur extends table {

   protected $id_reg;
   protected $id_bon;
   protected $mode_reg;
   protected $num_cheque;
   protected $date_reg;
   protected $montant;
   protected $remarque;
   protected $etat;
   protected $id_user;
   protected $id_compte;
   protected $date_validation;
	
   public function selectAll2($id) 
   {
      $result = connexion::getConnexion()->query("select * from reg_vendeur where id_bon=" . trim($id, ',') . " order by id_reg  desc ");
	   
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
}

?>
