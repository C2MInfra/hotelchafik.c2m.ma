<?php
class facture_avoir extends table{

protected $id_facture;
protected $id_avoir; 
protected $date_facture;
protected $remarque;
protected $id_user;
protected $tva; 

public function selectAll2(){
	$result=connexion::getConnexion()->query("SELECT * from facture_avoir");
	return $result->fetchAll(PDO::FETCH_OBJ);
	}
public function selectAvoir($list){
	$result=connexion::getConnexion()->query("SELECT sum(prix_produit*qte_rendu) as montant 
	from avoir a inner join detail_avoir d on  a.id_avoir=d.id_avoir
	where a.id_avoir in($list)
	");
	return $result->fetch(PDO::FETCH_OBJ);
}
public function selectOperationAvoir($list){
	$result=connexion::getConnexion()->query("SELECT sum(prix_produit*qte_rendu) as montant 
	from avoir a inner join detail_avoir d on  a.id_avoir=d.id_avoir
	where a.id_avoir in(1,5)
	");
	return $result->fetchAll(PDO::FETCH_OBJ);
}
 
}






?>