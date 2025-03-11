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
                            <div class="form-group col-md-4">
                              <label for="pays">Pays : </label>
                              <select name="id_pays" id="pays" class="form-control select2-single">
                                <?php
                                foreach ($pays as $key => $value) {
                                  $option = "<option value=\"$value->id_pays\">$value->nom</option>";
                                  echo $option;
                                }
                                ?>
                              </select>
                            </div>
                            <div class="form-group  col-md-4">
                              <label>Ville :</label>
                              <div class="input-group">
                                <div class="custom-file">
                                  <input type="text" class="form-control" id="ville" name="ville">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-4 d-none">
                              <label for="nom"> Prix vente :</label>
                              <select name="prix_vente" class="form-control prix_vente" data-style="btn-white"
                                placeholder="Type Compte">
                                <option value="1"> Prix vente 1</option>
                                <option value="2"> Prix vente 2</option>
                                <option value="3"> Prix vente 3</option>
                              </select>
                            </div>
                            <div class="form-group col-md-4 d-none">
                              <label for="nom"> Remise :</label>
                              <input type="text" class="form-control" id="remise" name="remise"
                                value="<?php echo $oldvalue['remise'] ?>">
                            </div>
                          </div>
                          <div class="form-row d-none">
                            <div class="form-group col-md-4">
                              <label for="type-client"> Type Client :</label>
                              <select name="type_client" class="form-control type_client" data-style="btn-white"
                                placeholder="Type Client">
                                <option value="0"> Normal</option>
                                <option value="1"> Point de vente</option>
                              </select>
                            </div>
                            <div class="form-group guid col-md-4">
                              <label for="pv_guid"> GUID PV :</label>
                              <input type="text" class="form-control" id="pv_guid" name="pv_guid"
                                value="<?php echo $oldvalue['pv_guid'] ?>">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="adresse">Adresse :</label>
                            <textarea class="form-control" name="adresse" id="adresse"></textarea>
                          </div>
                          <div class="form-group">
                            <label for="remarque"> Remarque : </label>
                            <textarea class="form-control" name="remarque" id="remarque"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <div class="float-sm-right text-zero">
                          <button type="button" id="store_client_btn"
                            class="btn btn-primary btn-lg mr-1 ">Enregistrer</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End of code authored by  in 2025/01/16 -->
              </div>
              <div class="form-group col-md-6">
                <label for="date_reservation">Date :</label>
                <input type="date" class="form-control" id="date_reservation" name="date_reservation"
                  value="<?php echo date('Y-m-d'); ?>">
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
                <button id="add-reservation-detail" type="button"
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
<script type="text/javascript">
  $(document).ready(function () {

    function uuidv4() {
      return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
      );
    }
    $("#pv_guid").prop("disabled", true);
    $("#pv_guid").addClass('d-none');
    $(".guid").addClass('d-none');
    $("#pv_guid").val('');
    $(".type_client").on('change', function () {
      if ($(".type_client").val() == 1) {
        $("#pv_guid").prop("disabled", false);
        $("#pv_guid").removeClass('d-none');
        $(".guid").removeClass('d-none');
        $("#pv_guid").val(uuidv4());
      } 
      else {
        $("#pv_guid").prop("disabled", true);
        $("#pv_guid").addClass('d-none');
        $(".guid").addClass('d-none');
        $("#pv_guid").val('');
      }
    })
    $(".societe").hide();
    $(".type_compte").change(function () {
      var $type_compte = $(this).find(":selected").val();
      if ($type_compte == "0" || $type_compte == 1) {
        $(".client").show();
        $(".type_client").show();
        $(".societe").hide();
      } else {
        $(".client").hide();
        $(".type_client").hide();
        $(".societe").show();
      }
    });
    $(".veriv").click(function () {
      var $type_compte = $(this).val();
      if ($type_compte == 0 || $type_compte == 1) {
        $("#veriv").hide();
      } else {
        $("#veriv").show();
      }
    });
  });
</script>
<!-- This code was authored by  in 2025/01/16-->
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
  function selectOptionByText(selectElement, text) {
    for (const option of selectElement.options) {
      if (option.text === text) {
        option.selected = true;
        break;
      }
    }
  }
  function getClients() {
    $.ajax({
      type: "POST",
      url: "<?php echo BASE_URL . 'views/client/'; ?>controle.php",
      data: {
        act: 'getClients'
      },
      success: function (clients) {
        clients = JSON.parse(clients);
        const clients_list = document.getElementById("clients");
        clients_list.innerHTML = "";
        clients.forEach((row) => {
          const option = document.createElement("option");
          option.value = row.id_client;
          option.textContent = `${row.nom} (${row.cin})`;
          clients_list.appendChild(option);
        });
        selectOptionByText(clients_list, `${$("#nom").val()} (${$("#cin").val()})`)
        $('#clientForm').modal('hide');
      }
    })
  }
  function validateClientFormInputs() {
    let raison_social = $("#resnom").val();
    let ice = $("#ice").val();
    let adresse = $("#adresse").val();
    if (raison_social == "" || adresse == "" || ice == "") {
      return confirm("Des champs sont vides. Souhaitez-vous continuer l'ajout de client?")
    }
    return true;
  }
  $(document).ready(function () {
    $("#store_client_btn").on("click", function (event) {
      // Select the container
      const container = document.getElementById("clientAddForm");

      // Get all input elements within the container
      const inputs = container.querySelectorAll("input, textarea, select");

      // Create an object to store the input values
      const inputData = {};

      // Loop through each input and store its name and value
      inputs.forEach(input => {
        inputData[input.name] = input.value;
      });

      event.preventDefault();
      $(this).prop('disabled', true);
      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL . 'views/client/'; ?>controle.php",
        data: inputData,
        success: function (data) {
          alert("Client bien ajouter")
          $("#store_client_btn").prop('disabled', false);
          getClients()
        }
      })
    })
  })
</script>
<!-- end of code authored by  in 2025/01/16-->