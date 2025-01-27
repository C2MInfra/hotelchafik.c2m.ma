  <?php

    if (isset($_POST['ajax'])) {

        include('../../evr.php');
    }





    $achat = new achat();

    $id = explode('?id=', $_SERVER["REQUEST_URI"]);



    $oldvalue = $achat->selectById($id[1]);
    $sub_data = connexion::getConnexion()->query("select d.cout_device from achat a , detail_achat d where d.id_achat = a .id_achat and a.id_achat = $id[1] limit 1 ")->fetchAll(PDO::FETCH_OBJ)[0] ; 
 







    ?>



  <div class="container-fluid disable-text-selection">

      <div class="row">

          <div class="col-12">

              <div class="mb-2">

                  <h1>achats </h1>
                  <div class="float-sm-right text-zero">
                          <button type="button" class="btn btn-success  url notlink" data-url="achat/index.php"> <i class="glyph-icon simple-icon-arrow-left"></i></button>
                      </div>





              </div>



              <div class="separator mb-5"></div>

          </div>

      </div>



      <div class="row">

          <div class="col align-self-start">

              <div class="card mb-4">

                  <div class="card-body">

                      <h5 class="mb-4">Modification achat</h5>
              



                      <form id="addform" method="post" name="form_achat" enctype="multipart/form-data">

                          <input type="hidden" name="act" value="update">

                          <input type="hidden" name="id" value="<?php echo $id[1]; ?>">



                          <div class="form-row">

                              <div class="form-group col-md-4">

                                  <label for="id_fournisseur">Fournisseur : </label>

                                  <select class="form-control select2-single" name="id_fournisseur" id="id_fournisseur">



                                      <?php

                                        $fournisseur = new fournisseur();

                                        $fournisseurs = $fournisseur->selectChamps("*", '', '', 'raison_sociale', 'asc');

                                        foreach ($fournisseurs as $row) {

                                            if ($row->id_fournisseur == $oldvalue['id_fournisseur']) {

                                                echo '<option value="' . $row->id_fournisseur . '" selected="selected">' . $row->raison_sociale . '</option>';
                                            } else {

                                                echo '<option value="' . $row->id_fournisseur . '">' . $row->raison_sociale . '</option>';
                                            }
                                        } ?>



                                  </select>

                              </div>

                              <div class="form-group col-md-4">

                                  <label for="date_achat">Date :</label>

                                  <input type="text" class="form-control datepicker" id="date_achat" name="date_achat" value="<?php echo $oldvalue['date_achat']; ?>">

                              </div>

                              <!--                             

                             <div class="form-group col-md-4">

                                <label for="type">Type:</label>

                               <select class="form-control select2-single" name="type" id="type" >



                                <option value="tout" <?php echo $oldvalue['type'] == 'tout' ? 'selected' : '' ?>>Tout</option>

                                 <option value="stock" <?php echo $oldvalue['type'] == 'stock' ? 'selected' : '' ?>>Stock</option>

                                  <option value="or_stock" <?php echo $oldvalue['type'] == 'or_stock' ? 'selected' : '' ?>>Or stock</option>

                               </select>

                            </div> -->





                              <div class='col-md-2'>
                                  <label for="id_fournisseur">Devis : </label>
                                  <select class="form-control select2-single " name="devise" id="devise">
                                      <option  <?php echo $sub_data->cout_device == '1' ? 'selected' : '' ?> value="1">MAD</option>
                                      <option  <?php echo $sub_data->cout_device == '9.8' ? 'selected' : '' ?> value="9.8">USD</option>
                                      <option  <?php echo $sub_data->cout_device == '9' ? 'selected' : '' ?> value="9">EUR</option>
                                  </select>
                              </div>


                              <div class="col-md-2">
                                  <label for="cout_device"> Cout devise : </label>
                                  <input name="cout_device" type="text" class="form-control" id="cout_device" />
                              </div>

                          </div>



                          <div class="form-group">

                              <label for="remarque"> Remarque : </label>

                              <textarea class="form-control" name="remarque" id="remarque"><?php echo $oldvalue['remarque']; ?></textarea>

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

          $("input.datepicker").datepicker({

              format: 'yyyy-mm-dd',

              templates: {

                  leftArrow: '<i class="simple-icon-arrow-left"></i>',

                  rightArrow: '<i class="simple-icon-arrow-right"></i>'

              }

          });











          $("#id_categorie").change(function() {







              var id_categorie = $(this).val();

              $.ajax({

                  type: "POST",

                  url: "<?php echo BASE_URL . 'views/achat/'; ?>controle.php",

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

                  url: "<?php echo BASE_URL . 'views/achat/'; ?>controle.php",

                  data: new FormData(this),

                  dataType: 'text', // what to expect back from the PHP script, if anything

                  cache: false,

                  contentType: false,

                  processData: false,

                  success: function(data) {



                      if (data.indexOf("success") >= 0) {



                          swal(

                              'Modification',

                              'achat a ete bien modifie',

                              'success'

                          ).then((result) => {

                              $.ajax({



                                  method: 'POST',

                                  data: {
                                      ajax: true
                                  },

                                  url: `<?php echo BASE_URL . "views/achat/index.php"; ?>`,

                                  context: document.body,

                                  success: function(data) {

                                      history.pushState({}, "", `<?php echo BASE_URL . "achat/index.php"; ?>`);

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

          $('#cout_device').val($('#devise').val()) ; 

          $('#devise').change(()=>{
        $('#cout_device').val($('#devise').val()) ; 
      })





      });
  </script>