<?php
if (isset($_POST['ajax'])) {
  session_start();
  include('../../evr.php');/*$conn = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
$result = $conn->query('select * from produit');
$data = $result->fetch_assoc();echo 'rows: ' . $data['code_bar'];var_dump($data);*/
}
$societe = new societe();
$info = $societe->selectById(1);

$_SESSION['LIMIT'] = 10;
$limit = $_SESSION['LIMIT'];
/*if($_GET['datatables_length']!='')
{
  $_SESSION['LIMIT'] = $_GET['datatables_length'];
}*/
$page = 'produit';
$produit = new produit();

$query = $produit->selectQuery("select p.* from produit p where archive=0 order by p.designation asc LIMIT 10");

$queryNum = $produit->selectQuery("SELECT COUNT(*) as numrows FROM produit where archive=0");


$pagConfig = array(
  'totalRows' => $queryNum[0]->numrows,
  'perPage' => $limit,
  'link_func' => 'searchFilter'
);
$depot = new depot();
$data_depot = $depot->selectAll();

$data_cat = $produit->selectAllCat();
$pagination = new Pagination($pagConfig);

$fournisseurs = connexion::getConnexion()->query("SELECT fournisseur AS nom FROM produit WHERE fournisseur IS NOT NULL GROUP BY fournisseur ORDER BY fournisseur ASC ")->fetchAll(PDO::FETCH_OBJ);

?>

<style>
  #barcode_container td,
  th {
    padding: 6px 8px;
    border-color: #ccc;
  }
