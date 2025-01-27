<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$id = explode('?id=', $_SERVER["REQUEST_URI"])[1];
// $infos = connexion::getConnexion()->query(
//   "
//  select c.* from fournisseur c,reservation a 
//  where a.id_fournisseur=c.id_fournisseur
//  and a.id_reservation=" . $id
// )->fetch(PDO::FETCH_ASSOC);
//  var_dump($infos['raison_sociale']);die();
$reg_reservation = new reg_reservation();
$data = $reg_reservation->selectAll2($id);
$total = 0;
$query = $result = connexion::getConnexion()->query("SELECT sum(montant*nombre_nuits)as total FROM detail_reservation  WHERE id_reservation=" . $id);
$result = $query->fetch(PDO::FETCH_OBJ);
$total = $result->total;
?>
<div class="container-fluid disable-text-selection">
  <input type="hidden" value="<?php //echo $infos['raison_sociale']; ?>" class="nom">
  <input type="hidden" value="<?php //echo $infos['ice']; ?>" class="ice">
  <input type="hidden" value="<?php //echo $infos['adresse']; ?>" class="adresse">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Nouveau reglement de reservation N° <?php echo $id ?></h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="reg_reservation/add.php?id=<?php echo $id ?>">AJOUTER</button>
        </div>
      </div>
      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-12 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="mb-2">Reglement de reservation N&deg; <?php echo $id; ?> Total est: <?php echo number_format($total, 2, '.', ' '); ?> Dh </h5>
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
          <h5 class="mb-2">Le reste est : <?php echo  number_format($total - $avance, 2, '.', ' '); ?> Dh </h5>
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
          order: [
            [0, "desc"]
          ],
          dom: 'Bfrtip',
          buttons: [{
              extend: 'excel',
              title: "reglement reservation N° : <?php echo $id ?> \n" + nom + "\n" + ice + "\n" + adresse,
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
              }
            },
            {
              extend: 'pdfHtml5',
              alignment: 'center',
              title: "reglement reservation N° : <?php echo $id ?> \n" + nom + "\n" + ice + "\n" + adresse,
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
              }
              //     customize: function ( doc ) {
              //     doc.content.splice( 1, 0, {
              //         margin: [ 0, 0, 0, 12 ],
              //         alignment: 'center',
              //         image: "data:image/png;base64,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d3R0eL3ecePGNTY2nucNjLHL5WKMSZJkO3DlypXnzp2zNdTj8fT398disZdeeqm/v3/Tpk1Op9O2bXgEKoqi2+1WFAVjDADYunXr6tWrc7kcACAQCAQCgXw+39LScumll3q9Xn5X83nol52Ghob29nZFUQAA7e3t27dv/9a3vmUf6u/vp5RCCC3Leumll2xde+WVV2KxmNvtRggNDg4CAApq8vEY+Vw+n48D6FIpABBjiEAKsiyA2nk1W1tb7chRUZTvfe97N9xwQ0lJSSqV2rt37/Tp0wunq6+v13VdURRRFA8dOtTU1DR58mR7dvbII4/09fVJkrR69eo77rjD6/W2tLSsXbt2//7927Zte/7557/zne98lJ2WZQWDwd/97nf2fPDmm2+mlD7wwAMLFy60L/bOO+9samqSZbm2tvaHP/zhhAkTBEF49913dV33eDymaf5Nxv+ua4UPtuWPP/54a2urLMurVq36l3/5l0Ag0N7e/vDDD+/ateuNN9741a9+tWbNmo9xoyRJg4OD999/v2EYkiTddtttK1eujEQimUxm7969EyZM+ITDwfk08LzcixpKaX19vWVZEyZMmDhxomVZ9fX1lFL7qC1b9rrSnj17otEopfSVV14RRZEQUtC1T8ixvTv0VJJCjDHK6gQLYqa/Iz/0/gdrbtq0aWBggBBy1113rV27tqqqSlXVcDi8bNmysrKygm0NDQ0Qwrlz50YikVQq9eKLL9qHmpqaDh48iBCaO3fu2rVrA4EAxnjMmDE/+9nPPB6PKIr19fWGYXx8dAkhhBAO1ya7pLm5efv27bIsjx07duPGjbNnz/Z4PA6Ho7a2dt68ecN7ME2TUkoptay/rSHKstzb2/v666+LojhlypTHH388HA5jjEeOHPnUU08VFRXJsvzyyy8bhiEIHxkQyLJcX1/f1dVFKV21atWjjz46atQoVVWDweB1111XVVXF72qua192jhw50tjYCCG89dZbb7nlFgjh4cOHjxw5Yh+NxWKU0rKystLS0s7Ozt27dx89evT48ePBYLC6uppSmkgkPmG81t/bvXXjv/udQtCJZBll0mY+TxnJDba980GpbWxsxBiHQqGbb775ozrctm1be3u7JEk/+clP5s+fzxjbvn37wMCAPQnNZrOU0gULFgxvUlVVNWnSJMZYV1dXX1/fx2jH8NXh8z7Y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"
              //     }, 
              //     );
              // }
            },
            {
              extend: 'csvHtml5',
              title: "reglement reservation N° : <?php echo $id ?> ",
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
                url: "<?php echo BASE_URL . 'views/reg_reservation/'; ?>controle.php",
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
                    location.reload();
                  });
                }
              });
            }
          });
        });
      });
    </script>