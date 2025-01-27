<?php

include('../../evr.php');



if ($_POST['act'] == 'filter') {



	$achat = new achat();

	if ($_POST['anne'] != 0) {
		$data = $achat->selectAll3($_POST['anne'] . "-" . $_POST['mois']);
	} else {
		$data = $achat->selectAll2();
	}
?>



	<table class="table  responsive table-striped table-bordered table-hover" id="datatables">

		<thead>

			<tr>

				<th scope="col" width="1px">Id</th>

				<th scope="col">Fournisseur</th>

				<th> Date </th>

				<th scope="col"> Montant </th>

				<th scope="col"> Reste </th>

				<th scope="col"> remarque </th>

				<th scope="col">Actions</th>

			</tr>

		</thead>

		<tbody>

			<?php

			foreach ($data as $ligne) {

			?>

				<tr>

					<td class="nowrap">
						<?php echo $ligne->id_achat; ?>
					</td>

					<td class="nowrap">
						<?php echo $ligne->fournisseur; ?>
					</td>

					<td class="nowrap">
						<?php echo $ligne->date_achat; ?>
					</td>

					<td class="nowrap" style="text-align: right;">
						<?php echo number_format($ligne->montant, 2, '.', ' '); ?> &nbsp;&nbsp;
					</td>

					<td class="nowrap" style="text-align: right;">
						<?php

						$query = $result = connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_achat where id_achat=" . $ligne->id_achat);

						$result = $query->fetch(PDO::FETCH_OBJ);

						$paye = $result->paye;

						echo number_format($ligne->montant - $paye, 2, '.', ' ');

						?> &nbsp;&nbsp;
					</td>

					<td>
						<?php echo strlen($ligne->remarque) > 50 ? substr($ligne->remarque, 0, 50) . "..." : $ligne->remarque; ?>
					</td>

					<td class="nowrap">

						<?php if (auth::user()['privilege'] == 'Admin') { ?>

							<a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_achat; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>

								<i class="simple-icon-trash" style="font-size: 15px;"></i>

							</a>

							<a class="badge badge-warning mb-2  url notlink" data-url="achat/update.php?id=<?php echo $ligne->id_achat; ?>" style="color: white;cursor: pointer;" title="Modifier" href="javascript:void(0)">
								<i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
							</a>



							<a class="badge badge-success mb-2  url notlink" data-url="reg_achat/index.php?id=<?php echo $ligne->id_achat; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)'>

								<i class=" iconsmind-Money-2" style="font-size: 15px;"></i>

							</a>

							<a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL . "views/achat/facture.php?id=" . $ligne->id_achat; ?>&h=15" target="_black">

								<i class=" simple-icon-printer" style="font-size: 15px;"></i>

							</a>

						<?php } ?>

						<a class="badge badge-secondary mb-2 url notlink" data-url="detail_achat/index.php?id=<?php echo $ligne->id_achat; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">



							<i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>

						</a>


						</a>
						<a class="badge badge-warning mb-2 url notlink" data-url="charge_achat/index.php?id=<?php echo $ligne->id_achat; ?>" style="color: white;cursor: pointer;" title="voir Charges" href="javascript:void(0)">

							<i class="glyph-icon  iconsmind-Billing" style="font-size: 15px;"></i>

						</a>

						<?php if ($ligne->valide == 0) : ?>
							<a class="badge badge-success mb-2 valide_achat" style="color: white;cursor: pointer;" title="Valide la commande" type="button" id="btn_valide_<?php echo $ligne->id_achat; ?>" data-id="<?php echo $ligne->id_achat; ?>">
								<i class="simple-icon-check" style="font-size: 15px;"></i>
							</a>
						<?php endif; ?>

					</td>

				</tr>

			<?php } ?>

		</tbody>

	</table>



	<?php



}

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
} elseif ($_POST['act'] == 'deleterow') {


	$detail_achat = new detail_achat();



	if (isset($_POST['id_detail'])) {

		$detail_achat->delete($_POST['id_detail']);
	}





	$data = $detail_achat->selectAllNonValide();

	$total = 0;

	foreach ($data as $ligne) {

	?>

		<tr>



			<td>
				<?php echo $ligne->designation; ?>
			</td>

			<td>
				<?php echo $ligne->depot ?>
			</td>

			<td>
				<?php echo $ligne->prix_produit; ?>
			</td>

			<td>
				<?php echo $ligne->qte_achete; ?>
			</td>
			<td>
				<?php echo $ligne->date_expiration; ?>
			</td>



			<td>
				<?php echo $ligne->poid * $ligne->qte_achete; ?> g
			</td>

			<td width="90" style="text-align: right;">

				<?php echo number_format($ligne->qte_achete * $ligne->prix_produit, 2, '.', ' ');

				$total += $ligne->qte_achete * $ligne->prix_produit;

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

		<td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right;">
				<?php echo number_format($total, 2, '.', ' '); ?>
			</b></td>



	</tr>

	<?php

} elseif ($_POST['act'] == 'addProduct') {

	if (!isset($_SESSION['rand_a_er']) || $_SESSION['rand_a_er'] === "") {
		$_SESSION['rand_a_er'] = rand(10, 1000);
	}

	$_POST["id_user"] = auth::user()["id"];

	$somme_poid = 0;

	$_POST["id_achat"] = "-1" . $_SESSION['rand_a_er'];

	$pu = $_POST["prix_produit"];
	$cd = $_POST["cout_device"];
	$fa = $_POST["f_approch"];

	// $_POST['prix_produit'] = $pu * $cd * $fa;
	$_POST['prix_produit'] = $pu * $fa;

	$detail_achat = new detail_achat();

	$detail_achat->insert();

	$data = $detail_achat->selectAllNonValide();

	$total = 0;

	foreach ($data as $ligne) {


	?>

		<tr>

			<td>
				<?php echo $ligne->designation; ?>
			</td>

			<td>
				<?php echo $ligne->depot ?>
			</td>

			<td>
				<?php echo $ligne->prix_produit; ?>
			</td>

			<td>
				<?php echo $ligne->qte_achete; ?>
			</td>

			<td>
				<?php echo $ligne->date_expiration; ?>
			</td>

			<td>
				<?php echo $ligne->poid * $ligne->qte_achete;

				$somme_poid += $ligne->poid * $ligne->qte_achete;

				?> g
			</td>

			<td width="90" style="text-align: right;">

				<?php echo number_format($ligne->qte_achete * $ligne->prix_produit, 2, '.', ' ');

				$total += $ligne->qte_achete * $ligne->prix_produit;

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

		<td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right;">
				<?php echo number_format($total, 2, '.', ' '); ?>
			</b></td>



	</tr>

<?php

} elseif ($_POST['act'] == 'insert') {


	

	$_POST["id_user"] = auth::user()["id"];

	$achat = new achat();

	if (isset($_POST["id_fournisseur"])) {


		$statut  = $achat->insert();

		 
  
		connexion::getConnexion()->exec("UPDATE  detail_achat  SET detail_achat.id_achat =(SELECT max(achat.id_achat) FROM achat)   WHERE detail_achat.id_achat=-1" . $_SESSION["rand_a_er"]);

		unset($_SESSION['rand_a_er']);

		// $query=$result=connexion::getConnexion()->query("SELECT max(id_achat) as dernier_achat FROM achat ");

		// $result=$query->fetch(PDO::FETCH_OBJ);

		// $dernier_achat=$result->dernier_achat;
		/*
		$result2=connexion::getConnexion()->query("select da.id_produit,sum(da.qte_achete)as qte_achete from detail_achat da inner join achat a on a.id_achat=da.id_achat

		where a.id_achat=$dernier_achat group by  da.id_produit");

		$data=$result2->fetchAll(PDO::FETCH_OBJ);

		foreach($data as $ligne)

			{

		connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel+".$ligne->qte_achete." WHERE  id_produit =".$ligne->id_produit);

			}
			*/
	}



	die("success");
} elseif ($_POST['act'] == 'valide_achat') {
	$achat = new achat();
	$a = $achat->selectById($_POST['id']);

	if ($a['valide'] == 1) {
		die();
	}

	#change state
	connexion::getConnexion()->exec('UPDATE achat SET valide = 1 WHERE id_achat =' . $_POST['id']);

	#update products qte
	// $result2 = connexion::getConnexion()->query("select da.id_produit, da.id_depot, sum(da.qte_achete)as qte_achete from detail_achat da inner join achat a on a.id_achat=da.id_achat
	// where a.id_achat=" . $_POST['id'] . " group by  da.id_produit");
	$result2 = connexion::getConnexion()->query("select da.id_produit, da.id_depot, da.qte_achete as qte_achete from detail_achat da WHERE da.id_achat =" . $_POST['id']);
	$data = $result2->fetchAll(PDO::FETCH_OBJ);

	foreach ($data as $d) {
		$rd = connexion::getConnexion()->exec("UPDATE produit SET qte_actuel = qte_actuel+ $d->qte_achete WHERE  id_produit = " . $d->id_produit);

		$produit_depot = new produit_depot();
		$target = $produit_depot->get_produit_depot($d->id_produit, $d->id_depot);

		if ($target) {
			$produit_depot->add_qte($d->id_produit, $d->id_depot, $d->qte_achete);
		} else {
			$produit_depot->new_produit_depot($d->id_produit, $d->id_depot, $d->qte_achete);
		}
	}

	//calculate composant et produit fini
	foreach ($data as $d) {
		//avoir produit
		$prod = connexion::getConnexion()->query("SELECT * FROM produit WHERE id_produit = " . $d->id_produit)->fetch(PDO::FETCH_OBJ);

		//Si produit est composant
		if ($prod->type_produit == 2) {
			//avoir produit fini
			$q = "SELECT p.* FROM produit p LEFT JOIN detail_produit dp ON dp.id_produit = p.id_produit WHERE dp.id_ingredient = " . $d->id_produit;

			$prod_fini = connexion::getConnexion()->query($q)->fetch(PDO::FETCH_OBJ);

			//avoir tous les composants
			$q = "SELECT * FROM detail_produit WHERE id_produit = " . $prod_fini->id_produit;

			$composants = connexion::getConnexion()->query($q)->fetchAll(PDO::FETCH_OBJ);

			$qteOfProduct = 0;
			$arr = [];

			foreach ($composants as $cmp) {
				$qte_actuel = connexion::getConnexion()->query("SELECT qte_actuel FROM produit WHERE id_produit = " . $cmp->id_ingredient)->fetch(PDO::FETCH_COLUMN);

				$arr[] = intval($qte_actuel / $cmp->qte);
			}

			$nbrOfProduct = min($arr);

			if ($nbrOfProduct) {
				//suctracter la qte des composants
				foreach ($composants as $cmp) {
					$qte_cmp = $cmp->qte * $nbrOfProduct;

					$rd = connexion::getConnexion()->exec("UPDATE produit SET qte_actuel = qte_actuel -  $qte_cmp WHERE  id_produit = " . $cmp->id_ingredient);

					$produit_depot = new produit_depot();
					$target = $produit_depot->minus_last($cmp->id_ingredient, $qte_cmp);
				}

				//incrementer produit qte 
				$rd = connexion::getConnexion()->exec("UPDATE produit SET qte_actuel = qte_actuel+ $nbrOfProduct WHERE  id_produit = " . $prod_fini->id_produit);

				$produit_depot = new produit_depot();
				$target = $produit_depot->get_produit_depot($prod_fini->id_produit, $prod_fini->emplacement);

				if ($target) {
					$produit_depot->add_qte($prod_fini->id_produit, $prod_fini->emplacement, $nbrOfProduct);
				} else {
					$produit_depot->new_produit_depot($prod_fini->id_produit, $prod_fini->emplacement, $nbrOfProduct);
				}
			}

			$arr = [];
		}
	}

	die('success');
} elseif ($_POST['act'] == 'update') {

	

	try {

		$_POST["idu"] = auth::user()["id"];

		$achat = new achat();
		$achat->update($_POST["id"]);


		connexion::getConnexion()->query("UPDATE detail_achat SET cout_device = " . $_POST['cout_device'] . " where id_achat =" .$_POST['id']) ;  
	
		

		die('success');
	} catch (Exception $e) {

		die($e);
	}
} elseif ($_POST['act'] == 'delete') {

	try {





		$achat = new achat();

		$data = achat::getdevis($_POST["id"]);
		foreach ($data as $value) {
			connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel -" . $value["qte_achete"] . " WHERE  id_produit =" . $value["id_produit"]);
		}


		$achat->delete($_POST["id"]);

		die('success');
	} catch (Exception $e) {

		die($e);
	}
} elseif ($_POST['act'] == 'getPrix') {

	$produit = new produit();

	$ligne = $produit->selectById($_POST['id_produit']);

	echo $ligne['prix_achat'] . "/" . $ligne['qte_actuel'];
}

?>