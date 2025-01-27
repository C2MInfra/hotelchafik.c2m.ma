<?php

include('../../evr.php');

if ($_POST['act'] == 'retour_bon') {
  //produit
  $detail_bon_vendeur = new detail_bon_vendeur();
  $data = $detail_bon_vendeur->selectAllValide($_POST['id']);

  foreach ($data as $key => $ligne) {
    $id_produit = $ligne->id_produit;
    $id_depot = $_POST['id_depots'][$key];
    $qte = $ligne->qte_actuel;





    $produitdepot = connexion::getConnexion()->query("SELECT * FROM produit_depot WHERE id_produit = $id_produit AND id_depot = $id_depot")->fetch(PDO::FETCH_OBJ);

    if ($produitdepot) {
      $query = "UPDATE produit_depot SET qte = qte + $qte WHERE id = " . $produitdepot->id;
      connexion::getConnexion()->exec($query);
    } else {
      $query = "INSERT INTO produit_depot(id_produit, id_depot, qte) VALUES($id_produit, $id_depot, $qte)";
      connexion::getConnexion()->exec($query);
    }

    $query = "UPDATE produit SET qte_actuel = qte_actuel + $qte WHERE id_produit = " . $id_produit;
    connexion::getConnexion()->exec($query);
  }

  //update etat bon
  $query = "UPDATE boncommandevendeur SET etat = 'Retour' WHERE id_bon = " . $_POST['id'];

  connexion::getConnexion()->exec($query);
  die('success');
} elseif ($_POST['act'] == 'filter') {

  $boncommandevendeur = new boncommandevendeur();

  if ($_POST['anne'] != 0) {

    $data = $boncommandevendeur->selectAll3($_POST['anne'] . "-" . $_POST['mois']);
  }
  if ($_POST['anne'] == 0)
    $data = $boncommandevendeur->selectAll3all();

?>
  <table class="table  responsive table-striped table-bordered table-hover" id="datatables">

    <thead>

      <tr>
        <th scope="col">Id</th>

        <th scope="col">Client</th>

        <th class="nowrap"> Date</th>

        <th scope="col"> Montant</th>

        <th scope="col"> Reste</th>

        <th scope="col"> Remarque</th>

        <th scope="col">Actions</th>

      </tr>

    </thead>

    <tbody>

      <?php

      foreach ($data as $ligne) {
      ?>

        <tr>

          <td> <?php echo $ligne->id_bon; ?></td>

          <td class="nowrap">

            <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->client;

                                                                                                                                                        if ($ligne->nom_prenom_ar != "" && $ligne->client == " ") {

                                                                                                                                                          echo $ligne->nom_prenom_ar;
                                                                                                                                                        }

                                                                                                                                                        if ($ligne->nom_prenom_ar != "" && $ligne->client != " ") {

                                                                                                                                                          echo "/" . $ligne->nom_prenom_ar;
                                                                                                                                                        }

                                                                                                                                                        ?> </a>
          </td>

          <td>
            <?php echo $ligne->date_bon; ?>
          </td>
          <td style="text-align: right;" class="nowrap" data-href="#">

            <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>">
              <?php
              if ($ligne->motunitv != 0 || !empty($ligne->motunitv)) {
                echo number_format($ligne->motunitv, 2, '.', ' ');
              } else {
                echo number_format($ligne->montantv, 2, '.', ' ');
              }
              ?>
            </a>
            &nbsp;&nbsp;

          </td>

          <td style="text-align: right;"> <?php

                                          $query = $result = connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_commande where id_bon=" . $ligne->id_bon);

                                          $result = $query->fetch(PDO::FETCH_OBJ);

                                          $paye = $result->paye != null  ?  $result->paye : 0;
                                          if ($ligne->motunitv != 0 || !empty($ligne->motunitv)) {
                                            $tr = $ligne->motunitv - $paye;
                                          } else {
                                            $tr = $ligne->montantv - $paye;
                                          }

                                          $tr = ($tr < 0 && $tr >= -250) ? 0 : $tr;
                                          echo number_format($tr, 2, '.', ' ');
                                          ?> &nbsp;&nbsp;

          </td>
          <td> <?php echo strlen($ligne->remarque) > 50 ? substr($ligne->remarque, 0, 50) . "..." : $ligne->remarque; ?> </td>

          <td class="nowrap">

            <?php if (auth::user()['privilege'] == 'Admin') { ?>

              <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>

                <i class="simple-icon-trash" style="font-size: 15px;"></i>

              </a>

              <a class="badge badge-success mb-2  url notlink" data-url="reg_vendeur/index.php?id=<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)'>
                <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>
              </a>

              <a class="badge badge-warning mb-2  url notlink" data-url="commande-vendeurs/update.php?id=<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="Modifier" href="javascript:void(0)">

                <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>

              </a>
            <?php } ?>


            <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL . "views/commande-vendeurs/facture.php?id=" . $ligne->id_bon; ?>&h=15" target="_black">

              <i class=" simple-icon-printer" style="font-size: 15px;"></i>

            </a>


            <?php

            $id_vente = connexion::getConnexion()->query("SELECT id_vente FROM vente WHERE id_bon = " . $ligne->id_bon)->fetchColumn();

            ?>

            <a class="badge badge-primary mb-2 notlink" style="background-color: #d322e8!important;color: white;cursor: pointer;" title="Ticket" href='<?php echo BASE_URL . '/views/vente/ticket.php?id=' . $id_vente; ?>' target="_black">

              <i class=" iconsmind-Billing" style="font-size: 15px;"></i>

            </a>


            <a class="badge badge-secondary mb-2 url notlink" data-url="detail_bon_vendeur/index.php?id=<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">
              <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>
            </a>



            <?php if ($ligne->etat == 'En cours' && auth::user()['privilege'] == 'Admin') : ?>
              <a class="badge badge-danger  url notlink mb-2" data-url="<?php echo 'commande-vendeurs/retour_bon.php?id=' . $ligne->id_bon ?>" href="javascript:void(0)" style="color: white;cursor: pointer;" title="Retour" href='javascript:void(0)'>
                <i class="fa-solid fa-arrow-rotate-left" style="font-size: 15px;"></i>
              </a>
            <?php endif; ?>

            <?php if ($ligne->etat == 'En cours' && auth::user()['privilege'] == 'Vendeur') : ?>
              <a class="badge badge-warning mb-2 " style="color: white;cursor: pointer;" title="Vente" href='<?php echo BASE_URL ?>vente_bon/index.php?bon=<?php echo $ligne->id_bon ?>'>
                <i class="iconsmind-Add-Cart" style="font-size: 15px;"></i>
              </a>
            <?php endif; ?>
            <a class="badge badge-success" href='<?php echo BASE_URL ?>commande-vendeurs/map.php?id=<?php echo $ligne->id_bon ?>' style="font-size:10pt;">
              <i class="fa-solid fa-map-location-dot"></i>
            </a>
          </td>


        </tr>

      <?php } ?>

    </tbody>

  </table>

<?php



}

