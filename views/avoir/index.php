<?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
$avoir = new avoir();
$data=$avoir->selectAll2();
?>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Liste des avoir</h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="avoir/add.php" >AJOUTER</button>
        </div>
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
                      <label > Année : </label>
                      <select name='anne' class="form-control">
                        <option value="0">Tous</option>
                       <?php for($d=Date("Y");$d>= 2009;$d--)
                        echo "<option> $d</option>";?>
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label> Mois : </label>
                      <select name='mois' class="form-control">
                        <option value="0">Tous</option>
                        <?php for($m= 1;$m<=9;$m++)
                        echo "<option>0$m</option>";?>
                        <?php for($m= 10;$m<=12;$m++)
                        echo "<option>$m</option>";?>
                      </select>
                    </div>
                    
                    <div class="form-group col-md-2">
                      <button  type="submit" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 30px;">Afficher</button>
                    </div>
                  </div>
                </form>
          <div id="results">
            <table class="table  responsive table-striped table-bordered table-hover" id="datatables" >
              <thead>
                <tr>
                  
                  <th scope="col" width="1px"  >Id</th>
                  <th   scope="col">Client</th>
                  <th> Date </th>
                  <th   scope="col"> Montant </th>
                  <th   scope="col"> Reste </th>
                  <th   scope="col"> remarque </th>
                  <th   scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                
                <?php
                foreach($data as $ligne){
                $query=$result=connexion::getConnexion()->query("SELECT count(*) as nbr,id_facture FROM facture_avoir where id_avoir like '%".$ligne->id_avoir."%'");
                $result=$query->fetch(PDO::FETCH_OBJ);
                $id_facture=$result->id_facture;
                $nbr=$result->nbr;
                ?>
                <tr>
                  
                  <td> <?php echo $ligne->id_avoir ; ?></td>
                  <td>  <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->client;
                  if($ligne->nom_prenom_ar != "" && $ligne->client == " ") {
                  echo $ligne->nom_prenom_ar;
                  }
                  if($ligne->nom_prenom_ar != "" && $ligne->client != " ") {
                  echo "/" . $ligne->nom_prenom_ar;
                  }
                ?> </a> </td>
                  <td><?php if($nbr>0){echo "<a target='_blank'  href='../avoir/facture.php?id=".$ligne->id_avoir."&idf=".$id_facture."' tilite='Facture'> ".$ligne->date_avoir."</a>" ;} else{echo $ligne->date_avoir ;} ?> </td>
                  <td style="text-align: right;" > <?php echo number_format($ligne->montantv,2,'.',' ') ;   ?> &nbsp;&nbsp;</td>
                  <td style="text-align: right;" > <?php
                    $query=$result=connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_avoir where id_avoir=".$ligne->id_avoir);
                    $result=$query->fetch(PDO::FETCH_OBJ);
                    $paye=$result->paye;
                    echo    number_format($ligne->montantv-$paye,2,'.',' ') ;
                  ?>  &nbsp;&nbsp;</td>
                  
                  
                  <td> <?php echo $ligne->remarque ; ?> </td>
                  <td>
                    <?php if(auth::user()['privilege'] == 'Admin') { ?>
                    <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_avoir; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                      <i class="simple-icon-trash" style="font-size: 15px;"></i>
                    </a>
                    <a class="badge badge-success mb-2  url notlink" data-url="reg_avoir/index.php?id=<?php echo $ligne->id_avoir; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)' >
                      <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>
                    </a>
                    
                    <?php } ?>
                    
                    <a  class="badge badge-secondary mb-2 url notlink" data-url="detail_avoir/index.php?id=<?php echo $ligne->id_avoir; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">
                      
                      <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>
                    </a>
                    <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL."views/avoir/etat.php?id=".$ligne->id_avoir; ?>"  target="_black" >

                        <i class=" simple-icon-printer" style="font-size: 15px;"></i>

                      </a>
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
                  
                  <input type="radio" id="customRadio1" value="avoir" checked="" name="etatProduit" class="custom-control-input">
                  <label class="custom-control-label"  for="customRadio1">avoir </label>
                  
                </div>
                <div class="custom-control custom-radio">
                  
                  <input type="radio" id="customRadio2" value="avoir" name="etatProduit" class="custom-control-input">
                  <label class="custom-control-label"  for="customRadio2">avoir  </label>
                  
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
            <h5 class="modal-title">Liste des avoir de client : kit des implants</h5>
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
                title: "liste des Avoir",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'pdfHtml5',
                title: "liste des Avoir",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'csvHtml5',
                title: "liste des Avoir",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
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
                    url: "<?php echo BASE_URL.'views/avoir/' ;?>controle.php",
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
                    url: "<?php echo BASE_URL.'views/avoir/' ;?>controle.php",
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
            url: "<?php echo BASE_URL.'views/avoir/' ;?>controle.php",
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

    $("#formfilter").on("submit", function(event) {
        event.preventDefault();
        $("#results").html('<div class="col-md-12"><br><br><br><br><div class="loading"></div></div>');
        var form = $(this);
        $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL.'views/avoir/' ;?>controle.php",
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
                                    title: "liste des avoir ",
                                    exportOptions: {
                                        columns: [0, 1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: "liste des avoir ",
                                    exportOptions: {
                                        columns: [0, 1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    extend: 'csvHtml5',
                                    title: "liste des avoir ",
                                    exportOptions: {
                                        columns: [0, 1, 2, 3, 4, 5]
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