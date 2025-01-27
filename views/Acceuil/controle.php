<?php
include('../../evr.php');

if($_POST['act'] == 'filter_ca')
{
    $month = $_POST['month'];

    if($month != 0){

    $ca = connexion::getConnexion()->query("SELECT t1.montantv from 
    (select v.id_vente,v.numbon,DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,concat_ws(' ',c.nom,c.prenom)  as client
        ,c.id_client,c.nom_prenom_ar,v.remarque   ,sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)) as montantv ,
    sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100) )as motunitv from vente v 
    left join client c on c.id_client=v.id_client 
    inner join detail_vente dv on dv.id_vente=v.id_vente 
    inner join produit p on dv.id_produit=p.id_produit
    where v.numbon <> 0 AND MONTH(v.date_vente) = $month AND YEAR(v.date_vente) = Year(now())
    order by id_vente desc  ) as t1 
    left join (select id_vente,ifnull(sum(montant),0) as avance 
    from reg_vente group by id_vente ) as t2 on t2.id_vente=t1.id_vente")->fetch(PDO::FETCH_COLUMN);

    //recette
    $recette = connexion::getConnexion()->query("select IF(sum(reg_vente.montant) IS NULL, 0, sum(reg_vente.montant)) as total from reg_vente,vente where reg_vente.id_vente = vente.id_vente
    AND MONTH(date_reg) = $month AND YEAR(date_reg) = YEAR(now())")->fetch(PDO::FETCH_COLUMN);

    //reg_f
    $reg_f = connexion::getConnexion()->query("select IF(sum(reg_achat.montant) IS NULL, 0, sum(reg_achat.montant)) as total from reg_achat,achat where reg_achat.id_achat = achat.id_achat
    AND MONTH(date_reg) = $month AND YEAR(date_reg) = YEAR(now())")->fetch(PDO::FETCH_COLUMN);

    //charges
    $charges = connexion::getConnexion()->query("select IF(sum(montant) IS NULL, 0, sum(montant)) as total from charge where 
    MONTH(date_charge) = $month AND YEAR(date_charge) = YEAR(now())")->fetch(PDO::FETCH_COLUMN);

    //depense
    $depense = $reg_f + $charges;

    //emoji
    if($recette > $depense)
    {
        $emoji = BASE_URL . '/asset/img/emoji-happy.png';
    }
    else
    {
        $emoji = BASE_URL . '/asset/img/emoji-unhappy.png';
    }

    $arr = [
        'ca' => number_format($ca, 2) . ' DH' ,
        'recette' => number_format($recette, 2) . ' DH',
        'reg_f' => number_format($reg_f, 2) . ' DH',
        'charges' => number_format($charges, 2) . ' DH',
        'depense' => number_format($depense, 2) . ' DH',
        'emoji' => $emoji,
        'result' => number_format(($recette - $depense), 2) . ' DH'
    ];

    echo json_encode($arr);
    }
    else{
 $ca = connexion::getConnexion()->query("SELECT t1.montantv from 
    (select v.id_vente,v.numbon,DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,concat_ws(' ',c.nom,c.prenom)  as client
        ,c.id_client,c.nom_prenom_ar,v.remarque   ,sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)) as montantv ,
    sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100) )as motunitv from vente v 
    left join client c on c.id_client=v.id_client 
    inner join detail_vente dv on dv.id_vente=v.id_vente 
    inner join produit p on dv.id_produit=p.id_produit
    where v.numbon <> 0 AND YEAR(v.date_vente) = Year(now())
    order by id_vente desc  ) as t1 
    left join (select id_vente,ifnull(sum(montant),0) as avance 
    from reg_vente group by id_vente ) as t2 on t2.id_vente=t1.id_vente")->fetch(PDO::FETCH_COLUMN);

    //recette
    $recette = connexion::getConnexion()->query("select IF(sum(reg_vente.montant) IS NULL, 0, sum(reg_vente.montant)) as total from reg_vente,vente where reg_vente.id_vente = vente.id_vente
    AND YEAR(date_reg) = YEAR(now())")->fetch(PDO::FETCH_COLUMN);

    //reg_f
    $reg_f = connexion::getConnexion()->query("select IF(sum(reg_achat.montant) IS NULL, 0, sum(reg_achat.montant)) as total from reg_achat,achat where reg_achat.id_achat = achat.id_achat
    AND YEAR(date_reg) = YEAR(now())")->fetch(PDO::FETCH_COLUMN);

    //charges
    $charges = connexion::getConnexion()->query("select IF(sum(montant) IS NULL, 0, sum(montant)) as total from charge where 
     YEAR(date_charge) = YEAR(now())")->fetch(PDO::FETCH_COLUMN);

    //depense
    $depense = $reg_f + $charges;

    //emoji
    if($recette > $depense)
    {
        $emoji = BASE_URL . '/asset/img/emoji-happy.png';
    }
    else
    {
        $emoji = BASE_URL . '/asset/img/emoji-unhappy.png';
    }

    $arr = [
        'ca' => number_format($ca, 2) . ' DH' ,
        'recette' => number_format($recette, 2) . ' DH',
        'reg_f' => number_format($reg_f, 2) . ' DH',
        'charges' => number_format($charges, 2) . ' DH',
        'depense' => number_format($depense, 2) . ' DH',
        'emoji' => $emoji,
        'result' => number_format(($recette - $depense), 2) . ' DH'
    ];


    echo json_encode($arr);
    }
}
if($_GET['act']=='vente') {

 
	
    $year=$_GET['year'];
    $datasource=array();
    $data_vente =array() ;
    for($j=1;$j<=12;++$j){
     $result=connexion::getConnexion()->query("select 
    ifnull((select sum(dv.qte_vendu)  from vente v inner join detail_vente dv on dv.id_vente=v.id_vente where  v.numbon>0 and month(v.date_vente)=$j and year(v.date_vente)=$year),0)  as vente,
    ifnull((select sum(da.id_achat) from achat a inner join detail_achat da on da.id_achat=a.id_achat where month(a.date_achat)=$j and year(a.date_achat)=$year),0)  as achat"); 
     $data=$result->fetch(PDO::FETCH_OBJ);

     array_push($data_vente ,$data->vente) ;

     }


        array_push($datasource,array("label"=> "Achat",
                            "data"=> $data_vente,
                            "borderColor"=> "#004D8C",
                            "pointBackgroundColor"=> "#fff",
                            "pointBorderColor"=> "#004D8C",
                            "pointHoverBackgroundColor"=> "#004D8C",
                            "pointHoverBorderColor"=> "#fff",
                            "pointRadius"=> 4,
                            "pointBorderWidth"=> 2,
                            "pointHoverRadius"=> 5,
                            "fill"=> !0,
                            "borderWidth"=> 2,
                            "backgroundColor"=> "rgba(0, 77, 140,0.1)"));
 $json = json_encode($datasource);

    die($json);

}

elseif ($_GET['act']=='achat') {
	

    $year=$_GET['year'];
    $datasource=array();
    $data_achat =array() ;
    for($j=1;$j<=12;++$j){
     $result=connexion::getConnexion()->query("select 
    ifnull((select sum(dv.qte_vendu)  from vente v inner join detail_vente dv on dv.id_vente=v.id_vente where  v.numbon>0 and month(v.date_vente)=$j and year(v.date_vente)=$year),0)  as vente,
    ifnull((select sum(da.id_achat) from achat a inner join detail_achat da on da.id_achat=a.id_achat where month(a.date_achat)=$j and year(a.date_achat)=$year),0)  as achat"); 
     $data=$result->fetch(PDO::FETCH_OBJ);

     array_push($data_achat ,$data->achat) ;

     }


        array_push($datasource,array("label"=> "Achat",
                            "data"=> $data_achat,
                            "borderColor"=> "#004D8C",
                            "pointBackgroundColor"=> "#fff",
                            "pointBorderColor"=> "#004D8C",
                            "pointHoverBackgroundColor"=> "#004D8C",
                            "pointHoverBorderColor"=> "#fff",
                            "pointRadius"=> 4,
                            "pointBorderWidth"=> 2,
                            "pointHoverRadius"=> 5,
                            "fill"=> !0,
                            "borderWidth"=> 2,
                            "backgroundColor"=> "rgba(0, 77, 140,0.1)"));
 $json = json_encode($datasource);

    die($json);

                            
}
?>
