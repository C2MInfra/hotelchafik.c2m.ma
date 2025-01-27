<?php
class  detail_commande extends table {

   protected $id_detail;
   protected $id_bon;
   protected $id_produit;
   protected $prix_produit;
   protected $qte_vendu;
   protected $remise;
   protected $id_user;
   protected $unit;
   protected $valunit;
   protected $id_depot;
	
   public function selectAllNonValide() 
   {

        $result = connexion::getConnexion()->query("
		SELECT p.tva as tva1,dv.unit,dv.valunit, dv.id_detail,    dv.remise,p.designation,p.code_bar,p.poid,dv.prix_produit,dv.qte_vendu  
	    FROM   detail_commande dv left join produit p on (p.id_produit=dv.id_produit) 
	    where dv.id_bon like '-1%" . $_SESSION["rand_v_er"] . "' and dv.id_user=" . $_SESSION["gs_id"] . " order by dv.id_detail desc");
	   
		 return $result->fetchAll(PDO::FETCH_OBJ);

   }
	
   public function selectAllValide($id) 
   {
      $result = connexion::getConnexion()->query("SELECT dp.nom AS depot, p.tva,dv.valunit,dv.unit,dv.remise,dv.id_detail,dv.id_produit,p.designation,dv.prix_produit,dv.qte_vendu  FROM  
	 detail_commande dv left join produit p on (p.id_produit=dv.id_produit) 
	 LEFT JOIN depot dp ON dp.id = dv.id_depot
	 where dv.id_bon=" . $id . " order by dv.id_detail desc");
	   
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
	
   public function gettotale($id) 
   {
      $result = connexion::getConnexion()->query("select 
      prix_produit , qte_vendu ,(1-remise/100) AS remise ,valunit
      from `detail_commande`,produit WHERE `id_bon`=" . $id . " and produit.id_produit=detail_commande.id_produit ");
	   
      return $result->fetchAll(PDO::FETCH_ASSOC);
   }

}

?>