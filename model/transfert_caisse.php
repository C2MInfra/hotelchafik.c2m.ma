<?php
class transfert_caisse extends table{

protected $id; 
protected $designation;
protected $date_transfert_caisse;
protected $montant_espece;
protected $type_reg;
protected $image;
protected $id_user;
protected $remarque;
protected $date_add;
protected $date_update;
protected $date_delete;
protected $devise ;




public function All()
{
	$result=connexion::getConnexion()->query("select * from transfert_caisse WHERE date_delete is NULL order by id desc");
	return $result->fetchAll(PDO::FETCH_OBJ);
}







public function selectAll3($date, $search_type = 0)
{

	  if($search_type == 0)
	   {
		   $dateCondition = ' TRUE';
	   }
	   elseif($search_type == 1)
	   {
		   $date_parts = explode('-', $date);
		   $year = $date_parts[0];
		   $month = $date_parts[1];

		   if($month != 0)
		   {
			  $dateCondition = "DATE_FORMAT(date_transfert_caisse,'%Y-%m') = '$year-$month' ";  
		   }
		   else
		   {
			  $dateCondition = "DATE_FORMAT(date_transfert_caisse,'%Y') = '$year'";
		   }
	   }
	
	$result=connexion::getConnexion()->query("select * from transfert_caisse  where 
	 $dateCondition
	order by id desc" );
	 return $result->fetchAll(PDO::FETCH_OBJ);
} 




}



?>