<?php
include("../../evr.php");

function dateFormat($dat)
{
	$date = new DateTime($dat);
	return $date->format('d-m-Y');
}

function design_ar($design, $design_ar)
{
	if ($design == '' && $design_ar != '') {
		echo $design_ar;
	}

	if ($design != '' && $design_ar != '') {
		echo "/ " . $design_ar;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Etat commandes des vendeurs</title>
	<link rel="icon" href="<?php echo BASE_URL . 'asset/img/icon.png' ?>" type="image/x-icon">
	<style type="text/css">
		.tableform {
			background-color: #999999;
			width: 400px;
			margin: 150px auto;
		}

		.inputText {
			height: 22px;
			width: 80%;
			border-radius: 3px;
			margin-top: 10px;
		}

		.button {
			height: 25px;
			width: 30%;
			border-radius: 3px;
			margin-top: 10px;
			font-weight: bold;
		}

		.button:hover {
			color: #666666;
			cursor: pointer;
		}

		h3 {
			text-decoration: underline;
			text-transform: uppercase;
		}

		.datatables {
			border-collapse: collapse;
			width: 100%;
		}

		.row {
			background-color: #008acc;
			color: white;
		}

		td,
		th {
			padding: 6px 10px;
		}

		.montant {
			text-align: right;
		}

		h3 {
			border: 2px solid black;
			padding: 6px;
			color: black;
			text-decoration: none;
		}

		.footer {
			background: #dbeef7;
		}

		.header {
			margin-bottom: 6px;
			color: #666;
			font-size: 11pt;
		}
	</style>
</head>

<body style="width:950px;margin:auto;">

	<?php if (isset($_POST['dd'])) { ?>
		<h3 align="center"> Etat des commandes vendeurs du <?php echo dateFormat($_POST['dd']); ?> a <?php echo dateFormat($_POST['df']); ?> .</h3>

		<?php
		$total_montant = 0;
		$total_tht = 0;
		$total_avance = 0;

		$boncommandevendeur = new boncommandevendeur();

		if (isset($_POST['vendeur']) && $_POST['vendeur'] != '') {
			$query = "SELECT t1.*,t2.avance from 
	   (select c.etat, c.id_bon,c.numbon,DATE_FORMAT(c.date_bon,'%d-%m-%Y')as date_bon,cl.nom  as client
		   ,c.remarque  ,sum(dc.prix_produit*dc.qte_vendu*(1-dc.remise/100)) as montantv ,
		  sum(dc.prix_produit*(if(dc.valunit=0,dc.qte_vendu,dc.valunit))*(1-dc.remise/100) )as motunitv from boncommandevendeur c 
	   left join utilisateur cl on cl.id = c.id_vendeur 
	   inner join detail_bon_vendeur dc on dc.id_bon= c.id_bon 
	   inner join produit p on dc.id_produit=p.id_produit
	   where cl.id= " . $_POST['vendeur'] . " AND  DATE_FORMAT(c.date_bon,'%Y-%m-%d') BETWEEN '" . $_POST['dd'] . "' AND '" . $_POST['df'] . "'
	   group by  c.id_bon  order by id_bon desc  ) as t1 
	   left join (select id_bon,ifnull(sum(montant),0) as avance 
	   from reg_commande group by id_bon ) as t2 
	   on t2.id_bon=t1.id_bon ";
		} else {
			$query = "SELECT t1.*,t2.avance from 
	   (select c.etat, c.id_bon,c.numbon,DATE_FORMAT(c.date_bon,'%d-%m-%Y')as date_bon,cl.nom  as client
		   ,c.remarque  ,sum(dc.prix_produit*dc.qte_vendu*(1-dc.remise/100)) as montantv ,
		  sum(dc.prix_produit*(if(dc.valunit=0,dc.qte_vendu,dc.valunit))*(1-dc.remise/100) )as motunitv from boncommandevendeur c 
	   left join utilisateur cl on cl.id = c.id_vendeur 
	   inner join detail_bon_vendeur dc on dc.id_bon= c.id_bon 
	   inner join produit p on dc.id_produit=p.id_produit
	   where  DATE_FORMAT(c.date_bon,'%Y-%m-%d') BETWEEN '" . $_POST['dd'] . "' AND '" . $_POST['df'] . "'
	   group by  c.id_bon  order by id_bon desc  ) as t1 
	   left join (select id_bon,ifnull(sum(montant),0) as avance 
	   from reg_commande group by id_bon ) as t2 
	   on t2.id_bon=t1.id_bon ";
		}
		$data = connexion::getConnexion()->query($query)->fetchAll(PDO::FETCH_OBJ);
		?>


		<table class="datatables" border=1>
			<tr class="row">
				<th width="13%"> Num</th>
				<th width="13%"> Vendeur</th>
				<th width="13%"> Date </th>
				<th width="14%"> Total commandé </th>
				<th width="15%"> Total retourné </th>
				<th width="15%"> Total réglé</th>
			</tr>
			<?php
			$total_c = 0;
			$total_r = 0;
			$total_rg = 0;

			foreach ($data as $rep) {
				//get total commande
				$total_cmd = connexion::getConnexion()
					->query("SELECT SUM(qte_vendu * prix_produit) FROM detail_bon_vendeur WHERE id_bon = " . $rep->id_bon)
					->fetch(PDO::FETCH_COLUMN);

				//get total retour
				$total_retour = connexion::getConnexion()
					->query("SELECT SUM(qte_actuel * prix_produit) 
	FROM detail_bon_vendeur  dc
	LEFT JOIN boncommandevendeur bc ON bc.id_bon = dc.id_bon 
	WHERE bc.etat = 'Retour'
	AND bc.id_bon = " . $rep->id_bon)
					->fetch(PDO::FETCH_COLUMN);

				//get total reg
				$total_reg = connexion::getConnexion()
					->query("SELECT SUM(montant) FROM reg_vendeur 
	WHERE id_bon = " . $rep->id_bon)
					->fetch(PDO::FETCH_COLUMN);

				$total_c += $total_cmd;
				$total_r += $total_retour;
				$total_rg += $total_reg;
			?>
				<tr>
					<th width="13%"><?php echo $rep->id_bon ?></th>
					<th width="13%"><?php echo $rep->client ?></th>
					<td width="13%" align="center"><?php echo $rep->date_bon; ?></td>
					<td width="14%" align="right">
						<?php echo number_format($total_cmd, 2) ?>
					</td>
					<td width="15%" class="montant">
						<?php echo number_format($total_retour, 2) ?>
					</td>
					<td width="15%" class="montant">
						<?php echo number_format($total_reg, 2) ?>
					</td>
				</tr>
			<?php } ?>
		</table>
		<br />


		<table class="datatables" border=1>
			<tr class="row">
				<th width="32%" colspan="2">Total G&eacute;n&eacute;rale </th>
				<th width="17%" class="montant"> <?php echo number_format(($total_c), 2, '.', ' '); ?></th>
				<th width="17%" class="montant"> <?php echo number_format($total_r, 2, '.', ' '); ?></th>
				<th width="17%" class="montant"> <?php echo number_format($total_rg, 2, '.', ' '); ?> </th>
			</tr>
		</table>

	<?php
	} else {
		include("form_date_vendeur.php");
	}
	?>
</body>

</html>