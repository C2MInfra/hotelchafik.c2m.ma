<?php
class  detail_avoir extends table{

protected $id_detail; 
protected $id_avoir;
protected $id_produit;
protected $prix_produit;
protected $qte_rendu;
protected $id_user;
protected $remise;
protected $unit;	
	
public function selectAllNonValide(){
	$result=connexion::getConnexion()->query("
	SELECT da.id_detail,remise,p.designation,p.code_bar,p.poid,da.prix_produit,da.qte_rendu  FROM   detail_avoir da left join produit p on (p.id_produit=da.id_produit) where da.id_avoir=-1".$_SESSION["rand_av_er"]." and da.id_user=".$_SESSION["gs_id"]." order by da.id_detail desc");
	return $result->fetchAll(PDO::FETCH_OBJ);
	} 
	
public function selectAllValide($id){
	$result=connexion::getConnexion()->query("SELECT dv.id_detail,remise,dv.id_produit,p.designation,dv.prix_produit,dv.unit, dv.qte_rendu  FROM  
	 detail_avoir dv left join produit p on (p.id_produit=dv.id_produit) where dv.id_avoir=".$id." order by dv.id_detail desc");
	return $result->fetchAll(PDO::FETCH_OBJ);
	} 	
	public function selectById($id){
	$result=connexion::getConnexion()->query("SELECT dv.prix_produit,remise,dv.qte_rendu,dv.id_produit,p.id_categorie  FROM  
	 detail_avoir dv left join produit p on (p.id_produit=dv.id_produit) where dv.id_detail=".$id);
	return $result->fetch(PDO::FETCH_ASSOC);
	} 
	public  function gettotale($id){
	
  $result=connexion::getConnexion()->query("select sum(`prix_produit`*`qte_rendu`*(1-remise/100))as totale from `detail_avoir` WHERE `id_avoir`=".$id." group by `id_avoir`");
	return $result->fetch(PDO::FETCH_ASSOC);
}
}
?>