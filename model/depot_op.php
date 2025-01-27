<?php

class depot_op extends table{

	//attributes
    protected $id_depot_op;
	protected $remarque;
	protected $date_op;
    protected $id_user;
	
	//functions
	
	public function getAllOp()
	{
	    $query = "SELECT op.*, u.nom AS user_name FROM depot_op op LEFT JOIN detail_depot_op ddo ON op.id_depot_op = ddo.id_depot_op LEFT JOIN utilisateur u ON u.id = op.id_user GROUP BY op.id_depot_op ORDER BY op.date_op DESC";	
		
		return connexion::getConnexion()->query($query)->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function get_operation($id)
	{
		$query = "SELECT * depot_op WHERE id_depot_op = " . $id;
		
		return connexion::getConnexion()->query($query)->fetch(PDO::FETCH_OBJ);
	}
	
	public function remove_operation($id)
	{
		$query = "DELETE FROM depot_op WHERE id_depot_op = " . $id;
		
		return connexion::getConnexion()->exec($query);
	}
	
	public function get_details($id)
	{
		$query = "SELECT ddo.*, d1.nom AS depot_src, d2.nom AS depot_dest, p.designation FROM detail_depot_op ddo LEFT JOIN depot d1 ON ddo.id_depot_src = d1.id LEFT JOIN depot d2 ON ddo.id_depot_dest = d2.id LEFT JOIN produit p ON ddo.id_produit = p.id_produit WHERE ddo.id_depot_op = " . $id . " order by ddo.id_detail desc";
		
		return connexion::getConnexion()->query($query)->fetchAll(PDO::FETCH_OBJ);
	}
	
}