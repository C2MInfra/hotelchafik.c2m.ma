<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$client = new client();
$data = $client->selectAllNonArchive();
?>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Liste des clients</h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="client/add.php">AJOUTER</button>
          <a target="_blanck" class="btn btn-primary btn-lg  mr-1 url notlink" target="_blanck" href="<?php echo BASE_URL . "views/client/import.php" ?>">Importer Les Clients</a>

        </div>

      </div>

      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">



    <div class="col-xl-12 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <?php if (count($data) > 0) { ?>

            <table class="table  responsive table-striped table-bordered table-hover" id="datatables">
              <thead>
                <tr>
                  <th width="5%">Id</th>
                  <th width="35%">Infos</th>
                  <th width="10%">T&eacute;l&eacute;phone</th>
                  <th width="10%">Email</th>
                  <th width="40%">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($data as $ligne) {
                ?>
                  <tr>
                    <td> <?php echo $ligne->id_client; ?></td>
                    <td> <?php
                          $info = "<ul class='list-style-none'><li><b><i class='glyph-icon simple-icon-arrow-right'></i>  " . $ligne->nom . " " . $ligne->prenom . " </b></li>";
                          if ($ligne->cin != '')
                            $info .= " <li> <i class='glyph-icon simple-icon-arrow-right'></i> CIN : " . $ligne->cin . "</li>";
                          if ($ligne->ice != '')
                            $info .= " <li> <i class='glyph-icon simple-icon-arrow-right'></i> ICE : " . $ligne->ice . "</li>";

                          $info .= "</ul>";
                          echo $info;

                          ?> </td>
                    <td> <?php echo $ligne->telephone; ?> </td>
                    <td> <?php echo $ligne->email; ?> </td>
                    <td class="d-flex">
                      <?php if (auth::user()['privilege'] == 'Admin') { ?>
                        <a class="badge badge-primary m-1 fiche" data-id="<?php echo $ligne->id_client; ?>" data-arc="1" style="color: white;cursor: pointer;" title="Fiche client" data-toggle="modal" data-target="#ficheModal">
                          <i class="glyph-icon iconsmind-Business-ManWoman" style="font-size: 15px;"></i>
                        </a>
                        <a class="badge badge-danger m-1 delete" data-id="<?php echo $ligne->id_client; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                          <i class="simple-icon-trash" style="font-size: 15px;"></i>
                        </a>
                        <a class="badge badge-warning m-1  url notlink" data-url="" style="color: white;cursor: pointer;" title="Modifier" href="<?php echo BASE_URL ?>client/update.php?id=<?php echo $ligne->id_client; ?>">
                          <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                        </a>

                        <a class="badge badge-info m-1 " href="<?php echo BASE_URL . 'views/etat/client_etat_vente.php?id=' . $ligne->id_client; ?>" target="_blanck" style="color: white;cursor: pointer;" title="Etat de vente">
                          <i class="iconsmind-Billing" style="font-size: 15px;"></i>
                        </a>
                        <br>
                        <a class="badge badge-success m-1  url notlink" data-url="reg_client/index.php?id=<?php echo $ligne->id_client; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)'>
                          <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>
                        </a>

                        <?php
                        if ($ligne->pv_guid) {
                        ?>
                          <a class="badge badge-danger m-1 " href="<?php echo BASE_URL . 'views/etat/client_etat_pv.php?id=' . $ligne->id_client; ?>" target="_blanck" style="color: white;cursor: pointer;" title="Etat des Ventes">
                            <i class="iconsmind-Billing" style="font-size: 15px;"></i>
                          </a>
                          <br>
    <a class="badge badge-danger m-1 " href="<?php echo BASE_URL . 'views/etat/etat_stock_pv.php?id=' . $ligne->id_client; ?>" target="_blanck" style="color: white;cursor: pointer;" title="Etat du stock pv" href="javascript:void(0)">
                          <i class="simple-icon-pie-chart" style="font-size: 15px;"></i>
                        </a>

                        <?php
                        }
                        ?>

                        <a class="badge badge-secondary m-1 " href="<?php echo BASE_URL . 'views/etat/etat_vente.php?id_client=' . $ligne->id_client; ?>" target="_blanck" style="color: white;cursor: pointer;" title="Immprimer etat de vente" href="javascript:void(0)">
                          <i class="simple-icon-pie-chart" style="font-size: 15px;"></i>
                        </a>
                        <!-- //////// -->
                        <a class="badge badge-success m-1 " href="<?php echo BASE_URL . 'views/client/Situation.php?id_client=' . $ligne->id_client; ?>" target="_blanck" style="color: white;cursor: pointer;" title="Immprimer etat de vente" href="javascript:void(0)">
                          <i class="simple-icon-pie-chart" style="font-size: 15px;"></i>
                        </a>
                        <!-- //////// -->
                        <a class="badge badge-primary m-1 archive" data-id="<?php echo $ligne->id_client; ?>" data-arc="1" style="color: white;cursor: pointer;" title="Archiver">
                          <i class="simple-icon-social-dropbox" style="font-size: 15px;"></i>
                        </a>

                      <?php  } ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          <?php } ?>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="ficheModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Fiche Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <label for="compte_fiche">Type Compte</label>
                    <input type="text" id="compte_fiche" class="form-control" disabled />
                  </div>
                  <div class="col-md-6">
                    <label for="compte_code">Code</label>
                    <input type="text" id="compte_code" class="form-control" disabled />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label for="compte_nom">Nom</label>
                    <input type="text" id="compte_nom" class="form-control" disabled />
                  </div>
                  <div class="col-md-6">
                    <label for="compte_prenom">Prenom</label>
                    <input type="text" id="compte_prenom" class="form-control" disabled />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label for="compte_cin">CIN</label>
                    <input type="text" id="compte_cin" class="form-control" disabled />
                  </div>
                  <div class="col-md-6">
                    <label for="compte_tel">Téléphone</label>
                    <input type="text" id="compte_tel" class="form-control" disabled />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label for="compte_ice">ICE</label>
                    <input type="text" id="compte_ice" class="form-control" disabled />
                  </div>
                  <div class="col-md-6">
                    <label for="compte_addresse">Adresse</label>
                    <input type="text" id="compte_adresse" class="form-control" disabled />
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Modal -->

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {

    $(".fiche").click(function() {
      let btn = $(this);

      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL . 'views/client/'; ?>controle.php",
        dataType: 'json',
        data: {
          act: "getFiche",
          id: btn.data('id')
        },
        success: function(data) {
          console.log(data);
          $('#compte_fiche').val();
          $('#compte_code').val(data.code);
          $('#compte_nom').val(data.nom);
          $('#compte_prenom').val(data.prenom);
          $('#compte_cin').val(data.cin);
          $('#compte_tel').val(data.telephone);
          $('#compte_ice').val(data.ice);
          $('#compte_adresse').val(data.adresse);
        }
      });
    });

    $("input.datepicker").datepicker({
      format: 'yyyy-mm-dd',
      templates: {
        leftArrow: '<i class="simple-icon-arrow-left"></i>',
        rightArrow: '<i class="simple-icon-arrow-right"></i>'
      }
    })
    $('#datatables').dataTable({
      order: [
        [0, "desc"]
      ],
      dom: 'Bfrtip',
      buttons: [{
          extend: 'excelHtml5',
          title: "list clients",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          title: "list clients",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csvHtml5',
          title: "list clients",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        }
      ],
      pageLength: 10,
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
        text: "Êtes-vous sûr de vouloir supprimer  ce Client !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, Supprimer !'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL . 'views/client/'; ?>controle.php",
            data: {
              act: "delete",
              id: btn.data('id')
            },
            success: function(data) {

              swal(
                'Supprimer',
                'Client était bien Supprimer',
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
        text: "Voulez vous vraiment Archiver ce  client !!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#145388',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, Archiver!'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL . 'views/client/'; ?>controle.php",
            data: {
              act: "archive",
              id: btn.data('id'),
              val: btn.data('arc')
            },
            success: function(data) {

              swal(
                "Archived",
                'Votre client a été archivé.',
                'success'
              ).then((result) => {
                btn.parents("td").parents("tr").remove();
              });

            }
          });

        }
      });

    });
  });
</script>
