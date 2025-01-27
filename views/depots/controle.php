<?php

include('../../evr.php');

if ($_POST['act']=='filter') 
{

    $vente=new vente();

if($_POST['anne'] != 0)
{
   $data = $vente->selectAll3($_POST['anne'] . "-" . $_POST['mois']);
}
if($_POST['anne']==0)
   $data = $vente->selectAll3all();

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

                  <th scope="col"> BL</th>
                  
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

                  <td> <?php echo $ligne->id_vente; ?></td>

                  <td class="nowrap">

                    <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->client;

                      if($ligne->nom_prenom_ar != "" && $ligne->client == " ") {

                      echo $ligne->nom_prenom_ar;

                      }

                      if($ligne->nom_prenom_ar != "" && $ligne->client != " ") {

                      echo "/" . $ligne->nom_prenom_ar;

                      }

                    ?> </a> </td>

                    <td class="nowrap">
                    <?php if($ligne->numbon!=0){?>
                    <a target="_blank" href="<?php echo BASE_URL."views/vente/facturebon.php?id=".$ligne->id_vente; ?>&h=15" class="badge badge-primary"><?php echo $ligne->date_vente; ?></a>  
                    <?php }else{?>
                      <span   class="badge badge-success"><?php echo $ligne->date_vente; ?></span> 
                    <?php }?>
                    </td>

                    <td style="text-align: right;" class="nowrap" data-href="#"> 
                       <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>">
                      <?php 
                      // var_dump($ligne->motunitv);
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

                      $query = $result = connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_vente where id_vente=" . $ligne->id_vente);

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
                    
                    <td>
                    	<?php echo $ligne->numbon ?>
                    </td>
                    
                    

                    <td class="nowrap">

                      <?php if(auth::user()['privilege'] == 'Admin') { ?>

                      <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >

                        <i class="simple-icon-trash" style="font-size: 15px;"></i>

                      </a>

                      <a class="badge badge-success mb-2  url notlink" data-url="reg_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)' >

                        <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>

                      </a>

                        <a class="badge badge-warning mb-2  url notlink" data-url="vente/update.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Modifier"

                    href="javascript:void(0)">

                    <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>

                  </a>

                     

                      <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL."views/vente/facture.php?id=".$ligne->id_vente; ?>&h=15"  target="_black" >

                        <i class=" simple-icon-printer" style="font-size: 15px;"></i>

                      </a>

                      <a  class="badge badge-secondary mb-2 url notlink" data-url="detail_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">

                        

                        <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>

                      </a>

                       <?php if($ligne->numbon == 0) { ?>

                      <a class="badge badge-warning mb-2  url notlink" data-url="vente/transfer.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Bon livraison" href='javascript:void(0)' >

                        <i class="iconsmind-Add-Cart" style="font-size: 15px;"></i>

                      </a>

                      <?php } ?>  

                      <?php if($nbr == 0) { ?>

                      <a class="badge badge-primary mb-2 url notlink" style="color: white;cursor: pointer;" title="Facture" data-url="<?php echo "facture/add.php?idv[]=".$ligne->id_vente; ?>" href='javascript:void(0)' >

                        <i class=" iconsmind-Billing" style="font-size: 15px;"></i>

                      </a>

                      <?php } ?>

                      <a class="badge badge-primary mb-2 notlink" style="background-color: #d322e8!important;color: white;cursor: pointer;" title="Ticket" href='<?php echo BASE_URL.'/views/vente/ticket.php?id='.$ligne->id_vente; ?>'  target="_black" >

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

if($_POST['act'] == 'getDepotQte')
{
	$produit_depot = new produit_depot();
	$pd = $produit_depot->get_produit_depot($_POST['id_produit'], $_POST['id_depot']);
	
	echo json_encode($pd); exit;
}

if($_POST['act'] == 'changebon')
{
   $id_bon = $_POST['id_bon'];
	
   $details = connexion::getConnexion()->query('SELECT d.*, p.designation  FROM detail_commande d LEFT JOIN produit p ON p.id_produit = d.id_produit WHERE id_bon = ' . $id_bon)->fetchAll(PDO::FETCH_ASSOC);
	
   $prods = connexion::getConnexion()->query('SELECT DISTINCT id_produit FROM detail_commande WHERE id_bon = ' . $id_bon)->fetchAll(PDO::FETCH_OBJ);
	
   $str = '';
   $cats = [];
   foreach($prods as $prod)
   {

	   $cat = connexion::getConnexion()->query("SELECT c.* FROM produit p LEFT JOIN categorie c ON c.id_categorie = p.id_categorie WHERE p.id_produit = " . $prod->id_produit)->fetch(PDO::FETCH_OBJ);
	   
	   if(!in_array($cat->id_categorie, $cats))
	   {
		   $cats[] = $cat->id_categorie;
	      $str .= '<option value="'.$cat->id_categorie.'">'.$cat->nom.'</option>';
	   }
	   
   }
	
   $str1 = '';
   foreach($details as $dt)
   {
	   $str1 .= '<option value="'. $dt['id_produit'] .'">' . $dt['designation'] . '</option>';
   }

   echo json_encode(['cat' => $str, 'prod' => $str1]);
}

if ($_POST['act']=='filterbon') 
{

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

if ($_POST['act']=='getproduit') 
{

  if(isset($_POST['id_bon']))
  {
	 $id_bon = $_POST['id_bon'];
	  
	 if($id_bon != 0)
	 {
	
		   $details = connexion::getConnexion()->query('SELECT p.* FROM detail_commande d LEFT JOIN produit p ON p.id_produit = d.id_produit WHERE p.id_categorie = ' . $_POST['id_categorie'] . ' AND  id_bon = ' . $id_bon)->fetchAll(PDO::FETCH_OBJ);

		   $prods = connexion::getConnexion()->query('SELECT id_produit FROM detail_commande WHERE id_bon = ' . $id_bon)->fetchAll(PDO::FETCH_OBJ);
           
		   $str = '';
		   foreach($details as $prod)
		   {
			   $str .= '<option value="'.$prod->id_produit.'">'.$prod->designation.'</option>';
		   }

		 die($str);
	 }
  }
	
  $depot=new depot();

  $res_depot=$depot->selectAll();

  foreach($res_depot as $rep_depot){

?>

  <?php

  $produits=$depot->selectQuery("SELECT  id_produit,designation  FROM produit where   id_categorie=".$_POST['id_categorie']." and   emplacement='".$rep_depot->id."' order by designation asc");



  foreach ($produits as $row) {

  echo '<option value="'.$row->id_produit.'">'.$row->designation.'</option>';

  }?>

<?php }

}

if ($_POST['act']=='rech') 
{

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

elseif ($_POST['act']=='deleterow') 
{

    $detail_depot_op = new detail_depot_op();

    if(isset($_POST['id_detail']))
	{
       $detail_depot_op->delete($_POST['id_detail']);
    }


   $data = $detail_depot_op->selectAllNonValide();


  foreach($data as $ligne){

?>

<tr>

            <td><?php echo $ligne->designation ?></td>
			<td><?php echo $ligne->depot_src ?></td>
			<td><?php echo $ligne->depot_dest ?></td>
			<td><?php echo $ligne->qte_op ?></td>
            <td>    
                <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                    <i class="simple-icon-trash" style="font-size: 15px;"></i>
                </a>
            </td>

 </tr>


<?php

}

?>

<?php

}



elseif ($_POST['act']=='addProduct') 
{

if (!isset($_SESSION['rand_v_er']) || $_SESSION['rand_v_er']==="" ){

  $_SESSION['rand_v_er']=rand(10,1000);

  }

  $_POST["id_user"] = auth::user()["id"] ;

  $_POST["id_depot_op"]= "-1".$_SESSION['rand_v_er'];

  $detail_depot_op = new detail_depot_op();

  $detail_depot_op->insert();

  $data = $detail_depot_op->selectAllNonValide();

  foreach($data as $ligne){

?>

 <tr>

            <td><?php echo $ligne->designation ?></td>
			<td><?php echo $ligne->depot_src ?></td>
			<td><?php echo $ligne->depot_dest ?></td>
			<td><?php echo $ligne->qte_op ?></td>
            <td>    
                <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                    <i class="simple-icon-trash" style="font-size: 15px;"></i>
                </a>
            </td>

 </tr>

<?php

}

}
elseif($_POST['act']=='vrefPlafond')
{
  $idv=connexion::getConnexion()->query("select id_vente from vente where id_client=".$_POST['id_client'])->fetchAll(PDO::FETCH_ASSOC);
  $data="";
  $reg=0;
  $summonts=connexion::getConnexion()->query("SELECT SUM(r.montant) AS summont  FROM reg_vente r,vente v WHERE r.id_vente=v.id_vente AND v.id_client=".$_POST['id_client'])->fetchAll(PDO::FETCH_ASSOC);
  foreach($summonts as $summont){
    if(!empty($summont['summont'])){
      $reg+=$summont['summont'];
    }
  }
  
  if(!empty($idv))
  {
    $data=connexion::getConnexion()->query("SELECT 
    SUM(dt.prix_produit * dt.qte_vendu *(1-(dt.remise/100)))-" . $reg ." as montantTot,
    SUM(dt.prix_produit * dt.valunit *(1-(dt.remise/100)))-". $reg ." as motunitv
    , c.* 
    from client c, vente v, detail_vente dt
    WHERE v.id_vente=dt.id_vente 
    AND c.id_client=v.id_client 
    AND v.numbon<>0 
    AND  c.id_client = ".$_POST['id_client'])->fetch(PDO::FETCH_ASSOC);
	  
  }
  else{
    $data=connexion::getConnexion()->query("select * from client where id_client=".$_POST['id_client'])->fetch(PDO::FETCH_ASSOC);
  }
  
  
  echo json_encode($data);
  }
elseif ($_POST['act']=='insert') 
{
$_POST["id_user"] = auth::user()["id"] ;
$session = $_SESSION['rand_v_er'];
$depot_op = new depot_op();

$depot_op->insert();

$query=$result=connexion::getConnexion()->query("SELECT max(depot_op.id_depot_op) FROM depot_op ");

$dernier_vente =  $query->fetch(PDO::FETCH_COLUMN);
	
connexion::getConnexion()->exec("UPDATE  detail_depot_op  SET detail_depot_op.id_depot_op = $dernier_vente  WHERE detail_depot_op.id_depot_op LIKE '-1%' AND detail_depot_op.id_user = " . $_POST["id_user"]);


	
$details = $depot_op->get_details($dernier_vente);
  $test = '';

  $endpoint = "http://pointcentral.gcmi.store/api/store-data";

foreach($details as $ligne)
  {


	$id_produit = $ligne->id_produit;
	$id_src = $ligne->id_depot_src;
	$id_dest = $ligne->id_depot_dest;
	$qte = $ligne->qte_op;




  $produit_actuel=connexion::getConnexion()->query("SELECT * FROM produit WHERE id_produit =". $id_produit)->fetch(PDO::FETCH_ASSOC);
  $produit_actuel_categorie=connexion::getConnexion()->query("SELECT * FROM categorie WHERE id_categorie = ".$produit_actuel['id_categorie'])->fetch(PDO::FETCH_ASSOC);
  $depot=connexion::getConnexion()->query("SELECT uuid FROM depot WHERE  id =". $id_dest)->fetch(PDO::FETCH_COLUMN);


  $designation = $produit_actuel['designation'];
  $unite = $produit_actuel['unite'];
  $code_bar = $produit_actuel['code_bar'];
  $minqte = $produit_actuel['minqte'];
  $type = 1;
  $image = '';  

 // Define POST data
    $data = array(
      'data'=>[
        "id_user"=>1,
    "categorie"=>"Miels  1 Kg",
    "prix_achat"=>12,
    "prix_vente"=>20,
    "lebelle"=>$designation,
    "unite"=>$unite,
    "code_bar"=>$code_bar,
    "img"=>$image,
    "type"=>$type, //send designation instead of id
    "qte"=>$qte,
    "qte_alert"=>$minqte

      ],'depot'=>$depot      
    );

// Initialize cURL session
$curl = curl_init($endpoint);

// Set cURL options
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($curl);

// Close cURL session
curl_close($curl);

// Output response
echo $response;


	//minus qte from source
	$query1 = "UPDATE produit_depot SET qte = qte - " . $qte . " WHERE id_produit = " . $id_produit . " AND id_depot = " . $id_src;
	$test = $query1;
	connexion::getConnexion()->exec($query1);


 

	//add qte or create destination
	$target = connexion::getConnexion()->query("SELECT * FROM produit_depot WHERE id_produit = $id_produit AND id_depot = $id_dest")->fetch(PDO::FETCH_OBJ);
	
	if($target)
	{
		connexion::getConnexion()->exec("UPDATE produit_depot SET qte = qte + " . $qte . " WHERE id_produit = " . $id_produit . " AND id_depot = " . $id_dest);
	}
	else
	{
		connexion::getConnexion()->exec("INSERT INTO produit_depot(id_produit, id_depot, qte) VALUES($id_produit, $id_dest, $qte)");
	}
	
  }




  die("success");

}





elseif ($_POST['act']=='update') {


  try 
  {
    $depot_op =new depot_op();

    $depot_op->update($_POST["id"]);

    die('success');

    } 
	catch (Exception $e) 
    {

        die($e);
    }

}

elseif ($_POST['act']=='delete') 
{
	try 
	{
		$depot_op = new depot_op();

		$data = $depot_op->get_details($_POST["id"]);

		foreach ($data as $ligne) 
		{
			$id_produit = $ligne->id_produit;
			$id_src = $ligne->id_depot_src;
			$id_dest = $ligne->id_depot_dest;
			$qte = $ligne->qte_op;
			
			//minus qte from source
			$query1 = "UPDATE produit_depot SET qte = qte + " . $qte . " WHERE id_produit = " . $id_produit . " AND id_depot = " . $id_src;
			
		    connexion::getConnexion()->exec($query1);

			//add qte or create destination
			connexion::getConnexion()->exec("UPDATE produit_depot SET qte = qte - " . $qte . " WHERE id_produit = " . $id_produit . " AND id_depot = " . $id_dest);
			
			connexion::getConnexion()->exec("DELETE FROM detail_depot_op WHERE id_detail = " . $ligne->id_detail);
			
		}

		connexion::getConnexion()->exec("DELETE FROM depot_op WHERE id_depot_op = " . $_POST["id"]);

		die('success');

	} 
	catch (Exception $e) 
	{
		die($e);
	}
}

elseif ($_POST['act']=='getPrix') 
{
	$produit_depot = new produit_depot();
	$depots = $produit_depot->depots($_POST['id_produit']);

	$d_options = '';
	foreach($depots as $d)
	{
		$d_options .= "<option value='$d->id'>" . $d->nom . "</option>";
	}
	echo  json_encode(['depots' => $d_options, 'val' => '']) ;exit;

}

elseif ($_POST['act']=='insertbon') 
{

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