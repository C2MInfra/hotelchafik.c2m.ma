<?php
include("../../evr.php");
if (!isset($_GET["id"])) {
  header("Location:../fournisseur/index.php");
}

function dateFormat($dat)
{
  $date = new DateTime($dat);
  return $date->format('d-m-Y');
}

function dateFormat1($dat)
{
  $date = new DateTime($dat);
  return $date->format('Y-m-d');
}


$poids = 0;
$qte = 0;
function design_ar($design, $design_ar)
{
  if ($design == '' && $design_ar != '') {
    echo $design_ar;
  }

  if ($design != '' && $design_ar != '') {
    echo $design_ar . " /";
  }
}

$fournisseur = new fournisseur();
$res = $fournisseur->selectById($_GET["id"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional// EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title></title>
  <style type="text/css">
    .row {
      background-color: #28a745 !important;
      color: white;
    }

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
      text-transform: uppercase;
      color: #666;
    }

    .datatables {
      border-collapse: collapse;
      width: 100%;
    }

    .row {
      background-color: #CCCCCC;
    }

    .montant {
      text-align: right;
    }

    .date {
      color: #28a745;
    }

    th,
    td {
      padding: 6px;
    }
  </style>
</head>

<body style="width:950px;margin:auto;">

  <?php if (isset($_POST['submit'])) {


    if ($_POST['dd'] == "") {
      $_POST['dd'] = '2000-01-01';
    }
    if ($_POST['df'] == "") {
      $_POST['df'] = '2100-12-31';
    }


    $data = connexion::getConnexion()->query("select a.remarque, a.id_achat,a.date_achat, sum(da.prix_produit*da.qte_achete)as
 montant from achat a left join  fournisseur f on  f.id_fournisseur=a.id_fournisseur left join detail_achat da on da.id_achat=a.id_achat where f.id_fournisseur=" . $_GET["id"] . "
and  (a.date_achat  between '" . $_POST["dd"] . "' and '" . $_POST["df"] . "') group by  a.id_achat  order by date_achat asc");



  ?>


    <h3 align="center" style="border: 2px solid black;
    padding: 6px; color:black;">Liste des Achat du <span class="date"><?php echo dateFormat($_POST['dd']); ?></span> A <span class="date"><?php echo dateFormat($_POST['df']); ?></span>
    </h3>
    <h3 align="center" style="
    padding: 6px; color:black;"><span class="date"><?php echo $res["raison_sociale"] ?></span>
      <?php
      $sum_achats = 0;
      $sum_regs = 0;
      ?>
      <table class="datatables" border=1 style="border-style:none;">
        <tr class="row">
          <th style="width: fit-content;" scope="col">DATE</th>
          <th scope="col">DESIGNATION</th>
          <th width="10%" scope="col">QTE</th>
          <th width="10%" scope="col">PU</th>
          <th width="10%" scope="col">MT</th>
          <th scope="col">Restante</th>
          <th scope="col">Prix Res</th>
          <th scope="col">OBSERVATION</th>
        </tr>
        <?php foreach ($data as $ligne) {   ?>

          <?php
          $data2 = connexion::getConnexion()->query("SELECT da.id_detail,p.id_produit,p.qte_actuel,p.designation,p.designation_ar,p.poid,da.prix_produit,da.qte_achete  FROM   detail_achat da 
  left join produit p on (p.id_produit=da.id_produit) where da.id_achat=" . $ligne['id_achat'] . " order by da.id_detail desc");

          $query = $result = connexion::getConnexion()->query("select  sum(ra.montant) as paye from achat a left join reg_achat ra on ra.id_achat=a.id_achat where a.id_achat=" . $ligne["id_achat"]);

          $result = $query->fetch(PDO::FETCH_OBJ);
          $paye = $result->paye;

          $total_mt = 0;
          $total_prix_res = 0;

          ?>
          <?php foreach ($data2 as $ligne2) {
            $total_mt += $ligne2["qte_achete"] * $ligne2["prix_produit"];
            $total_prix_res += $ligne2['qte_actuel'] * $ligne2['prix_produit'];

          ?>
            <tr>
              <td align="left" style="font-size: 16px"><?php echo $ligne['date_achat'] ?></td>
              <td align="left" style="font-size: 16px" scope="col"><?php echo $ligne2["designation"] ?></td>

              <td align="right" style="font-size: 16px " scope="col"><?php echo number_format($ligne['qte_achete'], 2, ',', '.');
                                                                      $qte += $ligne2["qte_achete"] ?> &nbsp;&nbsp;&nbsp;&nbsp; </td>

              <td align="right" style="font-size: 16px" scope="col"><?php echo number_format($ligne['prix_produit'], 2, ',', '.') ?>&nbsp;&nbsp;</td>

              <td align="right" style="font-size: 16px" scope="col"><?php
                                                                    $sum_achats += $ligne2["qte_achete"] * $ligne2["prix_produit"];
                                                                    echo number_format($ligne2["qte_achete"] * $ligne2["prix_produit"], 2, '.', ' '); ?></td>

              <td align="right" style="font-size: 16px"><?php echo number_format($ligne['qte_actuel'], 2, ',', '.') ?></td>
              <td align="right" style="font-size: 16px"><?php echo number_format($ligne2['qte_actuel'] * $ligne2['prix_produit'], 2, ',', '.') ?></td>
              <td align="left" style="font-size: 16px"><?php echo number_format($ligne['remarque'], 2, ',', '.') ?></td>



            </tr>
          <?php } ?>


          <tr>
            <th style="font-size: 16px;">Total : </th>
            <th></th>
            <th></th>
            <th></th>
            <th align="right" style="font-size: 16px"><?php echo number_format($total_mt, 2, ',', '.') ?></th>
            <th></th>
            <th align="right" style="font-size: 16px"><?php echo number_format($total_prix_res, 2, ',', '.') ?></th>
            <th></th>
          </tr>

        <?php } ?>
      </table>
      <br />
      <br />

      <!-- Réglements -->

      <?php
      $query = "SELECT ra.* FROM achat a LEFT JOIN reg_achat ra ON ra.id_achat = a.id_achat WHERE a.id_fournisseur=" . $_GET["id"] . " 
     AND  (ra.date_reg  between '" . $_POST["dd"] . "' 
     AND '" . $_POST["df"] . "') 
     AND ra.montant IS NOT NULL
     ORDER BY ra.date_reg asc";

      $regs = connexion::getConnexion()->query($query)->fetchAll(PDO::FETCH_OBJ);
      ?>

      <h3 align="left">Les réglements</h3>
      <table class="datatables" border=1 style="border-style:none;">
        <tr class="row">
          <th scope="col">DATE</th>
          <th scope="col">MODE</th>
          <th scope="col">NUMERO</th>
          <th scope="col">OBSERVATION</th>
          <th scope="col">MONTANT</th>
        </tr>

        <?php foreach ($regs as $reg) : ?>
          <tr>
            <td><?php echo $reg->date_reg ?></td>
            <td><?php echo $reg->mode_reg ?></td>
            <td><?php echo ($reg->num_cheque != '') ? $reg->num_cheque : '_' ?></td>
            <td><?php echo $reg->remarque ?></td>
            <td align="right">
              <?php
              $sum_regs += $reg->montant;
              echo $reg->montant ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
      <!-- Fin Réglements -->
      <?php
      //last reg
      $query = "select SUM(ra.montant) from achat a left join reg_achat ra ON ra.id_achat = a.id_achat where  a.id_fournisseur=" . $_GET["id"] . " AND ra.date_reg < '" . $_POST["dd"] . "' AND ra.montant IS NOT NULL";

      $last_totalr = connexion::getConnexion()->query($query)->fetch(PDO::FETCH_COLUMN);

      $last_totalr = ($last_totalr == null) ? 0 : $last_totalr;

      //last reg

      $query = "select SUM(da.prix_produit * da.qte_achete) AS Total from achat a left join detail_achat da ON da.id_achat = a.id_achat left join produit p ON p.id_produit = da.id_produit where a.id_fournisseur=" . $_GET["id"] . " AND a.date_achat < '" . $_POST["dd"] . "'";

      $last_totalv = connexion::getConnexion()->query($query)->fetch(PDO::FETCH_COLUMN);
      $last_totalv = ($last_totalv == null) ? 0 : $last_totalv;

      $last_r = $last_totalv - $last_totalr;
      ?>
      <br>
      <br>
      <table class="datatables" border=1 style="border-style:none; width:30%;">
        <tr>
          <td width="50%" style="background-color:#28a745; color:white;">Reste précédent</td>
          <td align="right">
            <?php echo number_format($last_r, 2) ?>
          </td>
        </tr>
        <tr>
          <td width="50%" style="background-color:#28a745; color:white;">Total achats</td>
          <td align="right">
            <?php echo number_format($sum_achats, 2) ?>
          </td>
        </tr>
        <tr>
          <td style="background-color:#28a745; color:white;">Total Reg</td>
          <td align="right">
            <?php echo number_format($sum_regs, 2) ?>
          </td>
        </tr>
        <tr>
          <td style="background-color:#28a745; color:white;">Reste</td>
          <td align="right">
            <?php echo number_format($last_r + $sum_achats - $sum_regs, 2) ?>
          </td>
        </tr>
      </table>
      <br><br><br>

    <?php
  } else {
    include("form_date.php");
  } ?>

</body>

</html>