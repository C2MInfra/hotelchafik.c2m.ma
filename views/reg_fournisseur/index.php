<?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}

$id = explode('?id=',$_SERVER["REQUEST_URI"])[1];

 $reg_fournisseur=new reg_fournisseur();
 $data=$reg_fournisseur->selectAll2($id);

 $fournisseur=new fournisseur();
 $fournisseur=$fournisseur->selectById($id);
?>
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-success  url notlink" data-url="fournisseur/index.php" > <i class="glyph-icon simple-icon-arrow-left"></i></button>
                </div>
                
                <h1>reglement fournisseur N° <?php echo $id ?> : <?php echo $fournisseur["nom"]." ".$fournisseur["prenom"] ?></h1>
                <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="reg_fournisseur/add.php?id=<?php echo $id ?>" >AJOUTER</button>
                </div>
                 
                
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                  
                    <table class="table responsive table-striped " id="datatables">
                        <thead>
                            <tr>
                                <th scope="col" width="1px" >Id</th>
                                <th   scope="col">Mode</th>
                                <th   scope="col">Num&egrave;ro</th>
                                <th> Date </th>
                                <th   scope="col"> Remarque </th>
                                <th    scope="col"> Montant </th>
                                
                                <th   scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $avance=0;
                            foreach($data as $ligne){
                            ?>
                            <tr>
                                <td> <?php echo $ligne->id_reg ; ?></td>
                                <td> <?php echo $ligne->mode_reg ; ?> </td>
                                <td> <?php echo $ligne->num_cheque ; ?> </td>
                                <td> <?php echo $ligne->date_reg ; ?> </td>
                                <td> <?php echo $ligne->remarque ; ?> </td>
                                <td style="float:right" > <?php echo number_format($ligne->montant,2,'.',' ') ;
                                    $avance+=$ligne->montant;
                                ?> </td>
            
                                <td>
                                    <?php if(auth::user()['privilege'] == 'Admin') { ?>
                                    <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_reg; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                                        <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
                                    </a>
                                    <?php } ?>
                                </td>
                                    
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <h5 class="mb-4">Total : <?php echo  number_format($avance,2,"."," ");?> </h5>
                       
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
                extend: 'excel',
                title:"reglement fournisseur N° <?php echo $id ?> : <?php echo $fournisseur["nom"]." ".$fournisseur["prenom"] ?>",
                exportOptions: {
                     columns: [ 0, 1, 2,3,4,5 ]
                }
            },
            {
                extend: 'pdfHtml5',
                alignment: 'center',
                title:"reglement fournisseur N° <?php echo $id ?> : <?php echo $fournisseur["nom"]." ".$fournisseur["prenom"] ?>",
                exportOptions: {
                    columns: [ 0, 1, 2,3,4,5 ]
                },
                customize: function ( doc ) {
                doc.content.splice( 1, 0, {
                    margin: [ 0, 0, 0, 12 ],
                    alignment: 'center',
                    image: "data:image/png;base64,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"
                } );
               
            }
            },
            {
                extend: 'csvHtml5',
                title:"reglement fournisseur N° <?php echo $id ?> : <?php echo $fournisseur["nom"]." ".$fournisseur["prenom"] ?>",
                exportOptions: {
                    columns: [ 0, 1, 2,3,4,5 ]
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
                url: "<?php echo BASE_URL.'views/reg_fournisseur/' ;?>controle.php",
                data: {act:"delete",id: btn.data('id')},
                success: function (data) {
                   
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