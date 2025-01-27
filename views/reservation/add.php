<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$client = new client();
$clients = $client->selectAll();
$chambre = new chambre();
$chambres = $chambre->selectAll();
?>
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Reservation </h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-success  url notlink" data-url="reservation/index.php"> <i class="glyph-icon simple-icon-arrow-left"></i></button>
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
              <div class="form-group col-md-6">
                <label for="clients">Client : </label>
                <select class="form-control select2-single" name="id_client" id="clients">
                  <?php
                  foreach ($clients as $key => $value) {
                    $selected = '';
                    if ($value->id_client == $reservation['id_client']) {
                      $selected = 'selected=selected';
                    }
                    $option =  "<option value=\"$value->id_client\" $selected>$value->cin</option>";
                    echo $option;
                  }
                  ?>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="date_reservation">Date :</label>
                <input type="date" class="form-control" id="date_reservation" name="date_reservation" value="<?php echo date('Y-m-d'); ?>">
              </div>
            </div>
            <div class="form-row" style="align-items:center;">
              <div class="form-group col-md-12">
                <label for="remarque"> Remarque : </label>
                <textarea class="form-control" name="remarque" id="remarque"></textarea>
              </div>
            </div>
            <div class="form-row mt-5" style="align-items:center;">
              <div class="form-group col-md-6">
                <label class="mr-4">
                  <input type="radio" name="saison" class="saison" checked value="haute"> Haute saison
                </label>
                <label class="mr-4">
                  <input type="radio" name="saison" class="saison" value="basse"> Basse saison
                </label>
                <label class="mr-4">
                  <input type="radio" name="saison" class="saison" value="normale"> Saison normale
                </label>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="id_chambre"> Chambre :</label>
                <select class="form-control select2-single" name="id_chambre" id="chambres">
                  <?php
                  foreach ($chambres as $key => $value) {
                    $option =  "<option value=\"$value->id_chambre\">$value->numero_chambre</option>";
                    echo $option;
                  }
                  ?>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="nb-personnes"> Nb Personnes :</label>
                <select class="form-control " name="nb-personnes" id="nb-personnes">
                  <option value="une_personne">Une personne</option>
                  <option value="deux_personnes">Deux personnes</option>
                  <option value="trois_personnes">Trois personnes</option>
                  <option value="supplement">Supplément</option>
                </select>
              </div>
              <div class="form-group col-md-1">
                <label for="montant">Montant</label>
                <input type="text" name="montant" id="montant" class="form-control" value="0">
              </div>
              <div class="form-group col-md-1">
                <label for="nombre_nuits">Nb nuits</label>
                <input type="text" name="nombre_nuits" id="nombre_nuits" class="form-control" value="0">
              </div>
              <div class="form-group col-md-2">
                <label for="date_arriver">Date d'arrivé:</label>
                <input type="date" class="form-control" id="date_arriver" name="date_arriver" value="<?php echo date('Y-m-d'); ?>">
              </div>
              <div class="form-group col-md-2">
                <label for="date_depart">Date depart:</label>
                <input type="date" class="form-control" id="date_depart" name="date_depart" value="<?php echo date('Y-m-d'); ?>">
              </div>
              <div class="form-group col-md-2">
                <button id="add-reservation-detail" type="button" class="pull-right btn btn-success default btn-lg btn-block mr-1 " style="margin-top: 31px;">Ajouter</button>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table" cellpadding="0" cellspacing="0" id="box-table-a" summary="Employee Pay Sheet">
                <thead>
                  <tr>
                    <th scope="col">Chambre</th>
                    <th scope="col">Montant</th>
                    <th scope="col">Nb Nuits</th>
                    <th scope="col">Nb Personnes</th>
                    <th scope="col">Date d'arrivé</th>
                    <th scope="col">Date depart</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="detail-reservation">
                </tbody>
              </table>
            </div>
            <div class="float-sm-right text-zero saveb">
              <button type="button" id="add-reservation" class="btn btn-primary btn-lg  mr-1 ">Enregistrer</button>
            </div>
            <input type="hidden" id="access_add" value="1">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include('js/add.js.php');
?>