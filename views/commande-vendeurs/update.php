  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}


$boncommandevendeur = new boncommandevendeur();
$id = explode('?id=',$_SERVER["REQUEST_URI"]);

$oldvalue = $boncommandevendeur->selectById($id[1]);

$vendeurs = connexion::getConnexion()->query("SELECT * FROM utilisateur WHERE privilege = 'Vendeur' ORDER BY nom ASC")->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container-fluid disable-text-selection">
<div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>bon commande des vendeurs </h1>
                
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div> 

    <div class="row">
        <div class="col align-self-start">
  <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-4">Modification bon</h5>

                          <form id="addform" method="post" name="form_vente" enctype="multipart/form-data">
                                    <input type="hidden" name="act" value="update">
                                     <input type="hidden" name="id" value="<?php echo $id[1] ;?>">

                             <div class="row">
                            <div class="form-group col-md-6">
                                <label for="id_client">Vendeur : </label>
                                <select class="form-control select2-single" name="id_vendeur" id="id_client" >
                                    
                                    <?php
                                    foreach ($vendeurs as $row)
                                    {
                                        if ($row->id == $oldvalue['id_vendeur']) 
                                        {
                                            echo '<option value="'.$row->id.'" selected="selected">'.$row->nom.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$row->id.'">'.$row->nom.'</option>';
                                        }
                                    
                                    }?>
                                    
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date_vente">Date :</label>
                                <input type="text" class="form-control datepicker" id="date_bon" name="date_bon" value="<?php echo $oldvalue['date_bon'] ; ?>" >
                            </div>
                            
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
                url: "<?php echo BASE_URL.'views/commande-vendeurs/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    swal(
                      'Modification',
                      'bon a ete bien modifie',
                      'success'
                    ).then((result) => {
                    $.ajax({

                              method:'POST',
                              data: {ajax:true},
                              url: `<?php echo BASE_URL."views/commande-vendeurs/index.php";?>`,
                              context: document.body,
                              success: function(data) { 
                                      history.pushState({},"",`<?php echo BASE_URL."commande-vendeurs/index.php";?>` );
                                      $("#main").html( data );
                                }
                            });
                    });
                    console.log(data); exit;
               if (data.indexOf("success")>=0) {
                   
                    swal(
                      'Modification',
                      'bon a ete bien modifie',
                      'success'
                    ).then((result) => {
                    $.ajax({

                              method:'POST',
                              data: {ajax:true},
                              url: `<?php echo BASE_URL."views/commande-vendeurs/index.php";?>`,
                              context: document.body,
                              success: function(data) { 
                                      history.pushState({},"",`<?php echo BASE_URL."commande-vendeurs/index.php";?>` );
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
