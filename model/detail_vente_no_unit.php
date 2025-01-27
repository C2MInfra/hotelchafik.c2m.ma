<?php

class  detail_vente extends table {


   protected $id_detail;
   protected $id_vente;
   protected $id_produit;
   protected $prix_produit;
   protected $qte_vendu;
   protected $remise;
   protected $id_user;
   protected $unit;
   protected $valunit;
   public function selectAllNonValide() {

      $result = connexion::getConnexion()->query("
	SELECT dv.unit,dv.valunit, dv.id_detail, dv.remise,p.designation,p.code_bar,p.poid,dv.prix_produit,dv.qte_vendu  FROM   detail_vente dv left join produit p on (p.id_produit=dv.id_produit) where dv.id_vente like '-1%" . $_SESSION["rand_v_er"] . "' and dv.id_user=" . $_SESSION["gs_id"] . " order by dv.id_detail desc");
      return $result->fetchAll(PDO::FETCH_OBJ);

   }

   public function selectAllValide($id) {
      $result = connexion::getConnexion()->query("SELECT dv.remise,dv.id_detail,dv.id_produit,p.designation,dv.prix_produit,dv.qte_vendu  FROM  
	 detail_vente dv left join produit p on (p.id_produit=dv.id_produit) where dv.id_vente=" . $id . " order by dv.id_detail desc");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectById($id) {
      $result = connexion::getConnexion()->query("SELECT dv.prix_produit,dv.qte_vendu,dv.id_produit,p.id_categorie  FROM  
	 detail_vente dv left join produit p on (p.id_produit=dv.id_produit) where dv.id_detail=" . $id);
      return $result->fetch(PDO::FETCH_ASSOC);
   }

   public function gettotale($id) {
      $result = connexion::getConnexion()->query("select sum(prix_produit*(1-remise/100)*`qte_vendu`)as totale from `detail_vente` WHERE `id_vente`=" . $id . " group by `id_vente`");
      return $result->fetch(PDO::FETCH_ASSOC);
   }
//public function selectById($id) {
//      $result = connexion::getConnexion()->query("SELECT dv.prix_produit,dv.qte_vendu,dv.id_produit,p.id_categorie  FROM  
//	 detail_vente dv left join produit p on (p.id_produit=dv.id_produit) where dv.id_detail=" . $id);
 //     return $result->fetch(PDO::FETCH_ASSOC);
  /// }
  
}

?>