  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}


$compte=new compte();
$id = explode('?id=',$_SERVER["REQUEST_URI"]);

$oldvalue=$compte->selectById($id[1]);


?>

<div class="container-fluid disable-text-selection">
<div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>comptes </h1>
                
                
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>

    <div class="row">
        <div class="col align-self-start">
  <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-4">Modification compte</h5>

                          <form id="addform" method="post" name="form_compte" enctype="multipart/form-data">
                                    <input type="hidden" name="act" value="update">
                                     <input type="hidden" name="id" value="<?php echo $id[1] ;?>">
                                   <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="num_compte"> Numero du compte  :</label>
                                            <input type="text" class="form-control" id="num_compte" name="num_compte"  value="<?php echo $oldvalue['num_compte']?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="nom_compte"> Nom du compte :</label>
                                            <input type="text" class="form-control" id="nom_compte" name="nom_compte"  value="<?php echo $oldvalue['nom_compte']?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="nom_banque">Nom du Banque  :</label>
                                            <input type="text" class="form-control" id="nom_banque" name="nom_banque"  value="<?php echo $oldvalue['nom_banque']?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="fax"> Fax :</label>

                                            <input type="text" class="form-control" id="fax" name="fax" value="<?php echo $oldvalue['fax']?>" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="telephone">T&eacute;l&eacute;phone :</label>
                                            <input type="text" class="form-control" id="telephone" name="telephone"  value="<?php echo $oldvalue['telephone']?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                      <div class="form-group col-md-6">
                                            <label for="responsable">Responsable : </label>
                                            <input type="text" class="form-control" id="responsable" name="responsable" value="<?php echo $oldvalue['responsable']?>" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">Email : </label>
                                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $oldvalue['email']?>" >
                                        </div>
                                    </div>
                                                <div class="form-group">
                                                  <label for="remarque"> Remarque : </label>
                                                  <textarea  class="form-control" name="remarque" id="remarque">
                                                  <?php echo $oldvalue['remarque']?>
                                                  </textarea>
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
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                if (data=="success") {
                    swal(
                      'Modification',
                      'compte a ete bien Modifie',
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

                     form.append(`<div id="alert-danger" class="alert  alert-danger alert-dismissible fade show rounded mb-0" role="alert">
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
