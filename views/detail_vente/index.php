<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$detail_vente = new detail_vente();
$id = explode('?id=', $_SERVER["REQUEST_URI"])[1];

$data = $detail_vente->selectAllValide($id);
?>
<style type="text/css">

</style>

<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Detail vente N° : <?php echo $id ?></h1>
        <input type="hidden" id="id_vente" value="<?php echo $id  ?>" />

        <?php if (auth::user()['privilege'] == 'Vendeur') { ?>


          <div class="float-sm-right text-zero">
            <a class="btn btn-success  url " href="<?php echo BASE_URL ?>index.php"> <i class="glyph-icon simple-icon-arrow-left"></i></a>
          </div>


        <?php } else { ?>
          <div class="float-sm-right text-zero">
            <button type="button" class="btn btn-success  url notlink" data-url="vente/index.php"> <i class="glyph-icon simple-icon-arrow-left"></i></button>
          </div>


        <?php } ?>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="detail_vente/add.php?id=<?php echo $id ?>">AJOUTER</button>
        </div>

      </div>
      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">



    <div class="col-xl-12 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">

          <table class="table  responsive table-striped table-bordered table-hover" id="datatables">
            <thead>
              <tr>
                <th scope="col" width="1px">Id</th>
                <th scope="col">Produit</th>
                <th scope="col">Dépot</th>
                <th>Prix</th>
                <th>Remise</th>
                <th>P.R</th>
                <th scope="col" style='width:170px;'> Qte</th>
                <th scope="col"> Unite</th>
                <th scope="col"> Qte*Prix</th>

                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $total = 0;
              foreach ($data as $ligne) {
              ?>
                <tr id="<?php echo $ligne->id_detail; ?>">
                  <td><?php echo $ligne->id_detail; ?></td>
                  <td id="<?php echo $ligne->id_produit; ?>"><?php echo $ligne->designation; ?></td>
                  <td>
                    <?php echo $ligne->depot ?>
                  </td>
                  <td style="text-align:right;">
                    <label>
                      <?php echo $ligne->prix_produit; ?>
                    </label> <input type='text' value="<?php echo $ligne->prix_produit; ?>" />
                  </td>
                  <td style="text-align:right;">
                    <label>
                      <?php echo $ligne->remise; ?>
                    </label> <input type='text' value="<?php echo $ligne->remise; ?>" />
                  </td>
                  <td style="text-align:right;"> <?php $prixr = $ligne->prix_produit * (1 - $ligne->remise / 100);
                                                  echo number_format($prixr, 2, '.', ''); ?> </td>
                  <td>
                    <label>
                      <?php echo $ligne->qte_vendu; ?>
                    </label>
                    <input style='width:80px;' type='text' value="<?php echo $ligne->qte_vendu; ?>" />
                    <span id="qte<?php echo $ligne->id_detail; ?>">
                    </span>
                  </td>

                  <td class="d-flex flex-row">
                    <label>
                      <?php if (!empty($ligne->valunit) && !empty($ligne->unit)) {
                        echo $ligne->valunit . " " . $ligne->unit;
                      }
                      ?>
                    </label>

                    <input style="text-align:left;width: 40px;" type="text" name="" id="" value="<?php if (!empty($ligne->valunit) && !empty($ligne->unit)) {
                                                                                                    echo $ligne->valunit;
                                                                                                  }
                                                                                                  ?>
                  ">
                    <input style="text-align:left;width: 40px;" type="text" name="" id="" value="<?php if (!empty($ligne->valunit) && !empty($ligne->unit)) {
                                                                                                    echo $ligne->unit;
                                                                                                  }
                                                                                                  ?>
                  ">
                  </td>

                  <td style="text-align:right;" width="90">
                    <?php
                    if (!empty($ligne->valunit) || $ligne->valunit != 0) {
                      echo number_format($ligne->valunit * $prixr, 2, '.', '');
                    } else {
                      echo number_format($ligne->qte_vendu * $prixr, 2, '.', '');
                    }

                    // var_dump($tva);
                    if (!empty($ligne->valunit) || $ligne->valunit != 0) {
                      $total += ($ligne->valunit * $prixr);
                    } else {
                      $total += $ligne->qte_vendu * $prixr;
                    }

                    ?>
                  </td>

                  <td>
                    <?php if (auth::user()['privilege'] == 'Admin') { ?>
                      <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                        <i class="simple-icon-trash" style="font-size: 15px;"></i>
                      </a>

                      <a class="badge badge-warning mb-2 updatee " data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Modifier" href="javascript:void(0)">
                        <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                      </a>
                    <?php } ?>

                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
          <h1 id="total">Total : <?php echo  number_format($total, 2, '.', '') ?> DH
            <input type="hidden" value="<?php echo  number_format($total, 2, '.', '') ?>" class="mytotal">
          </h1>


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
    });




    var table = $('#datatables').dataTable({
      order: [
        [0, "desc"]
      ],
      dom: 'Bfrtip',
      buttons: [{
          extend: 'excelHtml5',
          title: "liste des ventes N° <?php echo $id ?>",
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          }
        },
        {
          extend: 'pdfHtml5',
          title: "liste des ventes N° <?php echo $id ?>",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, ]
          }
        },
        {
          extend: 'csvHtml5',
          title: "liste des ventes N° <?php echo $id ?>",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, ]
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
        text: "Voulez vous vraiment Supprimer ce vente !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, Supprimer !'
      }).then((result) => {
        if (result.value) {

          $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL . 'views/detail_vente/'; ?>controle.php",
            data: {
              act: "delete",
              id: btn.data('id')
            },
            success: function(data) {

              swal(
                'Supprimer',
                'vente a ete bien Supprimer',
                'success'
              ).then((result) => {
                btn.parents("td").parents("tr").remove();
                location.reload();
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
        text: "Voulez vous vraiment Archiver ce vente !!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#145388',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, Archiver!'
      }).then((result) => {
        if (result.value) {

          $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL . 'views/detail_vente/'; ?>controle.php",
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
        url: "<?php echo BASE_URL . 'views/detail_vente/'; ?>controle.php",
        data: {
          act: "getName",
          id: btn.data('id')
        },
        success: function(datas) {
          var data = datas.split(';;;');
          $('#exampleModalRight .modal-title').html("Etat vente " + data[1]);
          $('#idstatic').val(data[0]);
        }
      });



    });


    $("#Staticform").on("submit", function(event) {
      event.preventDefault();

      var form = $(this);
      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL . 'views/detail_vente/'; ?>controle.php",
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


    $('#datatables tbody').on('click', '.updatee', function() {


      var value = $(this).data('id');
      $('#' + value).find("label").hide();
      $('#' + value).find("input[type='text']").show();
      $('#' + value).children("td:eq(9)").html("<input type='button' class='Applique'  value='Applique'data-id= '" + value + "' />");

      var qte_inp = $('#' + value).find("input[type='text']")[2];
      var unite_inp = $('#' + value).find("input[type='text']")[3];

      qte_inp.addEventListener('change', function() {
        unite_inp.value = qte_inp.value
      })

    });


    $('#datatables tbody').on('click', '.Applique', function() {


      var value = $(this).data('id');
      var qte = $('#' + value).find("input[type='text']:eq(2)").val();
      var reste_stock = parseInt($('#qte' + value).html());
      var prix = $('#' + value).find("input[type='text']:eq(0)").val();
      var remise = $('#' + value).find("input[type='text']:eq(1)").val();
      var unit = $('#' + value).find("input[type='text']:eq(4)").val();
      var valunit = $('#' + value).find("input[type='text']:eq(3)").val();

      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL . 'views/detail_vente/'; ?>controle.php",
        data: {
          act: 'update_detail',
          id_detail: $(this).data('id'),
          id_vente: $('#id_vente').val(),
          remise: remise,
          prix_produit: prix,
          qte_vendu: qte,
          unit: $('#' + value).find("input[type='text']:eq(4)").val(),
          valunit: $('#' + value).find("input[type='text']:eq(3)").val(),
          id_produit: $('#' + value).children("td:eq(1)").attr('id'),
          qte_venduh: $('#' + value).find("label:eq(2)").html(),

        },
        success: function(data) {
          console.log(data);

          $('#' + value).find("label:eq(0)").html($('#' + value).find("input[type='text']:eq(0)").val());
          $('#' + value).find("label:eq(1)").html($('#' + value).find("input[type='text']:eq(1)").val());
          $('#' + value).find("label:eq(2)").html($('#' + value).find("input[type='text']:eq(2)").val());
          $('#' + value).find("label:eq(3)").html($('#' + value).find("input[type='text']:eq(3)").val() + ' ' + $('#' + value).find("input[type='text']:eq(4)").val());

          $('#' + value).find("label").show();
          $('#' + value).find("input[type='text']").hide();

          $('#' + value).children("td:eq(9)").html(`<a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                      <i class="simple-icon-trash" style="font-size: 15px;"></i>
                    </a>
                    
                    <a class="badge badge-warning mb-2 updatee " data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Modifier"
                      href="javascript:void(0)">
                      <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                    </a>`);
          if (valunit != null || valunit != 0) {
            $('#' + value).children("td:eq(7)").html(parseFloat(prix) * parseFloat(valunit) * (1 - remise / 100));
          }
          if (valunit == null || valunit == 0) {
            alert(parseFloat(prix) * parseFloat(qte) * (1 - remise / 100));
            $('#' + value).children("td:eq(7)").html(parseFloat(prix) * parseFloat(qte) * (1 - remise / 100));
          }

          $('#qte' + value).html("");
          $('#total').html("Total : " + data + " DH");

        },
        error: function(error) {
          console.log(error);
        }
      });

    })
  });
</script>