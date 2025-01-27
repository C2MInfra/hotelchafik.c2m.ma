<?php
include("../../evr.php");
?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
<h3 align="center"  > Etat des achats du <?php echo $_POST['dd']; ?> a <?php echo $_POST['df']; ?> .</h3>
 <fieldset><legend></legend>
<table class="datatables"  border=1  >
	<tr class="row">		
		<th width="20%"> Num Facture </th> 
		<th width="20%"> Date  </th> 
		<th width="20%"> Montant  </th>
		<th width="20%"> Avance  </th>
		<th width="20%"> Reste </th>
   </tr>
</table>  
</fieldset>
<?php  

$fournisseur=new fournisseur();
if(isset($_GET['id_fournisseur'])){$data_fournisseur=$fournisseur->selectById2($_GET['id_fournisseur']);}
else{$data_fournisseur=$fournisseur->selectAll_($_POST['dd'],$_POST['df']);}

$total_montant=0;
$total_avance=0;
foreach($data_fournisseur as $rep_fournisseur ){
$achat=new achat();
$data=$achat->selectAllDate($rep_fournisseur->id_fournisseur,$_POST['dd'],$_POST['df']); 
 ?>
 

 <fieldset><legend> <strong><?php echo $rep_fournisseur->raison_sociale;  ?> ... </strong></legend>
<table class="datatables"  border=1  >
 <?php 
 $montant=0; $avance=0; 
 foreach($data as $rep ){ 
 $montant+=$rep->montant;
 $query=$result=connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_achat where id_achat=".$rep->id_achat);
 $result=$query->fetch(PDO::FETCH_OBJ);
 $avance+=$result->paye;
 ?>
   <tr>
        <th width="20%" ><?php echo $rep->id_achat;?></th>
		<td width="20%" align="center"><?php echo $rep->date_achat;?></td>
		<td width="20%" class="montant"><?php echo number_format($rep->montant,2,'.',' ');?></td>
		<td width="20%" class="montant"><?php echo number_format($result->paye,2,'.',' ');?></td>
		<td width="20%" class="montant"><?php echo number_format($rep->montant-$result->paye,2,'.',' ');?></td>
   </tr>
   <?php } ?>
   <tr class="row">
		<th  colspan="2">Total </th> 
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
		<th  width="40%" colspan="2">Total G&eacute;n&eacute;rale </th> 
		<th  width="20%" class="montant"> <?php echo number_format($total_montant,2,'.',' ');?></th>
		<th  width="20%" class="montant"> <?php echo number_format($total_avance,2,'.',' ');?> </th>
		<th  width="20%" class="montant"> <?php echo number_format($total_montant-$total_avance,2,'.',' ');?> </th>
   </tr>
</table>
 </fieldset>
 
<?php }else{ include("form_date.php"); } ?>

</body>

</html>