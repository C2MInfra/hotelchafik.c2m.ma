<?php
class charge_achat extends table{

protected $id; 
protected $id_achat; 
 protected $fournisseur ; 
protected $designation;
protected $date_charge;
protected $montant;
protected $mode_reg;
protected $devise;
protected $image;
protected $num_cheque;
protected $date_validation;
protected $id_user;
protected $remarque;
protected $date_add;
protected $date_update;
protected $date_delete;
protected $cout_devise; 

public function All()
{
	$result=connexion::getConnexion()->query("select * from charge WHERE date_delete is NULL order by id desc");
	return $result->fetchAll(PDO::FETCH_OBJ);
}


public function selectbyId($id)

{
    
	$result=connexion::getConnexion()->query("select * from charge WHERE id = $id and date_delete is NULL order by id desc");

	if ($result)  return $result->fetchAll(PDO::FETCH_OBJ);
	
}
public function selectByIdAchat($id){
	$result=connexion::getConnexion()->query("select * from charge WHERE id_achat = $id and date_delete is NULL order by id desc");

	if ($result)  return $result->fetchAll(PDO::FETCH_OBJ);

}

public function selectEtat($dd,$df)
{
	//echo "select * from charge c where c.date_charge between $dd and $df and archive = 0 group by c.id_charge order by c.id_charge desc";

	$result=connexion::getConnexion()->query("select * from charge c where c.date_charge between '$dd' and '$df'  group by c.id  order by c.id desc" );
	return $result->fetchAll(PDO::FETCH_OBJ);
}



 
public function selectAll3($date, $search_type = 0 , $id)
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
			  $dateCondition = "DATE_FORMAT(date_charge,'%Y-%m') = '$year-$month' ";  
		   }
		   else
		   {
			  $dateCondition = "DATE_FORMAT(date_charge,'%Y') = '$year'";
		   }
	   }
	
	$result=connexion::getConnexion()->query("select *  from charge  where 

	 $dateCondition 
	order by id desc" );
	
	return $result->fetchAll(PDO::FETCH_OBJ);
} 


 public function selectDesignation(){
$result=connexion::getConnexion()->query("select * from design d group by  d.id  order by d.id desc" );
	return $result->fetchAll(PDO::FETCH_OBJ);
}

}



?>