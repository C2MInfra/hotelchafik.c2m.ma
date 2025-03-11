<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$db = connexion::getConnexion();
$client = new client();
$clients = $client->selectAll();
$chambre = new chambre();
$chambres = $chambre->selectAll();
$sql = "select * from nationalite";
$nationalite = $db->query($sql)->fetchAll(PDO::FETCH_OBJ);
$sql = "select * from pays";
$pays = $db->query($sql)->fetchAll(PDO::FETCH_OBJ);
?>
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Reservation </h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-success  url notlink" data-url="reservation/index.php"> <i
              class="glyph-icon simple-icon-arrow-left"></i></button>
        </div>
      </div>
      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">
    <div class="col align-self-start">
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="mb-4">Ajouter Une Nouvelle Reservation</h5>
          <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
            <input type="hidden" name="act" value="insert">
            <div class="form-row">
              <div class="form-group col-md-2">
                <button type="button" id="add_client_form_btn" data-toggle="modal" data-target="#clientForm"
                  class="form-control">Ajouter nouveau client</button>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="clients">Client : </label>
                <div id="clients_container">
                  <div class="form-row">
                    <div class="col-11">
                      <select class="form-control select2-single " name="id_client[]" id="clients">
                        <?php
                        foreach ($clients as $key => $value) {
                          $selected = '';
                          if ($value->id_client == $reservation['id_client']) {
                            $selected = 'selected=selected';
                          }
                          $option = "<option value=\"$value->id_client\" $selected>$value->nom ($value->cin)</option>";
                          echo $option;
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-1">
                      <button type="button" class="btn form-control">X</button>
                    </div>
                  </div>

                </div>
                <button type="button" id="duplicate-button" class="form-control">Ajouter autre client</button>
                <!-- code authored by  in 2025/01/16 -->

                <div class="modal fade" id="clientForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                  aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajouter client</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div id="clientAddForm" name="form_client">
                          <input type="hidden" name="act" value="insert">
                          <div class="form-row">
                            <div class="form-group col-md-4">
                              <label for="nom"> Type Compte :</label>
                              <select name="type_compte" class="form-control type_compte" data-style="btn-white"
                                placeholder="Type Compte">
                                <option value="0"> Client (Marocaine)</option>
                                <option value="1"> Client (Ètrangère)</option>
                                <option value="2"> Société (Marocaine)</option>
                                <option value="3"> Société (Ètrangère)</option>
                              </select>
                            </div>
                            <div class="form-group col-md-4 d-none">
                              <label for="code"> Code :</label>
                              <input type="text" class="form-control" id="code" name="code">
                            </div>
                            <div class="form-group col-md-4 societe">
                              <label for="remarque">vérifier la sociétè :</label>
                              <div class="mb-4">
                                <div style="display: inline-block;margin-left: 20px"
                                  class="custom-control custom-radio">
                                  <input type="radio" id="fournisseur2" value="0" checked="" name="fournisseur"
                                    class="custom-control-input veriv">
                                  <label class="custom-control-label" for="fournisseur2">Non</label>
                                </div>
                                <div style="display: inline-block;" class="custom-control custom-radio">
                                  <input type="radio" id="fournisseur1" value="1" name="fournisseur"
                                    class="custom-control-input veriv">
                                  <label class="custom-control-label" for="fournisseur1">oui</label>
                                </div>
                              </div>
                            </div>
                            <div class="form-group col-md-4 ">
                              <label for="nom" class="client"> Nom Prenom :</label>
                              <label for="nom" class="societe"> Raison Social :</label>
                              <input type="text" class="form-control" id="nom" name="nom">
                            </div>
                            <!-- <div class="form-group col-md-4 client">
                <label for="prenom">Prenom :</label>
                <input type="text" class="form-control" id="prenom" name="prenom">
              </div> -->
                            <div class="form-group col-md-4 client">
                              <label for="cin"> CIN :</label>
                              <input type="text" class="form-control" id="cin" name="cin">
                            </div>
                            <div class="form-group col-md-4 client">
                              <label for="passport"> Passport :</label>
                              <input type="text" class="form-control" id="passport" name="passport">
                            </div>
                            <div class="form-group col-md-4 client">
                              <label for="carte_sejour"> Carte sejour :</label>
                              <input type="text" class="form-control" id="carte_sejour" name="carte_sejour">
                            </div>
                            <div class="form-group col-md-4">
                              <label for="telephone">T&eacute;l&eacute;phone :</label>
                              <input type="text" class="form-control" id="telephone" name="telephone">
                            </div>
                            <div class="form-group col-md-4">
                              <label for="email">Email : </label>
                              <input type="text" class="form-control" id="email" name="email">
                            </div>
                            <!-- <div class="form-group col-md-4 societe">
                              <label for="nom"> Raison Social :</label>
                              <input type="text" class="form-control" id="nomres" name="nomres">
                            </div> -->
                          </div>
                          <div class="form-row">
                            <div class="col-md-12 " id="veriv"
                              style="overflow: hidden; margin: 0 auto; max-width: 836px; display: none;">
                              <iframe src="https://simpl-recherche.tax.gov.ma/RechercheEntreprise"
                                style=" border: 0px none; height: 609px;  width: 100%; margin-top: -150px">
                              </iframe>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-4 d-none">
                              <label for="cin"> ICE :</label>
                              <input type="text" class="form-control" id="ice" name="ice">
                            </div>

                            <div class="form-group  col-md-4 d-none">
                              <label>Plafond :</label>
                              <div class="input-group">
                                <div class="custom-file">
                                  <input type="text" class="form-control" id="plafond" name="plafond">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-4">
                              <label for="nationalite"> Nationalié :</label>
                              <select name="id_nationalite" id="nationalite" class="form-control select2-single">
                                <?php
                                foreach ($nationalite as $key => $value) {
                                  $option = "<option value=\"$value->id_nationalite\">$value->nom</option>";
                                  echo $option;
                                }
                                ?>
                              </select>
                            </div>
