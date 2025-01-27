<?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
 $categorie=new categorie();
 $data=$categorie->selectAll();
?>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Liste des categorie</h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="categorie/add.php" >AJOUTER</button>
        </div>
        
      </div>
      
      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">
    
    
    
    <div class="col-xl-12 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <?php if(count($data) > 0) { ?>
          
          <table class="table  responsive table-striped table-bordered table-hover" style="width: 60%" id="datatables" >
            <thead>
              <tr>
                <th scope="col" width="17" >Id</th>
                <th width="292"   scope="col">Nom</th>
                <th width="292"   scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($data as $ligne){
              ?>
              <tr>
                <td> <?php echo $ligne->id_categorie ; ?></td>
                <td> <?php echo $ligne->nom ; ?> </td>
                <td>
                  <?php if(auth::user()['privilege'] == 'Admin') { ?>
                  <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_categorie; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                    <i class="simple-icon-trash" style="font-size: 15px;"></i>
                  </a>
                  <?php } ?>
                  <?php if(auth::user()['privilege'] == 'Admin' || auth::user()['privilege'] == 'User+'){ ?>
                  <a class="badge badge-warning mb-2  url notlink" data-url="categorie/update.php?id=<?php echo $ligne->id_categorie; ?>" style="color: white;cursor: pointer;" title="Modifier"
                    href="javascript:void(0)">
                    <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                  </a>
                  <?php } ?>
                  
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

            <script type="text/javascript">
            
            $(document).ready(function () {

        

           $('#datatables').dataTable({
                  order: [[ 0, "desc" ]],
                  dom: 'Bfrtip',
                  buttons: [
                      {
                          extend: 'excelHtml5',
                          title:"list categories",
                          exportOptions: {
                               columns: [ 0, 1,]
                          }
                      },
                      {
                          extend: 'pdfHtml5',
                          title:"list categories",
                          exportOptions: {
                              columns: [ 0, 1,]
                          }
                      },
                      {
                          extend: 'csvHtml5',
                          title:"list categories",
                          exportOptions: {
                              columns: [ 0, 1,]
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
            
            $('body').on( "click",".delete", function( event ) {
             event.preventDefault();


                    var btn = $(this);
                swal({
                 title: 'Êtes-vous sûr?',
                  text: "Voulez vous vraiment Supprimer ce categorie !",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, Supprimer !'
                }).then((result) => {
                  if (result.value) {

                $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/categorie/' ;?>controle.php",
                data: {act:"delete",id: btn.data('id')},
                success: function (data) {
                   
                   swal(
                      'Supprimer',
                      'categorie a ete bien Supprimer',
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