<?php
class  detail_demande_achat extends table{

  
protected $id_detail_demande; 
protected $id_demande;
protected $id_produit;
protected $prix_produit;
protected $qte_achete;
protected $id_user;

public function selectAllNonValide(){
	$result=connexion::getConnexion()->query("SELECT da.id_detail_demande,da.id_produit,p.designation,p.poid,da.prix_produit,da.qte_achete  FROM 
	  detail_demande_achat da left join produit p on (p.id_produit=da.id_produit) where
	   da.id_demande=-1".$_SESSION["rand_a_d"]." and da.id_user=".$_SESSION["gs_id"]." order by da.id_detail_demande desc");
	return $result->fetchAll(PDO::FETCH_OBJ);
	} 
	
public function selectAllValide($id){
	$result=connexion::getConnexion()->query("SELECT da.id_detail_demande,da.id_produit,p.designation,da.prix_produit,da.qte_achete,p.prix_achat  FROM 
	  detail_demande_achat da left join produit p on (p.id_produit=da.id_produit) where da.id_demande=".$id." order by da.id_detail_demande desc");
	return $result->fetchAll(PDO::FETCH_OBJ);
	} 	
public  function gettotale($id){
  $result=connexion::getConnexion()->query("select sum(`prix_produit`*`qte_achete`)as totale from `detail_demande_achat` WHERE `id_demande`=".$id." group by `id_demande`");
	return $result->fetch(PDO::FETCH_ASSOC);
}
  
}
?>