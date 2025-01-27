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
  $detail_detail_achat = new detail_detail_achat();

  if (isset($_POST['id_detail'])) {
    $detail_detail_achat->delete($_POST['id_detail']);
  }


  $data = $detail_detail_achat->selectAllNonValide();
  $total = 0;
  foreach ($data as $ligne) {
  ?>
    <tr>

      <td><?php echo $ligne->designation; ?></td>
      <td><?php echo $ligne->prix_produit; ?></td>
      <td><?php echo $ligne->qte_achete; ?></td>

      <td><?php echo $ligne->poid * $ligne->qte_achete; ?> g </td>
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
    <td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right;"><?php echo number_format($total, 2, '.', ' '); ?></b></td>

  </tr>
  <?php
} elseif ($_POST['act'] == 'addProduct') {
  if (!isset($_SESSION['rand_a_er']) || $_SESSION['rand_a_er'] === "") {
    $_SESSION['rand_a_er'] = rand(10, 1000);
  }
  $_POST["id_user"] = auth::user()["id"];

  $somme_poid = 0;
  $_POST["id_detail_achat"] = "-1" . $_SESSION['rand_a_er'];
  $detail_detail_achat = new detail_detail_achat();
  $detail_detail_achat->insert();
  $data = $detail_detail_achat->selectAllNonValide();
  $total = 0;

  foreach ($data as $ligne) {

  ?>
    <tr>
      <td><?php echo $ligne->designation; ?></td>
      <td><?php echo $ligne->prix_produit; ?></td>
      <td><?php echo $ligne->qte_achete; ?></td>
      <td><?php echo $ligne->poid * $ligne->qte_achete;
          $somme_poid += $ligne->poid * $ligne->qte_achete;
          ?> g </td>
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
    <td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right;"><?php echo number_format($total, 2, '.', ' '); ?></b></td>

  </tr>
<?php
} elseif ($_POST['act'] == 'insert') {
  if (isset($_POST["id_produit"])) {
    $_POST["id_achat"] = $_POST["id"];
    $_POST["id_user"] = auth::user()["id"];
    $_POST["prix_produit"] = trim($_POST["prix_produit"]);
    $detail_achat = new detail_achat();

    $detail_achat->insert();

    connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel+" . $_POST["qte_achete"] . " WHERE  id_produit =" . $_POST["id_produit"]);
  }
  die("success");
} elseif ($_POST['act'] == 'update') {
} elseif ($_POST['act'] == 'delete') {
  try {


    $detail_achat = new detail_achat();


    $qte_to_restore = connexion::getConnexion()->query("select qte_achete from detail_achat where id_detail= " . $_POST['id'])->fetchColumn(); 


    connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel+" . (int)$qte_to_restore . " WHERE  id_produit =" . $_POST["id_produit"]);

    $detail_achat->delete($_POST["id"]);

    die('success');
  } catch (Exception $e) {
    die($e);
  }
} elseif ($_POST['act'] == 'getPrix') {
  $produit = new produit();
  $ligne = $produit->selectById($_POST['id_produit']);
  echo  $ligne['prix_achat'] . "/" . $ligne['qte_actuel'];
} elseif ($_POST['act'] == 'update_detail') {

  $detail_achat = new detail_achat();

  $new_qte = $_POST['qte_achete'];

  $old_qte = $_POST['qte_acheteh'];

  if ((int)$new_qte > (int)$old_qte) {
    connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel-" . ($new_qte - $old_qte) . " WHERE  id_produit =" . $_POST["id_produit"]);
  } else {
    connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel+" . ($old_qte - $new_qte) . " WHERE  id_produit =" . $_POST["id_produit"]);
  }




  $detail_achat->update($_POST['id_detail']);
  $produit = new produit();



  $totale = $detail_achat->gettotale($_POST['id_achat']);

  die(number_format($totale['totale'], 2, '.', ''));
}
?>
