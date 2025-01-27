<?php

if (isset($_POST['ajax'])) {

  include('../../evr.php');

}

$client = new client();

$id = explode('?id=', $_SERVER["REQUEST_URI"]);

$oldvalue = $client->selectById($id[1]);

$db = connexion::getConnexion();

$sql = "select * from nationalite";

$nationalite = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC); 

$sql = "select * from pays";

$pays = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container-fluid disable-text-selection">

  <div class="row">

    <div class="col-12">

      <div class="mb-2">

        <h1>Clients </h1>





      </div>



      <div class="separator mb-5"></div>

    </div>

  </div>

  <div class="row">

    <div class="col align-self-start">

      <div class="card mb-4">

        <div class="card-body">

          <h5 class="mb-4">Modification Client</h5>

          <form id="addform" method="post" name="form_Client" enctype="multipart/form-data">

            <input type="hidden" name="act" value="update">

            <input type="hidden" name="id" value="<?php echo $id[1]; ?>">

            <div class="form-row">

              <div class="form-group col-md-4">

                <label for="nom"> Type Compte :</label>

                <select name="type_compte" class="form-control type_compte" data-style="btn-white" placeholder="Type Compte">

                  <option value="0" <?php echo ($oldvalue['type_compte'] == 0) ? "selected" : '' ?>>

                    Client</option>

                  <option value="1" <?php echo ($oldvalue['type_compte'] == 1) ? "selected" : '' ?>>

                    Société</option>

                </select>

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

                <input type="text" class="form-control" value="<?php echo $oldvalue['nom']; ?>" id="nom" name="nom">

              </div>

              <div class="form-group col-md-4 client">

                <label for="prenom">Prenom :</label>

                <input type="text" class="form-control" value="<?php echo $oldvalue['prenom']; ?>" id="prenom" name="prenom">

              </div>

              <div class="form-group col-md-4 societe">

                <label for="nom"> Raison Social :</label>

                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $oldvalue['nom'] . ' ' . $oldvalue['prenom']; ?>">

              </div>

            </div>

            <div class="form-row mb-3">

              <div class="col-md-12 " id="veriv" style=" box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);overflow: hidden; margin: 0 auto; max-width: 836px; display: none;">

                <iframe src="https://ice.marocfacture.com/" style=" overflow: scroll; border: 0px none; height: 809px;  width: 100%; margin-top: -150px">



                </iframe>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-md-4 client">

                <label for="cin"> CIN :</label>

                <input type="text" class="form-control" value="<?php echo $oldvalue['cin']; ?>" id="cin" name="cin">

              </div>

              <div class="form-group col-md-4">

                <label for="telephone">T&eacute;l&eacute;phone :</label>

                <input type="text" class="form-control" value="<?php echo $oldvalue['telephone']; ?>" id="telephone" name="telephone">

              </div>

              <div class="form-group col-md-4">

                <label for="email">Email : </label>

                <input type="text" class="form-control" value="<?php echo $oldvalue['email']; ?>" id="email" name="email">

              </div>

              <div class="form-group col-md-4 d-none">

                <label for="cin"> ICE :</label>

                <input type="text" class="form-control" id="ice" name="ice" value="<?php echo $oldvalue['ice']; ?>">

              </div>



            </div>

            <div class="form-row">

              <div class="form-group col-md-4">

                <label for="cin"> Nationalié :</label>

                <select name="nationalite" id="nationalite" class="form-control select2-single">

                  <?php

                    foreach ($nationalite as $key => $value) {

                      $selected = '';

                      if($oldvalue['nationalite']==$value['id_nationalite']){

                        $selected = 'selected';

                      }

                      echo "<option value=\"$value[id_nationalite]\" $selected>$value[nom]</option>";

                    }

                  ?>

                </select>

              </div>

              <div class="form-group col-md-4">

                <label for="pays">Pays : </label>

                <select name="pays" id="pays" class="form-control select2-single">

                  <?php

                    foreach ($pays as $key => $value) {

                      $selected = '';

                      if($oldvalue['pays']==$value['id_pays']){

                        $selected = 'selected';

                      }

                      echo "<option value=\"$value[id_pays]\" $selected>$value[nom]</option>";

                    }

                  ?>

                </select>

              </div>

              <div class="form-group  col-md-4">

                <label>Ville :</label>

                <div class="input-group">

                  <div class="custom-file">

                    <input type="text" class="form-control" id="ville" name="ville" value="<?php echo $oldvalue['ville']; ?>">

                  </div>

                </div>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-md-4 d-none">



                <label for="code"> Code :</label>



                <input type="text" class="form-control" id="code" name="code" value="<?php echo $oldvalue['code']; ?>">



              </div>

              

              <!-- <div class="form-group  col-md-4 d-none">



                <label>Plafond :</label>



                <div class="input-group">



                  <div class="custom-file">



                    <input type="text" class="form-control" id="plafond" name="plafond" value="<?php echo $oldvalue['plafond'] ?>">



                  </div>



                </div>



              </div> -->

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

                  <option value="1" <?php echo ($oldvalue['prix_vente'] == 1) ? "selected" : '' ?>> Prix

                    Gros</option>

                  <option value="2" <?php echo ($oldvalue['prix_vente'] == 2) ? "selected" : '' ?>> Prix

                    Détail</option>

                  <option value="3" <?php echo ($oldvalue['prix_vente'] == 3) ? "selected" : '' ?>> Prix

                    Wanny</option>

                </select>

              </div>



              <!-- <div class="form-group col-md-4 d-none">



                <label for="nom"> Remise :</label>



                <input type="text" class="form-control" id="remise" name="remise" value="<?php echo $oldvalue['remise'] ?>">



              </div> -->

            </div>



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

          <textarea class="form-control" name="adresse" id="adresse"><?php echo $oldvalue['adresse']; ?></textarea>

        </div>

        <div class="form-group">

          <label for="remarque"> Remarque : </label>

          <textarea class="form-control" name="remarque" id="remarque"><?php echo $oldvalue['remarque']; ?></textarea>

        </div>



        <div class="float-sm-right text-zero">

          <button id="save-btn" type="submit" class="btn btn-primary btn-lg  mr-1 ">Enregistrer</button>

        </div>



        </form>

      </div>

    </div>

  </div>

