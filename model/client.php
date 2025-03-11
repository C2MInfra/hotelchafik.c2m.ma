<?php

class client extends table {

   protected $id_client;
   protected $code;
   protected $nom;
   protected $prenom;
   protected $nom_prenom_ar;
   protected $cin;
   protected $telephone;
   protected $email;
   protected $adresse;
   protected $image;
   protected $date_creation;
   protected $remarque;
   protected $id_user;
   protected $archive;
   protected $date_archive;
   protected $id_archiveur;
   protected $ice;
   protected $type_compte;
   protected $plafond;
   protected $prix_vente;
   protected $remise;
   protected $pv_guid;
   protected $id_nationalite;
   protected $id_pays;
   protected $ville;
   protected $passport;
   protected $carte_sejour;

   public function selectOperationNonPaye($id_client) {
      $result = connexion::getConnexion()->query("
SELECT v.id_vente,sum(prix_produit*qte_vendu) as montant 
FROM `vente`v inner join detail_vente dv on dv.id_vente=v.id_vente
   WHERE v.id_vente >=(select ifnull(max(id_vente),0) from reg_vente 
        where id_vente in (select id_vente from vente 
             where id_client =$id_client)) 
group by v.id_vente asc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll_($dd, $df) {
      $result = connexion::getConnexion()->query("select distinct(c.id_client),c.nom,c.prenom from vente v inner join client  c  on c.id_client=v.id_client where v.date_vente between '$dd' and '$df' ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   } 
   public function selectAll2_($dd, $df) {
      $result = connexion::getConnexion()->query("select distinct(c.id_client),c.nom,c.prenom from vente v 
      inner join client  c  on c.id_client=v.id_client 
      inner join detail_vente dt on dt.id_vente=v.id_vente
       where v.date_vente between '$dd' and '$df' and v.numbon<>0");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
   public function selectAllImp() {
      $result = connexion::getConnexion()->query("select distinct(c.id_client),c.nom,c.prenom,c.nom_prenom_ar from vente v inner join client c on c.id_client=v.id_client ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function archiver($id, $val, $id_user) {
      $statut = connexion::getConnexion()->exec("UPDATE  client SET archive=$val ,date_archive= '" . date("Y-m-d") . "',id_archiveur=$id_user WHERE id_client=$id");
      return $statut;
   }

   public function selectAllNonArchive() {
      $result = connexion::getConnexion()->query("select * from " . $this->className . " where archive=0 order by " . $this->primary_key . " desc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectNomAr($id) {
      $result = connexion::getConnexion()->query("select nom_prenom_ar from " . $this->className . " where id_client=1");
      return $result->fetchAll(PDO::FETCH_OBJ);
      echo $result->nom_prenom_ar;
   }

   public function selectAllArchive() {
      $result = connexion::getConnexion()->query("select * from " . $this->className . " where archive=1 order by " . $this->primary_key . " desc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }


   public function selectById2($id) {
      $result = connexion::getConnexion()->query("select * from " . $this->className . " where " . $this->primary_key . "=$id");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectClientFidele() {
      $result = connexion::getConnexion()->query("select c.id_client,c.telephone,c.nom as nomclient,sum(dv.qte_vendu)as nbr_pcs_vendu
 from client c inner join vente v on v.id_client=c.id_client inner join detail_vente dv on v.id_vente=dv.id_vente group by c.id_client order by nbr_pcs_vendu desc limit 6");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   //function by er
   public function selectAllVente($id_client) {
      $result = connexion::getConnexion()->query("select * from vente where id_client = ".$id_client." ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
   public function selectAllDetailVente($id_vente) {
      $result = connexion::getConnexion()->query("select * from detail_vente where id_vente = ".$id_vente." ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
   public function selectAllVenteReg($id_vente) {
      $result = connexion::getConnexion()->query("select * from reg_vente where id_vente = ".$id_vente." ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }



   

}

?>
