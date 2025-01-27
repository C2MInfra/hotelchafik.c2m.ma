<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 27/02/2018
 * Time: 17:07
 */

class compte extends table {
   protected $id;
   protected $num_compte;
   protected $nom_compte;
   protected $nom_banque;
   protected $responsable;
   protected $telephone;
   protected $fax;
   protected $email;
   protected $remarque;

   public function selectAllCompte() {
      $result = connexion::getConnexion()->query("select * from compte order by id desc");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectCompteRgAchat($id, $dd, $df) {
      $result = connexion::getConnexion()->query("select ra.remarque,ra.montant,ra.id_reg,ra.mode_reg,ra.date_validation,ra.date_reg,ra.etat,'debiteur' as type
from reg_achat ra , achat a , fournisseur f  
where ra.id_compte='$id' and a.id_fournisseur = f.id_fournisseur and ra.id_achat = a.id_achat 
and ra.date_reg BETWEEN '$dd' and '$df' ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectCompteRgVente($id, $dd, $df) {
      $result = connexion::getConnexion()->query("select rv.remarque,rv.montant,rv.id_reg,rv.mode_reg,rv.date_validation,rv.date_reg,rv.etat,'crediteut' as type
from reg_vente rv , vente v , client c  
where rv.id_compte = '$id' and v.id_client = c.id_client and rv.id_vente = v.id_vente and rv.date_reg 
between '$dd' and '$df' ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllRgCompte($id, $dd, $df) {
      $result = connexion::getConnexion()->query("select ra.remarque,ra.montant,ra.id_reg,ra.mode_reg,ra.date_validation,ra.date_reg,ra.etat,'debiteur' as type
from reg_achat ra , achat a , fournisseur f  
where ra.id_compte='$id' and a.id_fournisseur = f.id_fournisseur and ra.id_achat = a.id_achat 
and ra.date_reg BETWEEN '$dd' and '$df' UNION ALL select rv.remarque,rv.montant,rv.id_reg,rv.mode_reg,rv.date_validation,rv.date_reg,rv.etat,'crediteur' as type
from reg_vente rv , vente v , client c  
where rv.id_compte = '$id' and v.id_client = c.id_client and rv.id_vente = v.id_vente and rv.date_reg 
between '$dd' and '$df' order by date_reg");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
}