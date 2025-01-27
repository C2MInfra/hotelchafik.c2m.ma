<?php

class reg_client extends table {


   protected $id_reg;
   protected $id_client;
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
      $result = connexion::getConnexion()->query("select * from reg_client where id_client=" . $id . "  order by id_reg  desc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll3_er($date) {
      $result = connexion::getConnexion()->query("select rgc.* from reg_client rgc where  DATE_FORMAT(rgc.date_reg,'%Y-%m')='" . $date . "'  order by rgc.date_reg desc  ");

      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function getUnpaidVentesWithRemainingAmount($id_client){

     return connexion::getConnexion()->query("SELECT v.id_vente,(SUM(dv.prix_produit * dv.qte_vendu) - v.amount_paid) as remaining , v.date_vente, v.id_vente FROM vente v JOIN detail_vente dv ON v.id_vente = dv.id_vente
WHERE v.id_client = $id_client and v.is_paied = 0
GROUP BY dv.id_vente")->fetchAll(PDO::FETCH_OBJ);

   }

   public function selectAll3all_er() {
      $result = connexion::getConnexion()->query("select rgc.* from reg_client rgc where mode_reg = 'Cheque' or mode_reg = 'Effet' order by rgc.date_reg desc  ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllYear_er($date) {
      $result = connexion::getConnexion()->query("select rgc.* from reg_client rgc where (mode_reg = 'Cheque' or mode_reg = 'Effet') and DATE_FORMAT(rgc.date_reg,'%Y')='" . $date . "'  order by rgc.date_reg desc  ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllQuery($q) {
      $result = connexion::getConnexion()->query($q);
      return $result->fetchAll(PDO::FETCH_OBJ);
   }


}

?>
