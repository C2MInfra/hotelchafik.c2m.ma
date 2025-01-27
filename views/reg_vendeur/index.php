<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}

$id = explode('?id=', $_SERVER["REQUEST_URI"])[1];

$reg_vendeur = new reg_vendeur();
$data = $reg_vendeur->selectAll2($id);

$infos = connexion::getConnexion()->query(
  "
 select c.* from utilisateur c,boncommandevendeur v 
 where v.id_vendeur=c.id
 and v.id_bon=" . $id
)->fetch(PDO::FETCH_ASSOC);


$query = $result = connexion::getConnexion()->query("SELECT sum(dv.prix_produit*(dv.qte_vendu-dv.qte_actuel)*(1-dv.remise/100)) as total ,
sum(dv.prix_produit*dv.valunit*(1-dv.remise/100)) as totalunit 
FROM detail_bon_vendeur dv 
 inner join produit p on dv.id_produit=p.id_produit WHERE id_bon=" . $id);

$result = $query->fetch(PDO::FETCH_OBJ);
$total = 0;
$total = $result->total;

?>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <input type="hidden" value="<?php echo $infos['nom']; ?>" class="nom">
    <div class="col-12">
      <div class="mb-2">
        <h1>Nouveau reglement commande N° <?php echo $id ?></h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="reg_vendeur/add.php?id=<?php echo $id ?>">AJOUTER</button>

          <button type="button" class="btn btn-success  url notlink" data-url="commande-vendeurs/index.php?id=<?php echo $id ?>"> <i class="glyph-icon simple-icon-arrow-left"></i></button>
        </div>
      </div>
      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-12 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">

          <?php

          $query = $result = connexion::getConnexion()->query("SELECT IFNULL((SELECT SUM((qte_vendu - qte_actuel)*prix_produit) FROM detail_bon_vendeur WHERE id_bon = " . $id . " GROUP BY id_bon) , 0)  - IFNULL((SELECT SUM(montant) FROM reg_vendeur WHERE id_bon = " . $id . " GROUP BY id_bon) , 0) AS reste");



          $result = $query->fetchColumn();




          ?>

          <!-- <h5 class="mb-2">Reglement commande N&deg; <?php echo $id; ?> Total est: <?php echo number_format($result, 2, '.', ' '); ?> Dh </h5> -->

          <input type="hidden" value="">
          <table class="table responsive table-striped " id="datatables">
            <thead>
              <tr>
                <th scope="col" width="1px">Id</th>
                <th scope="col">Mode</th>
                <th scope="col">Num&egrave;ro</th>
                <th> Date </th>
                <th scope="col"> Remarque </th>
                <th scope="col"> Montant </th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $avance = 0;
              foreach ($data as $ligne) {
              ?>
                <tr>
                  <td> <?php echo $ligne->id_reg; ?></td>
                  <td> <?php echo $ligne->mode_reg; ?> </td>
                  <td> <?php echo $ligne->num_cheque; ?> </td>
                  <td> <?php echo $ligne->date_reg; ?> </td>
                  <td> <?php echo $ligne->remarque; ?> </td>
                  <td style="float:right"> <?php echo number_format($ligne->montant, 2, '.', ' ');
                                            $avance += $ligne->montant;
                                            ?> </td>

                  <td>
                    <?php if (auth::user()['privilege'] == 'Admin') { ?>
                      <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_reg; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>
                        <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
                      </a>
                    <?php } ?>
                  </td>

                </tr>
              <?php } ?>
            </tbody>
          </table>

          <h5 class="mb-2">Le reste est : <?php echo  number_format($result, 2, '.', ' '); ?> Dh </h5>

        </div>
      </div>
    </div>



    <script type="text/javascript">
      $(document).ready(function() {
        var nom = "";
        if ($('.nom').val() != null) {
          nom = $('.nom').val();
        }
        var ice = "";
        if ($('.ice').val() != null) {
          ice = $('.ice').val();
        }
        var adresse = "";
        if ($('.adresse').val() != null) {
          adresse = $('.adresse').val();
        }
        $('#datatables').dataTable({
          responsive: true,
          order: [
            [0, "desc"]
          ],
          dom: 'Bfrtip',
          buttons: [

            {
              extend: 'excel',
              title: "reglement vente N° :  <?php echo $id ?> \n" + nom + "\n" + ice + "\n" + adresse,
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
              }
            },
            {
              extend: 'pdfHtml5',
              alignment: 'center',
              title: "reglement vente N° : <?php echo $id ?> \n" + nom + "\n" + ice + "\n" + adresse,
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
              }
            },
            {
              extend: 'csvHtml5',
              title: "reglement vente N° : <?php echo $id ?> ",
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
              }
            }
          ],
          pageLength: 6,
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
            text: "Voulez vous vraiment Supprimer ce reglement !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, Supprimer !'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL . 'views/reg_vendeur/'; ?>controle.php",
                data: {
                  act: "delete",
                  id: btn.data('id')
                },
                success: function(data) {

                  swal(
                    'Supprimer',
                    'reglement bien Supprimer',
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
