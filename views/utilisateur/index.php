<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$utilisateur = new utilisateur();
//  $data=$utilisateur->selectAll();
//  if(auth::user()['privilege']=='Admin'){
if (auth::user()['privilege'] == "Admin") {
  $data = $utilisateur->selectAll();
} else {
  $data = $utilisateur->getById(auth::user()['id']);
}

?>

<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Liste Des utilisateur</h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="utilisateur/add.php">AJOUTER</button>
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

            <table class="table responsive table-striped table-bordered table-hover" id="datatables">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Identifient</th>
                  <th> Mot de passe </th>
                  <th> Privilège </th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($data as $ligne) {
                ?>
                  <tr>
                    <td> <?php echo $ligne->id; ?></td>
                    <td> <?php echo $ligne->login; ?> </td>
                    <td> <?php echo '**********'; ?> </td>
                    <td> <?php echo $ligne->privilege; ?> </td>
                    <td>
                      <?php if (auth::user()['privilege'] == 'Admin') { ?>
                        <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                          <i class="simple-icon-trash" style="font-size: 15px;"></i>
                        </a>

                      <?php } ?>
                      <?php if (auth::user()['privilege'] == 'Admin' || auth::user()['privilege'] == 'User+') { ?>
                        <a class="badge badge-warning mb-2  url notlink" data-url="utilisateur/update.php?id=<?php echo $ligne->id; ?>" style="color: white;cursor: pointer;" title="Modifier" href="javascript:void(0)">
                          <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                        </a>
                      <?php } ?>

                      <?php if (auth::user()['privilege'] == 'Admin' && $ligne->privilege == 'Vendeur') : ?>

                        <a class="badge badge-secondary mb-2  " target="_blank" style="color: white;cursor: pointer;" title="Cmd Founisseur" href="<?php echo BASE_URL ?>/views/etat/commande_vendeur_fourniss.php?id=<?php echo $ligne->id; ?>">
                          <i class=" simple-icon-printer" style="font-size: 15px;"></i>

                        </a>
                      <?php endif; ?>


                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          <?php } ?>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      $(document).ready(function() {



        $('#datatables').dataTable({
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
            text: "Voulez vous vraiment Supprimer ce utilisateur !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, Supprimer !'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL . 'views/utilisateur/'; ?>controle.php",
                data: {
                  act: "delete",
                  id: btn.data('id')
                },
                success: function(data) {

                  swal(
                    'Supprimer',
                    'utilisateur a ete bien Supprimer',
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
            text: "Voulez vous vraiment Archiver ce utilisateur !!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#145388',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, Archiver!'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL . 'views/utilisateur/'; ?>controle.php",
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
            url: "<?php echo BASE_URL . 'views/utilisateur/'; ?>controle.php",
            data: {
              act: "getName",
              id: btn.data('id')
            },
            success: function(datas) {
              var data = datas.split(';;;');
              $('#exampleModalRight .modal-title').html("Etat utilisateur " + data[1]);
              $('#idstatic').val(data[0]);
            }
          });



        });


        $("#Staticform").on("submit", function(event) {
          event.preventDefault();

          var form = $(this);
          $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL . 'views/utilisateur/'; ?>controle.php",
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

      });
    </script>