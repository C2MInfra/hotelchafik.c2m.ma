<?php

if (isset($_POST['ajax'])) {
  echo '<script>window.location.reload()</script>';
  exit;
  include('../../evr.php');
}
$boncommandevendeur = new boncommandevendeur();

$data = $boncommandevendeur->selectAll3(date('Y') . '-' . date('m'));

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="container-fluid disable-text-selection">

  <div class="row">

    <div class="col-12">

      <div class="mb-2">

        <h1>Les commandes vendeurs</h1>

        <div class="float-sm-right text-zero">

          <?php if (auth::user()['privilege'] != 'Vendeur') : ?>
            <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="commande-vendeurs/add.php">AJOUTER</button>
          <?php endif; ?>
        </div>

      </div>

      <div class="separator mb-5"></div>

    </div>

  </div>

  <div class="row">

    <div class="col-xl-12 col-lg-12 mb-4">

      <div class="card h-100">

        <div class="card-body">



          <form method="get" name="frm" id="formfilter">

            <input type="hidden" name="act" value="filter">

            <div class="form-row ">

              <div class="form-group col-md-2">

                <h4 class="mt-5 ">Mois de recherche : </h4>

              </div>

              <div class="form-group col-md-2">

                <label for="id_client">A : </label>

                <select name='anne' class="form-control" id="anne">

                  <option value="0">Tous</option>

                  <?php for ($d = Date("Y"); $d >= 2009; $d--)

                    echo "<option value='$d'> $d</option>"; ?>

                </select>

              </div>

              <div class="form-group col-md-2 ">

                <label> M: </label>

                <select name='mois' class="form-control" id="mois">
                  <option value="0">Tous</option>
                  <?php for ($m = 1; $m <= 9; $m++)

                    echo "<option value='0$m' >0$m</option>"; ?>

                  <?php for ($m = 10; $m <= 12; $m++)

                    echo "<option value='$m' >$m</option>"; ?>

                </select>

              </div>



              <div class="form-group col-md-2">

                <button type="submit" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 30px;">Afficher</button>

              </div>

            </div>

          </form>

          <div id="results">


            <table class="table responsive table-striped table-bordered" id="datatables">

              <thead>

                <tr>
                  <th scope="col">Id</th>

                  <th class="col"> Client</th>
                  <th class="col"> Date</th>

                  <th scope="col"> Montant</th>

                  <th scope="col"> Etat</th>

                  <th scope="col"> Reste</th>

                  <th scope="col"> Remarque</th>

                  <th scope="col">Actions</th>

                </tr>

              </thead>

              <tbody>

                <?php

                foreach ($data as $ligne) {
                ?>

                  <tr>

                    <td> <?php echo $ligne->id_bon; ?></td>

                    <td>
                      <?php echo $ligne->client ?>
                    </td>

                    <td>
                      <?php echo $ligne->date_bon; ?>
                    </td>
                    <td style="text-align: right;" class="nowrap" data-href="#">

                      <button class="mb-1" style="
    color: #034dff;
    border-style: none;
">
                        <?php
                        if ($ligne->motunitv != 0 || !empty($ligne->motunitv)) {
                          echo number_format($ligne->motunitv, 2, '.', ' ');
                        } else {
                          echo number_format($ligne->montantv, 2, '.', ' ');
                        }
                        ?>
                      </button>
                      &nbsp;&nbsp;

                    </td>
                    <td>
                      <?php if ($ligne->etat == 'En cours') : ?>
                        <span class="badge badge-success">En cours</span>
                      <?php else : ?>
                        <span class="badge badge-danger">Retour</span>
                      <?php endif; ?>
                    </td>
                    <td style="text-align: right;"> <?php

                                                    //$query = $result = connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_commande where id_bon=" . $ligne->id_bon);

                                                    $query = $result = connexion::getConnexion()->query("SELECT IFNULL((SELECT SUM((qte_vendu - qte_actuel)*prix_produit) FROM detail_bon_vendeur WHERE id_bon = " . $ligne->id_bon . " GROUP BY id_bon) , 0)  - IFNULL((SELECT SUM(montant) FROM reg_vendeur WHERE id_bon = " . $ligne->id_bon . " GROUP BY id_bon) , 0) AS reste");



                                                    $result = $query->fetchColumn();


                                                    print_r($result);

                                                    //$paye = $result != null  ?  $result : 0;
                                                    //if ($ligne->motunitv != 0 || !empty($ligne->motunitv)) {
                                                    //$tr = $ligne->motunitv - $paye;
                                                    //} else {
                                                    //$tr = $ligne->montantv - $paye;
                                                    // }
                                                    //


                                                    // if ($paye == 0) {
                                                    //  $tr = 0;
                                                    //} else {
                                                    //if ($ligne->motunitv != 0 || !empty($ligne->motunitv)) {
                                                    // $tr = $ligne->motunitv - $paye;
                                                    //} else {
                                                    // $tr = $ligne->montantv - $paye;
                                                    // }
                                                    //}


                                                    // $tr = ($tr < 0 && $tr >= -250) ? 0 : $tr;
                                                    // echo number_format($tr, 2, '.', ' ');
                                                    //
                                                    ?> &nbsp;&nbsp;

                    </td>
                    <td> <?php echo strlen($ligne->remarque) > 50 ? substr($ligne->remarque, 0, 50) . "..." : $ligne->remarque; ?> </td>


                    <td class="nowrap">

                      <?php if (auth::user()['privilege'] == 'Admin') { ?>

                        <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>

                          <i class="simple-icon-trash" style="font-size: 15px;"></i>

                        </a>

                        <a class="badge badge-success mb-2  url notlink" data-url="reg_vendeur/index.php?id=<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)'>
                          <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>
                        </a>

                        <a class="badge badge-warning mb-2  url notlink" data-url="commande-vendeurs/update.php?id=<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="Modifier" href="javascript:void(0)">

                          <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>

                        </a>
                      <?php } ?>


                      <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL . "views/commande-vendeurs/facture.php?id=" . $ligne->id_bon; ?>&h=15" target="_black">

                        <i class=" simple-icon-printer" style="font-size: 15px;"></i>

                      </a>


                      <?php

                      $id_vente = connexion::getConnexion()->query("SELECT id_vente FROM vente WHERE id_bon = " . $ligne->id_bon)->fetchColumn();

                      ?>

                      <a class="badge badge-primary mb-2 notlink" style="background-color: #d322e8!important;color: white;cursor: pointer;" title="Ticket" href='<?php echo BASE_URL . '/views/vente/ticket.php?id=' . $id_vente; ?>' target="_black">

                        <i class=" iconsmind-Billing" style="font-size: 15px;"></i>

                      </a>


                      <a class="badge badge-secondary mb-2 url notlink" data-url="detail_bon_vendeur/index.php?id=<?php echo $ligne->id_bon; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">
                        <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>
                      </a>



                      <?php if ($ligne->etat == 'En cours' && auth::user()['privilege'] == 'Admin') : ?>
                        <a class="badge badge-danger  url notlink mb-2" data-url="<?php echo 'commande-vendeurs/retour_bon.php?id=' . $ligne->id_bon ?>" href="javascript:void(0)" style="color: white;cursor: pointer;" title="Retour" href='javascript:void(0)'>
                          <i class="fa-solid fa-arrow-rotate-left" style="font-size: 15px;"></i>
                        </a>
                      <?php endif; ?>

                      <?php if ($ligne->etat == 'En cours' && auth::user()['privilege'] == 'Vendeur') : ?>
                        <a class="badge badge-warning mb-2 " style="color: white;cursor: pointer;" title="Vente" href='<?php echo BASE_URL ?>vente_bon/index.php?bon=<?php echo $ligne->id_bon ?>'>
                          <i class="iconsmind-Add-Cart" style="font-size: 15px;"></i>
                        </a>
                      <?php endif; ?>
                      <a class="badge badge-success" href='<?php echo BASE_URL ?>commande-vendeurs/map.php?id=<?php echo $ligne->id_bon ?>' style="font-size:10pt;">
                        <i class="fa-solid fa-map-location-dot"></i>
                      </a>
                    </td>
                  </tr>
                <?php } ?>

              </tbody>

            </table>

          </div>

        </div>

      </div>

    </div>

    <div class="modal fade modal-right" id="exampleModalRight" tabindex="-1" role="dialog" aria-labelledby="exampleModalRight" aria-hidden="true">

      <div class="modal-dialog" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">Etat client</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">&times;</span>

            </button>

          </div>

          <form id="Staticform" method="post" name="form_client" enctype="multipart/form-data">

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

                  <label class="custom-control-label" for="customRadio1">Vente </label>



                </div>

                <div class="custom-control custom-radio">



                  <input type="radio" id="customRadio2" value="achat" name="etatProduit" class="custom-control-input">

                  <label class="custom-control-label" for="customRadio2">Achat </label>



                </div>

              </div>



            </div>

            <div class="modal-footer">

              <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>

              <input type="submit" name="submit" value="Afficher" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">

            </div>

          </form>

        </div>

      </div>

    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">

      <div class="modal-dialog modal-lg">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">Liste des Ventes des clients : kit des implants</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">&times;</span>

            </button>

          </div>

          <div class="modal-body" id="etatstatic">



          </div>

        </div>

      </div>

    </div>

  </div>

