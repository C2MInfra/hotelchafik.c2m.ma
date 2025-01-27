<?php
include('../../evr.php');


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
elseif ($_POST['act']=='deleterow') {
		$detail_detail_avoir=new detail_detail_avoir();

			if(isset($_POST['id_detail'])){
			$detail_detail_avoir->delete($_POST['id_detail']);
			}
			
			
		$data=$detail_detail_avoir->selectAllNonValide();
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
				$total+=$ligne->qte_rendu * $ligne->prix_produit;
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
	if (!isset($_SESSION['rand_a_er']) || $_SESSION['rand_a_er']==="" ){
			$_SESSION['rand_a_er']=rand(10,1000);
			}
			$_POST["id_user"] = auth::user()["id"] ;

			$somme_poid=0;
			$_POST["id_avoir"]= "-1".$_SESSION['rand_a_er'];
			$detail_detail_avoir=new detail_detail_avoir();
			$detail_detail_avoir->insert();
			$data=$detail_detail_avoir->selectAllNonValide();
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
				$total+=$ligne->qte_rendu * $ligne->prix_produit;
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
				if(isset($_POST["id_produit"])){
	


			$_POST["id_user"]= auth::user()["id"] ;
		$_POST["prix_produit"]=trim($_POST["prix_produit"]);
		$detail_avoir=new detail_avoir(); 

		$detail_avoir->insert();

		connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel+".$_POST["qte_rendu"]." WHERE  id_produit =".$_POST["id_produit"] );
		}
						die("success");
}
elseif ($_POST['act']=='update') {
}
elseif ($_POST['act']=='delete') {
	try {
		
	
		$detail_avoir=new detail_avoir();
		$detail_avoir->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}
elseif ($_POST['act']=='getPrix') {
	$produit=new produit();
		$ligne=$produit->selectById($_POST['id_produit']);
		echo  $ligne['prix_vente']."/".$ligne['qte_actuel']  ;
}
elseif ($_POST['act']=='update_detail') {

$detail_avoir=new detail_avoir();
$_POST['qte_rendu']=$_POST['qte_renduh']-$_POST['qte_rendu'];
$detail_avoir->update($_POST['id_detail']);
$produit=new produit();

connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel-".$_POST["qte_rendu"]." WHERE  id_produit =".$_POST["id_produit"] );
$totale=$detail_avoir->gettotale($_POST['id_avoir']);

die( number_format($totale['totale'], 2, '.', ''));
}
?>