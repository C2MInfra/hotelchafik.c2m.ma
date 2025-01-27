<?php

class facture extends table {

   protected $id_facture;
   protected $id_vente;
   protected $date_facture;
   protected $num_fact;
   protected $remarque;
   protected $id_user;
   protected $tva;
   protected $taux;

   public function selectAll2($date = '') {
      $Req = "";
      if($date != "")
         $Req = (strpos($date, "-") > 0 ? "DATE_FORMAT(date_facture,'%Y-%m') = '$date' AND " : "YEAR(date_facture) = '$date' AND ");
      $result = connexion::getConnexion()->query("SELECT f.* , SUM(prix_produit * qte_vendu * (1- remise/100) +  prix_produit * qte_vendu * (1- remise/100) * p.tva) AS montant FROM facture f  inner join produit p on d.id_produit=p.id_produit
         inner join detail_vente d on f.id_vente like concat('%,',d.id_vente,',%') WHERE $Req trim(d.id_vente) REGEXP REPLACE(TRIM(BOTH ',' FROM f.id_vente),',','|') GROUP BY f.id_facture;");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll3($date ,$search_type = 0) {
	   if($search_type == 0)
	   {
		   $condition_date = '';
	   }
	   elseif($search_type == 1)
	   {
		   $date_parts = explode('-', $date);
		   $year = $date_parts[0];
		   $month = $date_parts[1];

		   if($month != 0)
		   {
			  $dateCondition = "DATE_FORMAT(date_facture,'%Y-%m') = '$year-$month' AND ";  
		   }
		   else
		   {
			  $dateCondition = "DATE_FORMAT(date_facture,'%Y') = '$year' AND ";
		   }
	   }
	   
      $result = connexion::getConnexion()->query("SELECT f.* ,c.nom ,
      

      SUM( prix_produit *if(valunit=0,qte_vendu,valunit)* (1- c.remise/100)) AS motunitv,
      
      SUM( prix_produit * qte_vendu * (1- c.remise/100) ) AS montant,
      sum(prix_produit*qte_vendu) AS montant1 
      FROM facture f inner join vente v on f.id_vente like concat('%,',v.id_vente,',%') inner join detail_vente d on v.id_vente = d.id_vente 
          inner join produit p on d.id_produit=p.id_produit
          inner join client c on c.id_client=v.id_client WHERE $dateCondition trim(d.id_vente) REGEXP REPLACE(TRIM(BOTH ',' FROM f.id_vente),',','|') GROUP BY f.id_facture ORDER BY f.id_facture DESC");
      return $result->fetchAll(PDO::FETCH_OBJ);
   
   }

   /*
      public function selectAll3(){
      $result=connexion::getConnexion()->query("SELECT distinct f.*,c.nom from facture f inner join
       vente v on f.id_vente like concat('%,',v.id_vente,',%') inner join client c on
       c.id_client=v.id_client ORDER BY `f`.`id_facture`  DESC");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }*/
   public function selectVente($list) {

      $result = connexion::getConnexion()->query("SELECT sum(prix_produit*qte_vendu) as montant 
	from vente v inner join detail_vente d on  v.id_vente=d.id_vente
	where v.id_vente in($list);");
      return $result->fetch(PDO::FETCH_OBJ);
   }

   public function selectOperationVente($list) {
      $result = connexion::getConnexion()->query("SELECT sum(prix_produit*qte_vendu) as montant 
	from vente v inner join detail_vente d on  v.id_vente=d.id_vente
	where v.id_vente in(1,5)
	");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
}

?>
