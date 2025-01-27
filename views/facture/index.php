<?php

if (isset($_POST['ajax'])) {
include('../../evr.php');
}
$facture=new facture();
$data=$facture->selectAll3(date('Y').'-'.date('m'), 1);


?>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Liste Des Factures</h1>
        
      </div>
      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">
    
    
    
    <div class="col-xl-12 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
      
          <form method="get" name="frm" id="formfilter">
                  <input type="hidden" name="act" value="filter">
                  <div class="form-row">
                    <div class="form-group col-md-2">
                      <h4 class="mt-5 ">Mois de  recherche : </h4>
                    </div>
                    
                    <div class="form-group col-md-2">
                      <label for="id_client">Année : </label>
                      <select name='anne' class="form-control" id="anne">
                        <option value="0">Tous</option>
                       <?php for($d=Date("Y");$d>= 2009;$d--)
						echo "<option value='$d'> $d</option>";?>
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label> Mois: </label>
                      <select name='mois' class="form-control" id="mois">
                       <option value="0">Tous</option>
                       <?php for($m= 1;$m<=9;$m++)

					  echo "<option value='0$m' >0$m</option>";?>

					  <?php for($m= 10;$m<=12;$m++)

					  echo "<option value='$m' >$m</option>";?>
                      </select>
                    </div>
                    
                    <div class="form-group col-md-2">
                      <button  type="submit" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top:30px;">Afficher</button>
                    </div>
                  </div>
                </form>
          <div id="results">
          
          <table class="table  responsive table-striped table-bordered table-hover" id="datatables" >
            <thead>
              <tr>
                <th>Id</th>
                <th>Client</th>
                <th>Date Facture</th>
                <th>N° FA</th>
                <th>Montant</th>
                <th>Id facture</th>
                <th>Remarque</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
             
             <?php      
 foreach($data as $ligne){
 ?>
          <tr>
             <td> <?php echo $ligne->id_facture ; ?></td>
            <td> <?php echo $ligne->nom ; ?></td>
            <td> <?php echo $ligne->date_facture ; ?> </td>
            <td> <?php echo $ligne->num_fact ; ?> </td>
            <td> <a style="cursor:pointer; color:#E84922;" href="<?php echo BASE_URL."views/facture/facture.php?id=".$ligne->id_vente."&idf=".$ligne->id_facture; ?>&h=15"  target="_blank">
              <?php 
              if(!empty($ligne->motunitv) || $ligne->motunitv!=0){
                echo number_format($ligne->montant1,2,'.',' ') ; 
              }else{
                echo number_format($ligne->montant1,2,'.',' ') ; 
              }
              ?> </a>
            </td> 
            <td> <?php echo trim($ligne->id_facture,',') ; ?> </td> 
            <td> <?php echo $ligne->remarque ; ?> </td>  
                <td>
                  <?php if(auth::user()['privilege'] == 'Admin') { ?>
                  <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_facture; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                    <i class="simple-icon-trash" style="font-size: 15px;"></i>
                  </a>

                  <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Facture" href="<?php echo BASE_URL."views/facture/facture.php?id=".$ligne->id_vente."&idf=".$ligne->id_facture; ?>&h=15"  target="_black" >
                    <i class=" simple-icon-printer" style="font-size: 15px;"></i>
                  </a>
                  
                  <a class="badge badge-success  mb-2  " style="color: white;cursor: pointer;" title="test" href="<?php echo BASE_URL."views/facture/facture_test.php?id=".$ligne->id_vente."&idf=".$ligne->id_facture; ?>&h=15"  target="_black" >
                    <i class=" simple-icon-printer" style="font-size: 15px;"></i>
                  </a>
                  
                  <?php } ?>
                  <?php if(auth::user()['privilege'] == 'Admin' || auth::user()['privilege'] == 'User+'){ ?>
                <a class="badge badge-warning mb-2  url notlink" data-url="facture/update.php?id=<?php echo $ligne->id_facture; ?>" style="color: white;cursor: pointer;" title="Modifier"
                  href="javascript:void(0)">
                  <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
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
                  
                  <input type="radio" id="customRadio1" value="facture" checked="" name="etatProduit" class="custom-control-input">
                  <label class="custom-control-label"  for="customRadio1">facture </label>
                  
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
            <h5 class="modal-title">Liste des facture de client : kit des implants</h5>
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
        order: [
            [0, "desc"]
        ],
        dom: 'Bfrtip',
        buttons: [{
                extend: 'excelHtml5',
                title: "liste des factures",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,]
                }
            },
            {
                extend: 'pdfHtml5',
                title: "liste des factures",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,]
                }
            },
            {
                extend: 'csvHtml5',
                title: "liste des factures",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,]
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

    $('body').on("click", ".delete", function(event) {
        event.preventDefault();


        var btn = $(this);
        swal({
            title: 'Êtes-vous sûr?',
            text: "Voulez vous vraiment Supprimer ce Client !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, Supprimer !'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    type: "POST",
                    url: "<?php echo BASE_URL.'views/facture/' ;?>controle.php",
                    data: {
                        act: "delete",
                        id: btn.data('id')
                    },
                    success: function(data) {

                        swal(
                            'Supprimer',
                            'Client a ete bien Supprimer',
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
                    url: "<?php echo BASE_URL.'views/facture/' ;?>controle.php",
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
            url: "<?php echo BASE_URL.'views/facture/' ;?>controle.php",
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
            url: "<?php echo BASE_URL.'views/facture/' ;?>controle.php",
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
    $("#formfilter").on("submit", function(event) {

        event.preventDefault();
        $("#results").html('<div class="col-md-12"><br><br><br><br><div class="loading"></div></div>');
        var form = $(this);
        $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL.'views/facture/' ;?>controle.php",
            data: new FormData(this),
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {


                $("#results").html(data);

                $('#datatables').dataTable({
                    order: [
                        [0, "desc"]
                    ],
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excelHtml5',
                            title: "liste des factures "+ $("#mois").val() +"-"+ $("#anne").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: "liste des factures "+ $("#mois").val() +"-"+ $("#anne").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: "liste des factures "+ $("#mois").val() +"-"+ $("#anne").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
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


            }
        });
    });

});

            </script>
