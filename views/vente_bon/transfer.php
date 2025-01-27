<?php
$page="vente";
if (isset($_POST['ajax'])) {
include('../../evr.php');
}
$vente=new vente();
$id = explode('?id=',$_SERVER["REQUEST_URI"]);




 $query1=$result1=connexion::getConnexion()->query("SELECT numbon as dernier_bon FROM vente where  numbon != 0 or numbon !='' ORDER BY id_vente DESC LIMIT 1");
      $result1=$query1->fetch(PDO::FETCH_OBJ);
      $last_num = $result1->dernier_bon+1;

?>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Bon livraison </h1>
        
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
          <h5 class="mb-4">Bon livraison</h5>
          <center>
          <?php $data1=$vente::isvalid( $id[1]);
          if (count($data1)>0){
          echo '<fieldset><legend>Produit monque en  Stock</legend><table id="tabm"><thead><tr><td>designation</td><td>Qte Actuel</td><td>Qte Vendu</td><td>Deferance</td></tr></thead>';
          foreach ($data1 as  $value) {
          echo "<tr><td>".$value["designation"]."</td><td>".$value["qte_actuel"]."</td><td>".$value["qte_vendu"]."</td><td>".($value["qte_actuel"]-$value["qte_vendu"])."</td></tr>";
          }
        echo "</table></fieldset>";
        }
        ?>
        </center>
        <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
          <input type="hidden" name="act" value="insertbon">
           <input type="hidden" name="id" value="<?php echo $id[1] ;?>">
          <div class="form-row">
           
            <div class="form-group col-md-6">
              <label for="date_vente">Date :</label>
              <input type="text" class="form-control datepicker" id="datebon" name="datebon" value="<?php echo date('Y-m-d'); ?>" >
            </div>
            
 <div class="form-group col-md-6">
               <label for="date_vente">num bon :</label>
              <input type="text" class="form-control " id="numbon" name="numbon" value="<?php echo $last_num  ?>" >
            </div>
            

          </div>
          <div class="form-group">
            <label for="remarque"> Remarque : </label>
            <textarea  class="form-control" name="remarquebon" id="remarque"
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
     
    $("input.datepicker").datepicker({
                     format: 'yyyy-mm-dd',
                     templates: {
                     leftArrow: '<i class="simple-icon-arrow-left"></i>',
                     rightArrow: '<i class="simple-icon-arrow-right"></i>'
                    }
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
                      'Bon de livraison a ete bien Ajouter',
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