<?php

include('../../evr.php');

if ($_POST['act']=='filter') {

   $boncommande = new boncommande();

if($_POST['anne'] != 0)
{

$data = $boncommande->selectAll3($_POST['anne'] . "-" . $_POST['mois']);

}
if($_POST['anne']==0)
$data = $boncommande->selectAll3all();

?>
<table class="table  responsive table-striped table-bordered table-hover" id="datatables" >

              <thead>

                <tr>
                  <th scope="col">Id</th>

                  <th scope="col">Client</th>

                  <th class="nowrap"> Date</th>

                  <th scope="col"> Montant</th>

                  <th scope="col"> Reste</th>

                  <th scope="col"> Remarque</th>
                  
                  <th scope="col">Actions</th>

                </tr>

              </thead>

              <tbody>

                <?php

                foreach($data as $ligne) 
				{
                ?>

                <tr>

                  <td> <?php echo $ligne->id_bon; ?></td>

                  <td class="nowrap">

                    <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->client;

                      if($ligne->nom_prenom_ar != "" && $ligne->client == " ") {

                      echo $ligne->nom_prenom_ar;

                      }

                      if($ligne->nom_prenom_ar != "" && $ligne->client != " ") {

                      echo "/" . $ligne->nom_prenom_ar;

                      }

                    ?> </a> </td>

                    <td>
                    	<?php echo $ligne->date_bon ; ?>
                    </td>
                    <td style="text-align: right;" class="nowrap" data-href="#"> 

                       <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>">
                      <?php 
                      if($ligne->motunitv!=0 || !empty($ligne->motunitv)){
                        echo number_format($ligne->motunitv, 2, '.', ' ');
                      }else{
                        echo number_format($ligne->montantv, 2, '.', ' ');
                      }
                       ?>
                        </a>
                      &nbsp;&nbsp;

                    </td>

                    <td style="text-align: right;"> <?php

                      $query = $result = connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_commande where id_bon=" . $ligne->id_bon);

                      $result = $query->fetch(PDO::FETCH_OBJ);

                      $paye = $result->paye!=null  ?  $result->paye : 0;
                      if($ligne->motunitv!=0 || !empty($ligne->motunitv)){
                        $tr = $ligne->motunitv - $paye;
                      }else{
                        $tr = $ligne->montantv - $paye;
                      }

					    $tr = ($tr < 0 && $tr >= -250)?0:$tr;
					     echo number_format($tr, 2, '.', ' ');
                      ?> &nbsp;&nbsp;

                    </td>
                    <td> <?php echo strlen($ligne->remarque) >50 ?substr($ligne->remarque, 0,50)."..." : $ligne->remarque; ?> </td>
                    
                    
                    <td class="nowrap">

                      <?php if(auth::user()['privilege'] == 'Admin') { ?>

                      <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >

                        <i class="simple-icon-trash" style="font-size: 15px;"></i>

                      </a>

                      <a class="badge badge-success mb-2  url notlink" data-url="reg_commande/index.php?id=<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)' >

                        <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>

                      </a>

                        <a class="badge badge-warning mb-2  url notlink" data-url="bon-commande/update.php?id=<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="Modifier"

                    href="javascript:void(0)">

                    <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>

                  </a>

                     

                      <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL."views/bon-commande/facture.php?id=".$ligne->id_bon; ?>&h=15"  target="_black" >

                        <i class=" simple-icon-printer" style="font-size: 15px;"></i>

                      </a>

                      <a  class="badge badge-secondary mb-2 url notlink" data-url="detail_commande/index.php?id=<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">

                        

                        <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>

                      </a>
                      
                      <a class="badge badge-warning mb-2" style="color: white;cursor: pointer;" title="Vente" href='<?php echo BASE_URL . '/'?>vente/add.php?bon=<?php echo $ligne->id_bon . '&client=' . $ligne->id_client; ?>' >

                        <i class="iconsmind-Add-Cart" style="font-size: 15px;"></i>

                      </a>
                      <?php } ?>

                      

                      

                      

                    </td>

                  </tr>

                  <?php } ?>

                </tbody>

              </table>

<?php

  

}

