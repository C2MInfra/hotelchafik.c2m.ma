<?php
include('../../evr.php');

if ($_POST['act']=='filter') {

	$avoir = new avoir();
	
	if($_POST['anne']!=0 )
        $data=$avoir->selectAll3($_POST['anne']."-".$_POST['mois']);
    else
       $data=$avoir->selectAll2();
        ?>

      <table class="table  responsive table-striped table-bordered table-hover" id="datatables" >
              <thead>
                <tr>
                  
                  <th scope="col" width="1px"  >Id</th>
                  <th   scope="col">Cliet</th>
                  <th> Date </th>
                  <th   scope="col"> Montant </th>
                  <th   scope="col"> Reste </th>
                  <th   scope="col"> remarque </th>
                  <th   scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                
                <?php
                foreach($data as $ligne){
                $query=$result=connexion::getConnexion()->query("SELECT count(*) as nbr,id_facture FROM facture_avoir where id_avoir like '%".$ligne->id_avoir."%'");
                $result=$query->fetch(PDO::FETCH_OBJ);
                $id_facture=$result->id_facture;
                $nbr=$result->nbr;
                ?>
                <tr>
                  
                  <td> <?php echo $ligne->id_avoir ; ?></td>
                  <td>  <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->client;
                  if($ligne->nom_prenom_ar != "" && $ligne->client == " ") {
                  echo $ligne->nom_prenom_ar;
                  }
                  if($ligne->nom_prenom_ar != "" && $ligne->client != " ") {
                  echo "/" . $ligne->nom_prenom_ar;
                  }
                ?> </a> </td>
                  <td><?php if($nbr>0){echo "<a target='_blank'  href='../avoir/facture.php?id=".$ligne->id_avoir."&idf=".$id_facture."' tilite='Facture'> ".$ligne->date_avoir."</a>" ;} else{echo $ligne->date_avoir ;} ?> </td>
                  <td style="text-align: right;" > <?php echo number_format($ligne->montantv,2,'.',' ') ;   ?> &nbsp;&nbsp;</td>
                  <td style="text-align: right;" > <?php
                    $query=$result=connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_avoir where id_avoir=".$ligne->id_avoir);
                    $result=$query->fetch(PDO::FETCH_OBJ);
                    $paye=$result->paye;
                    echo    number_format($ligne->montantv-$paye,2,'.',' ') ;
                  ?>  &nbsp;&nbsp;</td>
                  
                  
                  <td> <?php echo $ligne->remarque ; ?> </td>
                  <td>
                    <?php if(auth::user()['privilege'] == 'Admin') { ?>
                    <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_avoir; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                      <i class="simple-icon-trash" style="font-size: 15px;"></i>
                    </a>
                    <a class="badge badge-success mb-2  url notlink" data-url="reg_avoir/index.php?id=<?php echo $ligne->id_avoir; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)' >
                      <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>
                    </a>
                    
                    <?php } ?>
                    
                    <a  class="badge badge-secondary mb-2 url notlink" data-url="detail_avoir/index.php?id=<?php echo $ligne->id_avoir; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">
                      
                      <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>
                    </a>
                    <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL."views/avoir/etat.php?id=".$ligne->id_avoir; ?>"  target="_black" >

                        <i class=" simple-icon-printer" style="font-size: 15px;"></i>

                      </a>
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
if ($_POST['act'] == 'rech') {

	$depot = new depot();

	$res_depot = $depot->selectAll();

	foreach ($res_depot as $rep_depot) {

		?>

		<optgroup label="<?php echo $rep_depot->nom; ?> ">

			<?php

			$produits = $depot->selectQuery("SELECT  id_produit,designation as designation FROM produit where  code_bar like '" . $_POST['id'] . "%' and   emplacement='" . $rep_depot->id . "' order by designation asc");

			foreach ($produits as $row) {

				echo '<option value="' . $row->id_produit . '">' . $row->designation . '</option>';

			} ?>

		</optgroup>

	<?php }

  } 

  elseif ($_POST['act'] == 'rech_designation') {

	$depot = new depot();

	$res_depot = $depot->selectAll();

	foreach ($res_depot as $rep_depot) {

		?>

		<optgroup label="<?php echo $rep_depot->nom; ?> ">

			<?php

			$produits = $depot->selectQuery("SELECT  id_produit,designation as designation FROM produit where  designation like '" . $_POST['designation'] . "%' and   emplacement='" . $rep_depot->id . "' order by designation asc");

			foreach ($produits as $row) {

				echo '<option value="' . $row->id_produit . '">' . $row->designation . '</option>';

			} ?>

		</optgroup>

	<?php }

}
elseif ($_POST['act']=='deleterow') {
		$detail_avoir=new detail_avoir();

			if(isset($_POST['id_detail'])){
			$detail_avoir->delete($_POST['id_detail']);
			}
			
			
		$data=$detail_avoir->selectAllNonValide();
		$total=0;
		foreach($data as $ligne){
		?>
		<tr>
			
			<td><?php echo $ligne->designation ; ?></td>
			<td><?php echo $ligne->prix_produit ; ?></td>
			<td><?php echo $ligne->qte_rendu ; ?></td>
			
			<td><?php echo $ligne->poid*$ligne->qte_rendu ; ?> g </td>
			<td width="90" style="text-align: right;" >
				<?php  echo number_format($ligne->qte_rendu * $ligne->prix_produit ,2,'.',' ');
				$total+=($ligne->qte_rendu * $ligne->prix_produit)*(1-$ligne->remise/100);
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
	if (!isset($_SESSION['rand_av_er']) || $_SESSION['rand_av_er']==="" ){
			$_SESSION['rand_av_er']=rand(10,1000);
			}
			$_POST["id_user"] = auth::user()["id"] ;

			$somme_poid=0;
			$_POST["id_avoir"]= "-1".$_SESSION['rand_av_er'];
			$detail_avoir=new detail_avoir();
			$detail_avoir->insert();
			$data=$detail_avoir->selectAllNonValide();
			$total=0;

			foreach($data as $ligne){

		?>
		<tr>
			<td><?php echo $ligne->designation ; ?></td>
			<td><?php echo $ligne->prix_produit ; ?></td>
			<td><?php echo $ligne->qte_rendu ; ?></td>
			<td><?php echo $ligne->poid*$ligne->qte_rendu ;
			$somme_poid+=$ligne->poid*$ligne->qte_rendu;
			?> g </td>
			<td width="90" style="text-align: right;" >
				<?php  echo number_format($ligne->qte_rendu * $ligne->prix_produit,2,'.',' ');
				$total+=($ligne->qte_rendu * $ligne->prix_produit)*(1-$ligne->remise/100);
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
			<td style="text-align: right;" colspan="3">  <b style="font-size: 15px;color: green;text-align: right;" ><?php echo number_format($total,2,'.',' '); ?></b></td>
			
		</tr>
		<?php
	}
elseif ($_POST['act']=='insert') {
		$_POST["date_avoir"]=date("Y-m-d");
		$_POST["id_user"] = auth::user()["id"] ;
		$avoir=new avoir();
		if(isset($_POST["id_client"])){
		$avoir->insert();
		connexion::getConnexion()->exec("UPDATE  detail_avoir  SET detail_avoir.id_avoir =(SELECT max(avoir.id_avoir) FROM avoir)   WHERE detail_avoir.id_avoir=-1".$_SESSION["rand_av_er"]);
		unset($_SESSION['rand_av_er']);
		$query=$result=connexion::getConnexion()->query("SELECT max(id_avoir) as dernier_avoir FROM avoir ");
		$result=$query->fetch(PDO::FETCH_OBJ);
		$dernier_avoir=$result->dernier_avoir;
		$result2=connexion::getConnexion()->query("select da.id_produit,sum(da.qte_rendu)as qte_rendu from detail_avoir da inner join avoir a on a.id_avoir=da.id_avoir
		where a.id_avoir=$dernier_avoir group by  da.id_produit");
		$data=$result2->fetchAll(PDO::FETCH_OBJ);
		foreach($data as $ligne)
			{
		connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel+".$ligne->qte_rendu." WHERE  id_produit =".$ligne->id_produit);
			}


				
			}

				die("success");
}
elseif ($_POST['act']=='update') {
}
elseif ($_POST['act']=='delete') {
	try {
		
	
		$avoir=new avoir();
		$avoir->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}
elseif ($_POST['act']=='getPrix') {
	$produit=new produit();
		$ligne=$produit->selectById($_POST['id_produit']);
		//print_r($ligne);
		echo  $ligne['prix_vente2']."/".$ligne['qte_actuel']  ;
}
?>