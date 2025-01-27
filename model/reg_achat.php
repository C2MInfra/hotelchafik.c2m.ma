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
      
      $result = connexion::getConnexion()->query("select rga.* from reg_achat rga
where  DATE_FORMAT(rga.date_reg,'%Y-%m')='" . $date . "'  order by rga.date_reg desc  ");

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

   public function alertReg() {
      $result = connexion::getConnexion()->query("select ra.*,f.raison_sociale,DATEDIFF(CURDATE(), date_validation) as 
dif from reg_achat ra,achat a,fournisseur f where ra.id_achat=a.id_achat 
and a.id_fournisseur=f.id_fournisseur and (ra.mode_reg = 'Cheque' or ra.mode_reg = 'Effet') 
and UNIX_TIMESTAMP(ra.date_validation) != 0 order by ra.date_reg desc");
      //$result = connexion::getConnexion()->query("SELECT DATEDIFF(\"2017-06-25\", \"2017-06-15\");");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

}

?>