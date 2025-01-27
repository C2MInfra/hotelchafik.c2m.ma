<?php

class  detail_vente extends table {


   protected $id_detail;
   protected $id_vente;
   protected $id_produit;
   protected $prix_produit;
   protected $qte_vendu;
   protected $qte_restante;
   protected $remise;
   protected $id_user;
   protected $unit;
   protected $valunit;
   protected $id_depot;
   protected $id_bon_vendeur;
	
   public function selectAllNonValide() {

      $result = connexion::getConnexion()->query("
	SELECT dp.nom AS depot,p.tva as tva1,dv.unit,dv.valunit, dv.id_detail, dv.remise,p.designation,p.code_bar,p.poid,dv.prix_produit,dv.qte_vendu  
   FROM detail_vente dv left join produit p on (p.id_produit=dv.id_produit) 
   LEFT JOIN depot dp ON dp.id = dv.id_depot
   where dv.id_vente like '-1%" . $_SESSION["rand_v_er"] . "' and dv.id_user=" . $_SESSION["gs_id"] . " 
   AND id_bon_vendeur IS NULL
   order by dv.id_detail desc");
      return $result->fetchAll(PDO::FETCH_OBJ);

   }

   public function selectAllNonValideVendeur($id) 
   {
      $query = "
      SELECT dp.nom AS depot,p.tva as tva1,dv.unit,dv.valunit, dv.id_detail, dv.remise,p.designation,p.code_bar,p.poid,dv.prix_produit,dv.qte_vendu  
      FROM detail_vente dv left join produit p on (p.id_produit=dv.id_produit) 
      LEFT JOIN depot dp ON dp.id = dv.id_depot
      where dv.id_vente like '-1%" . $_SESSION["rand_v_er"] . "' and dv.id_user=" . $_SESSION["gs_id"] . " 
      AND id_bon_vendeur = $id
      order by dv.id_detail desc";

      $result = connexion::getConnexion()->query($query);

      return $result->fetchAll(PDO::FETCH_OBJ);

   }

   public function selectAllValide($id) {
      $result = connexion::getConnexion()->query("SELECT dp.nom AS depot, p.tva,dv.valunit,dv.unit,dv.remise,dv.id_detail,dv.id_produit,p.designation,dv.prix_produit,dv.qte_vendu  FROM  
	 detail_vente dv left join produit p on (p.id_produit=dv.id_produit) 
	 LEFT JOIN depot dp ON dp.id = dv.id_depot
	 where dv.id_vente=" . $id . " order by dv.id_detail desc");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectById($id) {
      $result = connexion::getConnexion()->query("SELECT dv.prix_produit,dv.qte_vendu,dv.id_produit,p.id_categorie  FROM  
	 detail_vente dv left join produit p on (p.id_produit=dv.id_produit) where dv.id_detail=" . $id);
      return $result->fetch(PDO::FETCH_ASSOC);
   }

   public function gettotale($id) {
      $result = connexion::getConnexion()->query("select 
      prix_produit , qte_vendu ,(1-remise/100) AS remise ,valunit
      from `detail_vente`,produit WHERE `id_vente`=" . $id . " and produit.id_produit=detail_vente.id_produit ");
      return $result->fetchAll(PDO::FETCH_ASSOC);
   }

  
}

?>