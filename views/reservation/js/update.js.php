<!-- utility functions -->
<script type="text/javascript">
  var reservation_details = [];
  let total = 0;

  function display_reservation_details(reservation_details) {
    $("#detail-reservation").empty();
    let reservation_index = 0;
    total = 0;
    reservation_details.forEach(reservation => {
      $("#detail-reservation").prepend(`<tr>
                <td>${reservation["numero_chambre"]}</td>
                <td>${reservation["montant"]}</td>
                <td>${reservation["nombre_nuits"]}</td>
                <td>${reservation["nombre_personnes"]}</td>
                <td>${reservation["date_arriver"]}</td>
                <td>${reservation["date_depart"]}</td>
                <td>
                    <a class="badge badge-danger mb-2 delete-reservation-detail" data-id="${reservation_index}" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                        <i class="simple-icon-trash" style="font-size: 15px;"></i>
                    </a>
                </td>
            </tr>`);
      reservation_index++;
      total += Number(reservation["montant"]) * Number(reservation["nombre_nuits"]);
    });
    $("#detail-reservation").append(`<tr>
            <td colspan="4" style="text-align: center;font-size: 15px;"> <b>Total</b> </td>
            <td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right;">${total}</b></td>
        </tr>`);
  }

  function get_reservation_details() {
    let id_reservation = <?php echo explode('?id=', $_SERVER["REQUEST_URI"])[1]; ?>;
    $.ajax({
      type: "POST",
      url: "<?php echo BASE_URL; ?>views/detail_reservation/controller.php",
      data: {
        act: "get_reservation_details",
        id_reservation: id_reservation,
      },
      dataType: 'text',
      success: function(data) {
        reservation_details = JSON.parse(data);
        display_reservation_details(reservation_details);
      }
    });
  }

  function get_chambre_prix() {
    $.ajax({
      type: "GET",
      url: "<?php echo BASE_URL; ?>views/chambre/controler.php",
      data: {
        act: "get_chambre_prix",
        saison: $("input[name='saison']:checked").val(),
        id_chambre: $("#chambres").val(),
        nombre_personnes: $('#nombre_personnes').val()
      },
      success: function(data) {
        data = JSON.parse(data);
        $("#montant").val(data.chambre_prix);
      }
    });
  }

  function increment_date_depart() {
    let nombre_nuits = Number($('#nombre_nuits').val());
    let date_depart = $('#date_depart').val();
    let dateObject = new Date(date_depart);
    dateObject.setDate(dateObject.getDate() + nombre_nuits);
    $('#date_depart').val(dateObject.toISOString().split('T')[0]);
  }
</script>
<!-- click handlers -->
<script type="text/javascript">
  //add reservation details
  $("#update-reservation-detail").click(function() {
    switch ($("#nombre_personnes :selected").val()) {
      case 'une_personne':
        nombre_personnes = 1;
        break;
      case 'deux_personnes':
        nombre_personnes = 2;
        break;
      case 'trois_personnes':
        nombre_personnes = 3;
        break;
      case 'supplement':
        nombre_personnes = 1;
        break;
      default:
        break;
    }
    reservation_detail = {
      "id_chambre": $("#chambres").val(),
      "numero_chambre": $("#chambres option:selected").text(),
      "montant": $("#montant").val(),
      "nombre_nuits": $("#nombre_nuits").val(),
      "nombre_personnes": nombre_personnes,
      "date_arriver": $("#date_arriver").val(),
      "date_depart": $("#date_depart").val(),
    }
    reservation_details.push(reservation_detail);
    display_reservation_details(reservation_details);
  });
  //delete reservation detail
  $('body').on("click", ".delete-reservation-detail", function(event) {
    event.preventDefault();
    var btn = $(this);
    swal({
      title: 'Êtes-vous sûr?',
      text: "Voulez vous vraiment Supprimer cette Reservation!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Oui, Supprimer !'
    }).then((result) => {
      if (result.value) {
        reservation_details.splice(btn.data('id'), 1);
        display_reservation_details(reservation_details);
      }
    });
  });
  //update reservation
  $("#update-reservation").click(function() {
    const elements = document.getElementsByName("id_client[]");
      const clients_ids = Array.from(elements).map(element => element.value);
    $.ajax({
      type: "POST",
      url: "<?php echo BASE_URL; ?>views/reservation/controller.php",
      data: {
        act: "update_reservation",
        id_reservation: <?php echo explode('?id=', $_SERVER["REQUEST_URI"])[1]; ?>,
        reservation_detail: reservation_details,
        id_client: clients_ids,
        date_reservation: $("#date_reservation").val(),
        remarque: $("#remarque").val(),
        montant_total: total
      },
      dataType: 'text',
      success: function(data) {
        swal(
          'Ajouter',
          'Reservation a éte bien Modifier',
          'success'
        ).then((result) => {
          $.ajax({
            method: 'POST',
            data: {
              ajax: true
            },
            url: `<?php echo BASE_URL . "views/reservation/index.php" ?>`,
            context: document.body,
            success: function(data) {
              window.location = "<?php echo BASE_URL . "reservation/index.php"; ?>";
            }
          });
        });
      }
    });
  });
  $('#nombre_personnes').change(function() {
    get_chambre_prix();
  });
  $('.saison').change(function() {
    get_chambre_prix();
  });
  $('#chambres').change(function() {
    get_chambre_prix();
  });
  // $('#date_arriver').change(function() {
  //   const date1 = new Date($('#date_arriver').val());
  //   const date2 = new Date($('#date_depart').val());
  //   let differenceInTime = date2 - date1;
  //   $('#nombre_nuits').val(Math.round(differenceInTime / (1000 * 60 * 60 * 24))); // increment_date_depart();
  // })
  // $('#date_depart').change(function() {
  //   const date1 = new Date($('#date_arriver').val());
  //   const date2 = new Date($('#date_depart').val());
  //   let differenceInTime = date2 - date1;
  //   $('#nombre_nuits').val(Math.round(differenceInTime / (1000 * 60 * 60 * 24)));
  //   // increment_date_depart();
  // })
  $('#nombre_nuits').keyup(function() {
    $('#date_depart').val($('#date_arriver').val());
    increment_date_depart();
  })
  $('#date_arriver').change(function() {
    $('#date_depart').val($('#date_arriver').val());
    increment_date_depart();
  })
</script>
<script type="text/javascript">
  //this code below is because javascript doesnt work at the first load
  if (!localStorage.getItem("reload")) {
    localStorage.setItem("reload", "true");
    location.reload();
  } else {
    localStorage.removeItem("reload");
  }
  $(document).ready(function() {
    get_reservation_details();
    get_chambre_prix();
  })
</script>