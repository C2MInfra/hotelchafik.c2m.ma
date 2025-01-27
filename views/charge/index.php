<?php



if (isset($_POST['ajax'])) {



include('../../evr.php');



}

$id = explode('?id=',$_SERVER["REQUEST_URI"])[1];

$charge = new charge();




if($_GET['id']){
  $data=$charge->selectbyId($id);
}else{
  $data=$charge->selectAll3(date('Y') . '-' . date('m'), 1);
}




?>



<div class="container-fluid disable-text-selection">



  <div class="row">



    <div class="col-12">



      <div class="mb-2">



        <h1>Liste des charges</h1>



        <div class="float-sm-right text-zero">



          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="charge/add.php?id=<?php echo $id ?>" >AJOUTER</button>



			<a target="_blank" class="btn btn-primary btn-lg  mr-1  " href="<?php echo BASE_URL.'views/etat/etat_charge.php'?>">  <i class="glyph-icon  iconsmind-Billing"></i> État charges </a>

		  

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



                <button  type="submit" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 31px;">Afficher</button>



              </div>



            </div>



          </form>



          <div id="results">



            <table  class="table  responsive table-striped table-bordered table-hover" id="datatables" >



              <thead>



                <tr>



                  



                  <th>NR°</th>



                  <th>Mode</th>



                  <th>Num&egrave;ro</th>



                  <th>Designation</th>



                  <th>Date charge</th>



                  <th>Montant</th>



                  <th>Remarque</th>



                  <th style="width: 85px;">Action</th>



                </tr>



              </thead>



              <tbody>



                



                



                <?php



                foreach ($data as $charge) {



                

             
                



                ?>



                <tr>



                  <td>



                    <?php echo $charge->id ?>



                  </td>



                   <td>



                    <?php echo $charge->mode_reg ?>



                  </td>



                   <td>



                    <?php echo $charge->num_cheque ?>



                  </td>



                  <td>



                    <?php echo $charge->designation ?>



                  </td>



                  



                  <td>



                    <?php echo $charge->date_charge ?>



                  </td>



                  <td>



                    <?php echo $charge->montant  ?>



                  </td>



                   <td>



                    <?php echo $charge->remarque ?>



                  </td>



                  <td>



                    <?php if(auth::user()['privilege'] == 'Admin') { ?>



                    <a class="badge badge-danger mb-2 delete" data-id="<?php echo $charge->id; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >



                      <i class="simple-icon-trash" style="font-size: 15px;"></i>



                    </a>



                   



                    <?php } ?>



                    



                  <a class="badge badge-warning mb-2  url notlink" data-url="charge/update.php?id=<?php echo $charge->id; ?>" style="color: white;cursor: pointer;" title="Modifier"



                    href="javascript:void(0)">



                    <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>



                  </a>



                    



                                        <?php if($charge->image !='' || $charge->deux_image !='' ) { $img =  $charge->image != ''? $charge->image : $charge->deux_image ; ?>



                                                <a class="badge badge-success " data-fancybox data-caption="<?php echo $charge->designation; ?> <br> Prix : <?php echo $charge->montant?> DH" style="color: white;cursor: pointer;" title="<?php echo $charge->designation; ?>" href="<?php echo BASE_URL.'upload/charge/'.$img; ?>">



                                                    <i class="simple-icon-picture" style="font-size: 15px;"> </i>



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



                title: "liste des charge",



                exportOptions: {



                    columns: [0, 1, 2, 3, 4, 5]



                }



            },



            {



                extend: 'pdfHtml5',



                title: "liste des charge",



                exportOptions: {



                    columns: [0, 1, 2, 3, 4, 5]



                }



            },



            {



                extend: 'csvHtml5',



                title: "liste des charge",



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



            text: "Voulez vous vraiment Supprimer ce charge !",



            type: 'warning',



            showCancelButton: true,



            confirmButtonColor: '#d33',



            cancelButtonColor: '#3085d6',



            confirmButtonText: 'Oui, Supprimer !'



        }).then((result) => {



            if (result.value) {



                $.ajax({



                    type: "POST",



                    url: "<?php echo BASE_URL.'views/charge/' ;?>controle.php",



                    data: {



                        act: "delete",



                        id: btn.data('id')



                    },



                    success: function(data) {







                        swal(



                            'Supprimer',



                            'charge a ete bien Supprimer',



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



            text: "Voulez vous vraiment Archiver ce charge !!",



            type: 'warning',



            showCancelButton: true,



            confirmButtonColor: '#145388',



            cancelButtonColor: '#3085d6',



            confirmButtonText: 'Oui, Archiver!'



        }).then((result) => {



            if (result.value) {



                $.ajax({



                    type: "POST",



                    url: "<?php echo BASE_URL.'views/charge/' ;?>controle.php",



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







    $("#formfilter").on("submit", function(event) {



        event.preventDefault();



        $("#results").html('<div class="col-md-12"><br><br><br><br><div class="loading"></div></div>');



        var form = $(this);



        $.ajax({



            type: "POST",



            url: "<?php echo BASE_URL.'views/charge/' ;?>controle.php",



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



                                    title: "liste des charge ",



                                    exportOptions: {



                                        columns: [0, 1, 2, 3, 4, 5]



                                    }



                                },



                                {



                                    extend: 'pdfHtml5',



                                    title: "liste des charge ",



                                    exportOptions: {



                                        columns: [0, 1, 2, 3, 4, 5]



                                    }



                                },



                                {



                                    extend: 'csvHtml5',



                                    title: "liste des charge ",



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