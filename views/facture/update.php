  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}


$facture=new facture();
$id = explode('?id=',$_SERVER["REQUEST_URI"]);

$oldvalue=$facture->selectById($id[1]);


?>

<div class="container-fluid disable-text-selection">
<div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>factures </h1>
                
                
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>

    <div class="row">
        <div class="col align-self-start">
  <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-4">Modification facture</h5>

                          <form id="addform" method="post" name="form_facture" enctype="multipart/form-data">
                                    <input type="hidden" name="act" value="update">
                                     <input type="hidden" name="id" value="<?php echo $id[1] ;?>">
                                   
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="date_facture"> Date :</label>
                                <input type="text" class="form-control datepicker" autocomplete="off" id="date_facture" name="date_facture" value=" <?php echo $oldvalue['date_facture'] ?>"  >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="num_fact">Numéro de Facture :</label>
                                <input type="text" class="form-control" id="num_fact" value="<?php echo $oldvalue['num_fact'] ?>" name="num_fact" >
                            </div>
                        </div>
                     <div class="form-group">
                            <label for="remarque">Remarque :</label>
                            <textarea  class="form-control" name="remarque" id="remarque"
                            ><?php echo $oldvalue['remarque'] ?> </textarea>
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






      


    $("#id_categorie").change(function() {
   

      
            var id_categorie = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/facture/' ;?>controle.php",
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
                url: "<?php echo BASE_URL.'views/facture/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

               if (data.indexOf("success")>=0) {
                   
                    swal(
                      'Modification',
                      'facture a ete bien modifie',
                      'success'
                    ).then((result) => {
                    $.ajax({

                              method:'POST',
                              data: {ajax:true},
                              url: `<?php echo BASE_URL."views/facture/index.php";?>`,
                              context: document.body,
                              success: function(data) { 
                                      history.pushState({},"",`<?php echo BASE_URL."facture/index.php";?>` );
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
