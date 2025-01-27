<?php
include('../../evr.php');

if($_GET['act'] == 'get_caracteristiques'){
    $chambre = new chambre();
    $cracteristiques = $chambre->get_chambre_cracteristiques();
    echo json_encode($cracteristiques);
}
else if($_GET['act'] == 'get_chambre_prix'){
  $chambre = new chambre();
  $chambre_prix = $chambre->selectChamps($_GET['prix_index'],"id_chambre = ". $_GET['id_chambre'],null,null,null,null);
  $prix_index = $_GET['prix_index'];
  echo json_encode(array("chambre_prix"=>$chambre_prix[0]->$prix_index));
}
else if($_POST['act'] == 'insert'){
    if(isset($_POST['caracteristique'])){
        $_POST['caracteristique'] = implode(',',$_POST['caracteristique']);
    }
    $chambre = new chambre();
    $result = $chambre->insert();
    echo json_encode(array('status'=> implode(',',$_POST),'msg'=> ''));
}
else if($_POST['act'] == 'update'){
    if(isset($_POST['caracteristique'])){
        $_POST['caracteristique'] = implode(',',$_POST['caracteristique']);
    }
    $chambre = new chambre();
    $result = $chambre->update($_POST['id_chambre']);
    echo json_encode(array('status'=> implode(',',$_POST),'msg'=> ''));
}
else if ($_POST['act'] == 'delete') {
    $id_chambre = $_POST['id_chambre'];
    $chambre = new chambre();
    $chambre->delete($id_chambre);
}
else if($_POST['act'] == 'get_chambres_datatable'){
    $chambre = new chambre();
    $requestData = $_REQUEST;
    $chambres = $chambre->get_chambres($requestData);
    echo json_encode(array(
        "draw" => intval($requestData['draw']),
        "recordsTotal" => $chambres['total'],
        "recordsFiltered" => $chambres['total'],
        "data" => $chambres['rows'],
    ));
}
else if($_POST['act'] == 'get_chambres'){
    $chambre = new chambre();
    $chambres = $chambre->selectChamps('id_chambre, numero_chambre');
    echo json_encode($chambres);
}
?>