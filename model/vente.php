<?php

class vente extends table
{

   protected $id_vente;
   protected $id_client;
   protected $date_vente;
   protected $montantv;
   protected $remarque;
   protected $id_user;
   protected $numbon;
   protected $bon_commande;
   protected $datebon;
   protected $remarquebon;
   protected $num_devi;
   protected $validite;
   protected $client;
   protected $id_bon;
   protected $localisation;
   protected $created_at;

   public function selectVenteByClient($id_client, $date)
   {
      $result = connexion::getConnexion()->query("
      select v.id_vente,
      DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,
      concat_ws(' ',c.nom,c.prenom)  as client,c.id_client,v.remarque   ,sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) + dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)*tva)as
      montantv ,(select ifnull(sum(montant),0) from reg_vente where id_vente=v.id_vente) as avance
      from vente v left join  client c on  c.id_client=v.id_client left join detail_vente dv on dv.id_vente=v.id_vente
      inner join produit p on dv.id_produit=p.id_produit
      where  v.id_client=$id_client and DATE_FORMAT(v.date_vente,'%Y-%m')='" . $date . "'
      group by  v.id_vente  order by id_vente desc
      ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public static function getmax()
   {
      $result = connexion::getConnexion()->query("select max(numbon) as'max'  from vente ");
      return $result->fetch(PDO::FETCH_ASSOC);
   }

   public static function getmax1($date)
   {
      $result = connexion::getConnexion()->query("select ifnull(max(numbon),0) as'max'  from vente where DATE_FORMAT(datebon,'%Y')= " . $date);
      return $result->fetch(PDO::FETCH_ASSOC);
   }

   public static function isvalid($ven)
   {
      $result = connexion::getConnexion()->query("SELECT p.`designation` ,qte_actuel, qte_vendu from vente v inner join detail_vente d  on v.`id_vente`=d.`id_vente` 
	inner join produit p on p.`id_produit`=d.`id_produit` where (`qte_actuel`-qte_vendu)< 0 and v.id_vente=" . $ven);
      return $result->fetchAll(PDO::FETCH_ASSOC);
   }

   public static function getdevis($ven)
   {
      $result = connexion::getConnexion()->query("SELECT p.id_produit , qte_vendu, d.id_depot from vente v inner join detail_vente d  on v.`id_vente`=d.`id_vente` 
	inner join produit p on p.`id_produit`=d.`id_produit` where v.id_vente=" . $ven);
      return $result->fetchAll(PDO::FETCH_ASSOC);
   }

   public static function selectallbon()
   {
      $result = connexion::getConnexion()->query("select v.id_vente,
DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,v.numbon
concat_ws(' ',c.nom,c.prenom)  as client,c.id_client,v.remarque ,v.remarquebon  ,sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) + dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)*tva)as
 montantv ,(select ifnull(sum(montant),0) from reg_vente where id_vente=v.id_vente) as avance
from vente v left join  client c on  c.id_client=v.id_client left join detail_vente dv on dv.id_vente=v.id_vente
 inner join produit p on dv.id_produit=p.id_produit
where v.`numbon`>0 
group by  v.id_vente  order by id_vente desc");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public static function selectallbond($date, $search_type = 0)
   {

      if ($search_type == 0) {
         $condition_date = '';
      } elseif ($search_type == 1) {
         $date_parts = explode('-', $date);
         $year = $date_parts[0];
         $month = $date_parts[1];

         if ($month != 0) {
            $dateCondition = "and  DATE_FORMAT(v.date_vente,'%Y-%m') = '$year-$month'";
         } else {
            $dateCondition = "and  DATE_FORMAT(v.date_vente,'%Y') = '$year'";
         }
      }

      $result = connexion::getConnexion()->query("select v.id_vente,
DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,v.numbon,
concat_ws(' ',c.nom,c.prenom)  as client,c.id_client,v.remarque ,v.remarquebon  ,

sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) )as montantv,
sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100) )as motunitv,

 sum(dv.prix_produit*dv.qte_vendu) as montantv1 ,(select ifnull(sum(montant),0) from reg_vente where id_vente=v.id_vente) as avance
from vente v left join  client c on  c.id_client=v.id_client left join detail_vente dv on dv.id_vente=v.id_vente
 inner join produit p on dv.id_produit=p.id_produit
where v.`numbon`>0 " . $dateCondition . "
group by  v.id_vente  order by id_vente desc");

      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll2()
   {
      $result = connexion::getConnexion()->query("select v.id_vente,v.numbon,
DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,
concat_ws(' ',c.nom,c.prenom)  as client,c.id_client,v.remarque   ,sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) + dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)*tva)as
 montantv ,(select ifnull(sum(montant),0) from reg_vente where id_vente=v.id_vente) as avance
from vente v left join  client c on  c.id_client=v.id_client left join detail_vente dv on dv.id_vente=v.id_vente
 inner join produit p on dv.id_produit=p.id_produit
group by  v.id_vente  order by id_vente desc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll3($date)
   {
      $date_parts = explode('-', $date);
      $year = $date_parts[0];
      $month = $date_parts[1];

      if ($month != 0) {
         $dateCondition = "DATE_FORMAT(v.date_vente,'%Y-%m') = '$year-$month'";
      } else {
         $dateCondition = "DATE_FORMAT(v.date_vente,'%Y') = '$year'";
      }

      $result = connexion::getConnexion()->query("SELECT t1.*,t2.avance from 
(select v.id_vente,v.numbon,DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,concat_ws(' ',c.nom,c.prenom)  as client
	,c.id_client,c.nom_prenom_ar,v.remarque   ,sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)) as montantv ,
   sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100) )as motunitv from vente v 
