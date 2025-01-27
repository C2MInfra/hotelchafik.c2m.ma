<?php
include('../../evr.php');


if($_GET['act']=='vente_client') {

 
	function random_color() {
	 $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    return $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    }
    $year=$_GET["year"];
    $datasource = array();
    $query=$result=connexion::getConnexion()->query("SELECT count(id)as nbr_user from utilisateur");
    $result=$query->fetch(PDO::FETCH_OBJ); 

    $query=$result=connexion::getConnexion()->query("SELECT id,login  from utilisateur");
    $result_user=$query->fetchAll(PDO::FETCH_OBJ); 
    foreach($result_user as $rep){
    $data_vante =  array();
    $borderColor = random_color();
    for($j=1;$j<=12;++$j){

    $result=connexion::getConnexion()->query("select count(id_vente) as nbr_vente 
    from vente where numbon >0 and month(date_vente)=$j and year(date_vente)=$year and id_user=".$rep->id);
    $data=$result->fetch(PDO::FETCH_OBJ);
     

      array_push($data_vante ,$data->nbr_vente) ;

     }
     array_push($datasource,array("label" => $rep->login,
                         "borderColor" => $borderColor,
                         "backgroundColor" => $borderColor,
                         "data" => $data_vante,
                         "borderWidth" => 2  ));
                        
    }


    $json = json_encode($datasource);

    die($json);

}
elseif ($_GET['act']=='vente_achat') {
	

    $year=$_GET['year'];
    $datasource=array();
    $data_vante =array() ;
    $data_achat =array() ;
    for($j=1;$j<=12;++$j){
     $result=connexion::getConnexion()->query("select 
    ifnull((select sum(dv.qte_vendu)  from vente v inner join detail_vente dv on dv.id_vente=v.id_vente where  v.numbon>0 and month(v.date_vente)=$j and year(v.date_vente)=$year),0)  as vente,
    ifnull((select sum(da.id_achat) from achat a inner join detail_achat da on da.id_achat=a.id_achat where month(a.date_achat)=$j and year(a.date_achat)=$year),0)  as achat"); 
     $data=$result->fetch(PDO::FETCH_OBJ);

     array_push($data_vante ,$data->vente) ;
     array_push($data_achat ,$data->achat) ;

     }


     array_push($datasource,array("label"=> "Vente",
                            "data"=> $data_vante,
                            "borderColor"=> "#008EE6",
                            "pointBackgroundColor"=> "#fff",
                            "pointBorderColor"=> "#008EE6",
                            "pointHoverBackgroundColor"=> "#008EE6",
                            "pointHoverBorderColor"=> "#fff",
                            "pointRadius"=> 4,
                            "pointBorderWidth"=> 2,
                            "pointHoverRadius"=> 5,
                            "fill"=> !0,
                            "borderWidth"=> 2,
                            "backgroundColor"=> "rgba(0, 142, 230,0.1)"));


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