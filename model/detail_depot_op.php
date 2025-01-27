<?php

class detail_depot_op extends table{

	//attributes
    protected $id_detail;
	protected $id_depot_op;
	protected $id_depot_src;
	protected $id_depot_dest;
	protected $id_produit;
	protected $qte_op;
	protected $id_user;
	
	//functions
	public function selectAllNonValide()
	{
		$query = "SELECT ddo.*, d1.nom AS depot_src, d2.nom AS depot_dest, p.designation FROM detail_depot_op ddo LEFT JOIN depot d1 ON ddo.id_depot_src = d1.id LEFT JOIN depot d2 ON ddo.id_depot_dest = d2.id LEFT JOIN produit p ON ddo.id_produit = p.id_produit WHERE ddo.id_depot_op LIKE '-1%' and ddo.id_user=" . $_SESSION["gs_id"] . " order by ddo.id_detail desc";
			
		return connexion::getConnexion()->query($query)->fetchAll(PDO::FETCH_OBJ);
	}
	
}