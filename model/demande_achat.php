<?php
class demande_achat extends table{

protected $id_demande; 
protected $id_fournisseur;
protected $date_achat;
protected $montant;
protected $remarque;
protected $id_user; 
protected $id_achat; 

public function selectAll2(){
$result=connexion::getConnexion()->query("select a.id_demande,a.date_achat,f.raison_sociale as fournisseur,f.id_fournisseur,a.remarque   ,sum(da.`prix_produit`*da.`qte_achete`)as
 montant from demande_achat a left join  fournisseur f on  f.id_fournisseur=a.id_fournisseur left join detail_demande_achat da on da.id_demande=a.id_demande
group by  a.id_demande  order by id_demande desc");
	return $result->fetchAll(PDO::FETCH_OBJ);
} 

public function selectAll3($date){
$result=connexion::getConnexion()->query("select a.id_achat, a.id_demande,a.date_achat,f.raison_sociale as fournisseur,f.id_fournisseur,a.remarque   ,sum(da.`prix_produit`*da.`qte_achete`)as
 montant from demande_achat a left join  fournisseur f on  f.id_fournisseur=a.id_fournisseur left join detail_demande_achat da on da.id_demande=a.id_demande
 where  DATE_FORMAT(a.date_achat,'%Y-%m')='".$date."' 
group by  a.id_demande  order by id_demande desc");
	return $result->fetchAll(PDO::FETCH_OBJ);
}

public function selectAllYear($date){
$result=connexion::getConnexion()->query("select a.id_demande,a.date_achat,f.raison_sociale as fournisseur,f.id_fournisseur,a.remarque   ,sum(da.`prix_produit`*da.`qte_achete`)as
 montant from demande_achat a left join  fournisseur f on  f.id_fournisseur=a.id_fournisseur left join detail_demande_achat da on da.id_demande=a.id_demande
 where  YEAR(a.date_achat)='".$date."' 
group by  a.id_demande  order by id_demande desc");
	return $result->fetchAll(PDO::FETCH_OBJ);
}

public function selectAllDate($fournisseur,$dd,$df){
$result=connexion::getConnexion()->query("select a.id_demande,a.date_achat, sum(da.prix_produit*da.qte_achete) as montant 
 from demande_achat a  
 left join detail_demande_achat da on da.id_demande=a.id_demande
 where  (date_achat  between '$dd' and '$df')  and  id_fournisseur=$fournisseur 
 group by  a.id_demande  
 order by date_achat asc " );
	return $result->fetchAll(PDO::FETCH_OBJ);
	}
}
?>