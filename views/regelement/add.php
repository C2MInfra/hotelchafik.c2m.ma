  <?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}

?>

<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>vente </h1>
                
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
                    <h5 class="mb-4">Ajouter Nouveau vente</h5>
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
                        <div class="form-group">
                            <label for="remarque"> Remarque : </label>
                            <textarea  class="form-control" name="remarque" id="remarque"
                            ></textarea>
                        </div>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="id_categorie"> Categorie :</label>
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
                                    <label for="remise">remise</label>
                                    <input type="text" name="remise" id="remise" class="form-control" value="0">
                                    
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="qte_vendu">Qte</label>
                                    <input type="text" name="qte_vendu" id="qte_vendu" class="form-control" value="0">
                                    
                                </div>

                                <div class="form-group col-md-2">
                                    <button id="addProduct" type="button" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 25px;">Ajouter</button>
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
                $total+=$ligne->qte_vendu * $ligne->prix_produit;
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
                url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
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
                url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
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
                url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
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
                url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
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
                              url: `<?php echo BASE_URL."views/vente/index.php?id=".$id;?>`,
                              context: document.body,
                              success: function(data) { 
                                      history.pushState({},"",`<?php echo BASE_URL."vente/index.php?id=".$id;?>` );
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
