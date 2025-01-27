  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}


$vente=new vente();
$id = explode('?id=',$_SERVER["REQUEST_URI"]);

$oldvalue=$vente->selectById($id[1]);


?>

<div class="container-fluid disable-text-selection">
<div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>ventes </h1>
                
                
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div> 

    <div class="row">
        <div class="col align-self-start">
  <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-4">Modification vente</h5>

                          <form id="addform" method="post" name="form_vente" enctype="multipart/form-data">
                                    <input type="hidden" name="act" value="update">
                                     <input type="hidden" name="id" value="<?php echo $id[1] ;?>">

                                    
                                    <div class="form-group col-md-6">
                                <label for="id_client">Client : </label>
                                <select class="form-control select2-single" name="id_client" id="id_client" >
                                    
                                    <?php
                                    $client = new client();
                                    $clients = $client->selectChamps("*",'','','nom','asc');
                                    foreach ($clients as $row)
                                    {
                                        if ($row->id_client==$oldvalue['id_client']) {
                                         echo '<option value="'.$row->id_client.'" selected="selected">'.$row->nom.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$row->id_client.'">'.$row->nom.'</option>';
                                        }
                                    
                                    }?>
                                    
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date_vente">Date :</label>
                                <input type="text" class="form-control datepicker" id="date_vente" name="date_vente" value="<?php echo $oldvalue['date_vente'] ; ?>" >
                            </div>
                            
                      
                        
                                    <div class="form-group">
                                        <label for="remarque"> Remarque : </label>
                                        <textarea  class="form-control" name="remarque" id="remarque"
                                        ><?php echo $oldvalue['remarque'] ;?></textarea>
                                    </div>
                                    
                                    <div class="float-sm-right text-zero">
                                        <button type="submit" class="btn btn-primary btn-lg  mr-1 " >Enregistrer</button>
                                    </div>
                                    
                                </form>

                        </div>
                    </div>
                </div>
            </div>
</div>

<script type="text/javascript">


    $( document ).ready(function() {




$(".select2-single").select2({
            theme: "bootstrap",
            placeholder: "",
            maximumSelectionSize: 6,
            containerCssClass: ":all:"
        });
        $("input.datepicker").datepicker({
                     format: 'yyyy-mm-dd',
                     templates: {
                     leftArrow: '<i class="simple-icon-arrow-left"></i>',
                     rightArrow: '<i class="simple-icon-arrow-right"></i>'
                    }
                });


      


    $("#id_categorie").change(function() {
   

      
            var id_categorie = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
                data: {act:"getcat",id_categorie: id_categorie},
                success: function (data) {
                   
                    $('#code_bar').val(data);
                }
            });
  
});


    $("#addform" ).on( "submit", function( event ) {
             event.preventDefault();

             var form = $( this );
             $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

               if (data.indexOf("success")>=0) {
                   
                    swal(
                      'Modification',
                      'vente a ete bien modifie',
                      'success'
                    ).then((result) => {
                    $.ajax({

                              method:'POST',
                              data: {ajax:true},
                              url: `<?php echo BASE_URL."views/vente/index.php";?>`,
                              context: document.body,
                              success: function(data) { 
                                      history.pushState({},"",`<?php echo BASE_URL."vente/index.php";?>` );
                                      $("#main").html( data );
                                }
                            });
                    });
                }
                else{

                     form.append(` <div id="alert-danger" class="alert  alert-danger alert-dismissible fade show rounded mb-0" role="alert">
                                <strong>${data}</strong> 
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>`);
                }
                }
            });
});

                


});
</script>