if ($_POST['act'] == 'filterbon') {

  $vente = new vente();

  if ($_POST['anne'] != 0) {
    $date_format = $_POST['anne'] . "-" . (($_POST['mois'] < 10) ? '0' . $_POST['mois'] : $_POST['mois']);
    $data = $vente->selectallbond($date_format);
  } else {
    $date_format = (($_POST['mois'] < 10) ? '0' . $_POST['mois'] : $_POST['mois']);
    $data = $vente->selectallbond($date_format);
  }



?>
  <?php
  #custom

  #endcustom

  ?>
  <table class="table  responsive table-striped table-bordered table-hover" id="datatables">

    <thead>

      <tr>

        <th scope="col">Id</th>

        <th scope="col">Cliet</th>

        <th> Date</th>

        <th scope="col"> Montant</th>

        <th scope="col"> Reste</th>

        <th scope="col"> remarque</th>

        <th scope="col">Actions</th>

      </tr>

    </thead>

    <tbody>

      <?php

      foreach ($data as $ligne) {

        $query = $result = connexion::getConnexion()->query("SELECT count(*) as nbr,id_facture FROM facture where id_vente like '%" . $ligne->id_vente . "%'");

        $result = $query->fetch(PDO::FETCH_OBJ);

        $id_facture = $result->id_facture;

        $nbr = $result->nbr;

      ?>

        <tr>

          <td> <?php echo $ligne->numbon; ?></td>

          <td class="nowrap">

            <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->client;

                                                                                                                                                        if ($ligne->nom_prenom_ar != "" && $ligne->client == " ") {

                                                                                                                                                          echo $ligne->nom_prenom_ar;
                                                                                                                                                        }

                                                                                                                                                        if ($ligne->nom_prenom_ar != "" && $ligne->client != " ") {

                                                                                                                                                          echo "/" . $ligne->nom_prenom_ar;
                                                                                                                                                        }

                                                                                                                                                        ?> </a>
          </td>

          <td class="nowrap"><?php echo $ligne->date_vente; ?> </td>

          <td class="nowrap" style="text-align: right;"> <?php echo number_format($ligne->montantv, 2, '.', ' '); ?>

            &nbsp;&nbsp;

          </td>

          <td class="nowrap" style="text-align: right;"> <?php

                                                          $query = $result = connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_vente where id_vente=" . $ligne->id_vente);

                                                          $result = $query->fetch(PDO::FETCH_OBJ);

                                                          $paye = $result->paye != null  ?  $result->paye : 0;

                                                          echo number_format($ligne->montantv - $paye, 2, '.', ' ');

                                                          ?> &nbsp;&nbsp;

          </td>

          <td> <?php echo strlen($ligne->remarquebon) > 50 ? substr($ligne->remarquebon, 0, 50) . "..." : $ligne->remarquebon; ?> </td>

          <td class="nowrap">

            <?php if (auth::user()['privilege'] == 'Admin') { ?>

              <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>

                <i class="simple-icon-trash" style="font-size: 15px;"></i>

              </a>

              <a class="badge badge-warning mb-2  url notlink" data-url="vente/updatebon.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Modifier" href='javascript:void(0)'>

                <i class="iconsmind-Pen-5" style="font-size: 15px;"></i>

              </a>

              <a class="badge badge-success mb-2  url notlink" data-url="reg_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)'>

                <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>

              </a>

              <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL . "views/vente/facturebon.php?id=" . $ligne->id_vente; ?>&h=15" target="_black">

                <i class=" simple-icon-printer" style="font-size: 15px;"></i>

              </a>

              <a class="badge badge-secondary mb-2 url notlink" data-url="detail_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">



                <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>

              </a>
              <?php if (auth::user()['privilege'] == 'Admin') : ?>

                <a class="badge badge-secondary mb-2  " target="_blank" style="color: white;cursor: pointer;" title="Cmd Founisseur" href="<?php echo BASE_URL ?>/views/etat/commande_vendeur_fourniss.php?id=<?php echo $ligne->id_bon; ?>">
                  <i class=" simple-icon-printer" style="font-size: 15px;"></i>

                </a>
              <?php endif; ?>







              <?php if ($nbr == 0 && $ligne->numbon != 0) { ?>

                <a class="badge badge-primary mb-2 url notlink" style="color: white;cursor: pointer;" title="Facture" data-url="<?php echo "facture/add.php?idv[]=" . $ligne->id_vente; ?>" href='javascript:void(0)'>

                  <i class=" iconsmind-Billing" style="font-size: 15px;"></i>

                </a>

              <?php } ?>

              <a class="badge badge-primary mb-2 notlink" style="background-color: #d322e8!important;color: white;cursor: pointer;" title="Ticket" href='<?php echo BASE_URL . '/views/vente/ticketbon.php?id=' . $ligne->id_vente; ?>' target="_black">

                <i class=" iconsmind-Billing" style="font-size: 15px;"></i>

              </a>

            <?php } ?>
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

  $detail_bon_vendeur = new detail_bon_vendeur();

  if (isset($_POST['id_detail'])) {


    $detail_bon_vendeur->delete($_POST['id_detail']);
  }

  $data = $detail_bon_vendeur->selectAllNonValide();

  $total = 0;

  foreach ($data as $ligne) {

  ?>

    <tr>



      <td><?php echo $ligne->designation; ?></td>

      <td><?php echo $ligne->prix_produit; ?></td>

      <td><?php echo $ligne->qte_vendu; ?></td>

      <td><?php echo $ligne->poid * $ligne->qte_vendu; ?> </td>

      <td width="90" style="text-align: right;">

        <?php echo number_format($ligne->qte_vendu * $ligne->prix_produit, 2, '.', ' ');

        if ($ligne->valunit != 0 || !empty($ligne->valunit)) {



          $total += ($ligne->valunit) * $ligne->prix_produit * (1 - $ligne->remise / 100);
        } else {


          $total += $ligne->qte_vendu * $ligne->prix_produit * (1 - $ligne->remise / 100);
        }

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

  if (!isset($_SESSION['rand_v_er']) || $_SESSION['rand_v_er'] === "") {

    $_SESSION['rand_v_er'] = rand(10, 1000);
  }

  $_POST["id_user"] = auth::user()["id"];
  $_POST['qte_actuel'] = $_POST['qte_vendu'];
  $somme_poid = 0;

  $_POST["id_bon"] = "-1" . $_SESSION['rand_v_er'];

  $detail_bon_vendeur = new detail_bon_vendeur();



  $query = "SELECT * FROM detail_bon_vendeur WHERE id_produit =" . $_POST['id_produit'] . " AND id_bon = " . $_POST['id_bon'];


  $result = connexion::getConnexion()->query($query)->fetch(PDO::FETCH_OBJ);




  if ($result->id_detail) {
    if ($_POST['prix_produit'] == 0) {
      $res = $detail_bon_vendeur->insert();
    } else {
      $query = "UPDATE detail_bon_vendeur SET qte_vendu = qte_vendu + " . $_POST['qte_vendu'] . "/2  , valunit=valunit +  " . $_POST['valunit'] . "/2 WHERE prix_produit != 0 AND id_bon = " . $_POST['id_bon'];
      connexion::getConnexion()->query($query)->execute();
    }
  } else {
    $res = $detail_bon_vendeur->insert();
  }



  $data = $detail_bon_vendeur->selectAllNonValide();

  $total = 0;

  foreach ($data as $ligne) {

  ?>

    <tr>

      <td><?php echo $ligne->designation; ?></td>

      <td><?php echo $ligne->prix_produit; ?></td>

      <td><?php
          if ($ligne->qte_vendu != null) {
            echo $ligne->qte_vendu;
          }


          ?></td>

      <td><?php
          if ($ligne->unit != null) {
            echo $ligne->valunit . ' ' .  $ligne->unit;
          }
          // $ligne->poid*$ligne->qte_vendu ;

          //                         $somme_poid+=$ligne->poid*$ligne->qte_vendu;

          ?> </td>

      <td width="90" style="text-align: right;">

        <?php
        if ($ligne->valunit != 0 || !empty($ligne->valunit)) {

          echo number_format(($ligne->valunit) * $ligne->prix_produit, 2, '.', ' ');

          $total += $ligne->valunit * $ligne->prix_produit * (1 - $ligne->remise / 100);
        } else {
          echo number_format($ligne->qte_vendu * $ligne->prix_produit, 2, '.', ' ');

          $total += $ligne->qte_vendu * $ligne->prix_produit * (1 - $ligne->remise / 100);
        }



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

    <td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right; metotal"><?php echo number_format($total, 2, '.', ' '); ?></b></td>



  </tr>

<?php


} elseif ($_POST['act'] == 'vrefPlafond') {
  $idv = connexion::getConnexion()->query("select id_vente from vente where id_client=" . $_POST['id_client'])->fetchAll(PDO::FETCH_ASSOC);
  $data = "";
  $reg = 0;
  $summonts = connexion::getConnexion()->query("SELECT SUM(r.montant) AS summont  FROM reg_vente r,vente v WHERE r.id_vente=v.id_vente AND v.id_client=" . $_POST['id_client'])->fetchAll(PDO::FETCH_ASSOC);
  foreach ($summonts as $summont) {
    if (!empty($summont['summont'])) {
      $reg += $summont['summont'];
    }
  }

  if (!empty($idv)) {
    $data = connexion::getConnexion()->query("SELECT 
    SUM(dt.prix_produit * dt.qte_vendu *(1-(dt.remise/100)))-" . $reg . " as montantTot,
    SUM(dt.prix_produit * dt.valunit *(1-(dt.remise/100)))-" . $reg . " as motunitv
    , c.* 
    from client c, vente v, detail_vente dt
    WHERE v.id_vente=dt.id_vente 
    AND c.id_client=v.id_client 
    AND v.numbon<>0 
    AND  c.id_client = " . $_POST['id_client'])->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $data = connexion::getConnexion()->query("select * from client where id_client=" . $_POST['id_client'])->fetchAll(PDO::FETCH_ASSOC);
  }


  echo json_encode($data);
} elseif ($_POST['act'] == 'insert') {
  $_POST["id_user"] = auth::user()["id"];

  $boncommandevendeur = new boncommandevendeur();

  $boncommandevendeur->insert();

  connexion::getConnexion()->exec("UPDATE detail_bon_vendeur  SET detail_bon_vendeur.id_bon =(SELECT max(boncommandevendeur.id_bon) FROM boncommandevendeur)   WHERE detail_bon_vendeur.id_bon = -1" . $_SESSION['rand_v_er']);

  $data = connexion::getConnexion()->query("SELECT * FROM detail_bon_vendeur  WHERE detail_bon_vendeur.id_bon =(SELECT max(boncommandevendeur.id_bon) FROM boncommandevendeur)")->fetchAll(PDO::FETCH_OBJ);

  foreach ($data as $item) {
    $query = "UPDATE produit_depot SET qte = qte - " . $item->qte_vendu . ' WHERE id_produit = ' . $item->id_produit . ' AND id_depot=' . $item->id_depot;

    connexion::getConnexion()->exec($query);



    $query = "UPDATE produit SET qte_actuel = qte_actuel - $item->qte_vendu WHERE id_produit = " . $item->id_produit;
    connexion::getConnexion()->exec($query);
  }

  unset($_SESSION['rand_v_er']);

  die("success");
} elseif ($_POST['act'] == 'update') {



  try {

    $_POST["idu"] = auth::user()["id"];

    $boncommandevendeur = new boncommandevendeur();

    $test = $boncommandevendeur->update($_POST["id"]);

    die('success');
  } catch (Exception $e) {

    die($e);
  }
} elseif ($_POST['act'] == 'delete') {

  try {


    $boncommandevendeur = new boncommandevendeur();

    $boncommandevendeur->delete($_POST["id"]);

    die('success');
  } catch (Exception $e) {

    die($e);
  }
} elseif ($_POST['act'] == 'getPrix') {

  $produit = new produit();

  $ligne = $produit->selectById($_POST['id_produit']);
  $prix_v = 0;

  $serch_cli = connexion::getConnexion()->query(
    '
SELECT dt.prix_produit from detail_vente dt 
where dt.id_detail= (SELECT MAX(dt1.id_detail) FROM detail_vente dt1, vente v 
WHERE dt1.id_vente=v.id_vente
AND v.id_client=' . $_POST['id_client'] . ' and id_produit=' . $_POST['id_produit'] . '
)'
  )->fetch(PDO::FETCH_ASSOC);
  if (empty($serch_cli['prix_produit'])) {
    $prix_v = $ligne['prix_vente'];
  } else {
    $prix_v = $serch_cli['prix_produit'];
  }

  echo  $prix_v . "/" . $ligne['qte_actuel'] . "/" . $ligne['unite2'];
} elseif ($_POST['act'] == 'getPrixSelect') {

  $produit = new produit();

  $ligne = $produit->selectById($_POST['id_produit']);


  $prix_v = $ligne[$_POST['prix_vente']];

  // $serch_cli=connexion::getConnexion()->query('
  // SELECT dt.prix_produit from detail_vente dt 
  // where dt.id_detail= (SELECT MAX(dt1.id_detail) FROM detail_vente dt1, vente v 
  // WHERE dt1.id_vente=v.id_vente
  // AND v.id_client='.$_POST['id_client'].' and id_produit='.$_POST['id_produit'].'
  // )'
  // )->fetch(PDO::FETCH_ASSOC);
  // if(empty($serch_cli['prix_produit'])){
  // $prix_v=$ligne['prix_vente'];
  // }else{
  // $prix_v=$serch_cli['prix_produit'];
  // }

  echo  $prix_v . "/" . $ligne['qte_actuel'] . "/" . $ligne['unite2'];
} elseif ($_POST['act'] == 'insertbon') {

  try {





    $data = vente::getdevis($_POST["id"]);

    foreach ($data as $value) {

      connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel - " . $value["qte_vendu"] . " WHERE  id_produit =" . $value["id_produit"]);
    }





    $vente = new vente();

    $vente->update($_POST["id"]);

    die('success');
  } catch (Exception $e) {

    die($e);
  }
}

?>