if ($_POST['act']=='filterbon') {

	$vente=new vente();

	if ($_POST['anne']!=0 )
	{
		$date_format = $_POST['anne'] . "-" . (($_POST['mois'] < 10)?'0'.$_POST['mois']:$_POST['mois']);
		$data = $vente->selectallbond($date_format);
	}
	else
	{
		$date_format = (($_POST['mois'] < 10)?'0'.$_POST['mois']:$_POST['mois']);
		$data = $vente->selectallbond($date_format); 
	}
	
	

?>
<?php
#custom

#endcustom
	
?>
<table class="table  responsive table-striped table-bordered table-hover" id="datatables" >

              <thead>

                <tr>

                  <th scope="col">Id</th>

                  <th scope="col">Cliet</th>

                  <th> Date</th>

                  <th scope="col"> Montant</th>

                  <th scope="col"> Reste</th>

                  <th scope="col"> remarque</th>

                  <th scope="col">Actions</th>

                </tr>

              </thead>

              <tbody>

                <?php

                foreach($data as $ligne) {

                $query = $result = connexion::getConnexion()->query("SELECT count(*) as nbr,id_facture FROM facture where id_vente like '%" . $ligne->id_vente . "%'");

                $result = $query->fetch(PDO::FETCH_OBJ);

                $id_facture = $result->id_facture;

                $nbr = $result->nbr;

                ?>

                <tr>

                  <td> <?php echo $ligne->numbon; ?></td>

                  <td class="nowrap">

                    <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->client;

                      if($ligne->nom_prenom_ar != "" && $ligne->client == " ") {

                      echo $ligne->nom_prenom_ar;

                      }

                      if($ligne->nom_prenom_ar != "" && $ligne->client != " ") {

                      echo "/" . $ligne->nom_prenom_ar;

                      }

                    ?> </a> </td>

                    <td class="nowrap"><?php   echo $ligne->date_vente; ?> </td>

                    <td class="nowrap" style="text-align: right;"> <?php echo number_format($ligne->montantv, 2, '.', ' '); ?>

                      &nbsp;&nbsp;

                    </td>

                    <td class="nowrap" style="text-align: right;"> <?php

                      $query = $result = connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_vente where id_vente=" . $ligne->id_vente);

                      $result = $query->fetch(PDO::FETCH_OBJ);

                      $paye = $result->paye!=null  ?  $result->paye : 0;

                      echo number_format($ligne->montantv - $paye, 2, '.', ' ');

                      ?> &nbsp;&nbsp;

                    </td>

                    <td> <?php  echo strlen($ligne->remarquebon) >50 ? substr($ligne->remarquebon, 0,50)."..." : $ligne->remarquebon;?> </td>

                    <td class="nowrap">

                      <?php if(auth::user()['privilege'] == 'Admin') { ?>

                      <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >

                        <i class="simple-icon-trash" style="font-size: 15px;"></i>

                      </a>

                      <a class="badge badge-warning mb-2  url notlink" data-url="vente/updatebon.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Modifier" href='javascript:void(0)' >

                        <i class="iconsmind-Pen-5" style="font-size: 15px;"></i>

                      </a>

                      <a class="badge badge-success mb-2  url notlink" data-url="reg_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)' >

                        <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>

                      </a>

                      <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL."views/vente/facturebon.php?id=".$ligne->id_vente; ?>&h=15"  target="_black" >

                        <i class=" simple-icon-printer" style="font-size: 15px;"></i>

                      </a>

                      <a  class="badge badge-secondary mb-2 url notlink" data-url="detail_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">

                        

                        <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>

                      </a>

                      <?php if($nbr==0 && $ligne->numbon!=0 ){ ?>

                      <a class="badge badge-primary mb-2 url notlink" style="color: white;cursor: pointer;" title="Facture" data-url="<?php echo "facture/add.php?idv[]=".$ligne->id_vente; ?>" href='javascript:void(0)' >

                        <i class=" iconsmind-Billing" style="font-size: 15px;"></i>

                      </a>

                      <?php } ?>

                      <a class="badge badge-primary mb-2 notlink" style="background-color: #d322e8!important;color: white;cursor: pointer;" title="Ticket" href='<?php echo BASE_URL.'/views/vente/ticketbon.php?id='.$ligne->id_vente; ?>'  target="_black" >

                        <i class=" iconsmind-Billing" style="font-size: 15px;"></i>

                      </a>

                      <?php } ?>
                    </td>

                  </tr>

                  <?php } ?>

                </tbody>

              </table>
<?php

  

}