</style>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Liste Des Produits</h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="produit/add.php">AJOUTER</button>

          <a target="_blanck" class="btn btn-primary btn-lg  mr-1 url notlink" target="_blanck" href="<?php echo BASE_URL . "views/produit/import.php" ?>">Importer Les Produits</a>
          <a id="export" class="btn btn-info btn-lg  mr-1" target="_blank" href="<?php echo BASE_URL . 'exportation.php' ?>">Exporter Les Produits</a>
        </div>

      </div>

      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">



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
                <?php foreach ($fournisseurs as $fournisseur) : ?>
                  <option value="<?php echo $fournisseur->nom; ?>"><?php echo $fournisseur->nom ?></option>
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

          <div id="posts_content">
            <div class="table-responsive ">
              <table class="table datatables table-striped table-bordered" id="myTable" style="width: 100%">
                <thead>
                  <tr>
                    <th>Numero</th>
                    <th>R&eacute;f</th>
                    <th>D&eacute;signation</th>
                    <th> Q_stock</th>
                    <th> P.achat</th>
                    <th> P.Gros</th>
                    <th> P.Détail</th>
                    <th> P.Wanny</th>
                    <th> CMUP </th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($query as $ligne) { ?>

                    <tr>
                      <td> <?php echo $ligne->id_produit; ?>
                      <td> <?php if (!empty($ligne->image)) { ?>
                          <a class="fancybox" title="image de <?php echo $ligne->designation; ?>" href="../upload/produit/<?php echo $ligne->image; ?>"> <img src="../icon/image.png" width="17" height="19" class="icon" /> </a>
                        <?php } ?>
                        <?php echo $ligne->code_bar; ?>
                      </td>
                      <td class="designation"> <?php echo $ligne->designation;
                                                if ($ligne->designation_ar != "") {
                                                  echo "/" . $ligne->designation_ar;
                                                } ?> </td>
                      <td> <?php echo $ligne->qte_actuel; ?> </td>
                      <td> <?php echo $ligne->prix_achat; ?> </td>
                      <td> <?php echo $ligne->prix_vente; ?> </td>
                      <td> <?php echo $ligne->prix_vente2; ?> </td>
                      <td> <?php echo $ligne->prix_vente3; ?> </td>

                      <td> <?php
                            $data =  $produit->getCmup($ligne->id_produit);
                            $stock_qte = $data[0]->qte_stock;
                            $up = 0;
                            $down = 0;
                            foreach ($data as $key => $d) {
                              if ($stock_qte > 0) {
                                if ($stock_qte > $d->qte_achat) {
                                  $dif = $stock_qte - $d->qte_achat;
                                  $up = $up + $dif * $d->prix_achat;
                                  $down = $down + $dif;
                                  $stock_qte = $stock_qte - $d;
                                } else {

                                  $up = $stock_qte * $d->prix_achat;
                                  $down = $stock_qte;

                                  $stock_qte = 0;
                                }
                              }
                            }

                            print_r(number_format($up / $down, 2, '.', ''));

                            ?>
                      </td>
                      <td>
                        <?php if (auth::user()['privilege'] == 'Admin') { ?>
                          <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_produit; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                            <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
                          </a>
                        <?php } ?>
                        <?php if (auth::user()['privilege'] == 'Admin' || auth::user()['privilege'] == 'User+') { ?>
                          <a class="badge badge-warning mb-2  url notlink" data-url="produit/update.php?id=<?php echo $ligne->id_produit; ?>" style="color: white;cursor: pointer;" title="Modifier" href="javascript:void(0)">
                            <i class="glyph-icon iconsmind-Pen-5" style="font-size: 15px;"> </i>
                          </a>
                        <?php } ?>

                        <a class="badge badge-secondary mb-2 static" data-id="<?php echo $ligne->id_produit; ?>" data-toggle="modal" data-backdrop="static" data-target="#exampleModalRight" style="color: white;cursor: pointer;" title="Etat vente/achat" href="javascript:void(0)">

                          <i class="simple-icon-pie-chart" style="font-size: 15px;"></i>
                        </a>
                        <?php if (auth::user()['privilege'] == 'Admin') { ?>
                          <?php if ($ligne->archive == 0) { ?>
                            <a class="badge badge-primary mb-2 archive" data-id="<?php echo $ligne->id_produit; ?>" data-arc="1" style="color: white;cursor: pointer;" title="Archiver">
                              <i class="glyph-icon simple-icon-social-dropbox" style="font-size: 15px;"></i> </a>
                          <?php } else { ?>
                            <a class="badge badge-dark mb-2 archive" data-id="<?php echo $ligne->id_produit; ?>" data-arc="0" style="color: white;cursor: pointer;" title="Disarchiver"> <i class="glyph-icon iconsmind-Box-withFolders" style="font-size: 15px;"> </i> </a>
                        <?php }
                        } ?>

                        <a class="badge badge-success mb-2 static depots_btn" data-id="<?php echo $ligne->id_produit; ?>" data-toggle="modal" data-target="#depotsModal" style="color: white;cursor: pointer;" title="Depots" href="javascript:void(0)" designation="<?php echo $ligne->designation ?>">

                          <i class="simple-icon-pie-chart" style="font-size: 15px;"></i>
                        </a>

                        <?php if ($ligne->image != '' || $ligne->deux_image != '') {
                          $img =  $ligne->image != '' ? $ligne->image : $ligne->deux_image; ?>
                          <a class="badge badge-success " data-fancybox data-caption="<?php echo $ligne->designation; ?> <br> Prix : <?php echo $ligne->prix_vente ?> DH" style="color: white;cursor: pointer;" title="<?php echo $ligne->designation; ?>" href="<?php echo BASE_URL . 'upload/produit/' . $img; ?>">
                            <i class="simple-icon-picture" style="font-size: 15px;"> </i>
                          </a>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <?php echo $pagination->createLinks(); ?>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="depotsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                  <label for="sku">Désignation</label>
                  <input id="sku" type="text" class="form-control" disabled>
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

    <div class="modal fade modal-right" id="exampleModalRight" tabindex="-1" role="dialog" aria-labelledby="exampleModalRight" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Etat produit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="Staticform" method="post" name="form_produit" action="<?php echo BASE_URL . 'views/produit/'; ?>controle.php" enctype="multipart/form-data" target="_blanck">
            <div class="modal-body">



              <input type="hidden" name="act" value="getetat">
              <input type="hidden" name="id" id="idstatic">
              <div class="form-group">

                <h5 class="mb-4">Date début</h5>

                <input class="form-control datepicker" autocomplete="off" placeholder="Date" name="dd">
              </div>
              <div class="form-group">

                <h5 class="mb-4">Date fin</h5>

                <input class="form-control datepicker" autocomplete="off" placeholder="Date" name="df">
              </div>

              <h5 class="mb-4">Type</h5>
              <div class="mb-4">
                <div class="custom-control custom-radio">

                  <input type="radio" id="customRadio1" value="vente" checked="" name="etatProduit" class="custom-control-input">

                  <label class="custom-control-label" for="customRadio1">Vente :</label>

                </div>
                <div class="custom-control custom-radio">

                  <input type="radio" id="customRadio2" value="achat" name="etatProduit" class="custom-control-input">

                  <label class="custom-control-label" for="customRadio2">Achat : </label>

                </div>
              </div>


            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
              <input type="submit" name="submit" value="Afficher" class="btn btn-primary">

            </div>
          </form>
        </div>
      </div>
    </div>


    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Liste des reservations</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="etatstatic">

          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      function searchFilter(page_num) {


        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords').val();
        var depot = $('#depot').val();
        var categorie = $('#categorie').val();
        var stock = $('#stock').val();
        var prix = $('#prix').val();
        var operateur = $('#operateur').val();
        var sortBy = $('#sortBy').val();
        var fournisseur = $('#fournisseur').val();

        $.ajax({
          type: 'POST',
          url: "<?php echo BASE_URL . 'views/produit/'; ?>controle.php",
          data: 'act=pagination&keywords=' + keywords + '&page=' + page_num + '&depot=' + depot + '&categorie=' + categorie + '&stock=' + stock + '&sortBy=' + sortBy + '&prix=' + prix + '&operateur=' + operateur + '&fournisseur=' + fournisseur,
          beforeSend: function() {
            $('.loading-overlay').show();
          },
          success: function(html) {
            $('#posts_content').html(html);
            $('#myTable').DataTable({
              responsive: true,
              columnDefs: [{
                  "targets": [0],
                  "visible": false,
                },
                {
                  responsivePriority: 1,
                  targets: -4
                },
                {
                  responsivePriority: 2,
                  targets: -2
                }
              ],
              bPaginate: false,
              bFilter: false,
              bInfo: false,
            });
          }
        });
      }



      $(document).ready(function() {

        $("input.datepicker").datepicker({
          format: 'yyyy-mm-dd',
          templates: {
            leftArrow: '<i class="simple-icon-arrow-left"></i>',
            rightArrow: '<i class="simple-icon-arrow-right"></i>'
          }
        })
        $('#myTable').DataTable({
          responsive: true,
          columnDefs: [{
              "targets": [0],
              "visible": false,
            },
            {
              responsivePriority: 1,
              targets: -4
            },
            {
              responsivePriority: 2,
              targets: -2
            }
          ],
          bPaginate: false,
          bFilter: false,
          bInfo: false,
        });

        $('body').on("click", ".delete", function(event) {
          event.preventDefault();

          var btn = $(this);
          swal({
            title: 'Êtes-vous sûr?',
            text: "Voulez vous vraiment Supprimer ce produit !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, Supprimer !'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL . 'views/produit/'; ?>controle.php",
                data: {
                  act: "delete",
                  id: btn.data('id')
                },
                success: function(data) {

                  swal(
                    'Deleted',
                    'Your file has been deleted.',
                    'success'
                  ).then((result) => {

                    btn.parents("td").parents("tr").remove();
                  });

                }
              });

            }
          });

        });


        $('body').on("click", ".archive", function(event) {
          event.preventDefault();


          var btn = $(this);
          swal({
            title: 'Êtes-vous sûr?',
            text: "Voulez vous vraiment Archiver ce produit !!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#145388',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, Archiver!'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL . 'views/produit/'; ?>controle.php",
                data: {
                  act: "archive",
                  id: btn.data('id'),
                  val: btn.data('arc')
                },
                success: function(data) {

                  swal(
                    "Archived",
                    'Your Product has been archived.',
                    'success'
                  ).then((result) => {
                    btn.parents("td").parents("tr").remove();
                  });

                }
              });

            }
          });

        });


        $('body').on("click", ".static", function(event) {
          event.preventDefault();

          var btn = $(this);
          $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL . 'views/produit/'; ?>controle.php",
            data: {
              act: "getName",
              id: btn.data('id')
            },
            success: function(datas) {
              var data = datas.split(';;;');
              $('#exampleModalRight .modal-title').html("Etat produit " + data[1]);
              $('#idstatic').val(data[0]);
            }
          });



        });

        $('body').on("click", ".depots_btn", function() {
          let designation = $(this).attr('designation');
          let id_product = $(this).data('id');

          //changer designation
          $('#sku').val(designation);

          //get emplacements
          $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL . 'views/produit/'; ?>controle.php",
            data: {
              act: "getDepots",
              id: id_product
            },
            success: function(data) {
              $('#depots_tbl').html(data);
            }
          });

        });
        /* $("#Staticform" ).on( "submit", function( event ) {
             event.preventDefault();

             var form = $( this );
             $.ajax({
                type: "POST",
                url: "<?php //echo BASE_URL.'views/produit/' ;
                      ?>controle.php",
                data: new FormData(this),
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                

                      $('#etatstatic').html(data);  
                         
                                              
                }
            });
           
            });*/

      });
    </script>