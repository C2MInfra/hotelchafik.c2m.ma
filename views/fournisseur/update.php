<?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
$fournisseur=new fournisseur();
$id = explode('?id=',$_SERVER["REQUEST_URI"]);
$oldvalue=$fournisseur->selectById($id[1]);
?>
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>fournisseurs </h1>
                
                
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row">
        <div class="col align-self-start">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-4">Modification fournisseur</h5>
                    <form id="addform" method="post" name="form_fournisseur" enctype="multipart/form-data">
                        <input type="hidden" name="act" value="update">
                        <input type="hidden" name="id" value="<?php echo $id[1] ;?>">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="raison_sociale"> Raison Social  :</label>
                                <input type="text" class="form-control" id="raison_sociale" name="raison_sociale"  value="<?php echo $oldvalue['raison_sociale']?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="telephone">T&eacute;l&eacute;phone :</label>
                                <input type="text" class="form-control" id="telephone" name="telephone"  value="<?php echo $oldvalue['telephone']?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">Email :</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $oldvalue['email']?>" >
                            </div>
                        </div>
                        <div class="form-row ">
                            <div class="form-group col-md-3">
                                <label for="ice">  ICE  :</label>
                                <input type="text" name="ice" parsley-trigger="change"
                                placeholder="IFF " class="form-control" id="ice"  value="<?php echo $oldvalue['ice']?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="iff">  IFF  :</label>
                                <input type="text" name="iff" parsley-trigger="change"
                                placeholder="IFF " class="form-control" id="iff"  value="<?php echo $oldvalue['iff']?>">
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label for="rc"> RC :</label>
                                
                                <input type="text" name="rc" parsley-trigger="change"
                                placeholder=" RC " class="form-control" id="rc"  value="<?php echo $oldvalue['rc']?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="patente">patente :</label>
                                
                                <input type="text" name="patente" parsley-trigger="change"
                                placeholder="patente" class="form-control" id="patente"   value="<?php echo $oldvalue['patente']?>">
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="image">Image :</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file"  class="custom-file-input" id="image" name="image">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="responsable">Responsable :</label>
                                <input type="text" class="form-control" id="responsable" name="responsable" value="<?php echo $oldvalue['responsable']?>" >
                            </div>
                            <div class="form-group col-md-4">
                                <label for="tph_respo">T&eacute;l&eacute;phone :</label>
                                <input type="text" class="form-control" id="tph_respo" name="tph_respo" value="<?php echo $oldvalue['tph_respo']?>" >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="adresse">Adresse :</label>
                            <textarea  class="form-control" name="adresse" id="adresse"
                            ><?php echo $oldvalue['adresse']?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="remarque"> Remarque : </label>
                            <textarea  class="form-control" name="remarque" id="remarque"
                            ><?php echo $oldvalue['remarque']?></textarea>
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
url: "<?php echo BASE_URL.'views/fournisseur/' ;?>controle.php",
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
url: "<?php echo BASE_URL.'views/fournisseur/' ;?>controle.php",
data: new FormData(this),
dataType: 'text',  // what to expect back from the PHP script, if anything
cache: false,
contentType: false,
processData: false,
success: function (data) {
if (data.indexOf("success")>=0) {

swal(
'Modification',
'fournisseur a ete bien modifie',
'success'
).then((result) => {
$.ajax({
method:'POST',
data: {ajax:true},
url: `<?php echo BASE_URL."views/fournisseur/index.php";?>`,
context: document.body,
success: function(data) {
history.replaceState({},"",`<?php echo BASE_URL."fournisseur/index.php";?>` );
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