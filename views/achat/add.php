  <?php

  if (isset($_POST['ajax'])) {
    include('../../evr.php');
  }
  $depot = new depot();
  $data_depot = $depot->selectAll();

  $produit = new produit();
  $data_cat = $produit->selectAllCat();

  $fournisseurs_all = connexion::getConnexion()->query("SELECT fournisseur AS nom FROM produit WHERE fournisseur IS NOT NULL GROUP BY fournisseur ORDER BY fournisseur ASC ")->fetchAll(PDO::FETCH_OBJ);

  ?>

  <div class="container-fluid disable-text-selection">
    <div class="row">
      <div class="col-12">
        <div class="mb-2">
          <h1>Achat</h1>

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
            <h5 class="mb-4">Ajouter Nouveau Achat</h5>
            <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
              <input type="hidden" name="act" value="insert">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="id_fournisseur">Fournisseur : </label>
                  <select class="form-control select2-single" name="id_fournisseur" id="id_fournisseur">

                    <?php
                    $fournisseur = new fournisseur();
                    $fournisseurs = $fournisseur->selectChamps("*", '', '', 'raison_sociale', 'asc');
                    foreach ($fournisseurs as $row) {
                      echo '<option value="' . $row->id_fournisseur . '">' . $row->raison_sociale . '</option>';
                    } ?>

                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="date_achat">Date :</label>
                  <input type="text" class="form-control datepicker" id="date_achat" name="date_achat" value="<?php echo date('Y-m-d'); ?>">
                </div>



                <div class="form-group col-md-2">
                  <label for="id_fournisseur">Devise : </label>
                  <select class="form-control select2-single" name="devise_produit" id="devise_produit">
                    <option value="1">MAD</option>
                    <option value="9.8">USD</option>
                    <option value="9">EUR</option>
                  </select>
                </div>

                <div class="col-md-2">
                  <label for="cout_device">Cout devise : </label>
                  <input name="cout_device" type="text" value=1 class="form-control" id="cout_device" />
                </div>



              </div>


              <div class="form-row">

                <div class="form-group col-md-4">
                  <label for="remarque">Remarque : </label>
                  <textarea class="form-control" name="remarque" id="remarque"></textarea>
                </div>


                <div class="form-group col-md-4">
                  <label for="rech"> Recherche Référence: </label>
                  <input type="text" class="form-control" style="border-color: red" id="rech">
                </div>


                <div class="form-group col-md-4">
                  <label for="rech_designation"> Recherche Désignation: </label>
                  <input type="text" class="form-control" style="border-color: red" id="rech_designation">
                </div>


                <div class="form-group col-md-4">

                  <button style="display: none;" type="button" data-toggle="modal" data-target="#addModal" class="btn btn-secondary" style="margin-top:30px;">
                    <i class='bx bx-globe'></i> Liste des produits
                  </button>

                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-2">
                  <label for="id_categorie"> Catégorie :</label>
                  <select class="form-control select2-single" name="id_categorie" id="id_categorie">

                    <?php
                    $categorie = new categorie();
                    $categories = $categorie->selectAll();
                    foreach ($categories as $row) {
                      echo '<option value="' . $row->id_categorie . '">' . $row->nom . '</option>';
                    } ?>

                  </select>
                </div>
                <div class="form-group col-md-3">
                  <label for="id_produit"> Produit :</label>
                  <select class="form-control select2-single" name="id_produit" id="id_produit">

                    <?php
                    $depot = new depot();
                    $res_depot = $depot->selectAll();
                    foreach ($res_depot as $rep_depot) {
                    ?>
                      <optgroup label="<?php echo $rep_depot->nom; ?> ">
                        <?php
                        $produits = $depot->selectQuery("SELECT  id_produit,designation  FROM produit where   id_categorie=" . $categories[0]->id_categorie . " and   emplacement='" . $rep_depot->id . "' order by designation asc");
                        foreach ($produits as $row) {
                          echo '<option value="' . $row->id_produit . '">' . $row->designation . '</option>';
                        } ?>
                      </optgroup>
                    <?php } ?>

                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label for="id_depot"> Dépot :</label>
                  <select class="form-control select2-single" name="id_depot" id="id_depot">

                    <?php
                    $depot = new depot();
                    $res_depot = $depot->selectAll();
                    foreach ($res_depot as $d) : ?>
                      <option value="<?php echo $d->id ?>"><?php echo $d->nom ?></option>';
                    <?php endforeach ?>

                  </select>
                </div>
                <div class="form-group col-md-1">
                  <label for="reste_stock">Stock</label>
                  <span class="badge badge-danger mb-1" style=" display: block; margin-top: 10px;" id="reste_stock">0</span>

                </div>
                <div class="form-group col-md-1">
                  <label for="prix_produit">P.U</label>
                  <input type="text" name="prix_produit" id="prix_produit" class="form-control">
                </div>

                <div class="form-group col-md-1">
                  <label for="qte_achete">Qte</label>
                  <input type="text" name="qte_achete" id="qte_achete" class="form-control">
                </div>

                <div class=" form-group col-md-2">
                  <label for="cout_device">Date expiration</label>
                  <input type="date" name="date_exp" id="date_exp" class="form-control">
                </div>


                <!-- <div class="hide form-group col-md-1">
                  <label for="cout_device">Cout D</label>
                  <input type="text" name="cout_device" id="cout_device" class="form-control" value="1">
                </div> -->
                <div class=" hide form-group col-md-1">
                  <label for="f_approch">F.approch</label>
                  <input type="text" name="f_approch" id="f_approch" class="form-control" value="1">
                </div>
                <div class="form-group col-md-2">
                  <button id="addProduct" type="button" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 30px;">Ajouter</button>
                </div>
              </div>

              <style>
                .hide {
                  display: none !important;
                }
              </style>
              <div class="table-responsive">
                <table class="table" cellpadding="0" cellspacing="0" id="box-table-a" summary="Employee Pay Sheet">
                  <thead>
                    <tr>
                      <th scope="col">Produit</th>
                      <th scope="col">Dépot</th>
                      <th width="102" scope="col">Prix</th>
                      <th width="109" scope="col">Qte</th>
                      <th width="109" scope="col">Date Exp</th>
                      <th width="109" scope="col">Poid</th>
                      <th width="129" scope="col">PU*Qte*Devise</th>
                      <th width="129" scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody id="detail_commande">
                    <?php
                    $detail_achat = new detail_achat();
                    $detail_achat->insert();
                    $data = $detail_achat->selectAllNonValide();
                    $total = 0;

                    foreach ($data as $ligne) {

                    ?>
                      <tr>
                        <td><?php echo $ligne->designation; ?></td>
                        <td>
                          <?php echo $ligne->depot; ?>
                        </td>
                        <td><?php echo $ligne->prix_produit; ?></td>
                        <td><?php echo $ligne->qte_achete; ?></td>
                        <td><?php echo $ligne->date_expiration; ?></td>
                        <td><?php echo $ligne->poid * $ligne->qte_achete;
                            $somme_poid += $ligne->poid * $ligne->qte_achete;
                            ?> g </td>
                        <td width="90" style="text-align: right;">
                          <?php echo number_format($ligne->prix_produit, 2, '.', ' ');
                          $total += $ligne->qte_achete * $ligne->prix_produit;
                          ?>
                        </td>
                        <td> <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                            <i class="simple-icon-trash" style="font-size: 15px;"></i>
                          </a>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>
                    <tr>
                      <td colspan="4" style="text-align: center;font-size: 15px;"> <b>Total</b> </td>
                      <td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right;"><?php echo number_format($total, 2, '.', ' '); ?></b></td>

                    </tr>

                  </tbody>
                </table>
              </div>
              <div class="float-sm-right text-zero">
                <button type="button" id="senddata" class="btn btn-primary btn-lg  mr-1 ">Enregistrer</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width:80%;">
      <div class="modal-content" style="padding:0px; background-color:initial;">
        <div class="modal-body" style="padding:0px; background-color:initial;">
          <div style=" background-color:white; border-radius:8px; overflow:hidden; border-top:2px solid #2a93d5;">
            <div>
              <h2 style="
														padding: 8px;
														font-size: 14pt;
														font-weight: 700;
														color: #555;
														margin-bottom: 0px;
														padding-bottom: 2px;
													">Produits</h2>
              <h3 style="
														padding: 8px;
														font-size: 10.4pt;
														font-weight: 300;
														color: #2a93d5;
														padding-top: 0px;
													">Consultation des produits</h3>
            </div>
            <div id="barcode_container">
              <div style="padding: 8px;">
                <div class="col-xl-12 col-lg-12 mb-4">
                  <div class="card h-100">
                    <div class="card-body">

                      <div class="form-row">
                        <div class="form-group   col-md-3">
                          <label> &nbsp;&nbsp;Code / Nom : </label>
                          <input type="text" id="keywords" class="form-control" placeholder="Recherche par Num ou Nom" onkeyup="searchFilter()" />
                        </div>
                        <div class="form-group   col-md-2">
                          <label> &nbsp;&nbsp;Emplacement : </label>
                          <select id="depot" onchange="searchFilter()" class="form-control">
                            <option value="0">Tous</option>
                            <?php foreach ($data_depot as $depot) : ?>
                              <option value="<?php echo $depot->id; ?>"><?php echo $depot->nom ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="form-group   col-md-2">
                          <label> Fournisseur : </label>
                          <select id="fournisseur" class="form-control select2-single" onchange="searchFilter()">
                            <option value='-1' selected>Choisir un fournisseur</option>
                            <?php foreach ($fournisseurs_all as $fr) : ?>
                              <option value="<?php echo $fr->nom; ?>"><?php echo $fr->nom ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="form-group   col-md-3">
                          <label> &nbsp;&nbsp;Catégorie :</label>
                          <select id="categorie" onchange="searchFilter()" class="form-control select2-single">
                            <option value="0">Choisir la catégorie</option>
                            <?php foreach ($data_cat as $cat) : ?>
                              <option value="<?php echo $cat->id_categorie; ?>"><?php echo $cat->nom ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="form-group col-md-2">
                          <label> &nbsp;&nbsp;Stock :</label>
                          <select name="stock" id="stock" onchange="searchFilter()" class="form-control">
                            <option value="all">Tous</option>
                            <option value="1"> stock > 0</option>
                            <option value="0"> stock = 0</option>
                          </select>
                        </div>
                      </div>
                      <div style="
						color: #555;
						margin-bottom: 4px;
					"><span id="el_nbr_count">0</span> produits ajoutés</div>
                      <div id="posts_content">
                        <div class="table-responsive">
                          <table id="datatables" style="width: 100%">
                            <thead id="advanced_tbody">
                              <tr style="background-color: #e5e7e5;">
                                <th>R&eacute;f</th>
                                <th>D&eacute;signation</th>
                                <th>P.achat</th>
                                <th>Depot</th>
                                <th>PU</th>
                                <th>Qte</th>
                                <th>Cout D</th>
                                <th>F.Approch</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <center>
                <p id="barcode_footer" style="font-weight:900; font-size:16pt;"></p>
              </center>
            </div>
            <div class="" style="font-weight:900; font-size:16pt;height:8px;background-color:#2a93d5;border-style: none;color: white;font-size: 12pt;font-weight: 900;width: 100%; cursor:pointer; ">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    function searchFilter() {

      var keywords = $('#keywords').val();
      var depot = $('#depot').val();
      var categorie = $('#categorie').val();
      var stock = $('#stock').val();
      var sortBy = $('#sortBy').val();
      var fournisseur = $('#fournisseur').val();

      $.ajax({
        type: 'POST',
        url: "<?php echo BASE_URL . 'views/produit/'; ?>controle.php",
        data: 'act=filter_vente&keywords=' + keywords + '&depot=' + depot + '&categorie=' + categorie + '&stock=' + stock + '&sortBy=' + sortBy + '&fournisseur=' + fournisseur,
        beforeSend: function() {
          $('.loading-overlay').show();
        },
        success: function(html) {
          $('#posts_content').html(html);
          $('#datatables').dataTable({
            responsive: false,
            searching: false,
            order: [

              [0, "desc"]

            ],

            dom: 'Bfrtip',

            buttons: [],

            pageLength: 20,

            language: {

              paginate: {

                previous: "<i class='simple-icon-arrow-left'></i>",

                next: "<i class='simple-icon-arrow-right'></i>"

              }

            },

            drawCallback: function() {

              $($(".dataTables_wrapper .pagination li:first-of-type")).find("a").addClass("prev"),

                $($(".dataTables_wrapper .pagination li:last-of-type")).find("a").addClass("next"),

                $(".dataTables_wrapper .pagination").addClass("pagination-sm")

            }

          });
        }
      });
    }

    $(document).ready(function() {

      $('body').on('click', '.add_v2_btn', function() {

        //get values
        let id = $(this).data('id');
        let prix = $('#prix_' + id).val();
        let qte = $('#qte_' + id).val();
        let unite = $('#unite_' + id).val();
        let depot = $('#depot_' + id).val();
        let cout = $('#cout_' + id).val();
        let approch = $('#approch_' + id).val();

        //add product
        //                var id_produit = $(this).val();
        //                var unit_qte=0;
        //                var unit='';

        //                if(unite!=0)
        //                {
        //                    unit_qte = unite;
        //                    if($('#unite').val()!="")
        //					{
        //                        unit= unite;
        //                    }else
        //					{
        //						unit = '';
        //                    }
        //                }
        //                else
        //                {
        //                    unit_qte=qte;
        //                }
        //                    if(unite!=0 )
        //					{
        //                        if($('#unite').val()!=""){
        //                        unit=$('#unite').val();
        //                        }else{
        //                           // alert('Entrer Votre Unité (Kg-M-M2...)');
        //                           // throw '';
        //							unit = '';
        //                        }
        //                    }

        $.ajax({
          type: "POST",
          url: "<?php echo BASE_URL . 'views/achat/'; ?>controle.php",
          data: {
            act: "addProduct",
            id_produit: id,
            prix_produit: prix,
            qte_achete: qte,
            id_depot: depot,
            cout_device: cout,
            f_approch: approch
          },
          success: function(data) {
            $('#detail_commande').html(data);
          }
        });
      });

      $("#rech").keyup(function() {

        var id = $(this).val();
        $.ajax({
          type: "POST",
          url: "<?php echo BASE_URL . 'views/achat/'; ?>controle.php",
          data: {
            act: "rech",
            id: id
          },
          success: function(data) {

            $('#id_produit').html(data);
            $("#id_produit").change();
          }
        });
      });


      $("#rech_designation").keyup(function() {

        var designation = $(this).val();
        $.ajax({
          type: "POST",
          url: "<?php echo BASE_URL . 'views/achat/'; ?>controle.php",
          data: {
            act: "rech_designation",
            designation: designation
          },
          success: function(data) {

            $('#id_produit').html(data);
            $("#id_produit").change();
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
      $("#id_categorie").change(function() {



        var id_categorie = $(this).val();
        $.ajax({
          type: "POST",
          url: "<?php echo BASE_URL . 'views/achat/'; ?>controle.php",
          data: {
            act: "getproduit",
            id_categorie: id_categorie
          },
          success: function(data) {
            console.log(data);
            $('#id_produit').html(data);
            $("#id_produit").change();
          }
        });

      });
      $("#id_produit").change(function() {



        var id_produit = $(this).val();
        $.ajax({
          type: "POST",
          url: "<?php echo BASE_URL . 'views/achat/'; ?>controle.php",
          data: {
            act: "getPrix",
            id_produit: id_produit
          },
          success: function(data) {

            console.log(data);
            var tab = data.split('/');
            $('#prix_produit').val(tab[0]);
            $('#reste_stock').html(tab[1]);
          }
        });

      });

      $("#addProduct").click(function() {

        var id_produit = $(this).val();

        $.ajax({
          type: "POST",
          url: "<?php echo BASE_URL . 'views/achat/'; ?>controle.php",
          data: {
            act: "addProduct",
            id_produit: $("#id_produit").val(),
            prix_produit: $("#prix_produit").val(),
            qte_achete: $("#qte_achete").val(),
            date_expiration: $("#date_exp").val(),
            id_depot: $("#id_depot").val(),
            cout_device: $("#cout_device").val(),
            f_approch: $("#f_approch").val()
          },
          success: function(data) {
            console.log(data);
            $('#detail_commande').html(data);
          }
        });

      });

      $('body').on("click", ".delete", function(event) {
        event.preventDefault();


        var btn = $(this);
        swal({
          title: 'Êtes-vous sûr?',
          text: "Voulez vous vraiment Supprimer ce Client !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Oui, Supprimer !'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              type: "POST",
              url: "<?php echo BASE_URL . 'views/achat/'; ?>controle.php",
              data: {
                act: "deleterow",
                id_detail: btn.data('id')
              },
              success: function(data) {

                swal(
                  'Supprimer',
                  'Client a ete bien Supprimer',
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


      $("body").on("click", "#senddata", function(event) {
        event.preventDefault();

        var form = document.getElementById('addform');
        $.ajax({
          type: "POST",
          url: "<?php echo BASE_URL . 'views/achat/'; ?>controle.php",
          data: new FormData(form),
          dataType: 'text',
          cache: false,
          contentType: false,
          processData: false,
          success: function(data) {

            if (data == "success") {

              swal(
                'Achat',
                'achat a ete bien Ajouter',
                'success'
              ).then((result) => {

                history.pushState({}, "", `<?php echo BASE_URL . "achat/index.php"; ?>`);
                location.reload();
                $.ajax({

                  method: 'POST',
                  data: {
                    ajax: true
                  },
                  url: `<?php echo BASE_URL . "views/achat/index.php"; ?>`,
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

      $('#devise_produit').change(()=>{
        $('#cout_device').val($('#devise_produit').val()) ; 
      })


    });
  </script>