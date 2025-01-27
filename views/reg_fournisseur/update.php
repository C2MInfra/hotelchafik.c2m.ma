  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}


$produit=new produit();


$id = explode('?id=',$_SERVER["REQUEST_URI"]);

$oldvalue=$produit->selectById($id[1]);
 


$categorie = new categorie();
$categories = $categorie->selectChamps("*",'','','nom','asc');

$depot = new depot();
$depots = $depot->selectChamps("*",'','','nom','asc');


?>

<div class="container-fluid disable-text-selection">
<div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Produits </h1>
                
                
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>

    <div class="row">
        <div class="col align-self-start">
  <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-4">Modification Produit</h5>

                            <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
                                <input type="hidden" name="act" value="update">
                                <input type="hidden" name="id" value="<?php echo $id[1] ;?>">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="code_bar">Reference :</label>
                                        <input type="text" class="form-control" value="<?php echo $oldvalue['code_bar'] ?>" id="code_bar" name="code_bar" placeholder="Reference">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="id_categorie">Cat&eacute;gorie :</label>
                                       <select class="form-control select2-single" name="id_categorie" id="id_categorie" >
                                            
                                            <?php foreach ($categories as $row) { 
                                               echo '<option value="'.$row->id_categorie.'">'.$row->nom.'</option>';
                                            }?>
                                            
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="designation">D&eacute;signation :</label>
                                    <textarea class="form-control"  name="designation" id="designation"
                                    ><?php echo $oldvalue['designation'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="designation_ar">D&eacute;signation_AR:</label>
                                    <textarea  class="form-control"  name="designation_ar" id="designation_ar"
                                    ><?php echo $oldvalue['designation_ar'] ?></textarea>
                                </div>


                                 <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="code_bar">Poid :</label>
                                       <input type="text" name="poid" value="<?php echo $oldvalue['poid'] ?>" class="form-control" id="poid"/>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="id_categorie">Qte Stock : </label>
                                      <input type="text" name="qte_actuel" value="<?php echo $oldvalue['qte_actuel'] ?>" class="form-control" id="qte_actuel"/>

                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="prix_achat">Prix achat :</label>
                                        <input type="text" class="form-control"name="prix_achat" value="<?php echo $oldvalue['prix_achat'] ?>" id="prix_achat">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="prix_achat"> Prix Achat :</label>
                                        <input type="text" class="form-control"name="prix_achat" value="<?php echo $oldvalue['prix_achat'] ?>" id="prix_achat">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="emplacement">Emplacement :</label>
                                        <select id="emplacement" name="emplacement" class="form-control">
                                              <?php foreach ($depots as $row) { 
                                               echo '<option value="'.$row->id.'">'.$row->nom.'</option>';
                                            }?>
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label >Image :</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"  class="custom-file-input" id="image" name="image">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="tva">T.V.A :</label>
                                        <input type="text" value="<?php echo $oldvalue['tva'] ?>" class="form-control"name="tva" id="tva" placeholder="TVA">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="remarque">Remarque :</label>
                                    <textarea  class="form-control" name="remarque" id="remarque"
                                    ><?php echo $oldvalue['remarque'] ?></textarea>
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
                url: "<?php echo BASE_URL.'views/produit/' ;?>controle.php",
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
                url: "<?php echo BASE_URL.'views/produit/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                if (data=="success") {
                    form.append(` <div id="alert-success" class="alert  alert-success alert-dismissible fade show rounded mb-0" role="alert"> <strong>Produit bient Modifie</strong> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button> </div>`);
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
