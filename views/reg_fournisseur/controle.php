<?php
include('../../evr.php');


if ($_POST['act']=='insert') 
{
	$last_achat = -1;
	try {
		
	 if ($_POST['date_validation']=='') {
   $_POST['date_validation'] = '1900-01-01';
   }
 if (empty($_POST['num_cheque'])) {
 	
   $_POST['num_cheque'] = 0;
   }

   $fournisseur = new fournisseur();
   $_POST["id_user"] =auth::user()["id"] ;
   $_POST["id_fournisseur"] = $_POST["id"];
   $reg_fournisseur = new reg_fournisseur();
   $reg_fournisseur->insert();
   $montant = $_POST["montant"];
   $montant_t = $_POST["montant"];
   if($_POST["remarque"] =="") {
      $_POST["remarque"] = "Reglement global [ Montant : " . $montant_t . "/Mode de Reglement : " . $_POST["mode_reg"] . "/Date : " . $_POST["date_reg"] . "]";
   }

  

   $list_achat = $fournisseur->selectAllachat($_POST["id"]);
   
   foreach($list_achat as $achat) {
      $list_detail_achat = $fournisseur->selectAllDetailachat($achat->id_achat);
      $montant_detail_achat = 0;
      foreach($list_detail_achat as $dv) {
         $montant_detail_achat = $montant_detail_achat + ($dv->prix_produit * $dv->qte_achete);
      }
      $list_reg_achat = $fournisseur->selectAllachatReg($achat->id_achat);
      $montant_reg_achat = 0;
      foreach($list_reg_achat as $rv) {
         $montant_reg_achat = $montant_reg_achat + $rv->montant;
      }
      if($montant_reg_achat < $montant_detail_achat) {
         $dif = $montant_detail_achat - $montant_reg_achat;
         if($montant >= $dif) {
            $_POST["id_achat"] = $achat->id_achat;
            $_POST["montant"] = $dif;
            $reg_achat = new reg_achat();
            $reg_achat->insert();
            $montant = $montant - $dif;

         } elseif($montant < $dif && $montant > 0) {
            $_POST["id_achat"] = $achat->id_achat;
            $_POST["montant"] = $montant;
            $reg_achat = new reg_achat();
            $reg_achat->insert();
            $montant = 0;
         }
      }
	  $last_achat = $achat->id_achat;
   }

		$_POST["id_achat"] = $last_achat;
        $_POST["montant"] = $montant;
        $reg_achat = new reg_achat();
        $reg_achat->insert();


		die('success');
		} catch (Exception $e) {
				die($e);
		
	}
}
elseif ($_POST['act']=='update') {
	try {
		
		$reg_fournisseur=new reg_fournisseur();
 		$reg_fournisseur->update($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
		
	}
}
elseif ($_POST['act']=='delete') 
{
	try {
		
		$reg_fournisseur=new reg_fournisseur();
		$reg_fournisseur->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}

elseif ($_POST['act']=='getetat') {
	try {
		
	$reg_fournisseur=new reg_fournisseur();
	$res=$reg_fournisseur->selectById($_POST["id"]);
		$total=0;
	$poids = 0;
	$qte = 0;
				if($_POST['etatreg_fournisseur']=="achat"){
				$result=connexion::getConnexion()->query("select concat_ws(' ',f.raison_sociale)as fournisseur ,
	a.date_achat,da.prix_reg_fournisseur,sum(da.qte_achete) as qte_achete ,da.prix_reg_fournisseur*da.qte_achete as total
	from fournisseur f left join  achat a on a.id_fournisseur=f.id_fournisseur left join detail_achat da on da.id_achat=a.id_achat where (a.date_achat between '". $_POST['dd'] ."' and '". $_POST['df'] ."')  and da.id_reg_fournisseur=". $_POST["id"] ." group by f.id_fournisseur");
				$data = $result->fetchAll(PDO::FETCH_OBJ);
				?>

			<center>
			<p><h3> Liste des achats de reg_fournisseur :</span>
			<?php echo $res["designation"]." ( ".$res["poid"] ." ) "; ?> <br />

			<b dir="rtl">
			لائحة الشراء المنتج : <?php echo $res["designation_ar"] ." ( ".$res["poid"] ." ) " ?></b></h3>
			De <?php echo $_POST['dd'] ; ?> من<br />
			Au <?php echo $_POST['df'] ; ?> الى<br />
			</p>
			<?php if (count($data)>0) {?>
			<table class="table table-dark responsive" id="example" border="1" cellspacing="0" cellpadding="0">
				 <thead class="thead-dark">
			<tr>
			<th scope="col">Fournisseur /المورد</th>
			<th scope="col">Date / التاريخ</th>
			<th scope="col">Prix Achat / ثمن الشراء</th>
			<th scope="col">Poid / الوزن</th>
			<th scope="col">Qte Achete /الكمية المشتراة</th>
			<th scope="col">Total /المجموع</th>

			</tr>
		</thead>
			<?php
			foreach($data as $ligne){ ?>
			<tr>
			<td> <?php echo $ligne->fournisseur; ?> </td>
			<td> <?php echo $ligne->date_achat; ?> </td>
			<td> <?php echo number_format($ligne->prix_reg_fournisseur,2,"."," "); ?> &nbsp;&nbsp;</td>
			<td> <?php echo $ligne->qte_achete*$res["poid"];$poids+=$ligne->qte_achete*$res["poid"]; ?> g  &nbsp;&nbsp;&nbsp;&nbsp; </td>
			<td><?php echo $ligne->qte_achete;$qte+=$ligne->qte_achete; ?> &nbsp;&nbsp;&nbsp;&nbsp; </td>
			<td> <?php echo number_format($ligne->total,2,"."," ");
				$total+=$ligne->total;
			?> &nbsp;&nbsp;&nbsp;&nbsp;</td>
			</td>
			</tr>
			<?php } ?> 
			</table>
			<table class="table table-dark responsive"  border="1" cellspacing="0" cellpadding="0">
				
			<tr>
			<td colspan="4" ><center>  Total / المجموع </center></td>
			<td> <?php echo $poids; ?>  g &nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td> <?php echo $qte; ?> &nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td> <?php echo number_format($total,2,"."," "); ?>&nbsp;&nbsp;&nbsp;&nbsp; </td>

			</tr>
		</table>
			<?php  } else {  ?>

				 <div class="alert alert-primary" role="alert">
                      No Data
                 </div>

			<?php }}else{
			$result=connexion::getConnexion()->query("select concat_ws(' ',c.nom,c.prenom)as fournisseur ,
			v.date_achat,dv.prix_reg_fournisseur,sum(dv.qte_achete) as qte_achete ,dv.prix_reg_fournisseur*dv.qte_achete as total
			from fournisseur c left join  achat v on v.id_fournisseur=c.id_fournisseur left join detail_achat dv on dv.id_achat=v.id_achat where (v.date_achat between '". $_POST['dd'] ."' and '". $_POST['df'] ."')  and dv.id_reg_fournisseur=". $_POST["id"] ." group by c.id_fournisseur");
			$data = $result->fetchAll(PDO::FETCH_OBJ);
		
			?>
			<center>
			<p><h3>Liste des achat de reg_fournisseur :</span>
			<?php echo $res["designation"] ." ( ".$res["poid"] ." ) " ?>  <br>
			<b dir="rtl">
			لائحة بيع المنتج : <?php echo $res["designation_ar"] ." ( ".$res["poid"] ." ) " ?></b>
			</h3>
			De <?php echo $_POST['dd'] ; ?> من<br />
			Au  <?php echo $_POST['df'] ; ?> الى<br />
			</p>

			<?php  if (count($data)>0) {?>
			<table class="table"  id="example" border="1" cellspacing="0" cellpadding="0">
				 <thead class="thead-dark">
			<tr>
			<th  scope="col">fournisseur / المنتوج</th>
			<th  scope="col">Date / التاريخ</th>
			<th  scope="col">Prix achat /ثمن البيع </th>
			<th  scope="col">Poid / الوزن</th>
			<th  scope="col">Qte Vendu /الكمية المباعة</th>
			<th  scope="col">Total /المجموع</th>
			</tr>
		</thead>
			<?php

	$total=0;
	$poids = 0;
	$qte = 0;
		foreach($data as $ligne){

	$total+=(int)$ligne->total;
			$qte+=(int)$ligne->qte_achete; 
				$poids+=(int)$ligne->qte_achete*(int)$res["poid"];
	 ?>
			<tr>
			<td> <?php echo $ligne->fournisseur; ?> </td>
			<td> <?php echo $ligne->date_achat; ?> </td>
			<td> <?php echo number_format($ligne->prix_reg_fournisseur,2,"."," "); ?>&nbsp;&nbsp;</td>
			<td ><?php echo $ligne->qte_achete*$res["poid"]; ?> &nbsp;&nbsp; </td>
			<td><?php echo $ligne->qte_achete;?> &nbsp;&nbsp; </td>
			<td> <?php echo number_format($ligne->total,2,"."," "); ?> &nbsp;&nbsp;</td>
			</tr  >
			<?php } ?>
		
			</table>
			<table class="table table-dark responsive"  border="1" cellspacing="0" cellpadding="0">
				<tr style="font-weight:bold" >
			<td colspan="3" ><center>  Total / المجموع</center></td>
			<td> <?php echo $poids; ?> &nbsp;&nbsp;</td>

			<td> <?php echo $qte; ?> &nbsp;&nbsp;</td>
			<td> <?php echo number_format($total,2,"."," "); ?> &nbsp;&nbsp;</td>
			</tr>
			</table>
			<?php } else {  ?>

				<div class="alert alert-primary" role="alert">
                               No Data
                            </div>

			<?php }} ?>


	<script type="text/javascript">
	 $(document).ready(function () {
	$('#example').DataTable( {
		ordering: false,
		pageLength: 6,
            language: {
                paginate: {
                    previous: "<i class='simple-icon-arrow-left'></i>",
                    next: "<i class='simple-icon-arrow-right'></i>"
                }
            },
            drawCallback: function() {
                $($(".dataTables_wrapper .pagination li:first-of-type")).find("a").addClass("prev"), $($(".dataTables_wrapper .pagination li:last-of-type")).find("a").addClass("next"), $(".dataTables_wrapper .pagination").addClass("pagination-sm")
            },
		    searching: !1,
            bLengthChange: !1,
            destroy: !0,
            info: !1,
            dom: 'Bfrtip',
            buttons: ["csv","pdf","excel"],
                                });

	});
	</script>
	<?php				
						
						} catch (Exception $e) {
								die($e);
					}
}


?>