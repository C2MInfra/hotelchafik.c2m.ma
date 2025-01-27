<?php

class achat extends table{



protected $id_achat; 

protected $id_fournisseur;

protected $date_achat;

protected $montant;

protected $remarque;

protected $id_user; 
	
protected $valide;



public function selectAll2(){

$result=connexion::getConnexion()->query("select a.valide, a.id_achat,a.date_achat,f.raison_sociale as fournisseur,f.id_fournisseur,a.remarque   ,sum(da.`prix_produit`*da.`qte_achete`)as

 montant from achat a left join  fournisseur f on  f.id_fournisseur=a.id_fournisseur left join detail_achat da on da.id_achat=a.id_achat

group by  a.id_achat  order by id_achat desc");

	return $result->fetchAll(PDO::FETCH_OBJ);

} 



public function selectAll3($date ){

	   $date_parts = explode('-', $date);
	   $year = $date_parts[0];
	   $month = $date_parts[1];
	   
	   if($month != 0)
	   {
		  $dateCondition = "DATE_FORMAT(a.date_achat,'%Y-%m') = '$year-$month'";  
	   }
	   else
	   {
		  $dateCondition = "DATE_FORMAT(a.date_achat,'%Y') = '$year'";
	   }
	
		$result=connexion::getConnexion()->query("select a.id_achat,a.valide, a.date_achat,f.raison_sociale as fournisseur,f.id_fournisseur,a.remarque   ,sum(da.`prix_produit`*da.`qte_achete`)as

		 montant from achat a left join  fournisseur f on  f.id_fournisseur=a.id_fournisseur left join detail_achat da on da.id_achat=a.id_achat

		 where  $dateCondition 

		group by  a.id_achat  order by id_achat desc");

	   return $result->fetchAll(PDO::FETCH_OBJ);
}



public function selectAllYear($date){

$result=connexion::getConnexion()->query("select a.valide, a.id_achat,a.date_achat,f.raison_sociale as fournisseur,f.id_fournisseur,a.remarque   ,sum(da.`prix_produit`*da.`qte_achete`)as

 montant from achat a left join  fournisseur f on  f.id_fournisseur=a.id_fournisseur left join detail_achat da on da.id_achat=a.id_achat

 where  YEAR(a.date_achat)='".$date."' 

group by  a.id_achat  order by id_achat desc");

	return $result->fetchAll(PDO::FETCH_OBJ);

}



public function selectAllDate($fournisseur,$dd,$df){

$result=connexion::getConnexion()->query("select a.id_achat,a.date_achat, sum(da.prix_produit*da.qte_achete) as montant 

 from achat a  

 left join detail_achat da on da.id_achat=a.id_achat

 where  (date_achat  between '$dd' and '$df')  and  id_fournisseur=$fournisseur 

 group by  a.id_achat  

 order by date_achat asc " );

	return $result->fetchAll(PDO::FETCH_OBJ);

	}
	

public static function getdevis($ven) {
      $result = connexion::getConnexion()->query("SELECT p.id_produit , qte_achete from achat v inner join detail_achat d  on v.`id_achat`=d.`id_achat` 
	inner join produit p on p.`id_produit`=d.`id_produit` where v.id_achat=" . $ven);
      return $result->fetchAll(PDO::FETCH_ASSOC);

}

}

?>