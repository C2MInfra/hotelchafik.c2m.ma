<?php

class reg_achat extends table {


   protected $id_reg;
   protected $id_achat;
   protected $mode_reg;
   protected $num_cheque;
   protected $date_reg;
   protected $montant;
   protected $etat;
   protected $remarque;
   protected $id_user;
   protected $id_compte;
   protected $date_validation;


   public function selectAll2($id) {
      $result = connexion::getConnexion()->query("select * from reg_achat where id_achat=" . $id . "  order by id_reg  desc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll3_er($date) {
      die("select rga.* from reg_achat rga
where (mode_reg = 'Cheque' or mode_reg = 'Effet') and DATE_FORMAT(rga.date_reg,'%Y-%m')='" . $date . "'  order by rga.date_reg desc  ");
      $result = connexion::getConnexion()->query("select rga.* from reg_achat rga
where (mode_reg = 'Cheque' or mode_reg = 'Effet') and DATE_FORMAT(rga.date_reg,'%Y-%m')='" . $date . "'  order by rga.date_reg desc  ");

      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll3all_er() {
      $result = connexion::getConnexion()->query("select rga.* from reg_achat rga
where mode_reg = 'Cheque' or mode_reg = 'Effet' order by rga.date_reg desc  ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllYear_er($date) {
      $result = connexion::getConnexion()->query("select rga.* from reg_achat rga
where (mode_reg = 'Cheque' or mode_reg = 'Effet') and DATE_FORMAT(rga.date_reg,'%Y')='" . $date . "'  order by rga.date_reg desc  ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

}

?>