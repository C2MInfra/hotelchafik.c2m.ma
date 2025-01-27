<?php 

include('../../evr.php'); 

$produits = connexion::getConnexion()->query("SELECT * FROM produit")->fetchAll(PDO::FETCH_OBJ);

echo 'start<br>';
foreach($produits as $produit)
{
	$query = "INSERT INTO produit_depot(id_produit, id_depot, qte) VALUES(" . $produit->id_produit . "," . $produit->emplacement . "," . $produit->qte_actuel . ")";
	
	echo $query . '<br>';
	connexion::getConnexion()->exec($query);
}
echo 'end<br>';