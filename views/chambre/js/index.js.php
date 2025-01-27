<script>
  $(document).ready(function() {
    $('#chambres-table').DataTable({
      "order": [
        [0, 'desc']
      ],
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?php echo BASE_URL; ?>views/chambre/controler.php",
        "type": "POST",
        "data": {
          act: "get_chambres_datatable"
        },
        // "success": function(data) {console.log(data);}
      },
      "columns": [{
          "data": "id_chambre",
        },
        {
          "data": "numero_chambre"
        },
        {
          "render": function(data, type, row, meta) {
            return `${row.metrage} m²`;
          }
        },
        {
          "data": "lit"
        },
        {
          "render": function(data, type, row, meta) {
            let html = '';
            <?php
            if (auth::user()['privilege'] == 'Admin') {
            ?>
              html += `<a class="badge badge-danger mb-2 delete" data-id="${row.id_chambre}" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                          <i class="simple-icon-trash" style="font-size: 15px;"></i>
                        </a>`;
              html += `<a class="badge badge-warning mb-2  url notlink" data-url="chambre/update.php?id=${row.id_chambre}" style="color: white;cursor: pointer;" title="Modifier" href="javascript:void(0)">
                          <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                        </a>`;
            <?php } ?>
            return html;
          }
        },
      ]
    });
    //delete resevation
    $('body').on("click", ".delete", function(event) {
      event.preventDefault();
      var btn = $(this);
      swal({
        title: 'Êtes-vous sûr?',
        text: "Voulez vous vraiment Supprimer cette chambre !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, Supprimer !'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL . 'views/chambre/'; ?>controler.php",
            data: {
              act: "delete",
              id_chambre: btn.data('id')
            },
            success: function(data) {
              swal(
                'Supprimer',
                'Chambre a été bien Supprimer',
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