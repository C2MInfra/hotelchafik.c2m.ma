<?php
class produit_depot extends table
{
   	protected $id;
	protected $id_produit;
	protected $id_depot;
	protected $qte;
	
	public function getById($id)
	{
		return connexion::getConnexion()->query("SELECT * FROM produit_depot WHERE id = " . $id)->fetch(PDO::FETCH_OBJ);
	}
	
	public function get_produit_depot($produit, $depot)
	{
		$result = connexion::getConnexion()->query("SELECT * FROM produit_depot WHERE id_produit = $produit AND id_depot = $depot")->fetch(PDO::FETCH_OBJ);
		
		return $result;
	}
	
	public function depots($produit)
	{
		
		$query = "SELECT d.* FROM produit_depot pd LEFT JOIN depot d ON pd.id_depot = d.id WHERE pd.id_produit = $produit GROUP BY d.id";
		
		return connexion::getConnexion()->query($query)->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function add_qte($produit, $depot, $qte)
	{
		$query = "UPDATE produit_depot SET qte = qte + $qte WHERE id_produit = $produit AND id_depot = $depot";
		
		connexion::getConnexion()->exec($query);
	}
	
	public function minus_qte($produit, $depot, $qte)
	{
		$query = "UPDATE produit_depot SET qte = qte - $qte WHERE id_produit = $produit AND id_depot = $depot";
		
		connexion::getConnexion()->exec($query);
	}
	
	public function minus_last($produit, $qte)
	{
		$last = connexion::getConnexion()->query("SELECT * FROM produit_depot WHERE id_produit = $produit ORDER BY id DESC LIMIT 1" )->fetch(PDO::FETCH_OBJ);
		
		$query = "UPDATE produit_depot SET qte = qte - $qte WHERE id_produit = $produit AND id_depot = " . $last->id_depot;
		
		connexion::getConnexion()->exec($query);
	}
	
	public function new_produit_depot($produit, $depot, $qte)
	{
		$query = "INSERT INTO produit_depot(id_produit, id_depot, qte) VALUES($produit, $depot, $qte)";
		
		connexion::getConnexion()->exec($query);
	}
}
?>