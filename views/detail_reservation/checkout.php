<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$id = explode('?id=', $_SERVER["REQUEST_URI"])[1];

?>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Checkout</h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="reservation/index.php">
            < </button>
        </div>
      </div>
      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-12 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="form-group col-md-3">
            <label for="date_checkout">Date checkout:</label>
            <input type="date" class="form-control" id="date_checkout" name="date_checkout" value="<?php echo date('Y-m-d'); ?>">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-12 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div id="results">
            <table class="table  responsive table-striped table-bordered table-hover" id="reservations-table">
              <thead>
                <tr>
                  <th scope="col" width="1px">Id</th>
                  <th scope="col">Client</th>
                  <th scope="col">N° chambre</th>
                  <th>Montant</th>
                  <th scope="col">Nbr nuits</th>
                  <th style="text-align:center" scope="col">date d'arrivé</th>
                  <th style="text-align:center" scope="col">date depart</th>
                  <th style="text-align:center" scope="col">Check-In</th>
                  <th style="text-align:center" scope="col">Check-Out</th>
                </tr>
              </thead>
              <tbody id="details_reservation">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    //this code below is because javascript doesnt work at the first load
    if (!localStorage.getItem("reload")) {
      localStorage.setItem("reload", "true");
      location.reload();
    } else {
      localStorage.removeItem("reload");
    }
    $('#date_checkout').change(function(){
      display_detail_reservation();
    });
    function display_detail_reservation() {
      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL . 'views/detail_reservation/'; ?>controller.php",
        data: {
          act: "get_reservation_details_par_date_checkout",
          date_checkout: $('#date_checkout').val()
        },
        success: function(data) {
          $('#details_reservation').empty();
          let html = "";
          let detail_reservation = JSON.parse(data);
          detail_reservation.forEach(value => {
            html += `<tr>
              <td>${value.id_detail_reservation}</td>
              <td>${value.nom} (${value.cin})</td>
              <td>${value.numero_chambre}</td>
              <td>${value.montant}</td>
              <td>${value.nombre_nuits}</td>
              <td>${value.date_arriver}</td>
              <td>${value.date_depart}</td>
              <td style=\"text-align:center\">
                <a class=\"badge ${value.checkin == 1 ? 'badge-success' : 'badge-danger'} mb-2 update-checkin\" data-id=\"${value.id_detail_reservation}\" style=\"color: white;cursor: pointer;\" href='javascript:void(0)'>
                  <i class=\" ${value.checkin == 1 ? 'simple-icon-check' : 'simple-icon-close'} \" style=\"font-size: 30px;\"></i>
                </a>
              </td>
              <td style=\"text-align:center\">
                <a class=\"badge ${value.checkout == 1 ? 'badge-success' : 'badge-danger'} mb-2 update-checkout\" data-id=\"${value.id_detail_reservation} \" style=\"color: white;cursor: pointer;\" href='javascript:void(0)'>
                  <i class=\" ${value.checkout == 1 ? 'simple-icon-check' : 'simple-icon-close'} \" style=\"font-size: 30px;\"></i>
                </a>
              </td>
            </tr>`;
          });
          $('#details_reservation').append(html);
        }
      })
    }
    display_detail_reservation();
    $('body').on("click", ".update-checkin", function(event) {
      event.preventDefault();
      var btn = $(this);
      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL . 'views/detail_reservation/'; ?>controller.php",
        data: {
          act: "update_checkin",
          id_detail_reservation: btn.data('id')
        },
        success: function(data) {
          display_detail_reservation()
        }
      });
    });
    $('body').on("click", ".update-checkout", function(event) {
      event.preventDefault();
      var btn = $(this);
      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL . 'views/detail_reservation/'; ?>controller.php",
        data: {
          act: "update_checkout",
          id_detail_reservation: btn.data('id')
        },
        success: function(data) {
          display_detail_reservation()
        }
      });
    });
  });
</script>