left join client c on c.id_client=v.id_client 
inner join detail_vente dv on dv.id_vente=v.id_vente 
inner join produit p on dv.id_produit=p.id_produit
where $dateCondition 
AND v.bon_commande IS NOT NULL
group by  v.id_vente  order by id_vente desc  ) as t1 
left join (select id_vente,ifnull(sum(montant),0) as avance 
from reg_vente group by id_vente ) as t2 
on t2.id_vente=t1.id_vente ");



      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll3all()
   {
      //      $result = connexion::getConnexion()->query("select t1.*,t2.avance from 
      //(select v.id_vente,v.numbon,DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,concat_ws(' ',c.nom,c.prenom)  as client
      //	,c.id_client,c.nom_prenom_ar,v.remarque   ,sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) + dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)*tva) as montantv from vente v 
      //left join client c on c.id_client=v.id_client 
      //inner join detail_vente dv on dv.id_vente=v.id_vente 
      //inner join produit p on dv.id_produit=p.id_produit
      //group by  v.id_vente  order by id_vente desc  ) as t1 
      //left join (select id_vente,ifnull(sum(montant),0) as avance 
      //from reg_vente group by id_vente ) as t2 
      //on t2.id_vente=t1.id_vente ");

      $result = connexion::getConnexion()->query("SELECT t1.*,t2.avance from 
(select v.id_vente,v.numbon,DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,concat_ws(' ',c.nom,c.prenom)  as client
	,c.id_client,c.nom_prenom_ar,v.remarque   ,sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)) as montantv ,
   sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100) )as motunitv from vente v 
left join client c on c.id_client=v.id_client 
inner join detail_vente dv on dv.id_vente=v.id_vente 
inner join produit p on dv.id_produit=p.id_produit
WHERE v.bon_commande IS NOT NULL
group by  v.id_vente  order by id_vente desc  ) as t1 
left join (select id_vente,ifnull(sum(montant),0) as avance 
from reg_vente group by id_vente ) as t2 
on t2.id_vente=t1.id_vente");

      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAll2_ancieene()
   {
      $result = connexion::getConnexion()->query("select v.id_vente,
DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,
concat_ws(' ',c.nom,c.prenom)  as client,c.id_client,c.nom_prenom_ar,v.remarque   ,
sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) + dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)*tva) as montantv from vente v 
left join client c on c.id_client=v.id_client 
inner join detail_vente dv on dv.id_vente=v.id_vente 
inner join produit p on dv.id_produit=p.id_produit
group by  v.id_vente  order by id_vente desc");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllDate($client, $dd, $df)
   {
      $result = connexion::getConnexion()->query("select v.id_vente,
DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,
v.remarque , sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) + dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)*tva) as montantv from vente v  
 left join detail_vente dv on dv.id_vente=v.id_vente
 inner join produit p on dv.id_produit=p.id_produit
 where  (date_vente between '$dd' and '$df')  and  id_client=$client 
 group by  v.id_vente  
 order by id_vente asc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllDate2($client, $dd, $df)
   {
      $result = connexion::getConnexion()->query("SELECT v.id_vente, v.numbon,
      DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,
      v.remarque , 
      sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) ) as montantv ,
      sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100) ) as motunitv 
      from vente v  
      left join detail_vente dv on dv.id_vente=v.id_vente
      inner join produit p on dv.id_produit=p.id_produit
      where  (date_vente between '$dd' and '$df')  and  id_client=$client  and v.numbon<>0
      group by  v.id_vente  
      order by v.numbon Desc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllBL($dd, $df)
   {
      $result = connexion::getConnexion()->query("SELECT v.id_vente, v.numbon,
      DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,
      v.remarque , 
      sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) ) as montantv ,
      sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100) ) as motunitv 
      from vente v  
      left join detail_vente dv on dv.id_vente=v.id_vente
      inner join produit p on dv.id_produit=p.id_produit
      where  (date_vente between '$dd' and '$df')  and v.numbon<>0
      group by  v.id_vente  
      order by v.numbon Desc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
   public function selectAllDate_er($client)
   {
      #30-04-21
      //      $result = connexion::getConnexion()->query("select v.numbon,v.datebon,v.id_vente,
      //DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,
      //v.remarque , sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) + dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)*tva) as montantv from vente v  
      // left join detail_vente dv on dv.id_vente=v.id_vente
      // where id_client=$client and v.numbon>0 and datediff(SYSDATE(),v.datebon) > 30 and v.datebon!= '0000-00-00'
      // group by  v.id_vente  
      // order by id_vente asc ");
      //
      //      return $result->fetchAll(PDO::FETCH_OBJ);
      $result = connexion::getConnexion()->query("select v.numbon,v.datebon,v.id_vente,DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,v.remarque , 
   sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100)) as montantv from vente v  
   left join detail_vente dv on dv.id_vente=v.id_vente where id_client=$client
   and  v.numbon<>0
   group by  v.id_vente order by id_vente asc ");

      return $result->fetchAll(PDO::FETCH_OBJ);
   }

   public function selectAllDate1($client, $dd, $df)
   {
      $result = connexion::getConnexion()->query("select v.id_vente,
DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,
v.remarque ,
sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) + dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)*tva) as montantv 

 from vente v  
 left join detail_vente dv on dv.id_vente=v.id_vente
 where  (date_vente between '$dd' and '$df')  and  id_client=$client  and v.numbon<>0
 group by  v.id_vente  
 order by date_vente asc ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }
}
