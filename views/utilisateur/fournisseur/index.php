<?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
$fournisseur = new fournisseur();
$data = $fournisseur->selectAllNonArchive();
?>
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Liste Des Fournisseurs</h1>
                <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="fournisseur/add.php" >AJOUTER</button>
                    <a target="_blanck" href="<?php echo BASE_URL.'views/fournisseur/import.php'; ?>" class="btn btn-primary btn-lg  mr-1 url">Importer des fournisseurs</a>
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
                    
                    <table class="table responsive table-striped table-bordered" id="datatables" >
                        <thead>
                            <tr>
                               <th scope="col" width="1px">Id</th>
                            <th scope="col">Raison Social</th>
                            <th scope="col">T&eacute;l&eacute;phone</th>
                            <th> Email</th>
                            <th scope="col"> Responsable</th>
                            <th scope="col">GSM Responsable</th>
                            <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php  
                         foreach($data as $ligne){
                                   ?>
                                  <tr>
                                  <td> <?php echo $ligne->id_fournisseur; ?> </td>
                               <td> <?php echo $ligne->raison_sociale; ?> </td>
                               <td> <?php echo $ligne->telephone; ?> </td>
                               <td> <?php echo $ligne->email; ?> </td>
                               <td> <?php echo $ligne->responsable; ?> </td>
                               <td> <?php echo $ligne->tph_respo; ?> </td>
                              <td>
                  <?php if(auth::user()['privilege'] == 'Admin') { ?>
                  <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_fournisseur; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                    <i class="simple-icon-trash" style="font-size: 15px;"></i>
                  </a>
                  <a class="badge badge-warning mb-2  url notlink" data-url="fournisseur/update.php?id=<?php echo $ligne->id_fournisseur; ?>" style="color: white;cursor: pointer;" title="Modifier"
                    href="javascript:void(0)">
                    <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                  </a>
                 
                  <a class="badge badge-info mb-2 " href="<?php echo BASE_URL.'views/etat/fournisseur_etat_achat.php?id='.$ligne->id_fournisseur; ?>" target="_blanck" style="color: white;cursor: pointer;" title="Etat de vente" >
                    <i class="iconsmind-Billing" style="font-size: 15px;"></i>
                  </a>
                   <br>
                  <a class="badge badge-success mb-2  url notlink" data-url="reg_fournisseur/index.php?id=<?php echo $ligne->id_fournisseur; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)' >
                    <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>
                  </a>
                  
                  <a  class="badge badge-secondary mb-2 " href="<?php echo BASE_URL.'views/etat/etat_achat.php?id_fournisseur='.$ligne->id_fournisseur; ?>" target="_blanck" style="color: white;cursor: pointer;" title="Immprimer etat de vente" href="javascript:void(0)">
                    
                    <i class="simple-icon-pie-chart" style="font-size: 15px;"></i>
                  </a>
                  
                  
                  <a class="badge badge-primary mb-2 archive" data-id="<?php echo $ligne->id_fournisseur; ?>" data-arc ="1" style="color: white;cursor: pointer;" title="Archiver">
                  <i class="simple-icon-social-dropbox" style="font-size: 15px;"></i> </a>
                  
                  
                  <?php  } ?>
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
            
            $(document).ready(function () {

                $("input.datepicker").datepicker({
                     format: 'yyyy-mm-dd',
                     templates: {
                     leftArrow: '<i class="simple-icon-arrow-left"></i>',
                     rightArrow: '<i class="simple-icon-arrow-right"></i>'
                    }
                })
 $('#datatables').dataTable({
                 order: [[ 0, "desc" ]],
                dom: 'Bfrtip',
                  buttons: [
                      
            {
                extend: 'excelHtml5',
                title:"list Fournisseurs",
                exportOptions: {
                     columns: [ 0, 1, 2,3,4,5,6 ]
                }
            },
            {
                extend: 'pdfHtml5',
                title:"list Fournisseurs",
                exportOptions: {
                    columns: [ 0, 1, 2,3,4,5,6 ]
                }
            },
            {
                extend: 'csvHtml5',
                title:"list Fournisseurs",
                exportOptions: {
                    columns: [ 0, 1, 2,3,4,5,6 ]
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
            
            $('body').on( "click",".delete", function( event ) {
             event.preventDefault();


                    var btn = $(this);
                swal({
                 title: 'Êtes-vous sûr?',
                  text: "Voulez vous vraiment Supprimer ce produit !",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, Supprimer !'
                }).then((result) => {
                  if (result.value) {

                $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/fournisseur/' ;?>controle.php",
                data: {act:"delete",id: btn.data('id')},
                success: function (data) {
                   
                   swal(
                      'Deleted',
                      'Your file has been deleted.',
                      'success'
                    ).then((result) => {

                        btn.parents("td").parents("tr").remove();
                    });
                   
                }
            });
                    
                  }
                });
           
            });


        $('body').on( "click",".archive", function( event ) {
             event.preventDefault();


                    var btn = $(this);
                swal({
                 title: 'Êtes-vous sûr?',
                  text: "Voulez vous vraiment Archiver ce produit !!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#145388',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, Archiver!'
                }).then((result) => {
                  if (result.value) {

                $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/fournisseur/' ;?>controle.php",
                data: {act:"archive",id: btn.data('id'),val:btn.data('arc')},
                success: function (data) {
                   
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


              $('body').on( "click",".static", function( event ) {
             event.preventDefault();

              var btn = $(this); 
                 $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/fournisseur/' ;?>controle.php",
                data: {act:"getName",id: btn.data('id')},
                success: function (datas) {
                    var data = datas.split(';;;');
                            $('#exampleModalRight .modal-title').html("Etat produit "+data[1]);
                            $('#idstatic').val(data[0]);
                            }
                        });

          
           
            });


               $("#Staticform" ).on( "submit", function( event ) {
             event.preventDefault();

             var form = $( this );
             $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/fournisseur/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                

                      $('#etatstatic').html(data);  
                         
                                              
                }
            });
           
            });

               });

            </script>