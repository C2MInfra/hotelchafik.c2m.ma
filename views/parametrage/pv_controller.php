<?php
include('../../eve.php');


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");



if (isset($_POST['pwd']) && $_POST['pwd'] == "8US64H%kMla6AqCVO9GkJZ%@5vb9") {
  if ($_POST['act'] == 'insert_cloturage') {

    $client = $_POST['client'];



    $_POST = $_POST['data'];

    $created_at = new DateTime($_POST['created_at']['date']);
    $updated_at = new DateTime($_POST['updated_at']['date']);


    unset($_POST['created_at']);
    unset($_POST['updated_at']);

    $_POST['created_at'] = $created_at->format('Y-m-d H:i:s');
    $_POST['updated_at'] = $updated_at->format('Y-m-d H:i:s');
    $_POST['pv_guid'] = $client;

    $clot = new cloturages();


    $clot->insert();

    die("success");
  }


  if ($_POST['act'] == 'list_avoir') {

    //$ventes = connexion::getConnexion()->query("SELECT * FROM `vente` WHERE id_client = (SELECT id_client FROM client WHERE pv_guid = '" . $_POST['client'] . "') AND is_synced = 0")->fetchAll(PDO::FETCH_OBJ);
    $ventes = connexion::getConnexion()->query("SELECT a.id_avoir,da.id_detail,p.designation,c.nom,da.qte_rendu,p.prix_vente3 AS prix_produit FROM avoir a JOIN detail_avoir da ON a.id_avoir = da.id_avoir JOIN produit p ON p.id_produit = da.id_produit JOIN categorie c ON p.id_categorie = c.id_categorie WHERE a.id_client = (SELECT id_client FROM client WHERE client.pv_guid = '" . $_POST['client'] . "') AND a.is_synced = 0")->fetchAll(PDO::FETCH_OBJ);


    die(json_encode($ventes));
  }

  if ($_POST['act'] == 'list_avoir_unique') {

    //$ventes = connexion::getConnexion()->query("SELECT * FROM `vente` WHERE id_client = (SELECT id_client FROM client WHERE pv_guid = '" . $_POST['client'] . "') AND is_synced = 0")->fetchAll(PDO::FETCH_OBJ);
    $ventes = connexion::getConnexion()->query("SELECT DISTINCT avoir.id_avoir FROM avoir WHERE id_client = (SELECT id_client FROM client WHERE pv_guid = '" . $_POST['client'] . "') AND is_synced = 0")->fetchAll(PDO::FETCH_OBJ);


    die(json_encode($ventes));
  }

  if ($_POST['act'] == 'list_avoir_details') {

    $details = [];


    foreach ($_POST['data'] as $vente) {
      array_push($details, connexion::getConnexion()->query("SELECT * FROM `detail_avoir` LEFT JOIN produit ON detail_avoir.id_produit = produit.id_produit LEFT JOIN categorie ON produit.id_categorie = categorie.id_categorie WHERE id_avoir = " . $vente['id_avoir'])->fetchAll(PDO::FETCH_OBJ));
    }


    foreach ($details as $det) {
      foreach ($det as $d) {
        $d->designation = utf8_encode($d->designation);
      }
    }


    $json = json_encode($details);


    die($json);
  }




  if ($_POST['act'] == 'list_vente') {

    //$ventes = connexion::getConnexion()->query("SELECT * FROM `vente` WHERE id_client = (SELECT id_client FROM client WHERE pv_guid = '" . $_POST['client'] . "') AND is_synced = 0")->fetchAll(PDO::FETCH_OBJ);
    $ventes = connexion::getConnexion()->query("SELECT vente.id_vente,detail_vente.id_detail,produit.designation,categorie.nom,detail_vente.qte_vendu,produit.prix_vente3 AS prix_produit FROM `vente` LEFT JOIN detail_vente ON vente.id_vente = detail_vente.id_vente LEFT JOIN produit ON detail_vente.id_produit = produit.id_produit LEFT JOIN categorie ON produit.id_categorie = categorie.id_categorie WHERE id_client = (SELECT id_client FROM client WHERE pv_guid = '" . $_POST['client'] . "') AND is_synced = 0")->fetchAll(PDO::FETCH_OBJ);


    die(json_encode($ventes));
  }

  if ($_POST['act'] == 'list_vente_unique') {

    //$ventes = connexion::getConnexion()->query("SELECT * FROM `vente` WHERE id_client = (SELECT id_client FROM client WHERE pv_guid = '" . $_POST['client'] . "') AND is_synced = 0")->fetchAll(PDO::FETCH_OBJ);
    $ventes = connexion::getConnexion()->query("SELECT DISTINCT vente.id_vente FROM vente WHERE id_client = (SELECT id_client FROM client WHERE pv_guid = '" . $_POST['client'] . "') AND is_synced = 0")->fetchAll(PDO::FETCH_OBJ);


    die(json_encode($ventes));
  }

  if ($_POST['act'] == 'list_vente_details') {

    $details = [];


    foreach ($_POST['data'] as $vente) {
      array_push($details, connexion::getConnexion()->query("SELECT * FROM `detail_vente` LEFT JOIN produit ON detail_vente.id_produit = produit.id_produit LEFT JOIN categorie ON produit.id_categorie = categorie.id_categorie WHERE id_vente = " . $vente['id_vente'])->fetchAll(PDO::FETCH_OBJ));
    }


    foreach ($details as $det) {
      foreach ($det as $d) {
        $d->designation = utf8_encode($d->designation);
      }
    }


    $json = json_encode($details);


    die($json);
  }

  if ($_POST['act'] == 'clear_pending') {

    connexion::getConnexion()->query("UPDATE vente SET is_synced = 1 WHERE id_client = (SELECT id_client FROM client WHERE pv_guid = '" . $_POST['client'] . "') ")->execute();
    connexion::getConnexion()->query("UPDATE avoir  SET is_synced = 1 WHERE id_client = (SELECT id_client FROM client WHERE pv_guid = '" . $_POST['client'] . "') ")->execute();
    die("success");
  }
  if ($_POST['act'] == 'pv_client_list') {

    $clients = connexion::getConnexion()->query("SELECT * FROM client WHERE pv_guid IS NOT NULL")->fetchAll(PDO::FETCH_OBJ);
    die(json_encode($clients));
  }


  if ($_POST['act'] == 'update_qte') {
    $prod_pv = json_decode($_POST['prod']);

    $prod_id = connexion::getConnexion()->query("SELECT id_produit FROM produit WHERE code_bar = '" . $prod_pv->code_bar . "'")->fetchAll(PDO::FETCH_OBJ);



    $qte = $prod_pv->qte;


    $prods_string = "";

    foreach ($prod_id as $id) {
      $prods_string += $id->id_produit . ', ';
    }



    connexion::getConnexion()->exec("UPDATE detail_vente SET qte_restante = $qte WHERE id_produit IN ($prods_string)");


    die("success");
  }


  if ($_POST['act'] == 'insert_history') {
    $history = json_decode($_POST['history']);


    connexion::getConnexion()->exec("insert INTO tracking_vente (code_bar,date_vente,qte_vendu,guid_client,num_ticket,prix) VALUES ('$history->code_bar','$history->date_vente',$history->qte_vendu,'$history->guid_client','$history->num_ticket',$history->prix)");

    die("success");
  }
}
