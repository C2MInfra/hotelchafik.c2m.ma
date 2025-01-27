  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
$id = explode('?id=',$_SERVER["REQUEST_URI"])[1];

 $reg_vente=new reg_vente();
 $data=$reg_vente->selectAll2($id);
 
 
 $total=0;
$query=$result=connexion::getConnexion()->query("SELECT 
sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)) as total ,
sum(dv.prix_produit*dv.valunit*(1-dv.remise/100)) as totalunit 

FROM detail_vente dv 
 inner join produit p on dv.id_produit=p.id_produit WHERE id_vente=".$id);
$result=$query->fetch(PDO::FETCH_OBJ);
$total=0;
if(!empty($result->totalunit) || $result->totalunit!=0){
    $total=$result->totalunit;
}else{
    $total=$result->total;
}

 $avance=0;
 foreach($data as $ligne){
         
      $avance+=$ligne->montant;

    }

    $montant_a_paye=  $total-$avance;
?>

<div class="container-fluid disable-text-selection">
<div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Nouveau reglement vente N&deg; : <?php echo $id ?>  </h1>

                 <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-success  url notlink" data-url="reg_vente/index.php?id=<?php echo $id ?>" > <i class="glyph-icon simple-icon-arrow-left"></i></button>
                </div>
               </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>

<div class="row">
    <div class="col align-self-start">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-4">Ajouter Nouveau reglement</h5>
                <p><strong> doit payer : </strong>
                    <span id="total_reste" val="<?php echo $montant_a_paye?>" class="badge badge-pill badge-danger mb-1"> <?php echo number_format($montant_a_paye, 2, ".", " "); ?> DH</span> </p>
                    <form id="addform" method="post" name="form_reg_vente" enctype="multipart/form-data">
                        
                        <input type="hidden" name="act" value="insert">
                         <input type="hidden" name="id_vente" value="<?php echo $id?>">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="mode_reg">Mode de r&eacute;glement : </label>
                                <select name="mode_reg" class="form-control" id="mode_reg"
                                    onchange="if(this.value=='Espece')
                                    {
                                    document.getElementById('num_cheque').disabled='false';
                                    document.getElementById('num_cheque').value='';
                                    }else{
                                    document.getElementById('num_cheque').disabled='';
                                    }
                                    if (this.value=='Effet' || this.value=='Cheque'){
                                    document.getElementById('date_validation_tr').style.visibility = 'visible';
                                    }else {
                                    document.getElementById('date_validation_tr').style.visibility = 'hidden';
                                    document.getElementById('date_validation').value='';
                                    }">
                                    <option value=""> Choix</option>
                                    <option value="Espece"> Espece</option>
                                    <option value="Cheque"> Cheque</option>
                                    <option value="Virement"> Virement</option>
                                    <option value="Effet"> Effet</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="num_cheque">Num&eacute;ro : </label>
                                
                                <input type="text" name="num_cheque" class="form-control" id="num_cheque"/>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="date_reg">Date :</label>
                                <input type="text" name="date_reg" value="<?php echo date('Y-m-d')?>" class="form-control datepicker" autocomplete="off" id="date_reg"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="montant">Montant : </label>
                                <input type="text" name="montant" class="form-control" id="montant"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remarque">Remarque :</label>
                            <textarea  class="form-control" name="remarque" id="remarque"
                            ></textarea>
                        </div>
                        <div class="form-row">
                             <div class="form-group col-md-4">
                            <label for="remarque">Valid&eacute; :</label>
                            <div class="mb-4">
                                <div style="display: inline-block;margin-left: 20px" class="custom-control custom-radio">
                                    <input type="radio" id="customRadio2" value="0" checked=""  name="etat" class="custom-control-input">
                                    <label class="custom-control-label"  for="customRadio2">Non</label>
                                    
                                </div>
                                <div style="display: inline-block;" class="custom-control custom-radio">
                                    <input type="radio" id="customRadio1" value="1" name="etat" class="custom-control-input">
                                    <label class="custom-control-label"  for="customRadio1">Oui</label>
                                </div>
                                
                            </div>
                            </div>
                                <div class="form-group col-md-4">
                                    <label for="id_compte">Compte</label>
                                    <select name="id_compte" class="form-control" id="id_compte">
                                      <?php $compte = new compte();
                                            $compte = $compte->selectAll();
                                                foreach ($compte as $cpt) {
                                                 
                                                 echo "<option value=".$cpt->id.">".$cpt->nom_compte."</option>";
                                              }
                                      ?>
                                        
                                    </select>
                                </div>
                                <div class="form-group col-md-4" id="date_validation_tr">
                                    <label for="date_validation">Date d'&eacute;ch&eacute;cance: </label>
                                    
                                    <input type="text" autocomplete="off" name="date_validation" class="form-control datepicker" id="date_validation"/>
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

$("input.datepicker").datepicker({
                     format: 'yyyy-mm-dd',
                     templates: {
                     leftArrow: '<i class="simple-icon-arrow-left"></i>',
                     rightArrow: '<i class="simple-icon-arrow-right"></i>'
                    }
                })
      




    $("#addform" ).on( "submit", function( event ) {
             event.preventDefault();


               var errors = "";
            var montant_a_paye = $("#total_reste").attr("val");
            montant_a_paye = parseFloat(montant_a_paye);

            var montant = $("#montant").val();

            if (!isNaN(montant) && montant > 0) {
                console.log("number good");
            } else {
                errors += "Vous devez entrer un montant valide\n";
            }

//            if (montant > montant_a_paye) {
//                errors += "Veuillez entrer un nombre inférieur ou égal à " + montant_a_paye + " DH\n";
//
//            }
            if (errors != "") {
                  swal(
                      'Erreur',
                       errors,
                      'danger'
                    )
                return false;

            }
             var form = $( this );
             $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/reg_vente/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
               if (data.indexOf("success")>=0) {
                   
                    swal(
                      'Ajouter',
                      'reglement vente a ete bien Ajouter',
                      'success'
                    ).then((result) => {
                    $.ajax({

                              method:'POST',
                              data: {ajax:true},
                              url: `<?php echo BASE_URL."views/reg_vente/index.php?id=".$id;?>`,
                              context: document.body,
                              success: function(data) { 
                                      history.pushState({},"",`<?php echo BASE_URL."reg_vente/index.php?id=".$id;?>` );
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
