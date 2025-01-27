<?php
include("../../evr.php");
function dateFormat($dat){
$date = new DateTime($dat);

return $date->format('d-m-Y');
}
function design_ar($design,$design_ar){
  if ($design==''&& $design_ar!='') {
    echo $design_ar;
  }
  if ($design!=''&& $design_ar!='') {
    echo "/ ".$design_ar;
  }
}
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<link rel = "icon" href ="<?php echo BASE_URL . 'asset/img/icon.png' ?>"
        type = "image/x-icon">	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title> 

 
 <style type="text/css">
 .tableform{background-color:#999999; width:400px; margin:150px auto; }
 .inputText{height:22px; width:80%; border-radius:3px;margin-top:10px;}
 .button{height:25px; width:30%; border-radius:3px;margin-top:10px; font-weight:bold;}
 .button:hover{color:#666666; cursor:pointer;}
 h3{text-decoration:underline;text-transform:uppercase;}
 .datatables{border-collapse:collapse; width:100%;}
 .row{background-color:#CCCCCC;}
 .montant{text-align:right;}
 </style>
 
</head>

<body style="width:950px;margin:auto;">

<?php if(isset($_POST['dd'])){ ?>
<h3 align="center"  > Etat des ventes du <?php echo dateFormat( $_POST['dd']); ?> a <?php echo dateFormat($_POST['df']); ?> .</h3>
 <fieldset><legend></legend>
<table class="datatables"  border=1  >
	<tr class="row">
		<th width="16%"> Num Facture</th> 
		<th width="16%"> Date </th> 
<!--        <th width="17">THT</th>-->
        <th width="17">N°Bon</th>
		<th width="17%"> TTC </th>
		<th width="17%"> Avance </th>
		<th width="16%"> Reste</th>
   </tr>
</table>  
</fieldset>
<?php  
$eleveurs=new client();
if(isset($_GET['id_client'])){$data_eleveurs=$eleveurs->selectById2($_GET['id_client']);}
else{$data_eleveurs=$eleveurs->selectAll2_($_POST['dd'],$_POST['df']);
// var_dump($data_eleveurs);die();
}
$total_montant=0;
$total_tht = 0;
$total_avance=0;
foreach($data_eleveurs as $rep_eleveurs ){
$vente=new vente();
$data=$vente->selectAllDate2($rep_eleveurs->id_client,$_POST['dd'],$_POST['df']); 

 ?>
 <fieldset><legend> <strong><?php echo $rep_eleveurs->nom." ".$rep_eleveurs->prenom;  ?><?php design_ar($rep_eleveurs->nom." ".$rep_eleveurs->prenom,$rep_eleveurs->nom_prenom_ar);?> </strong> </legend>
<table class="datatables"  border=1 >

 <?php 
 $montant=0; $avance=0; $tht = 0;
 foreach($data as $rep ){ 
	 if(!empty($rep->motunitv) || $rep->motunitv!=0){
		$montant+=($rep->motunitv);
		$montv= $rep->motunitv;
	 }else{
		$montant+=($rep->montantv); 
		$montv= $rep->montantv;
	 }
	 
 $tht+=$rep->tht;
 $total_tht += $rep->tht;
//  $query=$result=connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_vente reg, vente v where reg.id_vente=".$rep->id_vente."and  reg.id_vente= v.id_vente and v.numbon <> 0");
 $query=$result=connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_vente where id_vente=".$rep->id_vente);
 
$result=$query->fetch(PDO::FETCH_OBJ);
 $avance+=$result->paye;
 ?>
   <tr>
		<th width="16%" ><?php echo $rep->id_vente;?></th>
		<td width="16%" align="center"><?php echo $rep->date_vente;?></td>
<!--		<td width="17%" class="montant"><?php echo number_format(($montv/1.2),2,'.',' ');?></td>-->
	    <td width="17%" align="center"> <?php echo $rep->numbon; ?></td>
		<td width="17%" class="montant"><?php echo number_format($montv,2,'.',' ');?></td>
		<td width="17%" class="montant"><?php echo number_format($result->paye,2,'.',' ');?></td>
		<td width="16%" class="montant"><?php echo number_format(($montv-$result->paye),2,'.',' ');?></td>
   </tr>
   <?php } ?>
   <tr class="row">
		<th  colspan="2">Total </th> 
		<th class="montant"></th>
<!--		<th class="montant"> <?php echo number_format(($montant/1.2),2,'.',' ');?></th>-->
		
		<th class="montant"> <?php echo number_format($montant,2,'.',' ');?></th>
		<th class="montant"> <?php echo number_format($avance,2,'.',' ');?> </th>
		<th class="montant"> <?php echo number_format($montant-$avance,2,'.',' ');?> </th>
   </tr>
</table>
</fieldset>
<br />
<?php   
$total_montant+=$montant;
$total_avance+=$avance;
 } ?>
 <fieldset><legend> Total G&eacute;n&eacute;rale </legend>
<table class="datatables"  border=1  > 
    <tr class="row">
		<th  width="32%" colspan="2">Total G&eacute;n&eacute;rale </th> 
		<th  width="17%" class="montant"> <?php echo number_format(($total_montant/1.2),2,'.',' ');?></th>
		<th  width="17%" class="montant"> <?php echo number_format($total_montant,2,'.',' ');?></th>
		<th  width="17%" class="montant"> <?php echo number_format($total_avance,2,'.',' ');?> </th>
		<th  width="17%" class="montant"> <?php echo number_format($total_montant-$total_avance,2,'.',' ');?> </th>
   </tr>
</table>
 </fieldset>
 
<?php }else{ include("form_date.php"); } ?>
</body>

</html>