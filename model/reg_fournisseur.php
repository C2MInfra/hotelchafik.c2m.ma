<?php

class reg_fournisseur extends table {


   protected $id_reg;
   protected $id_fournisseur;
   protected $mode_reg;
   protected $num_cheque;
   protected $date_reg;
   protected $montant;
   protected $remarque;
   protected $id_user;
   protected $etat;
   protected $id_compte;
   protected $date_validation;


   public function selectAll2($id) {
      $result = connexion::getConnexion()->query("select * from reg_fournisseur where id_fournisseur=" . $id . "  order by id_reg  desc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll3_er($date) {
      $result = connexion::getConnexion()->query("select rgf.* from reg_fournisseur rgf
where DATE_FORMAT(rgf.date_reg,'%Y-%m')='" . $date . "'  order by rgf.date_reg desc  ");

      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll3all_er() {
      $result = connexion::getConnexion()->query("select rgf.* from reg_fournisseur rgf
where mode_reg = 'Cheque' or mode_reg = 'Effet' order by rgf.date_reg desc  ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllYear_er($date) {
      $result = connexion::getConnexion()->query("select rgf.* from reg_fournisseur rgf
where (mode_reg = 'Cheque' or mode_reg = 'Effet') and DATE_FORMAT(rgf.date_reg,'%Y')='" . $date . "'  order by rgf.date_reg desc  ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }


}

?>