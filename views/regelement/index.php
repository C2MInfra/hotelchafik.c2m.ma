<?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
$reg_vente = new reg_vente();
$reg_achat = new reg_achat();
$reg_client = new reg_client();
$reg_fournisseur = new reg_fournisseur();
?>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Liste des règlements</h1>
        <!-- <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="vente/add.php" >AJOUTER</button>
        </div> -->
      </div>
      <ul class="nav nav-tabs separator-tabs ml-0 mb-5" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="Ventes-tab" data-toggle="tab" href="#Ventes" role="tab" aria-controls="Ventes" aria-selected="true">Règlements des Ventes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="Achats-tab" data-toggle="tab" href="#Achats" role="tab" aria-controls="Achats" >Règlements des Achats</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="Client-tab" data-toggle="tab" href="#Client" role="tab" aria-controls="Client" >Règlements des Client</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="Fournisseurs-tab" data-toggle="tab" href="#Fournisseurs" role="tab" aria-controls="Fournisseurs" >Règlements des Fournisseurs</a>
        </li>
      </ul>
    </div>
  </div>
  <div class="tab-content">
    <div class="tab-pane fade show active" id="Ventes" role="tabpanel" aria-labelledby="Ventes-tab">
      <div class="row">
        <div class="col-xl-12 col-lg-12 mb-4">
          <div class="card h-100">
            <div class="card-body">
              
              <form method="get" name="frm" id="formfilterVentes">
                <input type="hidden" name="act" value="filterVentes">
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <h4 class="mt-5 ">Mois de  recherche : </h4>
                  </div>
                  
                  <div class="form-group col-md-2">
                    <label for="id_client">Année : </label>
                    <select name='anne' class="form-control" id="anne1">
                      <option value="0">Tous</option>
                      <?php for($d=Date("Y");$d>= 2009;$d--)
                      echo "<option value='$d'> $d</option>";?>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label> Mois: </label>
                    <select name='mois' class="form-control" id="mois1">
                      <?php for($m= 1;$m<=9;$m++)
                      echo "<option value='0$m' >0$m</option>";?>
                      <?php for($m= 10;$m<=12;$m++)
                      echo "<option value='$m' >$m</option>";?>
                    </select>
                  </div>
                  
                  <div class="form-group col-md-2">
                    <button  type="submit" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 30px;">Afficher</button>
                  </div>
                </div>
              </form>
              <div id="resultsVentes">
                <table class="table responsive table-striped " id="datatablesVentes">
                  <thead>
                    <tr>
                      <th scope="col" width="1px" >Id</th>
                      <th   scope="col">Client</th>
                      <th   scope="col">Mode</th>
                      <th   scope="col">BL</th>
                      <th> Date </th>
                      <th   scope="col"> Remarque </th>
                      <th    scope="col"> Montant </th>
                      <th   scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $avance=0;
                    foreach($reg_vente->selectAll3_er(date('Y') . '-' . date('m')) as $ligne){
                    ?>
                    <tr>
                      <td> <?php echo $ligne->id_reg ; ?></td>
                      <td> <?php echo $ligne->nom ; ?> </td>
                      <td> <?php echo $ligne->mode_reg ; ?> </td>
                      
                      <td> <?php echo $ligne->numbon ; ?> </td>
                      <td> <?php echo $ligne->date_reg ; ?> </td>
                      <td> <?php echo $ligne->remarque ; ?> </td>
                      <td style="float:right" > <?php echo number_format($ligne->montant,2,'.',' ') ;
                        $avance+=$ligne->montant;
                      ?> </td>
                      
                      <td>
                        <?php if(auth::user()['privilege'] == 'Admin') { ?>
                        <a class="badge badge-danger mb-2 deleteVentes" data-id="<?php echo $ligne->id_reg; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                          <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
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
      </div>
    </div>
    <div class="tab-pane fade" id="Achats" role="tabpanel" aria-labelledby="Achats-tab">
      <div class="row">
        <div class="col-xl-12 col-lg-12 mb-4">
          <div class="card h-100">
            <div class="card-body">
              
              <form method="get" name="frm" id="formfilterAchats">
                <input type="hidden" name="act" value="filterAchats">
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <h4 class="mt-5 ">Mois de  recherche : </h4>
                  </div>
                  
                  <div class="form-group col-md-2">
                    <label for="id_client">Année : </label>
                    <select name='anne' class="form-control" id="anne2">
                      <option value="0">Tous</option>
                      <?php for($d=Date("Y");$d>= 2009;$d--)
                      echo "<option value='$d'> $d</option>";?>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label> Mois: </label>
                    <select name='mois' class="form-control" id="mois2">
                      <?php for($m= 1;$m<=9;$m++)
                      echo "<option value='0$m' >0$m</option>";?>
                      <?php for($m= 10;$m<=12;$m++)
                      echo "<option value='$m' >$m</option>";?>
                    </select>
                  </div>
                  
                  <div class="form-group col-md-2">
                    <button  type="submit" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 25px;">Afficher</button>
                  </div>
                </div>
              </form>
              <div id="resultsAchats">
                <table class="table responsive table-striped " id="datatablesAchats">
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
                    
                    foreach($reg_achat->selectAll3_er(date('Y') . '-' . date('m')) as $ligne){
                    ?>
                    <tr>
                      <td> <?php echo $ligne->id_reg ; ?></td>
                      <td> <?php echo $ligne->mode_reg ; ?> </td>
                      <td> <?php echo $ligne->num_cheque ; ?> </td>
                      <td> <?php echo $ligne->date_reg ; ?> </td>
                      <td> <?php echo $ligne->remarque ; ?> </td>
                      <td style="float:right" > <?php echo number_format($ligne->montant,2,'.',' ') ; ?> </td>
                      
                      <td>
                        <?php if(auth::user()['privilege'] == 'Admin') { ?>
                        <a class="badge badge-danger mb-2 deleteAchats" data-id="<?php echo $ligne->id_reg; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                          <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
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
      </div>
    </div>
    <div class="tab-pane fade" id="Client" role="tabpanel" aria-labelledby="Client-tab">
      <div class="row">
        <div class="col-xl-12 col-lg-12 mb-4">
          <div class="card h-100">
            <div class="card-body">
              
              <form method="get" name="frm" id="formfilterClient">
                <input type="hidden" name="act" value="filterClient">
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <h4 class="mt-5 ">Mois de  recherche : </h4>
                  </div>
                  
                  <div class="form-group col-md-2">
                    <label for="id_client">Année : </label>
                    <select name='anne' class="form-control" id="anne3">
                      <option value="0">Tous</option>
                      <?php for($d=Date("Y");$d>= 2009;$d--)
                      echo "<option value='$d'> $d</option>";?>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label> Mois: </label>
                    <select name='mois' class="form-control" id="mois3">
                      <?php for($m= 1;$m<=9;$m++)
                      echo "<option value='0$m' >0$m</option>";?>
                      <?php for($m= 10;$m<=12;$m++)
                      echo "<option value='$m' >$m</option>";?>
                    </select>
                  </div>
                  
                  <div class="form-group col-md-2">
                    <button  type="submit" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 25px;">Afficher</button>
                  </div>
                </div>
              </form>
              <div id="resultsClient">
                <table class="table responsive table-striped " id="datatablesClient">
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
                    
                    foreach($reg_client->selectAll3_er(date('Y') . '-' . date('m')) as $ligne){
                    ?>
                    <tr>
                      <td> <?php echo $ligne->id_reg ; ?></td>
                      <td> <?php echo $ligne->mode_reg ; ?> </td>
                      <td> <?php echo $ligne->num_cheque ; ?> </td>
                      <td> <?php echo $ligne->date_reg ; ?> </td>
                      <td> <?php echo $ligne->remarque ; ?> </td>
                      <td style="float:right" > <?php echo number_format($ligne->montant,2,'.',' ') ; ?> </td>
                      
                      <td>
                        <?php if(auth::user()['privilege'] == 'Admin') { ?>
                        <a class="badge badge-danger mb-2 deleteClient" data-id="<?php echo $ligne->id_reg; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                          <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
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
      </div>
    </div>
    <div class="tab-pane fade" id="Fournisseurs" role="tabpanel" aria-labelledby="Fournisseurs-tab">
      <div class="row">
        <div class="col-xl-12 col-lg-12 mb-4">
          <div class="card h-100">
            <div class="card-body">
              
              <form method="get" name="frm" id="formfilterFournisseurs">
                <input type="hidden" name="act" value="filterFournisseurs">
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <h4 class="mt-5 ">Mois de  recherche : </h4>
                  </div>
                  
                  <div class="form-group col-md-2">
                    <label for="id_Fournisseurs">Année : </label>
                    <select name='anne' class="form-control" id="anne4">
                      <option value="0">Tous</option>
                      <?php for($d=Date("Y");$d>= 2009;$d--)
                      echo "<option value='$d'> $d</option>";?>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label> Mois: </label>
                    <select name='mois' class="form-control" id="mois4">
                      <?php for($m= 1;$m<=9;$m++)
                      echo "<option value='0$m' >0$m</option>";?>
                      <?php for($m= 10;$m<=12;$m++)
                      echo "<option value='$m' >$m</option>";?>
                    </select>
                  </div>
                  
                  <div class="form-group col-md-2">
                    <button  type="submit" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 25px;">Afficher</button>
                  </div>
                </div>
              </form>
              <div id="resultsFournisseurs">
                <table class="table responsive table-striped " id="datatablesFournisseurs">
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
                    
                    foreach($reg_fournisseur->selectAll3_er(date('Y') . '-' . date('m')) as $ligne){
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
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


 <script type="text/javascript">
            
 $(document).ready(function() {

    $('#datatablesVentes').dataTable({
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

    $('body').on("click", ".deleteVentes", function(event) {
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
                    url: "<?php echo BASE_URL.'views/regelement/' ;?>controle.php",
                    data: {
                        act: "deleteVentes",
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

    $("#formfilterVentes").on("submit", function(event) {
        event.preventDefault();
        $("#resultsVentes").html('<div class="col-md-12"><br><br><br><br><div class="loading"></div></div>');
        var form = $(this);
        $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL.'views/regelement/' ;?>controle.php",
            data: new FormData(this),
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {

           
                $("#resultsVentes").html(data);

                $('#datatablesVentes').dataTable({
                    order: [
                        [0, "desc"]
                    ],
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excelHtml5',
                            title: "liste des regelement ventes "+ $("#mois1").val() +"-"+ $("#anne1").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: "liste des regelement ventes "+ $("#mois1").val() +"-"+ $("#anne1").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: "liste des regelement ventes "+ $("#mois1").val() +"-"+ $("#anne1").val(),
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



  //@@@@@@@@@@@@@@@@  Achat 

      $('#datatablesAchats').dataTable({
        order: [
            [0, "desc"]
        ],
        dom: 'Bfrtip',
        buttons: [{
                extend: 'excelHtml5',
                title: "liste des  reglument Achats",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,]
                }
            },
            {
                extend: 'pdfHtml5',
                title: "liste des reglument Achats",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,]
                }
            },
            {
                extend: 'csvHtml5',
                title: "liste des reglument Achats",
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

    $('body').on("click", ".deleteAchats", function(event) {
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
                    url: "<?php echo BASE_URL.'views/regelement/' ;?>controle.php",
                    data: {
                        act: "deleteAchats",
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

    $("#formfilterAchats").on("submit", function(event) {
        event.preventDefault();
        $("#resultsAchats").html('<div class="col-md-12"><br><br><br><br><div class="loading"></div></div>');
        var form = $(this);
        $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL.'views/regelement/' ;?>controle.php",
            data: new FormData(this),
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {

           
                $("#resultsAchats").html(data);

                $('#datatablesAchats').dataTable({
                    order: [
                        [0, "desc"]
                    ],
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excelHtml5',
                            title: "liste des regelement Achats "+ $("#mois2").val() +"-"+ $("#anne2").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: "liste des regelement Achats "+ $("#mois2").val() +"-"+ $("#anne2").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: "liste des regelement Achats "+ $("#mois2").val() +"-"+ $("#anne2").val(),
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





 // @@@@@@@@@@@@@@@@@ client 

      $('#datatablesClient').dataTable({
        order: [
            [0, "desc"]
        ],
        dom: 'Bfrtip',
        buttons: [{
                extend: 'excelHtml5',
                title: "liste des  reglument Client",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,]
                }
            },
            {
                extend: 'pdfHtml5',
                title: "liste des reglument Client",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,]
                }
            },
            {
                extend: 'csvHtml5',
                title: "liste des reglument Client",
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

    $('body').on("click", ".deleteClient", function(event) {
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
                    url: "<?php echo BASE_URL.'views/regelement/' ;?>controle.php",
                    data: {
                        act: "deleteClient",
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

    $("#formfilterClient").on("submit", function(event) {
        event.preventDefault();
        $("#resultsClient").html('<div class="col-md-12"><br><br><br><br><div class="loading"></div></div>');
        var form = $(this);
        $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL.'views/regelement/' ;?>controle.php",
            data: new FormData(this),
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {

           
                $("#resultsClient").html(data);

                $('#datatablesClient').dataTable({
                    order: [
                        [0, "desc"]
                    ],
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excelHtml5',
                            title: "liste des regelement Client "+ $("#mois3").val() +"-"+ $("#anne3").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: "liste des regelement Client "+ $("#mois3").val() +"-"+ $("#anne3").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: "liste des regelement Client "+ $("#mois3").val() +"-"+ $("#anne3").val(),
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


    //@@@@@@@@@@@@@@@ Fo


        $('#datatablesFournisseurs').dataTable({
        order: [
            [0, "desc"]
        ],
        dom: 'Bfrtip',
        buttons: [{
                extend: 'excelHtml5',
                title: "liste des  reglument Fournisseurs",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,]
                }
            },
            {
                extend: 'pdfHtml5',
                title: "liste des reglument Fournisseurs",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,]
                }
            },
            {
                extend: 'csvHtml5',
                title: "liste des reglument Fournisseurs",
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

    $('body').on("click", ".deleteFournisseurs", function(event) {
        event.preventDefault();


        var btn = $(this);
        swal({
            title: 'Êtes-vous sûr?',
            text: "Voulez vous vraiment Supprimer ce Fournisseurs !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, Supprimer !'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    type: "POST",
                    url: "<?php echo BASE_URL.'views/regelement/' ;?>controle.php",
                    data: {
                        act: "deleteFournisseurs",
                        id: btn.data('id')
                    },
                    success: function(data) {

                        swal(
                            'Supprimer',
                            'Fournisseurs a ete bien Supprimer',
                            'success'
                        ).then((result) => {

                            btn.parents("td").parents("tr").remove();
                        });

                    }
                });

            }
        });
    });

    $("#formfilterFournisseurs").on("submit", function(event) {
        event.preventDefault();
        $("#resultsFournisseurs").html('<div class="col-md-12"><br><br><br><br><div class="loading"></div></div>');
        var form = $(this);
        $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL.'views/regelement/' ;?>controle.php",
            data: new FormData(this),
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {

           
                $("#resultsFournisseurs").html(data);

                $('#datatablesFournisseurs').dataTable({
                    order: [
                        [0, "desc"]
                    ],
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excelHtml5',
                            title: "liste des regelement Fournisseurs "+ $("#mois4").val() +"-"+ $("#anne4").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: "liste des regelement Fournisseurs "+ $("#mois4").val() +"-"+ $("#anne4").val(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: "liste des regelement Fournisseurs "+ $("#mois4").val() +"-"+ $("#anne4").val(),
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
</script>