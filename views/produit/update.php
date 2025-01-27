<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}

$produit = new produit();

$id = explode('?id=', $_SERVER["REQUEST_URI"]);

$oldvalue = $produit->selectById($id[1]);

$categorie = new categorie();
$categories = $categorie->selectChamps("*", '', '', 'nom', 'asc');

$depot = new depot();
$depots = $depot->selectChamps("*", '', '', 'nom', 'asc');

$fournis =  new fournisseur();

$allfourn = $fournis->selectAll();

$id_produit = $id[1];
?>
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Produits </h1>
      </div>

      <div class="separator mb-5"></div>
    </div>
  </div>

  <div class="row">
    <div class="col align-self-start">
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="mb-4">Modification Produit</h5>

          <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
            <input type="hidden" name="act" value="update">
            <input type="hidden" name="id" value="<?php echo $id[1]; ?>">
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="code_bar">Reference :</label>
                <input type="text" class="form-control" value="<?php echo $oldvalue['code_bar'] ?>" id="code_bar" name="code_bar" placeholder="Reference">
              </div>
              <div class="form-group col-md-3">
                <label for="code_bar">Fournisseur :</label>
                <select id="fournisseur" name="fournisseur" class=" select2-single  form-control">
                  <option value="">Sélectionner un option ... </option>


                  <?php
                  foreach ($allfourn as $f) {


                  ?>
                    <option <?php ($oldvalue['fournisseur'] == $f->id_fournisseur ? print_r('selected') : '') ?> value="<?php echo $f->id_fournisseur ?>"> <?php echo $f->raison_sociale ?> </option>

                  <?php
                  }
                  ?>
                </select>


              </div>
              <div class="form-group col-md-3">
                <label for="id_categorie">Cat&eacute;gorie :</label>
                <select class="form-control select2-single" name="id_categorie" id="id_categorie">

                  <?php foreach ($categories as $row) {

                    if ($row->id_categorie == $oldvalue['id_categorie']) {
                      echo '<option value="' . $row->id_categorie . '" selected>' . $row->nom . '</option>';
                    } else {
                      echo '<option value="' . $row->id_categorie . '">' . $row->nom . '</option>';
                    }
                  } ?>

                </select>

              </div>

              <div class="form-group col-md-3">
                <label for="tva"> Min QTE :</label>
                <input type="text" class="form-control" name="minqte" id="minqte" value="<?php echo $oldvalue['minqte'] ?>" placeholder="minqte">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="designation">D&eacute;signation :</label>
                <textarea class="form-control" name="designation" id="designation"><?php echo $oldvalue['designation'] ?></textarea>
              </div>
              <div class="form-group col-md-6">
                <label for="designation_ar">D&eacute;signation_AR:</label>
                <textarea class="form-control" name="designation_ar" id="designation_ar"><?php echo $oldvalue['designation_ar'] ?></textarea>
              </div>


            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="poid">Poid :</label>
                <input type="text" name="poid" value="<?php echo $oldvalue['poid'] ?>" class="form-control" id="poid" />
              </div>

              <div class="form-group col-md-3">
                <label for="Unite">Unité : </label>
                <input type="text" name="unite" value="<?php echo $oldvalue['unite'] ?>" class="form-control" id="unite" />
              </div>

              <div class="form-group col-md-3">
                <label for="unite2">Unité 2 : </label>
                <input type="text" name="unite2" value="<?php echo $oldvalue['unite2'] ?>" class="form-control" id="unite2" />
              </div>

              <div class="form-group col-md-3">
                <label for="prix_achat"> Remise Max :</label>
                <input type="text" class="form-control" name="remise_max" value="<?php echo $oldvalue['remise_max'] ?>" id="remise_max">
              </div>


            </div>




            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="prix_vente">Prix Vente :</label>
                <input type="text" class="form-control" name="prix_vente" value="<?php echo $oldvalue['prix_vente'] ?>" id="prix_vente">
              </div>
              <div class="form-group col-md-2">
                <label for="prix_vente2">Prix Vente 2:</label>
                <input type="text" class="form-control" name="prix_vente2" value="<?php echo $oldvalue['prix_vente2'] ?>" id="prix_vente2">
              </div>
              <div class="form-group col-md-2">
                <label for="prix_vente3">Prix Vente 3:</label>
                <input type="text" class="form-control" name="prix_vente3" value="<?php echo $oldvalue['prix_vente3'] ?>" id="prix_vente3">
              </div>
              <div class="form-group col-md-2">
                <label for="prix_achat_i"> Prix Achat :</label>
                <input type="text" class="form-control" name="prix_achat" value="<?php echo $oldvalue['prix_achat'] ?>" id="prix_achat">
              </div>
              <div class="form-group col-md-2">
                <label for="prix_achat_i"> Prix Revient :</label>
                <input type="text" class="form-control" name="prix_achat" value="<?php echo $oldvalue['prix_achat'] ?>" id="prix_achat">
              </div>
              <div class="form-group col-md-2">
                <label for="tva">T.V.A :</label>
                <input type="text" value="<?php echo $oldvalue['tva'] ?>" class="form-control" name="tva" id="tva" placeholder="TVA">
              </div>
            </div>
            <div class="form-row" style="align-items:center;">
              <div class="form-group col-md-3">
                <label for="emplacement">Emplacement :</label>
                <select id="emplacement" name="emplacement" class="form-control">
                  <?php foreach ($depots as $row) {

                    if ($oldvalue['emplacement'] == $row->id) {
                      echo '<option value="' . $row->id . '" selected>' . $row->nom . '</option>';
                    } else
                      echo '<option value="' . $row->id . '">' . $row->nom . '</option>';
                  } ?>
                </select>

              </div>
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
                  <option value="1" <?php echo ($oldvalue['type_produit'] == 1) ? 'selected' : '' ?>>
                    Produit Fini</option>
                  <option value="2" <?php echo ($oldvalue['type_produit'] == 2) ? 'selected' : '' ?>>
                    Composant</option>
                </select>
              </div>

              <div class="form-group col-md-3">
                <label for="remarque">Remarque :</label>
                <textarea class="form-control" name="remarque" id="remarque"><?php echo $oldvalue['remarque'] ?></textarea>
              </div>
            </div>
            <div class="row" style="background-color:#d8ddda; border-radius:4px; align-items:center;">
              <div class="form-group col-md-1">
                <label style="font-size: 13px;">Coeff Device :</label>
                <input type="text" value="1" class="form-control" id="cal_coff">
              </div>
              <div class="form-group col-md-1">
                <label style="font-size: 13px;">F.Approche :</label>
                <input type="text" value="1" class="form-control" id="cal_approche">
              </div>
              <div class="form-group col-md-2">
                <label>Marge Prix Vente 1 :</label>
                <input type="text" value="25" class="form-control" id="cal_pv1">
              </div>
              <div class="form-group col-md-2">
                <label>Marge Prix Vente 2 :</label>
                <input type="text" value="20" class="form-control" id="cal_pv2">
              </div>
              <div class="form-group col-md-2">
                <label>Marge Prix Vente 3 :</label>
                <input type="text" value="15" class="form-control" id="cal_pv3">
              </div>
              <div class="form-group col-md-2">
                <label>TVA</label>
                <input type="text" id="cal_tva" value="0" class="form-control">
              </div>
              <div class="form-group col-md-2">
                <button id="calculate" type="button" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 25px;">Récalculer </button>
              </div>
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

                  $data = $detail_produit->selectAllValide($id_produit);
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
<div class="modal fade" id="updateDepotModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="padding:0px; background-color:initial;">
      <div class="modal-body" style="padding:0px; background-color:initial;">
        <div style=" background-color:white; border-radius:8px; overflow:hidden; border-top:2px solid #2cce81;">
          <div>
            <h2 style="
                        padding: 8px;
                        font-size: 14pt;
                        font-weight: 700;
                        color: #555;
                        margin-bottom: 0px;
                        padding-bottom: 2px;
                    ">Emplacements</h2>
            <h3 style="
                        padding: 8px;
                        font-size: 10.4pt;
                        font-weight: 300;
                        color: #2cce81;
                        padding-top: 0px;
                    ">Consultation des emplacements</h3>
          </div>
          <div style="padding: 0px 16px !important;">
            <input type="hidden" name="id_detail" id="barcode_detail">
            <div class="form-row">
              <label for="sku">Dépot</label>
              <input id="depot_1" type="text" class="form-control" disabled>
            </div>
          </div>
          <div id="barcode_container">
            <div style="padding: 8px;">
              <label>Emplacements</label>

              <table class="" border="1" style=" width:100%;">
                <tr>
                  <th>Depot</th>
                  <th>Qte</th>
                </tr>
                <tbody id="depots_tbl">

                </tbody>
              </table>
            </div>
            <center>
              <p id="barcode_footer" style="font-weight:900; font-size:16pt;"></p>
            </center>
          </div>
          <div class="" style="font-weight:900; font-size:16pt;height:8px;background-color:#2cce81;border-style: none;color: white;font-size: 12pt;font-weight: 900;width: 100%; cursor:pointer; ">
          </div>
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



    $('#calculate').click(function() {

      let prix_achat = $('#prix_achat').val();
      let f_approche = $('#cal_approche').val();
      let coff = $('#cal_coff').val();
      let pv1_p = $('#cal_pv1').val();
      let pv2_p = $('#cal_pv2').val();
      let pv3_p = $('#cal_pv3').val();
      let tva = 1 + $('#cal_tva').val() / 100;

      if (prix_achat != '' && f_approche != '') {
        let prix_revient = prix_achat * coff * f_approche;

        //Prix Achat
        $('#prix_achat').val((prix_revient * tva).toFixed(2));
        $('#prix_achat').css('background', '#abe7b9');

        //Prix Vente
        $('#prix_vente').val(((prix_revient + (prix_revient / 100) * pv1_p) * tva).toFixed(2));
        $('#prix_vente').css('background', '#abe7b9');

        //Prix Vente 2
        $('#prix_vente2').val(((prix_revient + (prix_revient / 100) * pv2_p) * tva).toFixed(2));
        $('#prix_vente2').css('background', '#abe7b9');

        //Prix Vente 3
        $('#prix_vente3').val(((prix_revient + (prix_revient / 100) * pv3_p) * tva).toFixed(2));
        $('#prix_vente3').css('background', '#abe7b9');
      }
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

    $("#addIngredient").click(function() {


      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL . 'views/produit/'; ?>controle.php",
        data: {
          act: "addIngredient",
          id_ingredient: $("#id_ingredient").val(),
          qte: $("#qte").val(),
          id_produit: "<?php echo $id_produit ?>"
        },
        success: function(data) {
          $('#detail_commande').html(data);
        }
      });

      console.log('hi there')

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
              'Produit a ete bien Modifier',
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