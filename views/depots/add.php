  <?php

if (isset($_POST['ajax'])) 
{
   include('../../evr.php');
}
$queries = explode('?', $_SERVER['REQUEST_URI']);
if(count($queries) > 1)
{
	$keys = explode('&', $queries[1]);
	$bon_key = str_replace('bon=', '', $keys[0]);
	$client_key = str_replace('client=', '', $keys[1]);
}

 $query1=$result1=connexion::getConnexion()->query("SELECT numbon as dernier_bon FROM vente where  numbon < 1000000 and numbon !='' ORDER BY numbon DESC LIMIT 1");

     $result1=$query1->fetch(PDO::FETCH_OBJ);

     $last_num = $result1->dernier_bon + 1;
?>

<div class="container-fluid disable-text-selection">

    <div class="row">

        <div class="col-12">

            <div class="mb-2">

                <h1>Dépots </h1>

                

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

                    <h5 class="mb-4">Ajouter une nouvelle opération</h5>

                    <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">

                        <input type="hidden" name="act" value="insert">
                        <input type="hidden" name="client_key" id="client_key_input" value="<?php echo (isset($client_key))?$client_key:'' ?>">
                        <input type="hidden" name="bon_key" id="bon_key_input" value="<?php echo (isset($bon_key))?$bon_key:'' ?>">
 
                        <div class="form-row">

                            <div class="form-group col-md-6">

                                <label for="date_op">Date opération:</label>

                                <input type="text" class="form-control datepicker" id="date_op" name="date_op" value="<?php echo date('Y-m-d'); ?>" >
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


                                            <?php

                                            $produits=$depot->selectQuery("SELECT  id_produit,designation  FROM produit where   id_categorie=".$categories[0]->id_categorie." and   emplacement='".$rep_depot->id."' order by designation asc");

                                            foreach ($produits as $row) 
											{

                                            	echo '<option value="'.$row->id_produit.'">'.$row->designation.'</option>';

                                            }?>


                                        <?php } ?>

                                        

                                    </select>

                                </div>
								<div class="form-group col-md-2">
                                    <label for="id_depot_src"> Dépot Source:</label>
									<?php
										$depot=new depot();
										$res_depot=$depot->selectAll();
									?>
									<select class="form-control select2-single" name="id_depot_src" id="id_depot_src" >
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

                                <div class="form-group col-md-1" style="max-width: 100px;">

                                    <label for="qte_op">Qte</label>
                                    <input type="text" name="qte_op" id="qte_op" class="form-control" value="0">
                                
                                </div>
								<div class="form-group col-md-2">
                                    <label for="id_depot_dest"> Dépot Destination:</label>
									<?php
										$depot=new depot();
										$res_depot=$depot->selectAll();
									?>
									<select class="form-control select2-single" name="id_depot_dest" id="id_depot_dest" >
										<?php foreach($res_depot as $d): ?>
										<option value="<?php echo $d->id?>">
										<?php echo $d->nom ?>
										</option>
										<?php endforeach; ?>
									</select>
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
                          <th   width="170" scope="col">Dépot Source</th>
                          <th   width="170" scope="col">Dépot Destination</th>
                          <th  width="129" scope="col">qte</th>
                          <th  width="129" scope="col">Action</th>
                        </tr>

                      </thead>

                      <tbody  id="detail_commande" >

        <?php   

            $detail_depot_op = new detail_depot_op();
            $data = $detail_depot_op->selectAllNonValide();

            foreach($data as $ligne){

        ?>

        <tr>

            <td><?php echo $ligne->designation ?></td>
			<td><?php echo $ligne->depot_src ?></td>
			<td><?php echo $ligne->depot_dest ?></td>
			<td><?php echo $ligne->qte_op ?></td>
            <td>    
                <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                    <i class="simple-icon-trash" style="font-size: 15px;"></i>
                </a>
            </td>

        </tr>

        <?php

        }

        ?>
    </tbody>

 </table>

                    </div>

                        <div class="float-sm-right text-zero saveb">

                            <button type="button" id="senddata"  class="btn btn-primary btn-lg  mr-1 " >Enregistrer</button>

                        </div>
                        <input type="hidden" id="access_add" value="1">
                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script type="text/javascript">

    $( document ).ready(function() 
	{
       if($("#client_key_input").val() != '')
	   {
		   $.ajax({
				type: "POST",

				url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",

				data: {act:"getCommandes", id_client: $("#id_client").val(), bon: $("#bon_key_input").val()},

				success: function (data) 
				{
				   $('#bon_commande').html(data);
				}

			});
	   }
		$("#bon_commande").change(function(){
			var id_bon = $(this).val();
			$.ajax({

				type: "POST",

				url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",

				data: {act:"changebon",id_bon: id_bon},

				dataType : 'json',

				success: function (data) 
				{
					$("#id_categorie").html(data.cat);
					$("#id_produit").html(data.prod);
				}

			});
		});

		$("#id_client").change(function(){
			var id_produit = $('#id_produit').val();
			$('#add_access').val(1);
			$.ajax({

				type: "POST",

				url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",

				data: {act:"getPrix",id_produit: id_produit,id_client: $("#id_client").val()},

				success: function (data) {
				var tab=data.split('/');

				$('#prix_produit').val(tab[0]);

					$('#reste_stock').html(tab[1]);
				}

			});

			//get bon commande list
			 $.ajax({

				type: "POST",

				url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",

				data: {act:"getCommandes", id_client: $("#id_client").val()},

				success: function (data) 
				{
				   $('#bon_commande').html(data);
				}

			});
		});


		$(".bon").click(function()
		{
			
				var $type_compte = $(this).val();
				var bonv = 0;

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

						url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",

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
                url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",
                data: {act:"getproduit",id_categorie: id_categorie, id_bon: $('#bon_commande').val()},
                success: function(data1){

                     $('#id_produit').html(data1);

					 let id_produit = $('#id_produit').val();
					 if(id_produit != null)
					 {
						 $.ajax({
								type: "POST",
								url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",
								dataType: 'json',
								data: {act:"getPrix",id_produit: id_produit, id_client: -1},
								success: function (data){
									
									var tab=data.val.split('/');
									$("#id_depot_src").html(data.depots);
									$('#prix_produit').val(tab[0]);
									$('#reste_stock').html(tab[1]);
									$('#unite2').val(tab[2]);
									$('#id_depot_src option:eq(0)').prop('selected', true);
									let id_depot = $("#id_depot_src").val();
									
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

          $("#id_produit").change(function() {

            var id_produit = $(this).val();

            $.ajax({

                type: "POST",
                url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",
				dataType: 'json',
                data: {act:"getPrix",id_produit: id_produit},

                success: function (data) 
				{
                    $("#id_depot_src").html(data.depots);
					$('#id_depot option:eq(1)').prop('selected', true);
					
					let id_depot = $("#id_depot_src").val();
					
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

			$("#id_depot_src").change(function(){

				var id_depot = $(this).val();
				var id_produit = $('#id_produit').val();
				
				$.ajax({

					type: "POST",
					url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",
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
            });

            $("#addProduct").click(function() 
			{
                $.ajax({
                    type: "POST",
                    url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",
                    data: {
                        act:"addProduct",
						id_produit: $("#id_produit").val() ,
                        qte_op: $("#qte_op").val(),
                        id_depot_src: $("#id_depot_src").val(),
                        id_depot_dest: $("#id_depot_dest").val()
					},
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
		

     $('body').on( "click",".delete", function( event ) {

            event.preventDefault();
            
                var btn = $(this);

                swal({

                 title: 'Êtes-vous sûr?',

                  text: "Voulez vous vraiment Supprimer ce ligne !",

                  type: 'warning',

                  showCancelButton: true,

                  confirmButtonColor: '#d33',

                  cancelButtonColor: '#3085d6',

                  confirmButtonText: 'Oui, Supprimer !'

                }).then((result) => {

                  if (result.value) {



                $.ajax({

                type: "POST",

                url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",

                data: {act:"deleterow",id_detail: btn.data('id')},

                success: function (data) {

                   

                   swal(

                      'Supprimer',

                      'Element a ete bien Supprimer',

                      'success'

                    ).then((result) => {




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

                url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",

                data: new FormData(form),

                dataType: 'text',  

                cache: false,

                contentType: false,

                processData: false,

                success: function (data) {

                if (data.indexOf("success")>=0) 
				{

                    swal(

                      'Ajouter',
                      'Opération a ete bien Ajouter',
                      'success'

                    ).then((result) => {

                    $.ajax({

                              method:'POST',

                              data: {ajax:true},

                              url: `<?php echo BASE_URL."views/depots/index.php"?>`,

                              context: document.body,

                              success: function(data) { 

                                      history.pushState({},"",`<?php echo BASE_URL."depots/index.php";?>` );

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

function verifierPlafond()
{
				var return_val = true;
	
	            prix = $("#prix_produit").val();
                if($("#valunite").val() != null && $("#valunite").val() != 0)
				{
                    qte_vendu= $("#valunite").val(); 
                }
				else
				{
                    qte_vendu= $("#qte_vendu").val();
                }
               
                remise = $("#remise").val();
                montantTot = 0;
                montantTotF = 0;
                let totale=parseFloat($('#detail_commande').last('tr').find('b').eq(1).html().replaceAll('', ''));

				if( $("#id_client").val()!=null){
                    $.ajax({

                    type: "POST",

                    url: "<?php echo BASE_URL.'views/depots/' ;?>controle.php",

                    data: {act:"vrefPlafond",id_client:$("#id_client").val() ,id_produit: $("#id_produit").val(),},

                    dataType: "json",

                    success: function (o) 
					{
                        if($('#numbon').val()!=null)
						{
                            if(totale!=0)
							{  
								montantTotF = o.montantTot;                              
                                var result = (qte_vendu * prix * (1 - remise/100));
                                
                                totale += parseFloat(result) + parseFloat(montantTotF);
								
                                
                            if(parseFloat(o.plafond) < totale)
							{
								$('#access_add').val(0);
								swal({
									title: 'Attention !',
									text: "Vous avez dépassé le plafond prédéfinie!",
									type: 'warning',
									confirmButtonColor: '#d33',
									cancelButtonColor: '#3085d6',
									confirmButtonText: 'Ignorer'
									});
                              }
                        }
						else
						{
							montantTotF = o.montantTot;
							
                            var result = (qte_vendu * prix * (1 - remise/100));
                                
                                totale += parseFloat(result) + parseFloat(montantTotF);

                            let a = parseFloat(eval(qte_vendu) * eval(prix) * (1 - remise/100)) + eval(montantTotF);
							
                            if(parseFloat(o.plafond)< a){
								$('#access_add').val(0);
                                swal({

                                    title: 'Attention !',

                                    text: "Vous avez dépassé le plafond prédéfinie!",

                                    type: 'warning',

                                    confirmButtonColor: '#d33',

                                    cancelButtonColor: '#3085d6',

                                    confirmButtonText: 'Ignorer'

                                    }).then((result) => {

                                    if (result.value) {

                                    }

                                    });
                            }
                        }
                        
                        }
                        },
                    error:function(error){
                        console.error( error);
                    }
                    });
                }
	return return_val;
}
</script>

