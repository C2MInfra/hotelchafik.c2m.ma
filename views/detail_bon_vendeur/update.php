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
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="nom"> Nom :</label>
                                            <input type="text" class="form-control" value="<?php echo $oldvalue['nom'] ;?>" id="nom" name="nom" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="prenom">Prenom :</label>
                                            <input type="text" class="form-control" value="<?php echo $oldvalue['prenom'] ;?>" id="prenom" name="prenom" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="nom_prenom_ar">Nom/Prenom (AR) :</label>
                                            <input type="text" class="form-control" value="<?php echo $oldvalue['nom_prenom_ar'] ;?>" id="nom_prenom_ar" name="nom_prenom_ar" >
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="cin"> CIN :</label>
                                            <input type="text" class="form-control" value="<?php echo $oldvalue['cin'] ;?>" id="cin" name="cin" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="telephone">T&eacute;l&eacute;phone :</label>
                                            <input type="text" class="form-control" value="<?php echo $oldvalue['telephone'] ;?>" id="telephone" name="telephone" >
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group  col-md-6">
                                            <label >Image :</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file"  class="custom-file-input" id="image" name="image">
                                                    <label class="custom-file-label" for="image">Choose file</label>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">Email : </label>
                                            <input type="text" class="form-control"value="<?php echo $oldvalue['email'] ;?>" id="email" name="email" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="adresse">Adresse :</label>
                                        <textarea  class="form-control" name="adresse" id="adresse"
                                        ><?php echo $oldvalue['adresse'] ;?></textarea>
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
                if (data=="success") {
                    form.append(` <div id="alert-success" class="alert  alert-success alert-dismissible fade show rounded mb-0" role="alert"> <strong>vente bient Modifie</strong> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button> </div>`);
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
