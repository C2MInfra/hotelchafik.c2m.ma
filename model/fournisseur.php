<?php
class fournisseur extends table{
    
 
  
protected $id_fournisseur; 
protected $raison_sociale;
protected $telephone;
protected $email;
protected $adresse;
protected $image;
protected $date_creation;
protected $remarque;
protected $responsable;
protected $tph_respo;
protected $id_user;
protected $archive;
protected $date_archive;
protected $id_archiveur;
protected $iff;
protected $rc;
protected $ice;
protected $patente;

 public function selectOperationNonPaye($id_fournisseur){
	$result=connexion::getConnexion()->query("
SELECT a.id_achat,sum(prix_produit*qte_achete) as montant 
FROM `achat` a inner join detail_achat da on da.id_achat=a.id_achat
   WHERE a.id_achat >=(select ifnull(max(id_achat),0) from reg_achat
        where id_achat in (select id_achat from achat
             where id_fournisseur =$id_fournisseur )) 
group by a.id_achat asc ");
	return $result->fetchAll(PDO::FETCH_OBJ);
	}


  public function selectAll_($dd,$df){
	$result=connexion::getConnexion()->query("select distinct(f.id_fournisseur),f.raison_sociale from achat a inner join fournisseur  f  on f.id_fournisseur=a.id_fournisseur where a.date_achat between '$dd' and '$df' ");
	return $result->fetchAll(PDO::FETCH_OBJ);
	}
  public function selectAll_1(){
	$result=connexion::getConnexion()->query("select distinct(f.id_fournisseur),f.raison_sociale from achat a inner join fournisseur  f ");
	return $result->fetchAll(PDO::FETCH_OBJ);
	}

public function archiver($id,$val,$id_user){
	   	$statut=connexion::getConnexion()->exec("UPDATE  fournisseur SET archive=$val ,date_archive= '".date("Y-m-d")."',id_archiveur=$id_user WHERE id_fournisseur=$id");
 	return $statut;
	}


         public function selectAllNonArchive(){
    	$result=connexion::getConnexion()->query("select * from ".$this->className." where archive=0 order by ".$this->primary_key." desc ");
    	return $result->fetchAll(PDO::FETCH_OBJ);
    	}
        
        public function selectAllArchive(){
    	$result=connexion::getConnexion()->query("select * from ".$this->className." where archive=1 order by ".$this->primary_key." desc ");
    	return $result->fetchAll(PDO::FETCH_OBJ);
    	}
 
	  public function selectById2($id){
	$result=connexion::getConnexion()->query("select * from ".$this->className." where ".$this->primary_key."=$id");
	return $result->fetchAll(PDO::FETCH_OBJ);
	}

	   public function selectAllAchat($id_client) {
      $result = connexion::getConnexion()->query("select * from achat where id_fournisseur = ".$id_client." ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
   public function selectAllDetailAchat($id_achat) {
      $result = connexion::getConnexion()->query("select * from detail_achat where id_achat = ".$id_achat." ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
   public function selectAllAchatReg($id_achat) {
      $result = connexion::getConnexion()->query("select * from reg_achat where id_achat = ".$id_achat." ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
}
?>