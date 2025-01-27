<?php
class  detail_produit extends table{

	protected $id;
	protected $id_produit; 
	protected $id_ingredient;
	protected $qte;
	protected $id_user;

	public function selectAllNonValide() 
	{
		$result = connexion::getConnexion()->query("SELECT dp.id,i.designation ,dp.qte  FROM detail_produit dp left join produit i on (i.id_produit=dp.id_ingredient)  where dp.id_produit like '-1%" . $_SESSION["rand_v_er"] . "' and dp.id_user=" . $_SESSION["gs_id"] . " order by dp.id desc");

		return $result->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function selectAllValide($id)
	{
		$result = connexion::getConnexion()->query("SELECT dp.id,i.designation ,dp.qte  FROM detail_produit dp left join produit i on (i.id_produit=dp.id_ingredient)  where dp.id_produit = $id and dp.id_user=" . $_SESSION["gs_id"] . " order by dp.id desc");

		return $result->fetchAll(PDO::FETCH_OBJ);
	}
}
?>