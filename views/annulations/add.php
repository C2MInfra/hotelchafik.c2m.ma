  <?php
    if (isset($_POST['ajax'])) {
        include('../../evr.php');
    }

    ?>

  <div class="container-fluid disable-text-selection">
      <div class="row">
          <div class="col-12">
              <div class="mb-2">
                  <h1>Ajouter un cloturage : </h1>
                  <h3>(Seulement au cas d'erreur dans la synchronisation automatique) </h3>


              </div>

              <div class="separator mb-5"></div>
          </div>
      </div>

      <div class="row">
          <div class="col align-self-start">
              <div class="card mb-4">
                  <div class="card-body">
                      <h5 class="mb-4">Ajouter Nouveau categorie</h5>
                      <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
                          <input type="hidden" name="act" value="insert">
                          <div class="form-row">

                              <div class="form-group col-md-4">
                                  <label for="nom"> ID Trav :</label>
                                  <input type="text" class="form-control" id="nom" name="id_trav">
                              </div>
                              <div class="form-group col-md-4">
                                  <label for="nom"> Id Cloturage PV :</label>
                                  <input type="text" class="form-control" id="nom" name="id_clotur_pv">
                              </div>
                              <div class="form-group col-md-4">
                                  <label for="nom"> Montant Du Cloturage :</label>
                                  <input type="text" class="form-control" value="0" id="nom" name="montant">
                              </div>
                          </div>

                          <div class="form-row">

                              <div class="form-group col-md-4">
                                  <label for="nom"> Nb d'operations :</label>
                                  <input type="text" class="form-control" id="nom" value="0" name="nombreOperation">
                              </div>
                              <div class="form-group col-md-4">
                                  <label for="nom"> Date Cloturage :</label>
                                  <input type="datetime-local" class="form-control" id="nom" name="created_at">
                              </div>
                              <div class="form-group col-md-4">
                                  <label for="nom"> Montant Espece:</label>
                                  <input type="text" class="form-control" id="nom" value="0" name="montant_espece">
                              </div>
                          </div>


                          <div class="form-row">

                              <div class="form-group col-md-4">
                                  <label for="nom"> Montant Carte :</label>
                                  <input type="text" class="form-control" id="nom" value="0" name="montant_carte">
                              </div>
                              <div class="form-group col-md-4">
                                  <label for="nom"> Montant Compte :</label>
                                  <input type="text" class="form-control" id="nom" value="0" name="montant_compte">
                              </div>
                              <div class="form-group col-md-4">
                                  <label for="nom"> Montant Offert :</label>
                                  <input type="text" class="form-control" id="nom" value="0" name="montant_offert">
                              </div>
                          </div>

                          <div class="form-row">
                              <div class="col-md-4">
                                  <label for="nom"> Point de vente :</label>
                                  <select name="pv_guid" id="" class="form-control">
                                      <?php
                                        $pvs = connexion::getConnexion()->query("SELECT * FROM client WHERE pv_guid IS NOT NULL and pv_guid != ''")->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($pvs as $pv) {
                                        ?>
                                          <option value="<?php echo $pv->pv_guid ?>"><?php echo $pv->nom ?></option>
                                      <?php
                                        }
                                        ?>



                                  </select>
                              </div>
                              <div class="col-md-4">
                              </div>
                              <div class="col-md-4">
                                  <div class="float-sm-right">
                                      <button type="submit" class="btn btn-primary btn-lg  mr-1 mt-2 ">Enregistrer</button>
                                  </div>
                              </div>

                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <script type="text/javascript">
      $(document).ready(function() {

          $("#addform").on("submit", function(event) {
              event.preventDefault();

              var form = $(this);
              $.ajax({
                  type: "POST",
                  url: "<?php echo BASE_URL . 'views/recette/'; ?>controle.php",
                  data: new FormData(this),
                  dataType: 'text',
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function(data) {

                      if (data == "success") {
                          swal(
                              'Ajouter',
                              'categorie a ete bien Ajouter',
                              'success'
                          ).then((result) => {



                              $.ajax({

                                  method: 'POST',
                                  data: {
                                      ajax: true
                                  },
                                  url: `<?php echo BASE_URL . "views/recette/index.php"; ?>`,
                                  context: document.body,
                                  success: function(data) {
                                      history.pushState({}, "", `<?php echo BASE_URL . "recette/index.php"; ?>`);
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