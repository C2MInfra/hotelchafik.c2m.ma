  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
$id = explode('?id=',$_SERVER["REQUEST_URI"])[1];
$charge=new charge(); 
$data = $charge->selectDesignation();
?>

<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>charge </h1>
                
        <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-success  url notlink" data-url="charge/index.php" > <i class="glyph-icon simple-icon-arrow-left"></i></button>
                </div>
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row">
        <div class="col align-self-start">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-4">Ajouter Nouveaux Charges</h5>
                    <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
                        <input type="hidden" name="act" value="insert">
                        <input type="hidden" name="id_achat" value="<?php echo $id; ?>">
 <div class="form-row">
                         <div class="form-group col-md-4" >
                                                <label for="designation">Désignation</label>

                                                  <input type="text" class="form-control" id="designation" placeholder="Designation" name="designation">
                                            </div>
                                            
                                            <div class="form-group col-md-4">
                                                <label for="mode_reg">Mode de r&eacute;glement : </label>
                                                <select name="mode_reg" class="form-control" id="mode_reg"
                                                    onchange="if(this.value=='Espece'){
                                                    document.getElementById('num_cheque').disabled='false';
                                                    document.getElementById('num_cheque').value='';
                                                    }else{
                                                    document.getElementById('num_cheque').disabled='';
                                                    }
                                                    if (this.value=='Effet' || this.value=='Cheque'){
                                                    document.getElementById('date_validation_tr').style.display = 'block';
                                                    }else {
                                                    document.getElementById('date_validation_tr').style.display = 'none';
                                                   
                                                    }">
                                                    <option value=""> Choix</option>
                                                    <option value="Espece"> Espece</option>
                                                    <option value="Cheque"> Cheque</option>
                                                    <option value="Virement"> Virement</option>
                                                    <option value="Effet"> Effet</option>
                                                </select>
                                            </div>
                             <div class="form-group col-md-4">
                                <label for="num_cheque">Num&eacute;ro : </label>
                                
                                <input type="text" name="num_cheque" class="form-control" id="num_cheque"/>
                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="montant" class="col-form-label">Montant</label>
                                                <input type="text" class="form-control" id="montant" placeholder="Montant" name="montant">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="date_charge">Date Charge</label>
                                                <input name="date_charge" type="text"  class="form-control  datepicker" placeholder="2019-01-03" value="<?php echo date('Y-m-d')?>"/>
                                            </div>
                                            

                                            

                                            <div class="form-group mb-10 col-md-4">
                                                <label>Piéce jointe</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="image" name="image">
                                                        <label class="custom-file-label" for="inputGroupFile04">Choisir piéce jointe charge</label>
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="form-group col-md-4" id="date_validation_tr">
                                    <label for="date_validation">Date d'&eacute;ch&eacute;cance: </label>
                                    
                                    <input type="text" autocomplete="off" name="date_validation" class="form-control datepicker" id="date_validation" value="<?php echo date('Y-m-d')?>"/>
                                </div>
</div>
                                          <div class="form-group">
                                            <label for="id_fournisseur">Devis : </label>
                                            <select class="form-control select2-single" name="devise" id="devise" >
                                                <option value="USD">MAD</option>
                                                <option value="USD">USD</option>
                                                <option value="EUR">EUR</option>                                    
                                            </select>
                                          </div>
                                            <div class="form-group">
                                                <label for="category">Remarque</label>
                                                <textarea name="remarque" class="form-control" rows="6"></textarea>
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
                url: "<?php echo BASE_URL.'views/charge/' ;?>controle.php",
                data: {act:"getproduit",id_categorie: id_categorie},
                success: function (data) {
                  
                    $('#id_produit').html(data);
                }
            });
  
                });
              $("#id_produit").change(function() {
   

            
            var id_produit = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/charge/' ;?>controle.php",
                data: {act:"getPrix",id_produit: id_produit},
                success: function (data) {
                   var tab=data.split('/');
                   $('#prix_produit').val(tab[0]);
                    $('#reste_stock').html(tab[1]);
                }
            });
  
            });

                 $("#addProduct").click(function() {
   


            var id_produit = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/charge/' ;?>controle.php",
                data: {act:"addProduct",id_produit: $("#id_produit").val() ,prix_produit: $("#prix_produit").val(), qte_vendu: $("#qte_vendu").val(),remise: $("#remise").val() ,},
                success: function (data) {
                    $('#detail_commande').html(data);
                }
            });
  
            });

     $('body').on( "click",".delete", function( event ) {
             event.preventDefault();


                    var btn = $(this);
                swal({
                 title: 'Êtes-vous sûr?',
                  text: "Voulez vous vraiment Supprimer ce Client !",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, Supprimer !'
                }).then((result) => {
                  if (result.value) {

                $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/charge/' ;?>controle.php",
                data: {act:"deleterow",id_detail: btn.data('id')},
                success: function (data) {
                   
                   swal(
                      'Supprimer',
                      'Client a ete bien Supprimer',
                      'success'
                    ).then((result) => {

                       // btn.parents("td").parents("tr").remove();
                          $('#detail_commande').html(data);
                    });
                   
                }
                 });
                    
                  }
                });
           
            });
    
    $("#addform" ).on( "submit", function( event ) {
             event.preventDefault();

             var form = $( this );
             $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/charge/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                if (data.indexOf("success")>=0) {
                   
                    swal(
                      'Ajouter',
                      'charge a ete bien Ajouter',
                      'success'
                    ).then((result) => {
                    $.ajax({

                              method:'POST',
                              data: {ajax:true},
                              url: `<?php echo BASE_URL."views/charge/index.php?id=".$id;?>`,
                              context: document.body,
                              success: function(data) { 
                                      history.pushState({},"",`<?php echo BASE_URL."charge/index.php?id=".$id;?>` );
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
