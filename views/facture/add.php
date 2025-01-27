  <?php
  if (isset($_POST['ajax'])) {
    include('../../evr.php');
  }
  $getarrey = explode('&', explode('?', $_SERVER["REQUEST_URI"])[1]);
  $GET = array();
  foreach ($getarrey as $key) {
    $row = explode('=', $key);
    $GET[$row[0]] = $row[1];
  }
  $query1 = $result1 = connexion::getConnexion()->query("SELECT num_fact as dernier_facture FROM facture ORDER BY id_facture DESC LIMIT 1");
  $result1 = $query1->fetch(PDO::FETCH_OBJ);
  $last_num = $result1->dernier_facture + 1;
  ?>
  <div class="container-fluid disable-text-selection">
    <div class="row">
      <div class="col-12">
        <div class="mb-2">
          <h1>facture </h1>
        </div>
        <div class="separator mb-5"></div>
      </div>
    </div>
    <div class="row">
      <div class="col align-self-start">
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="mb-4">Ajouter Nouveau facture</h5>
            <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
              <input type="hidden" name="act" value="insert">
              <input type="hidden" name="id" value="<?php echo $GET['id'] ?>">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="date_facture"> Date :</label>
                  <input type="text" class="form-control datepicker" autocomplete="off" id="date_facture" name="date_facture" value="<?php echo date('Y-m-d') ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="num_fact">Numéro de Facture :</label>
                  <input type="text" class="form-control" id="num_fact" value="<?php echo $last_num ?>" name="num_fact">
                </div>
              </div>
              <div class="form-group">
                <label for="remarque">Remarque :</label>
                <textarea class="form-control" name="remarque" id="remarque"></textarea>
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
      $("#addform").on("submit", function(event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
          type: "POST",
          url: "<?php echo BASE_URL . 'views/facture/'; ?>controle.php",
          data: new FormData(this),
          dataType: 'text',
          cache: false,
          contentType: false,
          processData: false,
          success: function(data) {
            if (data.indexOf("success") >= 0) {
              swal(
                'Ajouter',
                'facture a ete bien Ajouter',
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
                    history.pushState({}, "", `<?php echo BASE_URL . "reservation/index.php" ?>`);
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
    });
  </script>