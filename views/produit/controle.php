<?php
include('../../evr.php');




if ($_POST['act'] == 'getcat' && isset($_POST['id_categorie'])) {
  $id = $_POST['id_categorie'];

  $produit = new produit();
  $data = $produit->selectChamps("code_bar", "id_categorie =  $id", '', "id_produit", "desc", "1");
  die($data[0]->code_bar);
}

if ($_POST['act'] == 'addIngredient') {

  if (!isset($_SESSION['rand_v_er']) || $_SESSION['rand_v_er'] === "") {
    $_SESSION['rand_v_er'] = rand(10, 1000);
  }
  $_POST["id_user"] = auth::user()["id"];
  $somme_poid = 0;

  $detail_produit = new detail_produit();
  $detail_produit->insert();
  $data = $detail_produit->selectAllValide($_POST['id_produit']);
  $total = 0;

  foreach ($data as $ligne) {
?>
    <tr>
      <td><?php echo $ligne->designation; ?></td>
      <td><?php echo $ligne->qte; ?></td>

      <td> <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
          <i class="simple-icon-trash" style="font-size: 15px;"></i>
        </a>
      </td>
    </tr>
  <?php
  }
  ?>
  <!--
<tr>
<td colspan="4" style="text-align: center;font-size: 15px;" > <b>Total</b>   </td>
<td style="text-align: right;" colspan="3">  <b style="font-size: 15px;color: green;text-align: right;" ><?php echo number_format($total, 2, '.', ' '); ?></b></td>

</tr>
-->
  <?php
}

if ($_POST['act'] == 'getDepots') {
  $query = "SELECT * FROM produit_depot pd LEFT JOIN depot d ON pd.id_depot = d.id WHERE pd.id_produit = " . $_POST['id'];

  $data = connexion::getConnexion()->query($query)->fetchAll(PDO::FETCH_OBJ);

  foreach ($data as $ligne) {
  ?>
    <tr>
      <td><?php echo $ligne->nom ?></td>
      <td><?php echo $ligne->qte ?></td>
    </tr>
  <?php
  }
}

