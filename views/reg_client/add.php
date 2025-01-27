  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
$id = explode('?id=',$_SERVER["REQUEST_URI"])[1];


$reg_vente = new reg_vente();
$reg_client = new reg_client();
$produit = new produit(); 
$client = new client();
$dataclient = $client->selectById($id);
$result = $client->selectOperationNonPaye($id);

$total_ventes = 0;
$montant_paye = 0;
$montant_a_paye = 0;
$all_vente = $client->selectAllVente($id);

foreach($all_vente as $vente) 
{
   $detail_vente = $client->selectAllDetailVente($vente->id_vente);

   foreach($detail_vente as $dv) {
$dataproduit = $produit->selectById($dv->id_produit);

  // $total_ventes += ($dv->prix_produit * $dv->valunit *(1-$dv->remise/100) +$dv->prix_produit*  $dv->valunit *(1-$dv->remise/100));
	   
	   //  if(!empty($dv->valunit) || $dv->valunit!=0)
		 //{
			//	$n =  ($dv->prix_produit * $dv->valunit * (1-$dv->remise/100));
		 //}else
		// {
			//	$n = ($dv->prix_produit * $dv->qte_vendu *(1-$dv->remise/100));
		// }
		 //$produit = new produit();
		 //$prod = $produit->selectById($dv->id_produit);
		 $total_ventes += $dv->qte_vendu * $dv->prix_produit;
   }
   $reg_vente = $client->selectAllVenteReg($vente->id_vente);
  
   foreach($reg_vente as $rv) {
      $montant_paye = $montant_paye + $rv->montant;
   }
}


$montant_a_paye = $total_ventes - $montant_paye;

?>

<div class="container-fluid disable-text-selection">
<div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Nouveau reglement client N&deg; <?php echo $id ?> : <?php echo $dataclient["nom"]." ".$dataclient["prenom"] ?></h1>

                 <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-success  url notlink" data-url="reg_client/index.php?id=<?php echo $id ?>" > <i class="glyph-icon simple-icon-arrow-left"></i></button>
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
                <p><strong> Ce client doit payer : </strong>
                    <span id="total_reste" val="<?php echo $montant_a_paye?>" class="badge badge-pill badge-danger mb-1"> <?php echo number_format($montant_a_paye, 2, ".", " "); ?> DH</span> </p>
                    <form id="addform" method="post" name="form_reg_client" enctype="multipart/form-data">
                        
                        <input type="hidden" name="act" value="insert">
                         <input type="hidden" name="id" value="<?php echo $id?>">
                        <div class="form-row">
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
                            <div class="form-group col-md-4">
                                <label for="num_cheque">Num&eacute;ro : </label>
                                
                                <input type="text" name="num_cheque" class="form-control" id="num_cheque"/>
                            </div>

<div class="form-group col-md-4">
                   <label for="ventes">Ventes : </label>
                   <select name="ventes[]" class="form-control ventes" multiple="multiple" id="ventes[]">
                     <option value="0"> Choix</option>
<?php
 $ventes = $reg_client->getUnpaidVentesWithRemainingAmount($id); 

foreach ($ventes as $key => $value) {
?>
  <option value="<?php echo $value->id_vente ?>"> <?php echo("Date Vente : ".$value->date_vente." - Reste : ".$value->remaining) ?></option>
<?php
}
?>

                   </select>
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
                                <div style="display: inline-block;" class="custom-control custom-radio">
                                    <input type="radio" id="customRadio1" value="1" checked="" name="etat" class="custom-control-input">
                                    <label class="custom-control-label"  for="customRadio1">Oui</label>
                                </div>
                                <div style="display: inline-block;margin-left: 20px" class="custom-control custom-radio">
                                    <input type="radio" id="customRadio2" value="0" name="etat" class="custom-control-input">
                                    <label class="custom-control-label"  for="customRadio2">Non</label>
                                    
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
      


       $(document).ready(function() {
         $('.ventes').select2();
       });



       $(".ventes").on('change', function() {
         let amount = 0;
         Array.from($(".ventes").select2("data")).forEach(x => {
           amount += parseFloat(x.text.split("Reste :")[1]);
         });

         $("#total_reste").attr("val", '' + amount);
         $("#total_reste").text(amount + " DH");




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
                url: "<?php echo BASE_URL.'views/reg_client/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
					console.log(data);
                if (data.indexOf("success")>=0) {
                   
                    swal(
                      'Ajouter',
                      'reglement Client a ete bien Ajouter',
                      'success'
                    ).then((result) => {
                    $.ajax({

                              method:'POST',
                              data: {ajax:true},
                              url: `<?php echo BASE_URL."views/reg_client/index.php?id=".$id;?>`,
                              context: document.body,
                              success: function(data) { 
                                      history.pushState({},"",`<?php echo BASE_URL."reg_client/index.php?id=".$id;?>` );
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
