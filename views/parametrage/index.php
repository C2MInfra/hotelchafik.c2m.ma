  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}

?>

<div class="container-fluid disable-text-selection">
<div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Parametrage </h1>
                
                
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>

    <div class="row">
        <div class="col align-self-start">
  <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-4">Paramétrage</h5>
                                <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
                                    <input type="hidden" name="act" value="insert">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="retour"> ID Suivant de Retour  :</label>
                                            <input type="text" class="form-control" id="retour" name="retour"  placeholder="Nouveau Dernier ID de Retour">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="facture">ID Suivant de Facture :</label>
                                            <input type="text" class="form-control" id="facture" name="facture" placeholder="Nouveau Dernier ID de Facture" >
                                        </div>
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
    
    $("#addform" ).on( "submit", function( event ) {
             event.preventDefault();
 var error ="", inputRetour = $('#retour').val(),
        inputFacture = $('#facture').val();

        if (isNaN(inputRetour) || inputRetour<0  ) {
          error+=('le numéro de retour doit être un nombre valide \n');
        }
        if (isNaN(inputFacture) || inputFacture<0  ) {
          error+=('le numéro de facture doit être un nombre valide  \n');
        }
        if (error!='') {
             swal(
                      'Erreur',
                       error,
                      'danger'
                    )
           return false;
        }  
             var form = $( this );
             $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/parametrage/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                if (data=="success") {
                    form.append(` <div id="alert-success" class="alert  alert-success alert-dismissible fade show rounded mb-0" role="alert"> <strong>Parametrage bient enregistrer</strong> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button> </div>`);
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
