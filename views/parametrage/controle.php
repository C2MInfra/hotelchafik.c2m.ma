<?php
include('../../eve.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


if ($_POST['act']=='insert') {
	try {
		$societe=new societe();

	if(isset($_POST["retour"]) && $_POST["retour"]!=""){
	$societe->alter_retour($_POST["retour"]);
	}

	if(isset($_POST["facture"]) && $_POST["facture"]!=""){
	$societe->alter_facture($_POST["facture"]);
	}

	$societe=new societe();
	$data_r=$societe->get_retour();
	$data_f=$societe->get_facture();
		

			die('success');


		} catch (Exception $e) {
				die($e);
		
	}
}
if ($_POST['act']=='list_depots') {
	$depot = new depot();
	$list = $depot->selectALl();

	die(json_encode($list));
}


if ($_POST['act']=='update_qte') {

	//Getting data from request
	$uuid_depot = $_POST['depot'];
	$nom_produit = $_POST['data'][0]['name'];
	$qte = $_POST['data'][0]['qte'];

	// fetching dpots and prods
	$result=connexion::getConnexion()->query("SELECT * FROM depot WHERE UUID='".$uuid_depot."'");
	$depot = $result->fetch(PDO::FETCH_OBJ);

	$result=connexion::getConnexion()->query("SELECT * FROM produit WHERE designation='".$nom_produit."'");
	$produit = $result->fetch(PDO::FETCH_OBJ);

	// Subtracting the quantity from the prod qte_actuel

	$query = "UPDATE produit SET qte_actuel = qte_actuel - $qte WHERE id_produit = $produit->id_produit";
	connexion::getConnexion()->exec($query);


	// Subtracting the quantity from the prod_depot qte

	$query = "UPDATE produit_depot SET qte = qte - $qte WHERE id_produit = $produit->id_produit AND id_depot = $depot->id";
	connexion::getConnexion()->exec($query);


	die("success");

}
?>