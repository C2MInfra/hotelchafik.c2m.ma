<?php
class utilisateur extends table{
protected $id;
protected $login;
protected $pwd;
protected $privilege;
protected $idu;

protected $achat;
protected $vente;
protected $client;
protected $fournisseur;
protected $produit;
protected $avoir;
protected $charge;

protected $nom;
protected $tele;
protected $email;

public function selectAllUtilisateur($moi){
$result=connexion::getConnexion()->query("select * from utilisateur where id <>'".$moi."' order by idu desc");
return $result->fetchAll(PDO::FETCH_OBJ);
}
}
?>