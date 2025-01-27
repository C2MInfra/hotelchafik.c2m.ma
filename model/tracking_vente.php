<?php

class tracking_vente extends table
{

   protected $code_bar;
   protected $date_vente;
   protected $qte_vendu;
   protected $guid_client;
   protected $num_ticket;
   protected $prix;


   public function getSoldByDateAndClient($guid, $date_d, $date_f)
   {
   }


   public function selectAll3($date)
   {
      $date_parts = explode('-', $date);
      $year = $date_parts[0];
      $month = $date_parts[1];

      if ($month != 0) {
         $dateCondition = "DATE_FORMAT(tv.date_vente,'%Y-%m') = '$year-$month'";
      } else {
         $dateCondition = "DATE_FORMAT(tv.date_vente,'%Y') = '$year'";
      }



      $result = connexion::getConnexion()->query("SELECT * FROM `tracking_vente` tv JOIN produit p ON tv.code_bar = p.code_bar JOIN client c ON tv.guid_client = c.pv_guid WHERE tv.qte_vendu<0 AND $dateCondition ORDER BY tv.date_vente DESC");


      return $result->fetchAll(PDO::FETCH_OBJ);
   }


   public function selectAll3all()
   {

      $result = connexion::getConnexion()->query("SELECT * FROM `tracking_vente` tv JOIN produit p ON tv.code_bar = p.code_bar JOIN client c ON tv.guid_client = c.pv_guid WHERE tv.qte_vendu<0 ORDER BY tv.date_vente DESC");


      return $result->fetchAll(PDO::FETCH_OBJ);
   }
}
