<?php
class cloturages extends table
{

	protected $id;
	protected $id_clotur_pv;
	protected $id_trav;
	protected $montant;
	protected $nombreOperation;
	protected $created_at;
	protected $updated_at;
	protected $montant_espece;
	protected $montant_carte;
	protected $montant_compte;
	protected $montant_offert;
	protected $pv_guid;

	public function All()
	{
		$result = connexion::getConnexion()->query("select * from cloturages");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}


	public function selectbyId($id)
	{
		$result = connexion::getConnexion()->query("select * from cloturages WHERE id = $id order by id desc");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}

	public function selectAll3($date)
	{
		$date_parts = explode('-', $date);
		$year = $date_parts[0];
		$month = $date_parts[1];

		if ($month != 0) {
			$dateCondition = "DATE_FORMAT(c.created_at,'%Y-%m') = '$year-$month'";
		} else {
			$dateCondition = "DATE_FORMAT(c.created_at,'%Y') = '$year'";
		}



		$result = connexion::getConnexion()->query("SELECT * FROM `cloturages` c JOIN client cl ON c.pv_guid = cl.pv_guid WHERE $dateCondition");


		return $result->fetchAll(PDO::FETCH_OBJ);
	}


	public function selectAll3all()
	{

		$result = connexion::getConnexion()->query("SELECT * FROM `cloturages` c JOIN client cl ON c.pv_guid = cl.pv_guid ");


		return $result->fetchAll(PDO::FETCH_OBJ);
	}
}