</div>

<script type="text/javascript">
  $(document).ready(function() {

    $("input.datepicker").datepicker({

      format: 'yyyy-mm-dd',

      templates: {

        leftArrow: '<i class="simple-icon-arrow-left"></i>',

        rightArrow: '<i class="simple-icon-arrow-right"></i>'

      }

    })

    $('#datatables').dataTable({
      responsive: true,
      order: [

        [0, "desc"]

      ],

      dom: 'Bfrtip',

      buttons: [{

          extend: 'excelHtml5',

          title: "liste des ventes",

          exportOptions: {

            columns: [0, 1, 2, 3, 4, 5, ]

          }

        },

        {

          extend: 'pdfHtml5',

          title: "liste des ventes",

          exportOptions: {

            columns: [0, 1, 2, 3, 4, 5, ]

          }

        },

        {

          extend: 'csvHtml5',

          title: "liste des ventes",

          exportOptions: {

            columns: [0, 1, 2, 3, 4, 5, ]

          }

        }

      ],

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

    $('body').on("click", ".delete", function(event) {

      event.preventDefault();

      var btn = $(this);

      swal({

        title: 'Êtes-vous sûr?',

        text: "Voulez vous vraiment Supprimer ce bon !",

        type: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#d33',

        cancelButtonColor: '#3085d6',

        confirmButtonText: 'Oui, Supprimer !'

      }).then((result) => {

        if (result.value) {

          $.ajax({

            type: "POST",

            url: "<?php echo BASE_URL . 'views/commande-vendeurs/'; ?>controle.php",

            data: {

              act: "delete",

              id: btn.data('id')

            },

            success: function(data) {

              swal(

                'Supprimer',

                'Client a ete bien Supprimer',

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

        text: "Voulez vous vraiment Archiver ce client !!",

        type: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#145388',

        cancelButtonColor: '#3085d6',

        confirmButtonText: 'Oui, Archiver!'

      }).then((result) => {

        if (result.value) {

          $.ajax({

            type: "POST",

            url: "<?php echo BASE_URL . 'views/commande-vendeurs/'; ?>controle.php",

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

        url: "<?php echo BASE_URL . 'views/commande-vendeurs/'; ?>controle.php",

        data: {

          act: "getName",

          id: btn.data('id')

        },

        success: function(datas) {

          var data = datas.split(';;;');

          $('#exampleModalRight .modal-title').html("Etat client " + data[1]);

          $('#idstatic').val(data[0]);

        }

      });

    });

    $("#Staticform").on("submit", function(event) {

      event.preventDefault();

      var form = $(this);

      $.ajax({

        type: "POST",

        url: "<?php echo BASE_URL . 'views/commande-vendeurs/'; ?>controle.php",

        data: new FormData(this),

        dataType: 'text', // what to expect back from the PHP script, if anything

        cache: false,

        contentType: false,

        processData: false,

        success: function(data) {

          $('#etatstatic').html(data);

        }

      });

    });
    //--------------------------------------------------
    $("#formfilter").on("submit", function(event) {

      event.preventDefault();

      $("#results").html('<div class="col-md-12"><br><br><br><br><br><br><div class="loading"></div></div>');

      var form = $(this);

      $.ajax({

        type: "POST",

        url: "<?php echo BASE_URL . 'views/commande-vendeurs/'; ?>controle.php",

        data: new FormData(this),

        dataType: 'text',

        cache: false,

        contentType: false,

        processData: false,
        success: function(data) {

          $("#results").html(data);

          $('#datatables').dataTable({
            responsive: true,
            order: [

              [0, "desc"]

            ],

            dom: 'Bfrtip',

            buttons: [{

                extend: 'excelHtml5',

                title: "liste des ventes " + $("#mois").val() + "-" + $("#anne").val(),

                exportOptions: {

                  columns: [0, 1, 2, 3, 4, 5, ]

                }

              },

              {

                extend: 'pdfHtml5',

                title: "liste des ventes " + $("#mois").val() + "-" + $("#anne").val(),

                exportOptions: {

                  columns: [0, 1, 2, 3, 4, 5, ]

                }

              },

              {

                extend: 'csvHtml5',

                title: "liste des ventes " + $("#mois").val() + "-" + $("#anne").val(),

                exportOptions: {

                  columns: [0, 1, 2, 3, 4, 5, ]

                }

              }

            ],

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

    });

  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const cols = document.querySelectorAll('td[data-href]');
    console.log(cols);
  });
</script>