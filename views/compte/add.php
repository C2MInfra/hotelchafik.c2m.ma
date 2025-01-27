  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}

?>

<div class="container-fluid disable-text-selection">
<div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Compte </h1>
                
                  <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-success  url notlink" data-url="compte/index.php" > <i class="glyph-icon simple-icon-arrow-left"></i></button>
                </div>
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>

    <div class="row">
        <div class="col align-self-start">
  <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-4">Ajouter Nouveau compte</h5>
                                <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
                                    <input type="hidden" name="act" value="insert">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="num_compte"> Numero du compte  :</label>
                                            <input type="text" class="form-control" id="num_compte" name="num_compte" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="nom_compte"> Nom du compte :</label>
                                            <input type="text" class="form-control" id="nom_compte" name="nom_compte" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="nom_banque">Nom du Banque  :</label>
                                            <input type="text" class="form-control" id="nom_banque" name="nom_banque" >
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="fax"> Fax :</label>

                                            <input type="text" class="form-control" id="fax" name="fax" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="telephone">T&eacute;l&eacute;phone :</label>
                                            <input type="text" class="form-control" id="telephone" name="telephone" >
                                        </div>
                                    </div>
                                    <div class="form-row">
                                      <div class="form-group col-md-6">
                                            <label for="responsable">Responsable : </label>
                                            <input type="text" class="form-control" id="responsable" name="responsable" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">Email : </label>
                                            <input type="text" class="form-control" id="email" name="email" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="remarque"> Remarque : </label>
                                        <textarea  class="form-control" name="remarque" id="remarque"
                                        ></textarea>
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
                url: "<?php echo BASE_URL.'views/compte/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                if (data=="success") {
                  swal(
                      'Ajouter',
                      'compte a ete bien Ajouter',
                      'success'
                    ).then((result) => {

                 
           
                    $.ajax({

                              method:'POST',
                              data: {ajax:true},
                              url: `<?php echo BASE_URL."views/compte/index.php"; ?>`,
                              context: document.body,
                              success: function(data) { 
                               history.pushState({},"",`<?php echo BASE_URL."compte/index.php"; ?>` );
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