if ($_POST['act'] == 'pagination') {

  $produit = new produit();
  $start = !empty($_POST['page']) ? $_POST['page'] : 0;
  $limit = $_SESSION['LIMIT'];

  //set conditions for search
  $depot_cat_sql = '';
  $whereSQL = $orderSQL = '';
  $keywords = $_POST['keywords'];
  $sortBy = $_POST['sortBy'];
  $prix = $_POST['prix'];
  $operateur = $_POST['operateur'];
  $fournisseur = $_POST['fournisseur'];
  $categorie = $_POST['categorie'];
  $depot = $_POST['depot'];
  $stock = $_POST['stock'];

  //	if($prix != '') {
  //	   $depot_cat_sql .= " AND p.prix_vente $operateur $prix ";
  //	}

  if ($fournisseur != '' && $fournisseur != '-1') {
    $depot_cat_sql .= " AND p.fournisseur LIKE '%" . $fournisseur . "%'";
  }

  if ($stock == '0') {
    $depot_cat_sql .= " AND p.qte_actuel < 1 ";
  }
  if ($stock == '1') {
    $depot_cat_sql .= " AND p.qte_actuel > 0 ";
  }
  if ($stock == 'all') {
    $depot_cat_sql .= "";
  }

  if ($depot != '0') {
    $depot_cat_sql .= " AND p.emplacement = '$depot' ";
  }
  if ($categorie != '0') {
    $depot_cat_sql .= " AND id_categorie = '$categorie' ";
  }

  if (!empty($keywords)) {
    $whereSQL = "WHERE Archive = 0 " . $depot_cat_sql . " AND (designation LIKE '%" . $keywords . "%' OR code_bar = '" . $keywords . "')";
  } else {
    $whereSQL = "WHERE Archive = 0 " . $depot_cat_sql . " ";
  }
  if (!empty($sortBy)) {
    $orderSQL = " ORDER BY code_bar " . $sortBy;
  } else {
    $orderSQL = " ORDER BY code_bar ";
  }

  $orderSQL = " ORDER BY id_produit DESC ";
  //get number of rows
  //die("select p.* from produit p $whereSQL order by p.designation asc LIMIT $start,$limit");
  $query = $produit->selectQuery("select p.* from produit p $whereSQL order by p.designation asc LIMIT $start,$limit");

  $queryNum = $produit->selectQuery("SELECT COUNT(*) as postNum FROM produit p 
		$whereSQL $orderSQL");
  /*echo "SELECT COUNT(*) as postNum FROM produit p inner join categorie c on c.id_categorie=p.id_categorie
		$whereSQL $orderSQL";*/
  //$queryNum = $produit->selectQuery("SELECT COUNT(*) as postNum FROM produit ".$whereSQL.$orderSQL);


  $rowCount = $queryNum[0]->postNum;

  //initialize pagination class
  $pagConfig = array(
    'currentPage' => $start,
    'totalRows' => $rowCount,
    'perPage' => $limit,
    'link_func' => 'searchFilter'
  );
  $pagination = new Pagination($pagConfig);

  //get rows
  //$query = $produit->selectQuery("SELECT * FROM produit $whereSQL $orderSQL LIMIT $start,$limit");



  if (count((array)$query) > 0) { ?>
    <div class="table-responsive ">
      <table class="table datatables table-striped table-bordered" id="myTable" style="width: 100%">
        <thead>
          <tr>
            <th>Numero</th>
            <th>R&eacute;f</th>
            <th>D&eacute;signation</th>
            <th> Q_stock</th>
            <th> P.achat</th>
            <th> P.Gros</th>
            <th> P.Détail</th>
            <th> P.Wanny</th>
            <th> CMUP </th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="advanced_tbody">
          <?php foreach ($query as $ligne) { ?>

            <tr>
              <td> <?php echo $ligne->id_produit; ?>
              <td> <?php if (!empty($ligne->image)) { ?>
                  <a class="fancybox" title="image de <?php echo $ligne->designation; ?>" href="../upload/produit/<?php echo $ligne->image; ?>"> <img src="../icon/image.png" width="17" height="19" class="icon" /> </a>
                <?php } ?>
                <?php echo $ligne->code_bar; ?>
              </td>
              <td class="designation"> <?php echo $ligne->designation;
                                        if ($ligne->designation_ar != "") {
                                          echo "/" . $ligne->designation_ar;
                                        } ?> </td>
              <td> <?php echo $ligne->qte_actuel; ?> </td>
              <td> <?php echo $ligne->prix_achat; ?> </td>
              <td> <?php echo $ligne->prix_vente; ?> </td>
              <td> <?php echo $ligne->prix_vente2; ?> </td>

              <td> <?php echo $ligne->prix_vente3; ?> </td>
              <td> <?php
                    $data =  $produit->getCmup($ligne->id_produit);
                    $stock_qte = $data[0]->qte_stock;
                    $up = 0;
                    $down = 0;
                    foreach ($data as $key => $d) {
                      if ($stock_qte > 0) {
                        if ($stock_qte > $d->qte_achat) {
                          $dif = $stock_qte - $d->qte_achat;
                          $up = $up + $dif * $d->prix_achat;
                          $down = $down + $dif;
                          $stock_qte = $stock_qte - $d;
                        } else {

                          $up = $stock_qte * $d->prix_achat;
                          $down = $stock_qte;

                          $stock_qte = 0;
                        }
                      }
                    }

                    print_r(number_format($up / $down, 2, '.', ''));

                    ?>
              </td>

              <td>
                <?php if (auth::user()['privilege'] == 'Admin') { ?>
                  <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_produit; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                    <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
                  </a>
                <?php } ?>
                <?php if (auth::user()['privilege'] == 'Admin' || auth::user()['privilege'] == 'User+') { ?>
                  <a class="badge badge-warning mb-2  url notlink" data-url="produit/update.php?id=<?php echo $ligne->id_produit; ?>" style="color: white;cursor: pointer;" title="Modifier" href="javascript:void(0)">
                    <i class="glyph-icon iconsmind-Pen-5" style="font-size: 15px;"> </i>
                  </a>
                <?php } ?>

                <a class="badge badge-secondary mb-2 static" data-id="<?php echo $ligne->id_produit; ?>" data-toggle="modal" data-backdrop="static" data-target="#exampleModalRight" style="color: white;cursor: pointer;" title="Etat vente/achat" href="javascript:void(0)">

                  <i class="simple-icon-pie-chart" style="font-size: 15px;"></i>
                </a>
                <?php if (auth::user()['privilege'] == 'Admin') { ?>
                  <?php if ($ligne->archive == 0) { ?>
                    <a class="badge badge-primary mb-2 archive" data-id="<?php echo $ligne->id_produit; ?>" data-arc="1" style="color: white;cursor: pointer;" title="Archiver">
                      <i class="glyph-icon simple-icon-social-dropbox" style="font-size: 15px;"></i> </a>
                  <?php } else { ?>
                    <a class="badge badge-dark mb-2 archive" data-id="<?php echo $ligne->id_produit; ?>" data-arc="0" style="color: white;cursor: pointer;" title="Disarchiver"> <i class="glyph-icon iconsmind-Box-withFolders" style="font-size: 15px;"> </i> </a>
                <?php }
                } ?>
                <a class="badge badge-success mb-2 static depots_btn" data-id="<?php echo $ligne->id_produit; ?>" data-toggle="modal" data-target="#depotsModal" style="color: white;cursor: pointer;" title="Depots" href="javascript:void(0)" designation="<?php echo $ligne->designation ?>">

                  <i class="simple-icon-pie-chart" style="font-size: 15px;"></i>
                </a>
                <?php if ($ligne->image != '' || $ligne->deux_image != '') {
                  $img =  $ligne->image != '' ? $ligne->image : $ligne->deux_image; ?>
                  <a class="badge badge-success " data-fancybox data-caption="<?php echo $ligne->designation; ?> <br> Prix : <?php echo $ligne->prix_vente ?> DH" style="color: white;cursor: pointer;" title="<?php echo $ligne->designation; ?>" href="<?php echo BASE_URL . 'upload/produit/' . $img; ?>">
                    <i class="simple-icon-picture" style="font-size: 15px;"> </i>
                  </a>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <?php echo $pagination->createLinks(); ?>
  <?php } else echo " <center> <strong style='color: red; '> rien n'a été trouvé </strong></center>";
}
if ($_POST['act'] == 'filter_vente') {

  $produit = new produit();


  //set conditions for search
  $depot_cat_sql = '';
  $whereSQL = $orderSQL = '';
  $keywords = $_POST['keywords'];
  $sortBy = $_POST['sortBy'];
  $prix = $_POST['prix'];
  $operateur = $_POST['operateur'];
  $fournisseur = $_POST['fournisseur'];
  $categorie = $_POST['categorie'];
  $depot = $_POST['depot'];
  $stock = $_POST['stock'];


  if ($fournisseur != '' && $fournisseur != '-1') {
    $depot_cat_sql .= " AND p.fournisseur LIKE '%" . $fournisseur . "%'";
  }

  if ($stock == '0') {
    $depot_cat_sql .= " AND p.qte_actuel < 1 ";
  }
  if ($stock == '1') {
    $depot_cat_sql .= " AND p.qte_actuel > 0 ";
  }
  if ($stock == 'all') {
    $depot_cat_sql .= "";
  }

  if ($depot != '0') {
    $depot_cat_sql .= " AND p.emplacement = '$depot' ";
  }
  if ($categorie != '0') {
    $depot_cat_sql .= " AND id_categorie = '$categorie' ";
  }

  if (!empty($keywords)) {
    $whereSQL = "WHERE Archive = 0 " . $depot_cat_sql . " AND (designation LIKE '%" . $keywords . "%' OR code_bar = '" . $keywords . "')";
  } else {
    $whereSQL = "WHERE Archive = 0 " . $depot_cat_sql . " ";
  }
  if (!empty($sortBy)) {
    $orderSQL = " ORDER BY code_bar " . $sortBy;
  } else {
    $orderSQL = " ORDER BY code_bar ";
  }

  $orderSQL = " ORDER BY id_produit DESC ";

  $query = $produit->selectQuery("select p.* from produit p $whereSQL order by p.designation asc ");

  $queryNum = $produit->selectQuery("SELECT COUNT(*) as postNum FROM produit p 
		$whereSQL $orderSQL");


  if (count((array)$query) > 0) { ?>
    <div class="table-responsive">
      <table id="datatables" style="width: 100%">
        <thead id="advanced_tbody">
          <tr style="background-color: #e5e7e5;">
            <th>R&eacute;f</th>
            <th>D&eacute;signation</th>
            <th> Q_stock</th>
            <th> P.achat</th>
            <th> P.vente</th>
            <th> Depot</th>
            <th>PU</th>
            <th>Remise %</th>
            <th>Qte</th>
            <th>Unite</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="advanced_tbody">
          <?php foreach ($query as $ligne) { ?>

            <tr>
              <td> <?php if (!empty($ligne->image)) { ?>
                  <a class="fancybox" title="image de <?php echo $ligne->designation; ?>" href="../upload/produit/<?php echo $ligne->image; ?>"> <img src="../icon/image.png" width="17" height="19" class="icon" /> </a>
                <?php } ?>
                <?php echo $ligne->code_bar; ?>
              </td>
              <td class="designation"> <?php echo $ligne->designation;
                                        if ($ligne->designation_ar != "") {
                                          echo "/" . $ligne->designation_ar;
                                        } ?> </td>
              <td> <?php echo $ligne->qte_actuel; ?> </td>
              <td> <?php echo $ligne->prix_achat; ?> </td>
              <td> <?php echo $ligne->prix_vente; ?> </td>
              <td>
                <?php
                $produit_depot = new produit_depot();
                $depots = $produit_depot->depots($ligne->id_produit);

                $d_options = '';
                ?>
                <select id="depot_<?php echo $ligne->id_produit ?>" class="form-control">
                  <?php

                  foreach ($depots as $d) { ?>
                    <option value='<?php echo $d->id ?>'><?php echo $d->nom ?></option>
                  <?php
                  }
                  ?>
                </select>
              </td>
              <td>
                <input type="text" style="width:60px;" value="0" class="form-control" id="prix_<?php echo $ligne->id_produit ?>">
              </td>
              <td>
                <input type="text" style="width:60px;" value="0" class="form-control" id="remise_<?php echo $ligne->id_produit ?>">
              </td>
              <td>
                <input type="text" style="width:60px;" value="0" class="form-control" id="qte_<?php echo $ligne->id_produit ?>">
              </td>
              <td>
                <input type="text" style="width:60px;" value="0" class="form-control" id="unite_<?php echo $ligne->id_produit ?>">
              </td>
              <td>
                <button type="button" class="btn btn-success add_v2_btn" style="border-radius: 12px;padding: 4px 12px;" data-id="<?php echo $ligne->id_produit ?>">Ajouter</button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php } else echo " <center> <strong style='color: red; '> rien n'a été trouvé </strong></center>";
} elseif ($_POST['act'] == 'insert') {




  try {
    $_POST["id_user"] = auth::user()["id"];
    $_POST["date_insertion"] = date("Y-m-d");
    $_POST["id_user"] = auth::user()["id"];
    $_POST["image"] = '';
    if (isset($_FILES["image"]["name"])) {
      $target_dir = '../../upload/produit/';
      $target_file = $target_dir . basename($_FILES["image"]["name"]);
      move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
      $_POST["image"] = basename($_FILES["image"]["name"]);
    }
    $_POST["archive"] = 0;
    $_POST["date_archive"] = date("Y-m-d");
    $_POST["id_archiveur"] = 0;
    $produit = new produit();
    $res = $produit->insert();

    if ($res) {
      $id_produit = connexion::getConnexion()->query('SELECT MAX(id_produit) FROM produit')->fetch(PDO::FETCH_COLUMN);

      $id_depot = $_POST['emplacement'];
      $qte = $_POST['qte_actuel'];

      connexion::getConnexion()->exec("INSERT INTO produit_depot(id_produit, id_depot, qte) VALUES($id_produit, $id_depot, $qte)");

      $produit = new produit();
      connexion::getConnexion()->exec("UPDATE  detail_produit  SET detail_produit.id_produit =(SELECT max(produit.id_produit) FROM produit)   WHERE detail_produit.id_produit = -1" . $_SESSION['rand_v_er']);
      unset($_SESSION['rand_v_er']);
      $query = $result = connexion::getConnexion()->query("SELECT max(id_produit) as dernier_produit FROM produit ");
      $result = $query->fetch(PDO::FETCH_OBJ);
      $dernier_produit = $result->dernier_produit;
      $result2 = connexion::getConnexion()->query("select da.id_produit,sum(da.qte)as qte from detail_produit da inner join produit a on a.id_produit=da.id_produit
			where a.id=$dernier_produit group by da.id_produit");

      if ($result2) {
        $data = $result2->fetchAll(PDO::FETCH_OBJ);
      } else {
        die('success');
      }

      die('success');
    }
    die('Erreur');
  } catch (Exception $e) {
    die($e);
  }
} elseif ($_POST['act'] == 'update') {
  try {
    //$_POST["date_insertion"] = date("Y-m-d");
    $_POST["id_user"] = auth::user()["id"];
    $_POST["poid"] = ($_POST["poid"] == '') ? 0 : $_POST["poid"];
    $_POST["remise_max"] = ($_POST["remise_max"] == '') ? 0 : $_POST["remise_max"];
    $target_dir = '../../upload/produit/';
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    $_POST["image"] = basename($_FILES["image"]["name"]);
    $produit = new produit();
    $produit->update($_POST["id"]);
    die('success');
  } catch (Exception $e) {
    die($e);
  }
} elseif ($_POST['act'] == 'delete') {
  try {


    $produit = new produit();
    $produit->delete($_POST["id"]);
    die('success');
  } catch (Exception $e) {
    die($e);
  }
} elseif ($_POST['act'] == 'archive') {
  try {



    $produit = new produit();
    $produit->archiver($_POST["id"], $_POST["val"], auth::user()["id"]);


    die('success');
  } catch (Exception $e) {
    die($e);
  }
} elseif ($_POST['act'] == 'getName') {
  try {



    $produit = new produit();
    $oldvalue = $produit->selectById($_POST['id']);
    die($_POST['id'] . ";;;<strong>" . $oldvalue['designation'] . "</strong>");
  } catch (Exception $e) {
    die($e);
  }
} elseif ($_POST['act'] == 'getetat') {
  try {

    $produit = new produit();
    $res = $produit->selectById($_POST["id"]);
    $total = 0;
    $poids = 0;
    $qte = 0;
  ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title></title>


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
          background-color: #CCCCCC;
        }

        .montant {
          text-align: right;
        }
      </style>

    </head>

    <body style="width:950px;margin:auto;">
      <?php
      if ($_POST['etatProduit'] == "achat") {

        $result = connexion::getConnexion()->query("SELECT f.raison_sociale AS fournisseur, a.date_achat, da.prix_produit, da.qte_achete AS qte_achete
			FROM achat a 
			LEFT JOIN detail_achat da ON da.id_achat=a.id_achat 
			LEFT JOIN fournisseur f ON a.id_fournisseur = f.id_fournisseur  
			WHERE (a.date_achat between '" . $_POST['dd'] . "' and '" . $_POST['df'] . "')  AND da.id_produit=" . $_POST["id"] . " AND a.valide = 1 AND da.qte_achete != 0 ORDER BY a.date_achat DESC");

        $data = $result->fetchAll(PDO::FETCH_OBJ);
      ?>

        <center>
          <p>
          <h3> Liste des achats de produit :</span>
            <?php echo $res["designation"] . " ( " . $res["poid"] . " ) "; ?> <br />

            <b dir="rtl">
              لائحة الشراء المنتج : <?php echo $res["designation_ar"] . " ( " . $res["poid"] . " ) " ?></b>
          </h3>
          De <?php echo $_POST['dd']; ?> من<br />
          Au <?php echo $_POST['df']; ?> الى<br />
          </p>
          <?php if (count($data) > 0) { ?>
            <table class="datatables" border=1 id="example" border="1" cellspacing="0" cellpadding="0">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Fournisseur /المورد</th>
                  <th scope="col">Date / التاريخ</th>
                  <th scope="col">Prix Achat / ثمن الشراء</th>
                  <th scope="col">Poid / الوزن</th>
                  <th scope="col">Qte Achete /الكمية المشتراة</th>
                  <th scope="col">Total /المجموع</th>

                </tr>
              </thead>
              <?php
              foreach ($data as $ligne) { ?>
                <tr>
                  <td> <?php echo $ligne->fournisseur; ?> </td>
                  <td> <?php echo $ligne->date_achat; ?> </td>
                  <td> <?php echo number_format($ligne->prix_produit, 2, ".", " "); ?> &nbsp;&nbsp;</td>
                  <td> <?php echo $ligne->qte_achete * $res["poid"];
                        $poids += $ligne->qte_achete * $res["poid"]; ?> g &nbsp;&nbsp;&nbsp;&nbsp; </td>
                  <td><?php echo $ligne->qte_achete;
                      $qte += $ligne->qte_achete; ?> &nbsp;&nbsp;&nbsp;&nbsp; </td>
                  <td> <?php echo number_format($ligne->total, 2, ".", " ");
                        $total += $ligne->total;
                        ?> &nbsp;&nbsp;&nbsp;&nbsp;</td>
                  </td>
                </tr>
              <?php } ?>
            </table>
            <table class="datatables" border="1" cellspacing="0" cellpadding="0">

              <tr>
                <td colspan="5">
                  <center> Total / المجموع </center>
                </td>
                <td> <?php echo $poids; ?> g &nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td> <?php echo $qte; ?> &nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td> <?php echo number_format($total, 2, ".", " "); ?>&nbsp;&nbsp;&nbsp;&nbsp; </td>

              </tr>
            </table>
          <?php  } else {  ?>

            <div class="alert alert-primary" role="alert">
              No Data
            </div>

          <?php }
        } else {
          $result = connexion::getConnexion()->query("select concat_ws(' ',c.nom,c.prenom)as client ,
			v.date_vente,dv.prix_produit,sum(dv.qte_vendu) as qte_vendu ,dv.prix_produit*dv.qte_vendu as total
			from client c left join  vente v on v.id_client=c.id_client left join detail_vente dv on dv.id_vente=v.id_vente where (v.date_vente between '" . $_POST['dd'] . "' and '" . $_POST['df'] . "')  and dv.id_produit=" . $_POST["id"] . " group by c.id_client");
          $data = $result->fetchAll(PDO::FETCH_OBJ);

          ?>
          <center>
            <p>
            <h3>Liste des Vente de produit :</span>
              <?php echo $res["designation"] . " ( " . $res["poid"] . " ) " ?> <br>
              <b dir="rtl">
                لائحة بيع المنتج : <?php echo $res["designation_ar"] . " ( " . $res["poid"] . " ) " ?></b>
            </h3>
            De <?php echo $_POST['dd']; ?> من<br />
            Au <?php echo $_POST['df']; ?> الى<br />
            </p>

            <?php if (count($data) > 0) { ?>
              <table class="datatables" id="example" border="1" cellspacing="0" cellpadding="0">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">Client / المنتوج</th>
                    <th scope="col">Date / التاريخ</th>
                    <th scope="col">Prix Vente /ثمن البيع </th>
                    <th scope="col">Poid / الوزن</th>
                    <th scope="col">Qte Vendu /الكمية المباعة</th>
                    <th scope="col">Total /المجموع</th>
                  </tr>
                </thead>
                <?php

                $total = 0;
                $poids = 0;
                $qte = 0;
                foreach ($data as $ligne) {

                  $total += (int)$ligne->total;
                  $qte += (int)$ligne->qte_vendu;
                  $poids += (int)$ligne->qte_vendu * (int)$res["poid"];
                ?>
                  <tr>
                    <td> <?php echo $ligne->client; ?> </td>
                    <td> <?php echo $ligne->date_vente; ?> </td>
                    <td> <?php echo number_format($ligne->prix_produit, 2, ".", " "); ?>&nbsp;&nbsp;</td>
                    <td><?php echo $ligne->qte_vendu * $res["poid"]; ?> &nbsp;&nbsp; </td>
                    <td><?php echo $ligne->qte_vendu; ?> &nbsp;&nbsp; </td>
                    <td> <?php echo number_format($ligne->total, 2, ".", " "); ?> &nbsp;&nbsp;</td>
                  </tr>
                  <tr height="20px">
                  </tr>
                  <tr style="font-weight:bold">
                    <td colspan="3">
                      <center> Total / المجموع</center>
                    </td>
                    <td> <?php echo $poids; ?> &nbsp;&nbsp;</td>

                    <td> <?php echo $qte; ?> &nbsp;&nbsp;</td>
                    <td> <?php echo number_format($total, 2, ".", " "); ?> &nbsp;&nbsp;</td>
                  </tr>
                <?php } ?>

              </table>

            <?php } else {  ?>

              <div class="alert alert-primary" role="alert">
                No Data
              </div>

          <?php }
          } ?>


    </body>

    </html>

<?php

  } catch (Exception $e) {
    die($e);
  }
}


?>