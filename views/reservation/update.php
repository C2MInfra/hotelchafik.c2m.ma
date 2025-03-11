<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$id = explode('?id=', $_SERVER["REQUEST_URI"])[1];
$client = new client();
$clients = $client->selectAll();
$reservation = new reservation();
$reservation = $reservation->selectById($id);
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
          <button type="button" class="btn btn-success url notlink" data-url="reservation/index.php"> <i
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
          <h5 class="mb-4">Modifier Reservation</h5>
          <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
            <input type="hidden" name="act" value="insert">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="clients">Client : </label>
                <div id="clients_container">

                  <?php
                  $reservations_ids = explode(',', $reservation['id_client']);
                  $i = 0;
                  foreach ($reservations_ids as $id) {
                    ?>
                    <div class="form-row">
                      <div class="col-11">
                        <select class="form-control select2-single" name="id_client[]" <?php echo ($i == count($reservations_ids) - 1) ? "id='clients'" : "" ?>>
                          <?php
                          foreach ($clients as $key => $value) {
                            $selected = '';
                            if ($value->id_client == $id) {
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

                    <?php
                    $i++;
                  }
                  ?>

                </div>
                <button type="button" id="duplicate-button" class="form-control">Ajouter autre client</button>
              </div>
              <div class="form-group col-md-6">
                <label for="date_reservation">Date :</label>
                <input type="date" class="form-control" id="date_reservation" name="date_reservation"
                  value="<?php echo $reservation['date_reservation']; ?>">
              </div>
            </div>
            <div class="form-row" style="align-items:center;">
              <div class="form-group col-md-12">
                <label for="remarque"> Remarque : </label>
                <textarea class="form-control" name="remarque"
                  id="remarque"><?php echo $reservation['remarque']; ?></textarea>
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
                    $option = "<option value=\"$value->id_chambre\">$value->numero_chambre</option>";
                    echo $option;
                  }
                  ?>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="nombre_personnes"> Nb Personnes :</label>
                <select class="form-control " name="nombre_personnes" id="nombre_personnes">
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
                <input type="date" class="form-control" id="date_arriver" name="date_arriver"
                  value="<?php echo date('Y-m-d'); ?>">
              </div>
              <div class="form-group col-md-2">
                <label for="date_depart">Date depart:</label>
                <input type="date" class="form-control" id="date_depart" name="date_depart"
                  value="<?php echo date('Y-m-d'); ?>">
              </div>
              <div class="form-group col-md-2">
                <button id="update-reservation-detail" type="button"
                  class="pull-right btn btn-success default btn-lg btn-block mr-1 "
                  style="margin-top: 31px;">Ajouter</button>
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
              <button type="button" id="update-reservation" class="btn btn-primary btn-lg  mr-1 ">Enregistrer</button>
            </div>
            <input type="hidden" id="access_add" value="1">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include('js/update.js.php');
?>
<script>
  function duplicateAnElement() {
    document.getElementById("duplicate-button").addEventListener("click", function () {

      const container = document.getElementById('clients_container');
      const elementToDuplicate = document.getElementById("clients");
      // Clone the element and all its children
      const clonedElement = elementToDuplicate.cloneNode(true);
      // Optionally, modify the cloned element (e.g., change an ID)
      clonedElement.id = elementToDuplicate.id;
      elementToDuplicate.id = "";

      // Create the markup structure
      var $formRow = $('<div>', { class: 'form-row' });
      var $col11 = $('<div>', { class: 'col-11' });
      var $col1 = $('<div>', { class: 'col-1' });
      var $button = $('<button>', {
        type: 'button',
        class: 'btn form-control',
        text: 'X'
      });

      // Add the button to .col-1
      $col1.append($button);

      // Add an element to .col-11 
      $col11.append(clonedElement);

      // Append .col-11 and .col-1 to .form-row
      $formRow.append($col11).append($col1);

      // Append .form-row to another element (example: a container div with id #container)
      $('#clients_container').append($formRow);

      // Append the cloned element to a container
      // document.getElementById("clients_container").appendChild(clonedElement);
      // Reinitialize the `select2` plugin for the cloned select
      $(clonedElement).select2({
        theme: 'bootstrap' // Use the same theme as the original
      });
    });
  }
  duplicateAnElement()
  $('#clients_container').on('click', '.btn', function () {
    // Remove the closest .form-row to the clicked button
    $(this).closest('.form-row').remove();
    // Assign an ID to the last .form-row in #container
    $('#clients_container .form-row:last select').attr('id', 'clients');
  });
</script>