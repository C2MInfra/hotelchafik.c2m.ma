<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$categorie = new categorie();
$categories = $categorie->selectChamps("*", '', '', 'nom', 'asc');



$depot = new depot();
$depots = $depot->selectChamps("*", '', '', 'nom', 'asc');

$fournis =  new fournisseur();

$allfourn = $fournis->selectAll();
?>

<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Produits </h1>

        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-success  url notlink" data-url="produit/index.php"> <i class="glyph-icon simple-icon-arrow-left"></i></button>
        </div>
      </div>

      <div class="separator mb-5"></div>
    </div>
  </div>

  <div class="row">
    <div class="col align-self-start">
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="mb-4">Ajouter Nouveau Produit</h5>

          <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
            <input type="hidden" name="act" value="insert">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="code_bar">Reference :</label>
                <input type="text" class="form-control" id="code_bar" name="code_bar" placeholder="Reference">
              </div>
              <div class="form-group col-md-4">
                <label for="id_categorie">Cat&eacute;gorie :</label>
                <select class="form-control select2-single" name="id_categorie" id="id_categorie">

                  <?php

                  foreach ($categories as $row) {
                    echo '<option value="' . $row->id_categorie . '">' . $row->nom . '</option>';
                  } ?>

                </select>

              </div>
              <div class="form-group col-md-4">
                <label for="code_bar">Fournisseur :</label>
                <select id="fournisseur" name="fournisseur" class=" select2-single  form-control">
                  <option value="">Sélectionner un option ... </option>


                  <?php
                  foreach ($allfourn as $f) {


                  ?>
                    <option value="<?php echo $f->id_fournisseur ?>"> <?php echo $f->raison_sociale ?> </option>

                  <?php
                  }
                  ?>
                </select>


              </div>

            </div>


            <div class="form-row">

              <div class="form-group col-md-6">
                <label for="designation">D&eacute;signation :</label>
                <textarea class="form-control" name="designation" id="designation"></textarea>
              </div>
              <div class="form-group col-md-6">
                <label for="designation_ar">D&eacute;signation_AR:</label>
                <textarea class="form-control" name="designation_ar" id="designation_ar"></textarea>
              </div>
            </div>


            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="poid">Poid :</label>
                <input type="text" name="poid" class="form-control" value="0" id="poid" />
              </div>
              <div class="form-group col-md-4">
                <label for="Unite">Unité : </label>
                <input type="text" name="unite" value="U" class="form-control" id="unite" />

              </div>
              <div class="form-group col-md-4">
                <label for="unite2">Unité 2 : </label>
                <input type="text" name="unite2" value="1" class="form-control" id="unite2" />

              </div>
            </div>


            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="prix_vente">Prix Vente 1:</label>
                <input type="text" class="form-control" name="prix_vente" value="0" id="prix_vente">
              </div>
              <div class="form-group col-md-3">
                <label for="prix_vente2">Prix Vente 2:</label>
                <input type="text" class="form-control" name="prix_vente2" value="0" id="prix_vente2">
              </div>
              <div class="form-group col-md-3">
                <label for="prix_vente3">Prix Vente 3:</label>
                <input type="text" class="form-control" name="prix_vente3" value="0" id="prix_vente3">
              </div>

              <div class="form-group col-md-3">
                <label for="prix_achat"> Remise Max :</label>
                <input type="text" class="form-control" name="remise_max" value="0" id="remise_max">
              </div>



              <div class="form-group col-md-4">
                <label for="prix_achat"> Prix Achat :</label>
                <input type="text" class="form-control" name="prix_achat" value="0" id="prix_achat">
              </div>



              <div class="form-group col-md-4">
                <label for="emplacement">Emplacement :</label>
                <select id="emplacement" name="emplacement" class="form-control">
                  <?php foreach ($depots as $row) {
                    echo '<option value="' . $row->id . '">' . $row->nom . '</option>';
                  } ?>
                </select>

              </div>
              <div class="form-group col-md-4">
                <label for="qte_actuel">Qte Stock : </label>
                <input type="text" name="qte_actuel" class="form-control" value="0" id="qte_actuel" />

              </div>

            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label>Image :</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image">
                    <label class="custom-file-label" for="image">Choose file</label>
                  </div>

                </div>
              </div>
              <div class="form-group col-md-3">
                <label for="type_produit">Type Produit :</label>
                <select id="type_produit" name="type_produit" class="form-control">
                  <option value="1">Produit Fini</option>
                  <option value="2">Composant</option>
                </select>
              </div>
              <div class="form-group col-md-3">
                <label for="tva">T.V.A :</label>
                <input type="text" class="form-control" name="tva" id="tva" value="0" placeholder="TVA">
              </div>

              <div class="form-group col-md-3">
                <label for="minqte"> Min QTE :</label>
                <input type="text" class="form-control" name="minqte" id="minqte" value="0" placeholder="minqte">
              </div>
            </div>
            <div class="form-group">
              <label for="remarque">Remarque :</label>
              <textarea class="form-control" name="remarque" value="0" id="remarque"></textarea>
            </div>

            <div class="form-row">

              <div class="form-group col-md-6">
                <label for="id_ingredient"> Composant :</label>
                <select class="form-control select2-single" name="id_ingredient" id="id_ingredient">

                  <?php
                  $composants = connexion::getConnexion()->query("SELECT * FROM produit WHERE type_produit = 2")->fetchAll(PDO::FETCH_OBJ);

                  foreach ($composants as $row) {
                    echo '<option value="' . $row->id_produit . '">' . $row->designation . '</option>';
                  } ?>

                </select>
              </div>
              <div class="form-group col-md-1">
                <label for="reste_stock">Stock</label>
                <span class="badge badge-danger mb-1" style=" display: block; margin-top: 10px;" id="reste_stock">0</span>

              </div>


              <div class="form-group col-md-3">
                <label for="qte">Qte</label>
                <input type="text" name="qte_vendu" id="qte" class="form-control" value="0">

              </div>

              <div class="form-group col-md-2">
                <button id="addIngredient" type="button" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 25px;">Ajouter</button>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table" cellpadding="0" cellspacing="0" id="box-table-a" summary="Employee Pay Sheet">
                <thead>
                  <tr>
                    <th width="70%" scope="col">Composant</th>
                    <th width="10%" scope="col">Qte</th>
                    <th width="20%" scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="detail_commande">
                  <?php
                  $detail_produit = new detail_produit();

                  $data = $detail_produit->selectAllNonValide();
                  $total = 0;

                  foreach ($data as $ligne) {

                  ?>
                    <tr>
                      <td>
                        <?php echo $ligne->designation; ?>
                      </td>
                      <td>
                        <?php echo $ligne->qte; ?>
                      </td>


                      <td> <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                          <i class="simple-icon-trash" style="font-size: 15px;"></i>
                        </a>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>
                  <tr>
                    <td colspan="4" style="text-align: center;font-size: 15px;"> <b>Total</b> </td>
                    <td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right;">
                        <?php echo number_format($total, 2, '.', ' '); ?>
                      </b></td>

                  </tr>

                </tbody>
              </table>
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
        url: "<?php echo BASE_URL . 'views/produit/'; ?>controle.php",
        data: {
          act: "getcat",
          id_categorie: id_categorie
        },
        success: function(data) {

          $('#code_bar').val(data);
        }
      });

    });

    $(".select2-single").select2({
      theme: "bootstrap",
      placeholder: "",
      maximumSelectionSize: 6,
      containerCssClass: ":all:"
    });
    $("input.datepicker").datepicker({
      format: 'yyyy-mm-dd',
      templates: {
        leftArrow: '<i class="simple-icon-arrow-left"></i>',
        rightArrow: '<i class="simple-icon-arrow-right"></i>'
      }
    });


    $("#addIngredient").click(function() {



      var id_ingratient = $(this).val();
      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL . 'views/ingredient/'; ?>controle.php",
        data: {
          act: "addIngredient",
          id_ingredient: $("#id_ingredient").val(),
          qte: $("#qte").val(),
        },
        success: function(data) {
          $('#detail_commande').html(data);
        }
      });

    });

    $('body').on("click", ".delete", function(event) {
      event.preventDefault();


      var btn = $(this);
      swal({
        title: 'Êtes-vous sûr?',
        text: "Voulez vous vraiment Supprimer ce Ingredient !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, Supprimer !'
      }).then((result) => {
        if (result.value) {

          $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL . 'views/ingredient/'; ?>controle.php",
            data: {
              act: "deleterow",
              id: btn.data('id')
            },
            success: function(data) {

              swal(
                'Supprimer',
                'Ingredient a ete bien Supprimer',
                'success'
              ).then((result) => {

                // btn.parents("td").parents("tr").remove();
                $('#detail_commande').html(data);
              });

            }
          });

        }
      });

    });


    $("#addform").on("submit", function(event) {
      event.preventDefault();

      var form = $(this);
      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL . 'views/produit/'; ?>controle.php",
        data: new FormData(this),
        dataType: 'text', // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          if (data.indexOf("success") >= 0) {

            swal(
              'Ajouter',
              'Produit a ete bien Ajouter',
              'success'
            ).then((result) => {
              $.ajax({

                method: 'POST',
                data: {
                  ajax: true
                },
                url: `<?php echo BASE_URL . "views/produit/index.php"; ?>`,
                context: document.body,
                success: function(data) {
                  history.pushState({}, "", `<?php echo BASE_URL . "produit/index.php"; ?>`);
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