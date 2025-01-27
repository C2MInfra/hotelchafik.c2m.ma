  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
$societe=new societe();
$societes = $societe->selectAll();
$so = $societes[0];

?>

<div class="container-fluid disable-text-selection">
<div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Societe </h1>
                
                
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>

    <div class="row">
        <div class="col align-self-start">
  <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-4">Sociéte</h5>
                                        <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
                                            <input type="hidden" name="act" value="update">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="id">id :</label>
                                                    <input type="text" class="form-control" id="id" name="id"  value="<?php echo $so->id?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="raisonsocial">raisonsocial :</label>
                                                    <input type="text" class="form-control" id="raisonsocial" name="raisonsocial"  value="<?php echo $so->raisonsocial?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="telephone">telephone :</label>
                                                    <input type="text" class="form-control" id="telephone" name="telephone"  value="<?php echo $so->telephone?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="fax">fax :</label>
                                                    <input type="text" class="form-control" id="fax" name="fax"  value="<?php echo $so->fax?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="email">email :</label>
                                                    <input type="text" class="form-control" id="email" name="email"  value="<?php echo $so->email?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="compte">compte :</label>
                                                    <input type="text" class="form-control" id="compte" name="compte"  value="<?php echo $so->compte?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="iff">iff :</label>
                                                    <input type="text" class="form-control" id="iff" name="iff"  value="<?php echo $so->iff?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="rc">rc :</label>
                                                    <input type="text" class="form-control" id="rc" name="rc"  value="<?php echo $so->rc?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="patente">patente :</label>
                                                    <input type="text" class="form-control" id="patente" name="patente"  value="<?php echo $so->patente?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="adresse">adresse :</label>
                                                    <input type="text" class="form-control" id="adresse" name="adresse"  value="<?php echo $so->adresse?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="logo">logo :</label>
                                                    <input type="text" class="form-control" id="logo" name="logo"  value="<?php echo $so->logo?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="header_footer">header_footer :</label>
                                                    <input type="text" class="form-control" id="header_footer" name="header_footer"  value="<?php echo $so->header_footer?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="unite_util">unite_util :</label>
                                                    <input type="text" class="form-control" id="unite_util" name="unite_util"  value="<?php echo $so->unite_util?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="etat_unite">etat_unite :</label>
                                                    <input type="text" class="form-control" id="etat_unite" name="etat_unite"  value="<?php echo $so->etat_unite?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="IdUser">IdUser :</label>
                                                    <input type="text" class="form-control" id="IdUser" name="IdUser"  value="<?php echo $so->IdUser?>" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="valeur_key">valeur_key :</label>
                                                    <input type="text" class="form-control" id="valeur_key" name="valeur_key"  value="<?php echo $so->valeur_key?>" >
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
             var form = $( this );
             $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/societe/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                if (data=="success") {
                    form.append(` <div id="alert-success" class="alert  alert-success alert-dismissible fade show rounded mb-0" role="alert"> <strong>Societe bient enregistrer</strong> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button> </div>`);
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
