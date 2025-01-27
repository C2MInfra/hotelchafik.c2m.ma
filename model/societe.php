<?php
class societe extends table{
	  
protected $id;
protected $raisonsocial;
protected $telephone;
protected $fax;
protected $email;
protected $compte;
protected $iff;
protected $rc;
protected $patente;
protected $adresse;
protected $logo;
protected $unite_util,$etat_unite;

protected $IdUser;

public function get_retour(){
	$retour=connexion::getConnexion()->query("select AUTO_INCREMENT AS id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'ikhlas_food_code' AND TABLE_NAME = 'avoir'");
	return $retour->fetchAll(PDO::FETCH_OBJ); 	
}

public function get_facture(){
	$retour=connexion::getConnexion()->query("select AUTO_INCREMENT AS id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'ikhlas_food_code' AND TABLE_NAME = 'facture'");
	return $retour->fetchAll(PDO::FETCH_OBJ); 	
}

public function alter_retour($num){
	$statut=connexion::getConnexion()->exec("ALTER TABLE avoir AUTO_INCREMENT = ".$num." ");
	return $statut;
	
}

public function alter_facture($num){
	$statut=connexion::getConnexion()->exec("ALTER TABLE facture AUTO_INCREMENT = ".$num." ");
	return $statut;
	
}


}
?>