</div>

</div>

<script type="text/javascript">

  $(document).ready(function() {



    var $type_compte = $(this).find(":selected").val();

    if ($type_compte == "0") {

      $(".client").show();

      $(".societe").hide();

    } else {

      $(".client").hide();

      $(".societe").show();

    }



    $(".type_compte").change(function() {



      var $type_compte = $(this).find(":selected").val();

      if ($type_compte == "0") {

        $(".client").show();

        $(".societe").hide();

      } else {

        $(".client").hide();

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

    $(".select2-single").select2({

      theme: "bootstrap",

      placeholder: "",

      maximumSelectionSize: 6,

      containerCssClass: ":all:"

    });



    $("#id_categorie").change(function() {





      var id_categorie = $(this).val();

      $.ajax({

        type: "POST",

        url: "<?php echo BASE_URL . 'views/client/'; ?>controle.php",

        data: {

          act: "getcat",

          id_categorie: id_categorie

        },

        success: function(data) {



          $('#code_bar').val(data);

        }

      });



    });

    $("#addform").on("submit", function(event) {

      event.preventDefault();

      var form = $(this);

      $.ajax({

        type: "POST",

        url: "<?php echo BASE_URL . 'views/client/'; ?>controle.php",

        data: new FormData(this),

        dataType: 'text', // what to expect back from the PHP script, if anything

        cache: false,

        contentType: false,

        processData: false,

        success: function(data) {

          console.log(data);

          if (data.indexOf("success") >= 0) {



            swal(

              'Modification',

              'client a été bien modifié',

              'success'

            ).then((result) => {

              $.ajax({

                method: 'POST',

                data: {

                  ajax: true

                },

                url: `<?php echo BASE_URL . "views/client/index.php"; ?>`,

                context: document.body,

                success: function(data) {

                  history.replaceState({}, "", `<?php echo BASE_URL . "client/index.php"; ?>`);

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