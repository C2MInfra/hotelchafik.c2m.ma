  <?php

if (isset($_POST['ajax'])) {

include('../../evr.php');

}

 	$query1=$result1=connexion::getConnexion()->query("SELECT numbon as dernier_bon FROM vente where  numbon < 1000000 and numbon !='' ORDER BY numbon DESC LIMIT 1");
     $result1=$query1->fetch(PDO::FETCH_OBJ);
     $last_num = $result1->dernier_bon + 1;

 $fournisseurs = connexion::getConnexion()->query("SELECT fournisseur AS nom FROM produit WHERE fournisseur IS NOT NULL GROUP BY fournisseur ORDER BY fournisseur ASC ")->fetchAll(PDO::FETCH_OBJ);

 $depot = new depot();
 $data_depot = $depot->selectAll();

 $produit = new produit();
 $data_cat = $produit->selectAllCat();   
?>



<div class="container-fluid disable-text-selection">

    <div class="row">

        <div class="col-12">

            <div class="mb-2">

                <h1>Bon commande </h1>

                

        <div class="float-sm-right text-zero">

                    <button type="button" class="btn btn-success  url notlink" data-url="bon-commande/index.php" > <i class="glyph-icon simple-icon-arrow-left"></i></button>

                </div>

            </div>

            

            <div class="separator mb-5"></div>

        </div>

    </div>

    <div class="row">

        <div class="col align-self-start">

            <div class="card mb-4">

                <div class="card-body">

                    <h5 class="mb-4">Ajouter Un Nouveau commande</h5>

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

                                <label for="date_bon">Date :</label>

                                <input type="text" class="form-control datepicker" id="date_bon" name="date_bon" value="<?php echo date('Y-m-d'); ?>" >
                            </div>

                            

                        </div>

                        <div class="form-group">

                            <label for="remarque"> Remarque : </label>

                            <textarea  class="form-control" name="remarque" id="remarque"

                            ></textarea>

                        </div>

                            <div class="form-row">

                                <div class="form-group col-md-4">

                                    <label for="rech"> Recherche Référence:  </label>

                                    <input type="text" class="form-control" style="border-color: red" id="rech" >

                                 </div>
                                 <div class="form-group col-md-4">
                                   <button type="button" data-toggle="modal" data-target="#addModal" class="btn btn-secondary" style="margin-top:30px;">
                                   	  <i class='bx bx-globe'></i> Liste des produits
                                   </button>
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

                                <div class="form-group col-md-2">

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

                                            foreach ($produits as $row) 
											{

                                            echo '<option value="'.$row->id_produit.'">'.$row->designation.'</option>';

                                            }?>

                                        </optgroup>

                                        <?php } ?>

                                        

                                    </select>

                                </div>
								<div class="form-group col-md-2">

                                    <label for="id_depot"> Dépot :</label>
									<?php
										$depot=new depot();
										$res_depot=$depot->selectAll();
									?>
									<select class="form-control select2-single" name="id_depot" id="id_depot" >
										<?php foreach($res_depot as $d): ?>
										<option value="<?php echo $d->id?>">
										<?php echo $d->nom ?>
										</option>
										<?php endforeach; ?>
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



                                <div class="form-group col-md-1" style="max-width: 100px;">

                                    <label for="qte_vendu">Qte</label>
                                    <input type="text" name="qte_vendu" id="qte_vendu" class="form-control" value="0">
                                
                                </div>

                                <div class="form-group col-md-2" >
                                    <label for="valunite">Unité</label>
                                    <div class="d-flex flex-row">
                                        <input style="max-width: 250px;" type="text" name="valunite" id="valunite" class="form-control " value="0">
                                        <input type="text" name="unite" id="unite" class="form-control ml-3" placeholder="Kg ou M²...">
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-1" >
                                    <label for="unite2">Unité 2</label>
                                    <div class="d-flex flex-row">
                                        <input style="max-width: 250px;" type="text" name="unite2" id="unite2" class="form-control " value="1" disabled>
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-2">

                                    <button id="addProduct" type="button" class="pull-right btn btn-success default btn-lg btn-block addProd  mr-1 " style="margin-top: 31px;">Ajouter</button>

                                </div>

                            </div>

                            <div class="table-responsive">

                <table  class="table" cellpadding="0" cellspacing="0" id="box-table-a" summary="Employee Pay Sheet">

                      <thead>

                        <tr>

                          <th   scope="col">Produit</th>

                          <th    width="102" scope="col">Prix</th>

                          <th   width="109" scope="col">Qte</th>

                          <th   width="109" scope="col">Unité</th>

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

            <td><?php 
            
            echo $ligne->qte_vendu ; 
            
           
            
            ?></td>

            <td><?php 
         
            echo $ligne->valunit . ' ' .  $ligne->unit;
          
            ?>  </td>
            
            <td width="90" style="text-align: right;" >
                
                <?php  
                   if($ligne->valunit!=0 || !empty($ligne->valunit)){
                    
                    echo number_format($ligne->valunit* $ligne->prix_produit,2,'.',' ');
                    $total+=$ligne->valunit* $ligne->prix_produit* (1 - $ligne->remise/100);
                    
                    }else{

                    echo number_format($ligne->qte_vendu * $ligne->prix_produit,2,'.',' ');
                    $total+=$ligne->qte_vendu * $ligne->prix_produit* (1 - $ligne->remise/100);
                }
                ?>

            </td>

            <td>    
                <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
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

                        <div class="float-sm-right text-zero saveb">

                            <button type="button" id="senddata"  class="btn btn-primary btn-lg  mr-1 " >Enregistrer</button>

                        </div>
                    
                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									  <div class="modal-dialog" role="document" style="min-width:80%;">
										<div class="modal-content"  style="padding:0px; background-color:initial;">
										  <div class="modal-body" style="padding:0px; background-color:initial;">
											 <div style=" background-color:white; border-radius:8px; overflow:hidden; border-top:2px solid #2a93d5;">
												 <div>
													<h2 style="
														padding: 8px;
														font-size: 14pt;
														font-weight: 700;
														color: #555;
														margin-bottom: 0px;
														padding-bottom: 2px;
													">Produits</h2><h3 style="
														padding: 8px;
														font-size: 10.4pt;
														font-weight: 300;
														color: #2a93d5;
														padding-top: 0px;
													">Consultation des produits</h3>
												 </div>
												 <div id="barcode_container">
													<div style="padding: 8px;">
														<div class="col-xl-12 col-lg-12 mb-4">
            <div class="card h-100">
                <div class="card-body">

                     <div class="form-row">
                        <div class="form-group   col-md-3">
                            <label> &nbsp;&nbsp;Code / Nom : </label>
                            <input type="text" id="keywords"class="form-control" placeholder="Recherche par Num ou Nom"onkeyup="searchFilter()"/>
                        </div>
                        <div class="form-group   col-md-2">
                            <label> &nbsp;&nbsp;Emplacement : </label>
                            <select id="depot" onchange="searchFilter()" class="form-control">
                                <option value="0">Tous</option>
                                <?php foreach($data_depot as $depot): ?>
                                <option value="<?php echo $depot->id; ?>"><?php echo $depot->nom ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                          <div class="form-group   col-md-2">
                            <label> Fournisseur : </label>
                            <select id="fournisseur" class="form-control select2-single" onchange="searchFilter()">
                            	<option value='-1' selected>Choisir un fournisseur</option>
                            	 <?php foreach($fournisseurs as $fournisseur): ?>
                                <option value="<?php echo $fournisseur->nom; ?>"><?php echo $fournisseur->nom ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group   col-md-3">
                            <label> &nbsp;&nbsp;Catégorie :</label>
                            <select id="categorie" onchange="searchFilter()" class="form-control select2-single">
                                <option value="0">Choisir la catégorie</option>
                                <?php foreach($data_cat as $cat): ?>
                                <option value="<?php echo $cat->id_categorie; ?>"><?php echo $cat->nom ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <label> &nbsp;&nbsp;Stock :</label>
                            <select name="stock" id="stock" onchange="searchFilter()" class="form-control">
                                <option value="all">Tous</option>
                                <option value="1"> stock > 0</option>
                                <option value="0"> stock = 0</option>
                            </select>
                        </div>
                    </div>
					<div style="
						color: #555;
						margin-bottom: 4px;
					"><span id="el_nbr_count">0</span> produits ajoutés</div>
                    <div id="posts_content">
                      <div class="table-responsive">
                        <table id="datatables" style="width: 100%">
                        <thead id="advanced_tbody">
                            <tr style="background-color: #e5e7e5;">
                                <th>R&eacute;f</th>
                                <th>D&eacute;signation</th>
                                <th> Q_stock</th>
                                <th> P.achat</th>
                                <th> P.vente</th>
                                <th> Depot</th>
                                <th>PU</th>
                                <th>Remise %</th>
                                <th>Qte</th>
                                <th>Unite</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody >
                           <?php
								$produit = new produit();

								 $query = $produit->selectQuery("select p.* from produit p where archive=0 order by p.designation asc");

								 $queryNum = $produit->selectQuery("SELECT COUNT(*) as numrows FROM produit where archive=0");
								
						   ?>
                            <?php foreach($query as $ligne) { ?>
                            
                            <tr>
                                <td> <?php if(!empty($ligne->image)) { ?>
                                        <a class="fancybox" title="image de <?php echo $ligne->designation; ?>"
                                            href="../upload/produit/<?php echo $ligne->image; ?>"> <img
                                        src="../icon/image.png" width="17" height="19" class="icon"/> </a>
                                    <?php } ?>
                                     <?php echo $ligne->code_bar; ?></td>
                                <td class="designation" > <?php echo $ligne->designation;
                                    if($ligne->designation_ar != "") {
                                    echo "/" . $ligne->designation_ar;
                                } ?> </td>
                                <td> <?php echo $ligne->qte_actuel; ?> </td>
                                <td> <?php echo $ligne->prix_achat; ?> </td>
                                <td> <?php echo $ligne->prix_vente; ?> </td>
                                 <td>
                                <?php
                                	$produit_depot = new produit_depot();
									$depots = $produit_depot->depots($ligne->id_produit);
				               ?>
				               <select id="depot_<?php echo $ligne->id_produit?>" class="form-control">
				               											 											 <?php											 
															 
									foreach($depots as $d)
									{?>
										<option value='<?php echo $d->id ?>'><?php echo $d->nom?></option>
											<?php
									}
								?>
									</select>
                                </td>
                                <td>
                                    <input type="text" style="width:60px;" value="0" class="form-control" id="prix_<?php echo $ligne->id_produit ?>">
                                 </td>
                                 <td>
                                    <input type="text" style="width:60px;" value="0" class="form-control"  id="remise_<?php echo $ligne->id_produit ?>">
                                 </td>
                                 <td>
                                    <input type="text" style="width:60px;" value="0" class="form-control" id="qte_<?php echo $ligne->id_produit ?>">
                                 </td>
                                 <td>
                                    <input type="text" style="width:60px;" value="0" class="form-control"  id="unite_<?php echo $ligne->id_produit ?>">
                                 </td>
                                 <td>
                                    <button type="button" class="btn btn-success add_v2_btn" style="border-radius: 12px;padding: 4px 12px;" data-id="<?php echo $ligne->id_produit ?>">Ajouter</button>
                                 </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                         </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
													</div>
													<center><p id="barcode_footer" style="font-weight:900; font-size:16pt;"></p></center>
												 </div>
												 <div  class="" style="font-weight:900; font-size:16pt;height:8px;background-color:#2a93d5;border-style: none;color: white;font-size: 12pt;font-weight: 900;width: 100%; cursor:pointer; ">
												 </div>
											 </div>
										  </div>
										</div>
									  </div>
									</div>
<script type="text/javascript">


function searchFilter()
{

            var keywords = $('#keywords').val();
            var depot = $('#depot').val();
            var categorie = $('#categorie').val();
            var stock = $('#stock').val();
            var sortBy = $('#sortBy').val();
		    var fournisseur = $('#fournisseur').val();
		
            $.ajax({
                type: 'POST',
                  url: "<?php echo BASE_URL.'views/produit/' ;?>controle.php",
                data: 'act=filter_vente&keywords=' + keywords + '&depot=' + depot + '&categorie=' + categorie + '&stock=' + stock + '&sortBy=' + sortBy + '&fournisseur=' + fournisseur,
                beforeSend: function () {
                    $('.loading-overlay').show();
                },
                success: function (html){
					$('#posts_content').html(html);
					$('#datatables').dataTable({
			  responsive: false,
			  searching: false,
			  order: [

			  [0, "desc"]

			  ],

			  dom: 'Bfrtip',

			  buttons: [
			  ],

			  pageLength: 20,

			  language: {

			  paginate: {

			  previous: "<i class='simple-icon-arrow-left'></i>",

			  next: "<i class='simple-icon-arrow-right'></i>"

			  }

			  },

			  drawCallback: function(){

			  $($(".dataTables_wrapper .pagination li:first-of-type")).find("a").addClass("prev"),

			  $($(".dataTables_wrapper .pagination li:last-of-type")).find("a").addClass("next"),

			  $(".dataTables_wrapper .pagination").addClass("pagination-sm")

			  }

			  });
                }
            });
    }

    $( document ).ready(function() {


  $('body').on('click', '.add_v2_btn', function(){
		     //get values
		     let id = $(this).data('id');
		     let prix = $('#prix_' + id).val();
		     let remise = $('#remise_' + id).val();
		     let qte = $('#qte_' + id).val();
		     let unite = $('#unite_' + id).val();
		     let depot = $('#depot_' + id).val();
	  
		     //add product
                var id_produit = $(this).val();
                var unit_qte=0;
                var unit='';
				
                if(unite!=0)
                {
                    unit_qte = unite;
                    if($('#unite').val()!="")
					{
                        unit= unite;
                    }else
					{
						unit = '';
                    }
                }
                else
                {
                    unit_qte=qte;
                }
                    if(unite!=0 )
					{
                        if($('#unite').val()!=""){
                        unit=$('#unite').val();
                        }else{
                           // alert('Entrer Votre Unité (Kg-M-M2...)');
                           // throw '';
							unit = '';
                        }
                    }
                $.ajax({
                    type: "POST",
                    url: "<?php echo BASE_URL.'views/bon-commande/' ;?>controle.php",
                    data: {
                        act:"addProduct",id_produit: $("#id_produit").val() ,
                        prix_produit: id, 
                        qte_vendu: qte,
                        remise: remise ,
                        valunit:unite ,
                        unit:unit,id_depot:depot},

                    success: function (data) {
                        $('#detail_commande').html(data);
                    }

                });
                		   
		     //initialiser
		     $('#prix_' + id).val(0);
		     $('#remise_' + id).val(0);
		     $('#qte_' + id).val(0);
		     $('#unite_' + id).val(0)
	   });
        
$("#id_client").change(function(){
    var id_produit = $('#id_produit').val();
    $.ajax({

        type: "POST",

        url: "<?php echo BASE_URL.'views/bon-commande/' ;?>controle.php",

        data: {act:"getPrix",id_produit: id_produit,id_client: $("#id_client").val()},

        success: function (data) {

        var tab=data.split('/');

        $('#prix_produit').val(tab[0]);
        //    $('#prix_produit').val(11);

            $('#reste_stock').html(tab[1]);
        }

    });
});





$(".bon").click(function(){



var  $type_compte = $(this).val();

var bonv=0;

if($type_compte==0)

{

$("#bon").hide();
bonv=$("#bon").val();
$("#bon").val("");
}

else

{

$("#bon").show();
$("#bon").val(bonv);
}

});



 $("#rech").keyup(function() {

   



            

            var id = $(this).val();

            $.ajax({

                type: "POST",

                url: "<?php echo BASE_URL.'views/bon-commande/' ;?>controle.php",

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

          $("#id_categorie").change(function() 
			{
               var id_categorie = $(this).val();
               $.ajax({
                 type: "POST",
                 url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
                 data: {act:"getproduit",id_categorie: id_categorie, id_bon: $('#bon_commande').val()},
                 success: function (data){
                     $('#id_produit').html(data);
                     $('#id_produit option:eq(1)').prop('selected', true);
					 
					 let id_produit = $('#id_produit').val();
					 if(id_produit != null)
					 {
						 $.ajax({
								type: "POST",
								url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
								dataType: 'json',
								data: {act:"getPrix",id_produit: id_produit, id_client: $("#id_client").val()},
								success: function (data){
									var tab=data.val.split('/');
									$("#id_depot").html(data.depots);
									$('#prix_produit').val(tab[0]);
									$('#reste_stock').html(tab[1]);
									$('#unite2').val(tab[2]);
									$('#id_depot option:eq(0)').prop('selected', true);
									
									let id_depot = $("#id_depot").val();

									if(id_depot != null)
									{
										$.ajax({
											type: "POST",
											url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
											dataType: 'json',
											data: {act:"getDepotQte",id_produit: id_produit, id_depot: id_depot},

											success: function (data)
											{
												if(data)
												{
													$('#reste_stock').html(data.qte);
												}
												else
												{
													$('#reste_stock').html('0.00');
												}
											}
										});
									}
								}
						  });
					 }
                }
            });
         });

          $("#id_produit").change(function(){
            var id_produit = $(this).val();

            $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
				dataType: 'json',
                data: {act:"getPrix",id_produit: id_produit, id_client: $("#id_client").val()},

                success: function (data){
                    var tab=data.val.split('/');
                    $("#id_depot").html(data.depots);
                    $('#prix_produit').val(tab[0]);
                    $('#reste_stock').html(tab[1]);
					$('#unite2').val(tab[2]);
					$('#id_depot option:eq(0)').prop('selected', true);
					let id_depot = $("#id_depot").val();
					
					if(id_depot != null)
					{
						$.ajax({
							type: "POST",
							url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
							dataType: 'json',
							data: {act:"getDepotQte",id_produit: id_produit, id_depot: id_depot},
							success: function (data)
							{
								if(data)
								{
									$('#reste_stock').html(data.qte);
								}
								else
								{
									$('#reste_stock').html('0.00');
								}
							}

						});
					}
                }

            });

  

            });

			$("#id_depot").change(function(){

				var id_depot = $(this).val();
				var id_produit = $('#id_produit').val();
				
				$.ajax({

					type: "POST",
					url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
					dataType: 'json',
					data: {act:"getDepotQte",id_produit: id_produit, id_depot: id_depot},

					success: function(data)
					{
						if(data)
						{
							$('#reste_stock').html(data.qte);
						}
						else
						{
							$('#reste_stock').html('0.00');
						}
					}

				});
            });



            $("#addProduct").click(function() {
                var id_produit = $(this).val();
                var unit_qte=0;
                var unit='';
                if($("#valunite").val()!=0)
                {
                    unit_qte=$("#valunite").val();
                    if($('#unite').val()!=""){
                        unit=$('#unite').val();
                    }else{
                        alert('Entrer Votre Unité (Kg-M-M2...)');
                        throw '';
                    }
                }
                else
                {
                    unit_qte=$("#qte_vendu").val();
                }
                    if($("#valunite").val()!=0 ){
                        if($('#unite').val()!=""){
                        unit=$('#unite').val();
                        }else{
                            alert('Entrer Votre Unité (Kg-M-M2...)');
                            throw '';
                        }
                    }
                $.ajax({
                    type: "POST",
                    url: "<?php echo BASE_URL.'views/bon-commande/' ;?>controle.php",
                    data: {
                        act:"addProduct",id_produit: $("#id_produit").val() ,
                        prix_produit: $("#prix_produit").val(), 
                        qte_vendu: $("#qte_vendu").val(),
                        remise: $("#remise").val() ,
                        valunit:$("#valunite").val() ,
                        unit:unit,id_depot: $("#id_depot").val()},

                    success: function (data) {
                        $('#detail_commande').html(data);
                    }

                });

            });
		
		    $('#qte_vendu').focusin(function(){
				let unite2 = $('#unite2').val();
				let unite = $('#valunite').val();
				
				if(unite != '')
				{
					$('#qte_vendu').val(unite2 * unite);
				}
				
			});

            $(".addProd").click(function() {
                prix = $("#prix_produit").val();
                if($("#valunite").val()!=null || $("#valunite").val()!=0){
                    qte_vendu= $("#valunite").val(); 
                }else{
                    qte_vendu= $("#qte_vendu").val();
                }
               
                remise= $("#remise").val();
                montantTot=0;
                montantTotF=0;
                 let totale=parseFloat($('#detail_commande').last('tr').find('b').eq(1).html().replaceAll(' ',''));

                 if( $("#id_client").val()!=null)
				 {
                    $.ajax({

                    type: "POST",

                    url: "<?php echo BASE_URL.'views/bon-commande/' ;?>controle.php",

                    data: {act:"vrefPlafond",id_client:$("#id_client").val() ,id_produit: $("#id_produit").val(),},

                    dataType: "json",

                    success: function (data) {
                        
                        $.each(data, function(index,o){
                         
                        //  ////console.log();
                        if($('#numbon').val()!=null){
                            if(totale!=0){  
                                if(!isNaN(o.motunitv)){
                                    montantTot=o.motunitv;
                                    montantTotF+=o.motunitv;
                                }else{
                                    montantTot=o.montantTot;
                                    montantTotF+=o.montantTot;
                                }
                                ////console.log(montantTotF);
                                var result=(qte_vendu * prix* (1 - remise/100));
                                
                                totale+=parseFloat(result)+parseFloat(montantTotF);
                                
                            if(parseFloat(o.plafond)<totale)
							{
                              
								swal({

									title: 'Attention !',

									text: "Vous avez dépassé le plafond prédéfinie!",

									type: 'warning',

									confirmButtonColor: '#d33',

									cancelButtonColor: '#3085d6',

									confirmButtonText: 'Ignorer'

								});
                             }
                        }else{
                            if(!isNaN(o.motunitv)){
                                    montantTot=o.motunitv;
                                    montantTotF+=o.motunitv;
                                }else{
                                    montantTot=o.montantTot;
                                    montantTotF+=o.montantTot;
                                }
                               

                             let a=parseFloat(qte_vendu * prix* (1 - remise/100))+montantTotF;
                             if(parseFloat(o.plafond)<a){
                                
                                swal({

                                    title: 'Attention !',

                                    text: "Vous avez dépassé le plafond prédéfinie!",

                                    type: 'warning',

                                    // showCancelButton: true,

                                    confirmButtonColor: '#d33',

                                    cancelButtonColor: '#3085d6',

                                    confirmButtonText: 'Ignorer'

                                    }).then((result) => {

                                    if (result.value) {

                                    }

                                    });
                                    // $('.saveb').hide();
                            }
                        }
                        
                        }
                        
                            });
                        },
                    error:function(error){
                        ////console.error( error);
                    }
                    });
                }
                

            });



     $('body').on( "click",".delete", function( event ) {

            event.preventDefault();
            totale=$('#detail_commande').last('tr').find('b').eq(1).html();
            
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

                url: "<?php echo BASE_URL.'views/bon-commande/' ;?>controle.php",

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

    

    $("body" ).on( "click","#senddata", function( event ) {

             event.preventDefault();



             var form = document.getElementById('addform');

             $.ajax({

                type: "POST",

                url: "<?php echo BASE_URL.'views/bon-commande/' ;?>controle.php",

                data: new FormData(form),

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

                              url: `<?php echo BASE_URL."views/bon-commande/index.php"?>`,

                              context: document.body,

                              success: function(data) { 

                                      history.pushState({},"",`<?php echo BASE_URL."bon-commande/index.php";?>` );

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

