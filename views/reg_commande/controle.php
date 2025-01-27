<?php
include('../../evr.php');




if ($_POST['act']=='insert') {
	try {
		
	 if ($_POST['date_validation']=='') {
   $_POST['date_validation'] = '1900-01-01';
   }
 if (empty($_POST['num_cheque'])) {
 	
   $_POST['num_cheque'] = 0;
   }


	if($_POST["remarque"]=='') 
	{
		  $_POST["remarque"] = "Reglement global [ Montant : " . $montant_t . "/Mode de Reglement : " . $_POST["mode_reg"] . "/Date : " . $_POST["date_reg"] . "]";

	 }

	$_POST["id_user"] =auth::user()["id"] ;
  
   //$i = 0;
   $montant = $_POST["montant"];
   $montant_t = $_POST["montant"];
  
   $reg_commande = new reg_commande();
   $reg_commande->insert();

		die('success');
		} catch (Exception $e) {
				die($e);
		
	}
}
elseif ($_POST['act']=='update') {
	try {
		
		$reg_commande=new reg_commande();
 		$reg_commande->update($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
		
	}
}
elseif ($_POST['act']=='delete') {
	try {
		
		$reg_commande = new reg_commande();
		$reg_commande->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}




?>