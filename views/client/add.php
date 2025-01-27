<?php

if (isset($_POST['ajax'])) {

  include('../../evr.php');

}

$db = connexion::getConnexion();

$sql = "select * from nationalite";

$nationalite = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC); 

$sql = "select * from pays";

$pays = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>

<style type="text/css">

  iframe * {

    cursor: inherit !important;

  }

</style>

<div class="container-fluid disable-text-selection">

  <div class="row">

    <div class="col-12">

      <div class="mb-2">

        <h1>Client </h1>

        <div class="float-sm-right text-zero">

          <button type="button" class="btn btn-success  url notlink" data-url="client/index.php"> <i class="glyph-icon simple-icon-arrow-left"></i></button>

        </div>

      </div>

      <div class="separator mb-5"></div>

    </div>

  </div>

  <div class="row">

    <div class="col align-self-start">

      <div class="card mb-4">

        <div class="card-body">

          <h5 class="mb-4">Ajouter Nouveau Client</h5>

          <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">

            <input type="hidden" name="act" value="insert">

            <div class="form-row">

              <div class="form-group col-md-4">

                <label for="nom"> Type Compte :</label>

                <select name="type_compte" class="form-control type_compte" data-style="btn-white" placeholder="Type Compte">

                  <option value="0"> Client</option>

                  <option value="1"> Société</option>

                </select>

              </div>

              <div class="form-group col-md-4 d-none">

                <label for="code"> Code :</label>

                <input type="text" class="form-control" id="code" name="code">

              </div>

              <div class="form-group col-md-4 societe">

                <label for="remarque">vérifier la sociétè :</label>

                <div class="mb-4">

                  <div style="display: inline-block;margin-left: 20px" class="custom-control custom-radio">

                    <input type="radio" id="fournisseur2" value="0" checked="" name="fournisseur" class="custom-control-input veriv">

                    <label class="custom-control-label" for="fournisseur2">Non</label>

                  </div>

                  <div style="display: inline-block;" class="custom-control custom-radio">

                    <input type="radio" id="fournisseur1" value="1" name="fournisseur" class="custom-control-input veriv">

                    <label class="custom-control-label" for="fournisseur1">oui</label>

                  </div>

                </div>

              </div>

              <div class="form-group col-md-4 client">

                <label for="nom"> Nom :</label>

                <input type="text" class="form-control" id="nom" name="nom">

              </div>

              <div class="form-group col-md-4 client">

                <label for="prenom">Prenom :</label>

                <input type="text" class="form-control" id="prenom" name="prenom">

              </div>

              <div class="form-group col-md-4 client">

                <label for="cin"> CIN :</label>

                <input type="text" class="form-control" id="cin" name="cin">

              </div>

              <div class="form-group col-md-4">

                <label for="telephone">T&eacute;l&eacute;phone :</label>

                <input type="text" class="form-control" id="telephone" name="telephone">

              </div>

              <div class="form-group col-md-4">

                <label for="email">Email : </label>

                <input type="text" class="form-control" id="email" name="email">

              </div>

              <div class="form-group col-md-4 societe">

                <label for="nom"> Raison Social :</label>

                <input type="text" class="form-control" id="nomres" name="nomres">

              </div>

            </div>

            <div class="form-row">

              <div class="col-md-12 " id="veriv" style="overflow: hidden; margin: 0 auto; max-width: 836px; display: none;">

                <iframe src="https://simpl-recherche.tax.gov.ma/RechercheEntreprise" style=" border: 0px none; height: 609px;  width: 100%; margin-top: -150px">

                </iframe>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-md-4 d-none">

                <label for="cin"> ICE :</label>

                <input type="text" class="form-control" id="ice" name="ice">

              </div>

              

              <!-- <div class="form-group  col-md-4 d-none">

                <label>Plafond :</label>

                <div class="input-group">

                  <div class="custom-file">

                    <input type="text" class="form-control" id="plafond" name="plafond">

                  </div>

                </div>

              </div> -->

            </div>

            <div class="form-row">

              <div class="form-group col-md-4">

                <label for="nationalite"> Nationalié :</label>

                <select name="id_nationalite" id="nationalite" class="form-control select2-single">

                  <?php

                    foreach ($nationalite as $key => $value) {

                      echo "<option value=\"$value[id_nationalite]\">$value[nom]</option>";

                    }

                  ?>

                </select>

              </div>

              <div class="form-group col-md-4">

                <label for="pays">Pays : </label>

                <select name="id_pays" id="pays" class="form-control select2-single">

                  <?php

                    foreach ($pays as $key => $value) {

                      echo "<option value=\"$value[id_pays]\">$value[nom]</option>";

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

              <div class="form-group  col-md-4">

                <label>Image :</label>

                <div class="input-group">

                  <div class="custom-file">

                    <input type="file" class="custom-file-input" id="image" name="image">

                    <label class="custom-file-label" for="image">Choose file</label>

                  </div>

                </div>

              </div>

              <div class="form-group col-md-4 d-none">

                <label for="nom"> Prix vente :</label>

                <select name="prix_vente" class="form-control prix_vente" data-style="btn-white" placeholder="Type Compte">

                  <option value="1"> Prix vente 1</option>

                  <option value="2"> Prix vente 2</option>

                  <option value="3"> Prix vente 3</option>

                </select>

              </div>

              <!-- <div class="form-group col-md-4 d-none">

                <label for="nom"> Remise :</label>

                <input type="text" class="form-control" id="remise" name="remise" value="<?php echo $oldvalue['remise'] ?>">

              </div> -->

            </div>

            <div class="form-row d-none">

              <div class="form-group col-md-4">

                <label for="type-client"> Type Client :</label>

                <select name="type_client" class="form-control type_client" data-style="btn-white" placeholder="Type Client">

                  <option value="0"> Normal</option>

                  <option value="1"> Point de vente</option>

                </select>

              </div>

              <div class="form-group guid col-md-4">

                <label for="pv_guid"> GUID PV :</label>

                <input type="text" class="form-control" id="pv_guid" name="pv_guid" value="<?php echo $oldvalue['pv_guid'] ?>">

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

            <div class="float-sm-right text-zero">

              <button type="submit" class="btn btn-primary btn-lg  mr-1 ">Enregistrer</button>

            </div>

          </form>

        </div>

      </div>

    </div>

  </div>

</div>

<script type="text/javascript">

  $(document).ready(function() {

    function uuidv4() {

      return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>

        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)

      );

    }

    $("#pv_guid").prop("disabled", true);

    $("#pv_guid").addClass('d-none');

    $(".guid").addClass('d-none');

    $("#pv_guid").val('');

    $(".type_client").on('change', function() {

      if ($(".type_client").val() == 1) {

        $("#pv_guid").prop("disabled", false);

        $("#pv_guid").removeClass('d-none');

        $(".guid").removeClass('d-none');

        $("#pv_guid").val(uuidv4());

      } else {

        $("#pv_guid").prop("disabled", true);

        $("#pv_guid").addClass('d-none');

        $(".guid").addClass('d-none');

        $("#pv_guid").val('');

      }

    })

    $(".societe").hide();

    $(".type_compte").change(function() {

      var $type_compte = $(this).find(":selected").val();

      if ($type_compte == "0") {

        $(".client").show();

        $(".type_client").show();

        $(".societe").hide();

      } else {

        $(".client").hide();

        $(".type_client").hide();

        $(".societe").show();

      }

    });

    $(".veriv").click(function() {

      var $type_compte = $(this).val();

      if ($type_compte == 0) {

        $("#veriv").hide();

      } else {

        $("#veriv").show();

      }

    });

  });

</script>

<script type="text/javascript">

  $(document).ready(function() {

    $("#addform").on("submit", function(event) {

      event.preventDefault();

      var form = $(this);

      $.ajax({

        type: "POST",

        url: "<?php echo BASE_URL . 'views/client/'; ?>controle.php",

        data: new FormData(this),

        dataType: 'text',

        cache: false,

        contentType: false,

        processData: false,

        success: function(data) {

          if (data == "success") {

            swal(

              'Ajouter',

              'Client était bien ajouter',

              'success'

            ).then((result) => {

              history.replaceState({}, "", `<?php echo BASE_URL . "client/index.php"; ?>`);

              $.ajax({

                method: 'POST',

                data: {

                  ajax: true

                },

                url: `<?php echo BASE_URL . "views/client/index.php"; ?>`,

                context: document.body,

                success: function(data) {

                  $("#main").html(data);

                }

              });

            });

          } else {

            form.append(` <div id="alert-danger" class="alert  alert-danger alert-dismissible fade show rounded mb-0" role="alert">

  <strong>${data}</strong>

  <button type="button" class="close" data-dismiss="alert" aria-label="Close">

  <span aria-hidden="true">×</span>

  </button>

</div>`);

          }

        }

      });

    });

  });

</script>

