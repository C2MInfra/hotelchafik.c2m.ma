<?php
class avoir extends table{

protected $id_avoir; 
protected $id_client;
protected $date_avoir;
protected $montantv;
protected $remarque;
protected $id_user; 

public function selectavoirByClient($id_client,$date){
$result=connexion::getConnexion()->query("select v.id_avoir,
DATE_FORMAT(v.date_avoir,'%d-%m-%Y')as date_avoir,
concat_ws(' ',c.nom,c.prenom)  as client,c.id_client,v.remarque   ,sum(dv.prix_produit*dv.qte_rendu*(1-remise/100))as
 montantv ,(select ifnull(sum(montant),0) from reg_avoir where id_avoir=v.id_avoir) as avance
from avoir v left join  client c on  c.id_client=v.id_client left join detail_avoir dv on dv.id_avoir=v.id_avoir
where  v.id_client=$id_client and DATE_FORMAT(v.date_avoir,'%Y-%m')='".$date."'
group by  v.id_avoir  order by id_avoir desc " );
	return $result->fetchAll(PDO::FETCH_OBJ);
} 

public function selectAll2(){
$result=connexion::getConnexion()->query("select v.id_avoir,
DATE_FORMAT(v.date_avoir,'%d-%m-%Y')as date_avoir,
concat_ws(' ',c.nom,c.prenom)  as client,c.id_client,c.nom_prenom_ar,v.remarque   ,sum(dv.prix_produit*dv.qte_rendu*(1-c.remise/100))as
 montantv ,(select ifnull(sum(montant),0) from reg_avoir where id_avoir=v.id_avoir) as avance
from avoir v left join  client c on  c.id_client=v.id_client left join detail_avoir dv on dv.id_avoir=v.id_avoir
group by  v.id_avoir  order by id_avoir desc " );
	return $result->fetchAll(PDO::FETCH_OBJ);
} 

public function selectAll3($date){

 	   $date_parts = explode('-', $date);
	   $year = $date_parts[0];
	   $month = $date_parts[1];
	   
	   if($month != 0)
	   {
		  $dateCondition = "DATE_FORMAT(v.date_avoir,'%Y-%m') = '$year-$month'";  
	   }
	   else
	   {
		  $dateCondition = "DATE_FORMAT(v.date_avoir,'%Y') = '$year'";
	   }
	
		$result=connexion::getConnexion()->query("select v.id_avoir,
		DATE_FORMAT(v.date_avoir,'%d-%m-%Y')as date_avoir,
		concat_ws(' ',c.nom,c.prenom)  as client,c.id_client,v.remarque   ,sum(dv.prix_produit*dv.qte_rendu*(1-remise/100))as
		 montantv ,(select ifnull(sum(montant),0) from reg_avoir where id_avoir=v.id_avoir) as avance
		from avoir v left join  client c on  c.id_client=v.id_client left join detail_avoir dv on dv.id_avoir=v.id_avoir
		where  $dateCondition
		group by  v.id_avoir  order by id_avoir desc" );
	    
	    return $result->fetchAll(PDO::FETCH_OBJ);
} 

public function All(){

$result=connexion::getConnexion()->query("select v.id_avoir,
DATE_FORMAT(v.date_avoir,'%d-%m-%Y')as date_avoir,
concat_ws(' ',c.nom,c.prenom)  as client,c.id_client,v.remarque   ,sum(dv.prix_produit*dv.qte_rendu*(1-remise/100))as
 montantv ,(select ifnull(sum(montant),0) from reg_avoir where id_avoir=v.id_avoir) as avance
from avoir v left join  client c on  c.id_client=v.id_client left join detail_avoir dv on dv.id_avoir=v.id_avoir
group by  v.id_avoir  order by id_avoir desc" );
	return $result->fetchAll(PDO::FETCH_OBJ);
} 


public function selectAll2_ancieene(){
$result=connexion::getConnexion()->query("select v.id_avoir,
DATE_FORMAT(v.date_avoir,'%d-%m-%Y')as date_avoir,
concat_ws(' ',c.nom,c.prenom)  as client,c.id_client,v.remarque   ,sum(dv.prix_produit*dv.qte_rendu*(1-remise/100))as
 montantv from avoir v left join  client c on  c.id_client=v.id_client left join detail_avoir dv on dv.id_avoir=v.id_avoir
group by  v.id_avoir  order by id_avoir desc" );
	return $result->fetchAll(PDO::FETCH_OBJ);
}
	
public function selectAllDate($client,$dd,$df){
$result=connexion::getConnexion()->query("select v.id_avoir,
DATE_FORMAT(v.date_avoir,'%d-%m-%Y')as date_avoir,
v.remarque ,
sum(dv.prix_produit*dv.qte_rendu*(1-remise/100)) as montantv 

 from avoir v  
 left join detail_avoir dv on dv.id_avoir=v.id_avoir
 where  (date_avoir between '$dd' and '$df')  and  id_client=$client 
 group by  v.id_avoir  
 order by date_avoir asc " );
	return $result->fetchAll(PDO::FETCH_OBJ);
	}	

}






?>
