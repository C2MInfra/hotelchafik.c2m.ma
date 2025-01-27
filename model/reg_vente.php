<?php

class reg_vente extends table {


   protected $id_reg;
   protected $id_vente;
   protected $mode_reg;
   protected $num_cheque;
   protected $date_reg;
   protected $montant;
   protected $remarque;
   protected $etat;
   protected $id_user;
   protected $id_compte;
   protected $date_validation;
   protected $id_reg_client;
	
   public function selectAll2($id) {
      $result = connexion::getConnexion()->query("select * from reg_vente where id_vente=" . trim($id, ',') . " order by id_reg  desc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectByIdVente($id) {
      $result = connexion::getConnexion()->query("select * from reg_vente where id_vente='" . trim($id, ',') . "'  order by id_reg  desc limit 0,1");
      return $result->fetch(PDO::FETCH_OBJ);
   }

   public function selectAll3($date) {
      $result = connexion::getConnexion()->query("select rgv.* from reg_vente rgv
where  DATE_FORMAT(rgv.date_reg,'%Y-%m')='" . $date . "'  order by rgv.date_reg desc  ");

      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll3all() {
      $result = connexion::getConnexion()->query("select t1.*,t2.avance from 
(select v.id_vente,v.numbon,DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,concat_ws(' ',c.nom,c.prenom)  as client
	,c.id_client,c.nom_prenom_ar,v.remarque   ,sum(dv.prix_produit*(1-dv.remise)*dv.qte_vendu) as montantv 
from vente v  left join  client c on  c.id_client=v.id_client 
inner join detail_vente dv on dv.id_vente=v.id_vente
group by  v.id_vente  order by id_vente desc  ) as t1 
left join (select id_vente,ifnull(sum(montant),0) as avance 
from reg_vente group by id_vente ) as t2 
on t2.id_vente=t1.id_vente ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllYear($date) {
      $result = connexion::getConnexion()->query("select rgv.* from reg_vente rgv
where  DATE_FORMAT(rgv.date_reg,'%Y')='" . $date . "'  order by rgv.date_reg desc  ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }


   public function selectAll3_er($date) {
$result = connexion::getConnexion()->query("select rgv.*,c.*,v.numbon from reg_vente rgv,client c,vente v
where DATE_FORMAT(rgv.date_reg,'%Y-%m')='" . $date . "' and c.id_client=v.id_client and v.id_vente=rgv.id_vente group by rgv.id_reg order by rgv.date_reg desc  ");

      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll3all_er() {
      $result = connexion::getConnexion()->query("select rgv.* from reg_vente rgv
where mode_reg = 'Cheque' or mode_reg = 'Effet' order by rgv.date_reg desc  ");

      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllYear_er($date) {
      $result = connexion::getConnexion()->query("select rgv.* from reg_vente rgv
where (mode_reg = 'Cheque' or mode_reg = 'Effet') and DATE_FORMAT(rgv.date_reg,'%Y')='" . $date . "'  order by rgv.date_reg desc  ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function alertReg() {
      $result = connexion::getConnexion()->query("select rv.*,c.nom,c.prenom,DATEDIFF(CURDATE(), date_validation) as dif from reg_vente rv,vente v,client c where rv.id_vente=v.id_vente and v.id_client=c.id_client and (rv.mode_reg = 'Cheque' or rv.mode_reg = 'Effet') and UNIX_TIMESTAMP(rv.date_validation) != 0 order by rv.date_reg desc");
      //$result = connexion::getConnexion()->query("SELECT DATEDIFF(\"2017-06-25\", \"2017-06-15\");");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
}

?>