if ($_POST['act']=='getproduit') {

  $depot=new depot();

  $res_depot=$depot->selectAll();

  foreach($res_depot as $rep_depot){

?>

<optgroup label="<?php echo $rep_depot->nom; ?> ">

  <?php

  $produits=$depot->selectQuery("SELECT  id_produit,designation  FROM produit where   id_categorie=".$_POST['id_categorie']." and   emplacement='".$rep_depot->id."' order by designation asc");



  foreach ($produits as $row) {

  echo '<option value="'.$row->id_produit.'">'.$row->designation.'</option>';

  }?>

</optgroup>

<?php }

}

if ($_POST['act']=='rech') {

$depot=new depot();

$res_depot=$depot->selectAll();

foreach($res_depot as $rep_depot){

?>

<optgroup label="<?php echo $rep_depot->nom; ?> ">

  <?php

  $produits=$depot->selectQuery("SELECT  id_produit,designation as designation   FROM produit where  code_bar like '".$_POST['id']."%' and   emplacement='".$rep_depot->id."' order by designation asc");

  foreach ($produits as $row) {

  echo '<option value="'.$row->id_produit.'">'.$row->designation.'</option>';

  }?>

</optgroup>

<?php }

}

elseif ($_POST['act']=='deleterow') {

  $detail_vente=new detail_vente();








    if(isset($_POST['id_detail'])){

	
		
    $detail_vente->delete($_POST['id_detail']);

    }

    

    

  $data=$detail_vente->selectAllNonValide();

  $total=0;

  foreach($data as $ligne){

?>

<tr>

  

  <td><?php echo $ligne->designation ; ?></td>

  <td><?php echo $ligne->prix_produit ; ?></td>

  <td><?php echo $ligne->qte_vendu ; ?></td>

  

  <td><?php echo $ligne->poid*$ligne->qte_vendu ; ?> g </td>

  <td width="90" style="text-align: right;" >

    <?php  echo number_format($ligne->qte_vendu * $ligne->prix_produit ,2,'.',' ');

      if($ligne->valunit!=0 || !empty($ligne->valunit)){
            
        
                
        $total+=($ligne->valunit)* $ligne->prix_produit* (1 - $ligne->remise/100);
      }else{
       

        $total+=$ligne->qte_vendu * $ligne->prix_produit* (1 - $ligne->remise/100);
  }

    ?>

    

  </td>

  <td><a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >

  <i class="simple-icon-trash" style="font-size: 15px;"></i></a> </td>

</tr>

<?php

}

?>

<tr>

  <td colspan="4" style="text-align: center;font-size: 15px;" > <b>Total</b>   </td>

  <td style="text-align: right;" colspan="3">  <b style="font-size: 15px;color: green;text-align: right;" ><?php echo number_format($total,2,'.',' '); ?></b></td>

  

</tr>

<?php

  }



