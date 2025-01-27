<?php
class auth {

		  
	 public static function user()
	 {      
	 	if (isset($_SESSION['gs_login'])) {
	 	
	 	$user = array(
	 		"login" => $_SESSION['gs_login'], 
	 		"id" => $_SESSION['gs_id'], 
	 		"email" => $_SESSION['gs_email'], 
	 		"nom" => $_SESSION['gs_nom'], 
	 		"tele" => $_SESSION['gs_tele'], 
	 		"privilege" => $_SESSION['gs_privilege'], 
	 		"client" => $_SESSION['gs_client'], 
	 		"fournisseur" => $_SESSION['gs_fournisseur'], 
	 		"produit" => $_SESSION['gs_produit'], 
	 		"achat" => $_SESSION['gs_achat'], 
	 		"vente" => $_SESSION['gs_vente'], 
	 		"charge" => $_SESSION['gs_charge'], 
	 		"avoir" => $_SESSION['gs_avoir']);
	 
	 	return $user;
	 
	 	}
	 	return null;
	 }


	public static function login($user)
	 {      

	 	
			 $_SESSION['gs_login'] = $user->login;
			 $_SESSION['gs_id'] = $user->id;
			 $_SESSION['gs_email'] = $user->email;
			 $_SESSION['gs_nom'] = $user->nom;
			 $_SESSION['gs_tele'] = $user->tele;
			 $_SESSION['gs_privilege'] = $user->privilege;
			 $_SESSION['gs_client'] = $user->client;
			 $_SESSION['gs_fournisseur'] = $user->fournisseur;
			 $_SESSION['gs_produit'] = $user->produit;
			 $_SESSION['gs_achat'] = $user->achat;
			 $_SESSION['gs_vente'] = $user->vente;
			 $_SESSION['gs_charge'] = $user->charge;
			 $_SESSION['gs_avoir'] = $user->avoir;

	 }

	public static function logout()
	 {  
             unset($_SESSION['gs_login']);
             unset($_SESSION['gs_email']);
             unset($_SESSION['gs_nom']);
             unset($_SESSION['gs_tele']);
			 unset($_SESSION['gs_id']);
			 unset($_SESSION['gs_privilege']);
			 unset($_SESSION['gs_client']);
			 unset($_SESSION['gs_fournisseur']);
			 unset($_SESSION['gs_produit']);
			 unset($_SESSION['gs_achat']);
			 unset($_SESSION['gs_vente']);
			 unset($_SESSION['gs_charge']);
			 unset($_SESSION['gs_avoir']);
	 }
 


}