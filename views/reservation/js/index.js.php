<script>
  $(document).ready(function () {
    $('#reservations-table').DataTable({
      "order": [
        [0, 'desc']
      ],
      "processing": true,
      "serverSide": true,
      lengthMenu: [
        [12, 25, 50, 100],
        [12, 25, 50, 100]
      ],
      "ajax": {
        "url": "<?php echo BASE_URL; ?>views/reservation/controller.php",
        "type": "POST",
        "data": {
          act: "get_reservations_datatable"
        },
        // "success": function(data) {console.log(data);}
      },
      "columns": [{
        "data": "id_reservation"
      },
      {
        "data": "cin"
      },
      {
        "data": "montant_total"
      },
      {
        "data": "rest"
      },
      {
        "data": "date_reservation"
      },
      {
        "data": "remarque"
      },
      {
        "render": function (data, type, row, meta) {
          console.log(row)
          let html = '';
          <?php
          if (auth::user()['privilege'] == 'Admin') {
            ?>
            html += `<a class="badge badge-danger mr-2 mb-2 delete-reservation" data-id="${row.id_reservation}" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                            <i class="simple-icon-trash" style="font-size: 15px;"></i>
                          </a>`;
            html += `<a class="badge badge-warning mr-2 mb-2  url notlink" data-url="reservation/update.php?id=${row.id_reservation}" style="color: white;cursor: pointer;" title="Modifier" href="javascript:void(0)">
                            <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                          </a>`;
            html += `<a class="badge badge-warning mr-2 mb-2  url notlink" data-url="detail_reservation/index.php?id=${row.id_reservation}" style="color: white;cursor: pointer;" title="Detail" href="javascript:void(0)">
                            <i class="glyph-icon simple-icon-list" style="font-size: 15px;"> </i>
                          </a>`;
            html += `<a class="badge badge-warning mr-2 mb-2" target="blank" href="<?php echo BASE_URL . 'views/detail_reservation'; ?>/facture.php?id=${row.id_reservation}" style="color: white;cursor: pointer;" title="Facture" href="javascript:void(0)">
                            <i class="simple-icon-printer" style="font-size: 15px;"> </i>
                          </a>`;
            html += `<a class="badge badge-info mr-2 mb-2" target="blank" href="<?php echo BASE_URL . 'views/detail_reservation'; ?>/confirmation.php?id=${row.id_reservation}" style="color: white;cursor: pointer;" title="Confirmation" href="javascript:void(0)">
                            <i class="simple-icon-printer" style="font-size: 15px;"> </i>
                          </a>`;
            html += `<a class="badge badge-warning mr-2 mb-2 url notlink" data-url="reg_reservation/index.php?id=${row.id_reservation}" style="color: white;cursor: pointer;" title="Reglé" href="javascript:void(0)">
                            <i class="iconsmind-Money-2" style="font-size: 15px;"> </i>
                          </a>`;
            if (row.nbr_facture == 0) {
              html += `<a class="badge badge-primary mb-2 url notlink" style="color: white;cursor: pointer;" title="Facture" data-url="facture/add.php?id=${row.id_reservation}" href='javascript:void(0)'>
                            <i class=" iconsmind-Billing" style="font-size: 15px;"></i>
                          </a>`
            }
            if (row.id_facture) {
              html += `<a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Facture" href="<?php echo BASE_URL ?>views/facture/facture.php?id=${row.id_reservation}&idf=${row.id_facture}&h=15" target="_black">
                              <i class=" simple-icon-printer" style="font-size: 15px;"></i>
                            </a>`
            }
          <?php } ?>

          return html;
        }
      },
      ]
    });
    //delete resevation
    $('body').on("click", ".delete-reservation", function (event) {
      event.preventDefault();
      var btn = $(this);
      swal({
        title: 'Êtes-vous sûr?',
        text: "Voulez vous vraiment Supprimer cette reservation !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, Supprimer !'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL . 'views/reservation/'; ?>controller.php",
            data: {
              act: "delete_reservation",
              id_reservation: btn.data('id')
            },
            success: function (data) {
              swal(
                'Supprimer',
                'Reservation a été bien Supprimer',
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