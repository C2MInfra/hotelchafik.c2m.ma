<?php
include('../../evr.php');


if ($_POST['act'] == 'getproduit') {
	$depot = new depot();
	$res_depot = $depot->selectAll();
	foreach ($res_depot as $rep_depot) {
?>
		<optgroup label="<?php echo $rep_depot->nom; ?> ">
			<?php
			$produits = $depot->selectQuery("SELECT  id_produit,designation  FROM produit where   id_categorie=" . $_POST['id_categorie'] . " and   emplacement='" . $rep_depot->id . "' order by designation asc");
			foreach ($produits as $row) {
				echo '<option value="' . $row->id_produit . '">' . $row->designation . '</option>';
			} ?>
		</optgroup>
	<?php }
} elseif ($_POST['act'] == 'deleterow') {
	$detail_detail_vente = new detail_detail_vente();

	if (isset($_POST['id_detail'])) {

		$detail_detail_vente->delete($_POST['id_detail']);
	}


	$data = $detail_detail_vente->selectAllNonValide();
	$total = 0;
	foreach ($data as $ligne) {
	?>
		<tr>

			<td><?php echo $ligne->designation; ?></td>
			<td><?php echo $ligne->prix_produit; ?></td>
			<td><?php echo $ligne->qte_vendu; ?></td>

			<td><?php echo $ligne->poid * $ligne->qte_vendu; ?> g </td>
			<td width="90" style="text-align: right;">
				<?php echo number_format($ligne->qte_vendu * $ligne->prix_produit, 2, '.', ' ');
				$total += $ligne->qte_vendu * $ligne->prix_produit;
				?>

			</td>
			<td><a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
					<i class="simple-icon-trash" style="font-size: 15px;"></i></a> </td>
		</tr>

	<?php
	}
	?>

	<tr>
		<td colspan="4" style="text-align: center;font-size: 15px;"> <b>Total</b> </td>
		<td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right;"><?php echo number_format($total, 2, '.', ' '); ?></b></td>

	</tr>
	<?php
} elseif ($_POST['act'] == 'addProduct') {
	if (!isset($_SESSION['rand_a_er']) || $_SESSION['rand_a_er'] === "") {
		$_SESSION['rand_a_er'] = rand(10, 1000);
	}
	$_POST["id_user"] = auth::user()["id"];

	$somme_poid = 0;
	$_POST["id_detail_vente"] = "-1" . $_SESSION['rand_a_er'];
	$detail_detail_vente = new detail_detail_vente();

	$detail_detail_vente->insert();
	$data = $detail_detail_vente->selectAllNonValide();
	$total = 0;

	foreach ($data as $ligne) {

	?>
		<tr>
			<td><?php echo $ligne->designation; ?></td>
			<td><?php echo $ligne->prix_produit; ?></td>
			<td><?php echo $ligne->qte_vendu; ?></td>
			<td><?php echo $ligne->poid * $ligne->qte_vendu;
				$somme_poid += $ligne->poid * $ligne->qte_vendu;
				?> g </td>
			<td width="90" style="text-align: right;">
				<?php echo number_format($ligne->qte_vendu * $ligne->prix_produit, 2, '.', ' ');
				$total += $ligne->qte_vendu * $ligne->prix_produit;
				?>
			</td>
			<td> <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
					<i class="simple-icon-trash" style="font-size: 15px;"></i>
				</a>
			</td>
		</tr>
	<?php
	}
	?>
	<tr>
		<td colspan="4" style="text-align: center;font-size: 15px;"> <b>Total</b> </td>
		<td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right;"><?php echo number_format($total, 2, '.', ' '); ?></b></td>

	</tr>
	<?php
} elseif ($_POST['act'] == 'insert') {
	if (isset($_POST["id_produit"])) {

		$_POST["id_bon"] = $_POST["id"];
		$_POST["id_user"] = auth::user()["id"];
		$_POST["prix_produit"] = trim($_POST["prix_produit"]);



		$detail_bon_vendeur = new detail_bon_vendeur();

		$detail_bon_vendeur->insert();
	}
	die("success");
} elseif ($_POST['act'] == 'delete') {
	try {
		$detail_bon_vendeur = new detail_bon_vendeur();
		$dv = $detail_bon_vendeur->selectById($_POST['id']);

		$detail_bon_vendeur->delete($_POST["id"]);

		die('success');
	} catch (Exception $e) {
		die($e);
	}
} elseif ($_POST['act'] == 'getPrix') {
	$produit = new produit();
	$ligne = $produit->selectById($_POST['id_produit']);
	echo  $ligne['prix_vente'] . "/" . $ligne['qte_actuel'];
} elseif ($_POST['act'] == 'update_detail') {

	$_POST['unit'] = trim($_POST['unit']);
	$_POST['valunit'] = trim($_POST['valunit']);

	$detail_bon_vendeur = new detail_bon_vendeur();

	$dv = $detail_bon_vendeur->selectById($_POST['id_detail']);


	$rs = $detail_bon_vendeur->update($_POST['id_detail']);


	$totales = $detail_bon_vendeur->gettotale($_POST['id_bon']);

	$totalee = 0;

	foreach ($totales as $totale) {
		if (!empty($totale['valunit']) || $totale['valunit'] != 0) {
			$totalee += $totale['valunit'] * $totale['prix_produit'] * $totale['remise'];
		} else {
			$totalee += $totale['qte_vendu'] * $totale['prix_produit'] * $totale['remise'];
		}
	}
	die(number_format($totalee, 2, '.', ''));
} elseif ($_POST['act'] == 'rech') {

	$depot = new depot();

	$res_depot = $depot->selectAll();

	foreach ($res_depot as $rep_depot) {

	?>

		<optgroup label="<?php echo $rep_depot->nom; ?> ">

			<?php

			$produits = $depot->selectQuery("SELECT  id_produit,designation as designation   FROM produit where  code_bar like '" . $_POST['id'] . "%' and   emplacement='" . $rep_depot->id . "' order by designation asc");

			foreach ($produits as $row) {

				echo '<option value="' . $row->id_produit . '">' . $row->designation . '</option>';
			} ?>

		</optgroup>

	<?php }
} elseif ($_POST['act'] == 'rech_designation') {

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

?>