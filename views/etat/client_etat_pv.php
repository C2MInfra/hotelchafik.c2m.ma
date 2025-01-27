<?php
include("../../evr.php");
if (!isset($_GET["id"])) {
  header("Location:../client/index.php");
}

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
      $is_all = true;
    }
    if ($_POST['df'] == "") {
      $_POST['df'] = '2100-12-31';
      $is_all = true;
    }



    $extra_filters1 = "";

    if (isset($_POST['nom']) && $_POST['nom'] != '') {
      $extra_filters1 = $extra_filters1 . " AND produit.designation LIKE '%" . $_POST['nom'] . "%'";
    }

    if (isset($_POST['code_bar']) && $_POST['code_bar'] != '') {
      $extra_filters1 = $extra_filters1 . " AND produit.code_bar = " . $_POST['code_bar'];
    }

    if (isset($_POST['categorie']) && $_POST['categorie'] != '') {
      $extra_filters1 = $extra_filters1 . " AND produit.id_categorie = " . $_POST['categorie'];
    }


    $client = connexion::getConnexion()->query("SELECT * FROM client WHERE id_client = " . $_GET['id'])->fetch(PDO::FETCH_OBJ);
    $ventes_id = connexion::getConnexion()->query("SELECT id_vente FROM vente where id_client = " . $_GET['id'] . " AND date_vente BETWEEN '" . $_POST['dd'] . "' AND '" . $_POST['df'] . "'")->fetchAll(PDO::FETCH_OBJ);


    if (!$ventes_id) {
      die("Aucune vente pour ce client");
    }



    $ventes_string = "";

    foreach ($ventes_id as $id) {
      $ventes_string = $ventes_string . $id->id_vente . ', ';
    }


    $ventes_string = substr_replace($ventes_string, "", -2);


    $date_d = $_POST['dd'];
    $date_f = $_POST['df'];


    $query = "SELECT id_detail,id_vente,produit.id_produit,produit.designation,produit.code_bar,prix_produit ,SUM(detail_vente.qte_vendu) / COUNT(detail_vente.id_produit) AS vendus , SUM(tv.qte_vendu) AS qte_vendu_prod, qte_restante AS restantes , SUM(detail_vente.prix_produit * detail_vente.qte_vendu) as total_mt , SUM(detail_vente.prix_produit * qte_restante) as total_reatant   FROM `detail_vente` LEFT JOIN produit ON detail_vente.id_produit = produit.id_produit LEFT JOIN tracking_vente tv ON produit.code_bar=tv.code_bar WHERE id_vente IN  (" . $ventes_string . ") AND tv.date_vente BETWEEN '$date_d' AND '$date_f' " . $extra_filters1 . " GROUP BY id_produit";


    $details = connexion::getConnexion()->query($query)->fetchAll(PDO::FETCH_OBJ);

    $cur_client = connexion::getConnexion()->query("SELECT * FROM client WHERE id_client = " . $_GET['id'])->fetch(PDO::FETCH_OBJ);


    $nom_client = $cur_client->nom . " " . $cur_client->prenom;


  ?>

    <h2 align="center"><?php echo $nom_client ?></h2>
    <?php
    if ($is_all) {
    ?>


      <h3 align="center" style="border: 2px solid black;
    padding: 6px; color:black;"> Etat des ventes GLOBAL </span>
      </h3>

    <?php
    } else {

    ?>
      <h3 align="center" style="border: 2px solid black;
    padding: 6px; color:black;"> Etat des ventes du <span class="date"><?php echo $_POST['dd']; ?></span> A <span class="date"><?php echo $_POST['df']; ?></span>
      </h3>
    <?php

    }

    ?>
    <h3 align="center" style="
    padding: 6px; color:black;"><span class="date"><?php echo $res["nom"] . " " . $res["prenom"] ?></span>
    </h3>
    <table class="datatables" border=1 style="border-style:none;">
      <tr class="row">
        <th scope="col">Ref</th>
        <th scope="col">Produit</th>
        <th scope="col">vendus</th>
        <th scope="col">MT vendus</th>
        <th scope="col">Restants</th>
        <th scope="col">MT Restants</th>
        <th scope="col">QTE Vendu </th>
      </tr>


      <?php

      foreach ($details as $det) {
      ?>

        <tr>
          <td>
            <?php echo $det->code_bar ?>
          </td>

          <td>
            <?php echo $det->designation ?>
          </td>


          <td align="right">
            <?php echo $det->vendus ?>
          </td>


          <td align="right">
            <?php echo number_format($det->total_mt, 2, '.', ',') ?>
          </td>
          <td align="right">
            <?php echo $det->restantes ?>
          </td>
          <td align="right">
            <?php echo number_format($det->total_reatant, 2, '.', '.') ?>
          </td>


          <td align="right">
            <?php echo $det->qte_vendu_prod ?>
          </td>

        </tr>

      <?php
      }

      ?>

    </table>
    <br />
    <br />




    <br>
    <br>


    <br><br><br>
  <?php
  } else {
    include("form_date_ventes_pv.php");
  } ?>

  </center>
</body>

</html>