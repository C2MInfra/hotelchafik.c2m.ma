<?php

if (isset($_POST['ajax'])) 
{
   echo '<script>window.location.reload()</script>';
   exit;
include('../../evr.php');

}
$depot_op = new depot_op();

$data = $depot_op->getAllOp();

?>

<div class="container-fluid disable-text-selection">

  <div class="row">

    <div class="col-12">

      <div class="mb-2">

        <h1>Liste Des Opérations dépots</h1>

        <div class="float-sm-right text-zero">

          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="depots/add.php" >AJOUTER</button>

        </div>

      </div>

      <div class="separator mb-5"></div>

    </div>

  </div>

  <div class="row">

    <div class="col-xl-12 col-lg-12 mb-4">

      <div class="card h-100">

        <div class="card-body">
          <div id="results">
            <table class="table  responsive table-striped table-bordered table-hover" id="datatables" >

              <thead>

                <tr>
                  <th scope="col">Id</th>

                  <th class="nowrap"> Date opération</th>

                  <th scope="col"> Remarque</th>

                  <th scope="col"> Crié par</th>
                  
                  <th scope="col">Actions</th>

                </tr>

              </thead>

              <tbody>

                <?php
                foreach($data as $ligne) 
				{

                ?>
                
                <tr>
                  <td> <?php echo $ligne->id_depot_op; ?></td>
                
                  <td class="nowrap">
                  	  <?php echo date('d-m-Y', strtotime($ligne->date_op)) ?>
                  </td>
                  <td> 
                    <?php 
					    echo strlen($ligne->remarque) >50 ?substr($ligne->remarque, 0,50)."..." : $ligne->remarque; 
					?> 
                 </td>
                 <td>
                 	<?php
					   echo $ligne->user_name;
					?>
                 </td>
                  <td class="nowrap">
                      <?php if(auth::user()['privilege'] == 'Admin') { ?>
                      <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_depot_op; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                        <i class="simple-icon-trash" style="font-size: 15px;"></i>
                      </a>
                  <a class="badge badge-warning mb-2  url notlink" data-url="depots/update.php?id=<?php echo $ligne->id_depot_op; ?>" style="color: white;cursor: pointer;" title="Modifier"
                    href="javascript:void(0)">
                       <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                  </a>
                  <a  class="badge badge-secondary mb-2 url notlink" data-url="detail_depots/index.php?id=<?php echo $ligne->id_depot_op; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">                        
                      <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>

                   </a>
                      <?php } ?>
                   </td>
                 </tr>
              <?php } ?>

             </tbody>
           </table>
         </div>
       </div>
     </div>
   </div>

      <div class="modal fade modal-right" id="exampleModalRight" tabindex="-1" role="dialog" aria-labelledby="exampleModalRight" aria-hidden="true">

        <div class="modal-dialog" role="document">

          <div class="modal-content">

            <div class="modal-header">

              <h5 class="modal-title">Etat client</h5>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">&times;</span>

              </button>

            </div>

            <form id="Staticform" method="post" name="form_client" enctype="multipart/form-data">

              <div class="modal-body">

                

                

                <input type="hidden" name="act" value="getetat">

                <input type="hidden" name="id" id="idstatic">

                <div class="form-group">

                  

                  <h5 class="mb-4">Date début</h5>

                  

                  <input class="form-control datepicker" autocomplete="off" placeholder="Date" name="dd">

                </div>

                <div class="form-group">

                  

                  <h5 class="mb-4">Date fin</h5>

                  

                  <input class="form-control datepicker" autocomplete="off" placeholder="Date" name="df">

                </div>

                <h5 class="mb-4">Type</h5>

                <div class="mb-4">

                  <div class="custom-control custom-radio">

                    

                    <input type="radio" id="customRadio1" value="vente" checked="" name="etatProduit" class="custom-control-input">

                    <label class="custom-control-label"  for="customRadio1">Vente </label>

                    

                  </div>

                  <div class="custom-control custom-radio">

                    

                    <input type="radio" id="customRadio2" value="achat" name="etatProduit" class="custom-control-input">

                    <label class="custom-control-label"  for="customRadio2">Achat  </label>

                    

                  </div>

                </div>

                

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>

                <input type="submit" name="submit" value="Afficher"  class="btn btn-primary"  data-toggle="modal" data-target=".bd-example-modal-lg">

                

              </div>

            </form>

          </div>

        </div>

      </div>

      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">

        <div class="modal-dialog modal-lg">

          <div class="modal-content">

            <div class="modal-header">

              <h5 class="modal-title">Liste des Ventes des clients : kit des implants</h5>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">&times;</span>

              </button>

            </div>

            <div class="modal-body" id="etatstatic">

              

            </div>

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

  })

  $('#datatables').dataTable({
  responsive: true,
  order: [

  [0, "desc"]

  ],

  dom: 'Bfrtip',

  buttons: [{

  extend: 'excelHtml5',

  title: "liste des ventes",

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  },

  {

  extend: 'pdfHtml5',

  title: "liste des ventes",

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  },

  {

  extend: 'csvHtml5',

  title: "liste des ventes",

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  }

  ],

  pageLength: 20,

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

  text: "Voulez vous vraiment Supprimer cet opération !",

  type: 'warning',

  showCancelButton: true,

  confirmButtonColor: '#d33',

  cancelButtonColor: '#3085d6',

  confirmButtonText: 'Oui, Supprimer !'

  }).then((result) => {

  if (result.value) {

  $.ajax({

  type: "POST",

  url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",

  data: {

  act: "delete",

  id: btn.data('id')

  },

  success: function(data) {

  swal(

  'Supprimer',

  'Opération a été bien Supprimer',

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

  text: "Voulez vous vraiment Archiver ce client !!",

  type: 'warning',

  showCancelButton: true,

  confirmButtonColor: '#145388',

  cancelButtonColor: '#3085d6',

  confirmButtonText: 'Oui, Archiver!'

  }).then((result) => {

  if (result.value) {

  $.ajax({

  type: "POST",

  url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",

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

  url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",

  data: {

  act: "getName",

  id: btn.data('id')

  },

  success: function(datas) {

  var data = datas.split(';;;');

  $('#exampleModalRight .modal-title').html("Etat client " + data[1]);

  $('#idstatic').val(data[0]);

  }

  });

  });

  $("#Staticform").on("submit", function(event) {

  event.preventDefault();

  var form = $(this);

  $.ajax({

  type: "POST",

  url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",

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
//--------------------------------------------------
  $("#formfilter").on("submit", function(event) {

  event.preventDefault();

  $("#results").html('<div class="col-md-12"><br><br><br><br><br><br><div class="loading"></div></div>');

  var form = $(this);

  $.ajax({

  type: "POST",

  url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",

  data: new FormData(this),

  dataType: 'text',

  cache: false,

  contentType: false,

  processData: false,

  success: function(data) {

  $("#results").html(data);

  $('#datatables').dataTable({
    responsive: true,
  order: [

  [0, "desc"]

  ],

  dom: 'Bfrtip',

  buttons: [{

  extend: 'excelHtml5',

  title: "liste des ventes "+ $("#mois").val() +"-"+ $("#anne").val(),

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  },

  {

  extend: 'pdfHtml5',

  title: "liste des ventes "+ $("#mois").val() +"-"+ $("#anne").val(),

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  },

  {

  extend: 'csvHtml5',

  title: "liste des ventes "+ $("#mois").val() +"-"+ $("#anne").val(),

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  }

  ],

  pageLength: 20,

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

  }

  });

  });

  });

  </script>
  <script>
     document.addEventListener('DOMContentLoaded', () => {
         const cols = document.querySelectorAll('td[data-href]');
         console.log(cols);
     });
  </script>