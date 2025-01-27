<?php
class categorie extends table{
  
protected $id_categorie	; 
protected $nom; 

protected $remise_max;
protected $id_user; 

 
public function selectById2($id){
	$result=connexion::getConnexion()->query("select * from ".$this->className." where id_categorie=$id order by nom asc");
	return $result->fetchAll(PDO::FETCH_OBJ);
}	
public function selectAll(){
	$result=connexion::getConnexion()->query("select * from ".$this->className."  order by nom asc");
	return $result->fetchAll(PDO::FETCH_OBJ);
}
public function selectVenteByCategorie($id_categorie,$dd,$df){
	$result=connexion::getConnexion()->query("select p.id_produit,p.designation,p.designation_ar,sum(dv.prix_produit*(1-dv.remise)*dv.qte_vendu) as total,sum(qte_vendu) as qte
from vente v 
inner join detail_vente dv on v.id_vente=dv.id_vente
inner join produit p on p.id_produit=dv.id_produit
where v.numbon>0 and p.id_categorie=$id_categorie and (v.date_vente between '$dd' and '$df')
group by p.id_produit ");
	return $result->fetchAll(PDO::FETCH_OBJ);
} 

public function selectProduitBtCategory($id_categorie,$date)
{
//	die("select * from produit where id_categorie=$id_categorie and qte_actuel>0 and date_insertion<='".$date."' order by code_bar asc");
//	
	$result=connexion::getConnexion()->query("select p.* from produit p inner join produit_depot pd ON
	 p.id_produit = pd.id_produit where id_categorie=$id_categorie  and date_insertion<='".$date."' group by p.id_produit, p.code_bar order by p.code_bar ASC");
	
	return $result->fetchAll(PDO::FETCH_OBJ);
} 
	
	public function selectProduitByCategory($id_categorie,$date){
	$result=connexion::getConnexion()->query("select * from produit where id_categorie=$id_categorie  ");
	return $result->fetchAll(PDO::FETCH_OBJ);
} 
}
?>