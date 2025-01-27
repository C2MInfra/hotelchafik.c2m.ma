<?php
class produit extends table
{

	protected $id_produit, $code_bar, $tva;
	protected $designation;
	protected $designation_ar;
	protected $poid;
	protected $qte_actuel;
	protected $prix_achat;
	protected $prix_achat_i;
	protected $prix_vente;
	protected $prix_vente2;
	protected $prix_vente3;
	protected $remise_max;
	protected $image;
	protected $emplacement;
	protected $date_insertion;
	protected $id_categorie;
	protected $remarque;
	protected $id_user;
	protected $archive;
	protected $date_archive;
	protected $id_archiveur;
	protected $unite;
	protected $minqte;
	protected $unite2;
	protected $type_produit;
	protected $fournisseur;

	public function selectQteVenteParProduit($id_produit, $date)
	{
		$result = connexion::getConnexion()->query("select ifnull(sum(qte_vendu),0) as vente from vente v inner join detail_vente dv 
	on v.id_vente=dv.id_vente where v.numbon>0 and v.date_vente>'" . $date . "' and dv.id_produit=$id_produit");
		return $result->fetch(PDO::FETCH_OBJ);
	}

	public function selectQteAchatParProduit($id_produit, $date)
	{
		$result = connexion::getConnexion()->query("select ifnull(sum(qte_achete),0) as achat from achat a inner join detail_achat da 
	on a.id_achat=da.id_achat where a.date_achat >'" . $date . "' and da.id_produit=$id_produit");
		return $result->fetch(PDO::FETCH_OBJ);
	}


	public function getCmup($id_prod)
	{
		$result = connexion::getConnexion()->query("SELECT p.qte_actuel AS qte_stock , da.qte_achete AS qte_achat , da.prix_produit AS prix_achat 
      FROM detail_achat da JOIN produit p ON p.id_produit = da.id_produit 
      WHERE da.id_produit = $id_prod ORDER BY da.id_detail DESC");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}


	public function selectAlertQte()
	{
		$result = connexion::getConnexion()->query("select * from produit where qte_actuel <= minqte LIMIT 10 ");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}

	public function archiver($id, $val, $id_user)
	{

		die("UPDATE  produit SET archive=$val ,date_archive= '" . date("Y-m-d") . "',id_archiveur=$id_user WHERE id_produit=$id");
		$statut = connexion::getConnexion()->exec("UPDATE  produit SET archive=$val ,date_archive= '" . date("Y-m-d") . "',id_archiveur=$id_user WHERE id_produit=$id");
		return $statut;
	}


	public function selectAllByCat($id_cat)
	{
		$result = connexion::getConnexion()->query("select p.*,c.nom as categorie
	from produit p inner join categorie c on c.id_categorie=p.id_categorie 
	where archive=0 and p.id_categorie=$id_cat order by id_produit desc ");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}
	public function selectAllByEmp($emp)
	{
		$result = connexion::getConnexion()->query("select p.*,c.nom as categorie
	from produit p inner join categorie c on c.id_categorie=p.id_categorie 
	where archive=0 and p.emplacement like '" . $emp . "%' order by id_produit desc ");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}

	public function selectAllNonArchive()
	{


		$result = connexion::getConnexion()->query("select p.*,c.nom as categorie 
	from produit p inner join categorie c on c.id_categorie=p.id_categorie 
	where archive=0 order by id_produit desc  ");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}

	public function selectAllArchive()
	{
		$result = connexion::getConnexion()->query("select p.*,c.nom as categorie from produit p inner join categorie c on c.id_categorie=p.id_categorie where archive=1 order by id_produit desc");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}

	public function selectPlusVendu()
	{
		$result = connexion::getConnexion()->query("select p.id_produit,p.designation,p.poid,p.qte_actuel,sum(dv.qte_vendu)as qtevendu ,c.nom 
			from produit p inner join detail_vente dv  on p.id_produit=dv.id_produit
			inner join categorie c on c.id_categorie=p.id_categorie 
			group by p.id_produit ORDER BY qtevendu desc limit 6");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}

	public function selectById2($id)
	{
		$result = connexion::getConnexion()->query("select * from " . $this->className . " where " . $this->primary_key . "=$id");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}
	public function selectAllCat()
	{
		$result = connexion::getConnexion()->query("select c.* from  categorie c  order by c.nom asc");
		return $result->fetchAll(PDO::FETCH_OBJ);
	}
}
