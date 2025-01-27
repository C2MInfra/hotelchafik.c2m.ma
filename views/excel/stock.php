<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

<?php

include('../../eve.php');

$file="stock_par_category.xls";

header("Content-type: application/vnd.ms-excel; charset=utf-8");

header("Content-Disposition: attachment; filename=$file");

function design_ar($design,$design_ar){

if ($design==''&& $design_ar!='') {

echo $design_ar;

}

if ($design!=''&& $design_ar!='') {

echo "/ ".$design_ar;

}

}

$categorie=new categorie();

if(isset($_GET['id_categorie']) && $_GET['id_categorie']>0 ){$resuly_cat=$categorie->selectById2($_GET['id_categorie']);}

else{$resuly_cat=$categorie->selectAll();}

$tqte_act=0;$tqte_stock=0;

$tpr_vente=0;$tpr_achat=0;

$tpr_achat_qte=0;$tpr_vente_qte=0;

?>

<html xmlns:v="urn:schemas-microsoft-com:vml"

	xmlns:o="urn:schemas-microsoft-com:office:office"

	xmlns:x="urn:schemas-microsoft-com:office:excel"

	xmlns="http://www.w3.org/TR/REC-html40">

	<head>

		<meta http-equiv=Content-Type content="text/html; charset=windows-1252">

		<meta name=ProgId content=Excel.Sheet>

		<meta name=Generator content="Microsoft Excel 12">

		<style type="text/css">

		.tableform{background-color:#999999; width:400px; margin:150px auto; }

		.inputText{height:22px; width:80%; border-radius:3px;margin-top:10px;}

		.button{height:25px; width:30%; border-radius:3px;margin-top:10px; font-weight:bold;}

		.button:hover{color:#666666; cursor:pointer;}

		h3{text-decoration:underline;text-transform:uppercase;}

		.datatables{border-collapse:collapse; width:100%;}

		.row{background-color:#CCCCCC;}

		.montant{text-align:right;}

		</style>

		<body>

			<?php

			foreach($resuly_cat as $rep_cat){

			$qte_act=0;$qte_stock=0;

			$pr_achat=0;$pr_vente=0;

			$pr_achat_qte=0;$pr_vente_qte=0;

			$data=$categorie->selectProduitByCategory($rep_cat->id_categorie,$_POST['dd']);

			?>

			<table class="datatables"  border=1  >

				<tr><th bgcolor="red" colspan="5"><?php echo $rep_cat->nom; ?></th></tr>

				<tr class="row">

					<th width="5%">Ref</th>

					<th width="29%">D&eacute;signation  </th>

					<th width="12%"> QStock</th>

					<th width="7%"> P.Achat</th>

					<th width="7%"> Total</th>

				</tr>

				<?php

				$totalqte_actuel = 0;

				$totalprix_achat = 0;

				$totalprix_vente = 0;

							$totaltal = 0;

							foreach($data as $ligne){

							$totalqte_actuel += $ligne->qte_actuel;

				$totalprix_achat += $ligne->prix_achat;

				$totaltal += $ligne->prix_achat*$ligne->qte_actuel;

				?>

				<tr>

					<td style="text-align: center;" > <?php echo $ligne->code_bar ; ?> </td>

					<td style="text-align: center;" > <?php echo $ligne->designation ; ?>

						<?php

						design_ar($ligne->designation,$ligne->designation_ar)

						?>

					</td>

					<td style="text-align: center;" > <?php echo $ligne->qte_actuel ; ?> </td>

					<td style="text-align: right;" > <?php echo $ligne->prix_achat ; ?> </td>

					<td style="text-align: right;" > <?php echo $ligne->prix_achat*$ligne->qte_actuel ; ?> </td>

					

				</tr>

				<?php }

				?>

				<tr bgcolor="green" style="font-weight: bold;font-size: 17px;">

					<td style="text-align: center;" colspan="2" >  Total :

					</td>

					<td style="text-align: center;" > <?php echo $totalqte_actuel ; ?> </td>

					<td style="text-align: right;" > <?php echo $totalprix_achat ; ?> </td>

					<td style="text-align: right;" > <?php echo  $totaltal ; ?> </td>

					

				</tr>

				<tr>

					<td colspan="5"></td>

				</tr>

			</table>

			<?php }?>

			

		</body>

	</html>