elseif ($_POST['act']=='addProduct') {
	

if (!isset($_SESSION['rand_v_er']) || $_SESSION['rand_v_er']==="" ){

  $_SESSION['rand_v_er']=rand(10,1000);

  }

  $_POST["id_user"] = auth::user()["id"] ;

  $somme_poid=0;



  $_POST["id_bon"]= "-1".$_SESSION['rand_v_er'];

	
  $detail_commande = new detail_commande();

  $detail_commande->insert();


  $data=$detail_commande->selectAllNonValide();


  $total=0;

  foreach($data as $ligne){

?>

<tr>

  <td><?php echo $ligne->designation ; ?></td>

  <td><?php echo $ligne->prix_produit ; ?></td>

  <td><?php 
            if($ligne->qte_vendu!=null){
                echo $ligne->qte_vendu ; 
            }
           
            
            ?></td>

            <td><?php 
            if($ligne->unit!=null){
            echo $ligne->valunit . ' ' .  $ligne->unit;
            }
            // $ligne->poid*$ligne->qte_vendu ;

            //                         $somme_poid+=$ligne->poid*$ligne->qte_vendu;

            ?>  </td>

  <td width="90" style="text-align: right;" >

    <?php  
    if($ligne->valunit!=0 || !empty($ligne->valunit)){
      
      echo number_format(($ligne->valunit )* $ligne->prix_produit,2,'.',' ');
              
      $total+=$ligne->valunit * $ligne->prix_produit* (1 - $ligne->remise/100);
    }else{
      echo number_format($ligne->qte_vendu * $ligne->prix_produit,2,'.',' ');

      $total+=$ligne->qte_vendu * $ligne->prix_produit* (1 - $ligne->remise/100);
    }
    
    

    ?>

  </td>

  <td>    <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >

    <i class="simple-icon-trash" style="font-size: 15px;"></i>

  </a>

</td>

</tr>

<?php

}

?>

<tr>

<td colspan="4" style="text-align: center;font-size: 15px;" > <b>Total</b>   </td>

<td style="text-align: right;" colspan="3">  <b style="font-size: 15px;color: green;text-align: right; metotal" ><?php echo number_format($total,2,'.',' '); ?></b></td>



</tr>

<?php


}
elseif($_POST['act']=='vrefPlafond'){
  $idv=connexion::getConnexion()->query("select id_vente from vente where id_client=".$_POST['id_client'])->fetchAll(PDO::FETCH_ASSOC);
  $data="";
  $reg=0;
  $summonts=connexion::getConnexion()->query("SELECT SUM(r.montant) AS summont  FROM reg_vente r,vente v WHERE r.id_vente=v.id_vente AND v.id_client=".$_POST['id_client'])->fetchAll(PDO::FETCH_ASSOC);
  foreach($summonts as $summont){
    if(!empty($summont['summont'])){
      $reg+=$summont['summont'];
    }
  }
  
  if(!empty($idv)){
    $data=connexion::getConnexion()->query("SELECT 
    SUM(dt.prix_produit * dt.qte_vendu *(1-(dt.remise/100)))-". $reg ." as montantTot,
    SUM(dt.prix_produit * dt.valunit *(1-(dt.remise/100)))-". $reg ." as motunitv
    , c.* 
    from client c, vente v, detail_vente dt
    WHERE v.id_vente=dt.id_vente 
    AND c.id_client=v.id_client 
    AND v.numbon<>0 
    AND  c.id_client = ".$_POST['id_client'])->fetchAll(PDO::FETCH_ASSOC);
  }
  else{
    $data=connexion::getConnexion()->query("select * from client where id_client=".$_POST['id_client'])->fetchAll(PDO::FETCH_ASSOC);
  }
  
  
  echo json_encode($data);
  }
elseif ($_POST['act']=='insert') {



$_POST["id_user"] = auth::user()["id"] ;

$boncommande = new boncommande();

$boncommande->insert();

connexion::getConnexion()->exec("UPDATE  detail_commande  SET detail_commande.id_bon =(SELECT max(boncommande.id_bon) FROM boncommande)   WHERE detail_commande.id_bon = -1".$_SESSION['rand_v_er']);

unset($_SESSION['rand_v_er']);

 die("success");

}



elseif ($_POST['act']=='update') {



  try {



    $_POST["idu"] = auth::user()["id"] ;

    $boncommande=new boncommande();

    $boncommande->update($_POST["id"]);

    die('success');

    } catch (Exception $e) {

        die($e);

    

  }

}

elseif ($_POST['act']=='delete') {

try {


$boncommande = new boncommande();

$boncommande->delete($_POST["id"]);

die('success');

} catch (Exception $e) {

    die($e);

}

}

elseif ($_POST['act']=='getPrix') {

$produit=new produit();

$ligne=$produit->selectById($_POST['id_produit']);
$prix_v=0;

$serch_cli=connexion::getConnexion()->query('
SELECT dt.prix_produit from detail_vente dt 
where dt.id_detail= (SELECT MAX(dt1.id_detail) FROM detail_vente dt1, vente v 
WHERE dt1.id_vente=v.id_vente
AND v.id_client='.$_POST['id_client'].' and id_produit='.$_POST['id_produit'].'
)'
)->fetch(PDO::FETCH_ASSOC);
if(empty($serch_cli['prix_produit'])){
$prix_v=$ligne['prix_vente'];
}else{
$prix_v=$serch_cli['prix_produit'];
}

echo  $prix_v."/".$ligne['qte_actuel']. "/" .$ligne['unite2'] ;

}

elseif ($_POST['act']=='insertbon') {

try {





$data = vente::getdevis($_POST["id"]);

foreach (  $data as $value) {

connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel - ".$value["qte_vendu"]." WHERE  id_produit =".$value["id_produit"] );

}

 



$vente=new vente();

$vente->update($_POST["id"]);

die('success');

} catch (Exception $e) {

die($e);

}



}

?>