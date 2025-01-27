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
    connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel - " . $_POST["qte_vendu"] . " WHERE  id_produit =" . $_POST["id_produit"]);

    $_POST["id_vente"] = $_POST["id"];
    $_POST["id_user"] = auth::user()["id"];
    $_POST["prix_produit"] = trim($_POST["prix_produit"]);



    $detail_vente = new detail_vente();

    $detail_vente->insert();

    connexion::getConnexion()->exec("UPDATE detail_vente SET qte_restante=qte_restante + qte_vendu WHERE id_vente = " . $_POST["id_vente"]);
  }
  die("success");
} elseif ($_POST['act'] == 'update') {
} elseif ($_POST['act'] == 'delete') {
  try {


    $detail_vente = new detail_vente();
    $dv = $detail_vente->selectById($_POST['id']);
    connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel + " . $dv["qte_vendu"] . " WHERE  id_produit =" . $dv["id_produit"]);
    $detail_vente->delete($_POST["id"]);
    die('success');
  } catch (Exception $e) {
    die($e);
  }
} elseif ($_POST['act'] == 'getPrix') {
  $produit = new produit();
  $ligne = $produit->selectById($_POST['id_produit']);
  echo  $ligne['prix_vente'] . "/" . $ligne['qte_actuel'];
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
} elseif ($_POST['act'] == 'update_detail') {

  $_POST['unit'] = trim($_POST['unit']);
  $_POST['valunit'] = trim($_POST['valunit']);

  $detail_vente = new detail_vente();

  $dv = $detail_vente->selectById($_POST['id_detail']);

  $qte_diff = $dv['qte_vendu'] - $_POST['qte_vendu'];
  //$_POST['qte_vendu'] = $_POST['qte_venduh'] - $_POST['qte_vendu'];

  $rs = $detail_vente->update($_POST['id_detail']);

  $produit = new produit();

  connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel + (" . $qte_diff . ") WHERE  id_produit =" . $dv["id_produit"]);

  $totales = $detail_vente->gettotale($_POST['id_vente']);

  $totalee = 0;

  foreach ($totales as $totale) {
    if (!empty($totale['valunit']) || $totale['valunit'] != 0) {
      $totalee += $totale['valunit'] * $totale['prix_produit'] * $totale['remise'];
    } else {
      $totalee += $totale['qte_vendu'] * $totale['prix_produit'] * $totale['remise'];
    }
  }
  die(number_format($totalee, 2, '.', ''));
}

?>
