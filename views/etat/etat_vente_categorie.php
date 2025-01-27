<?php
include("../../evr.php");
include("../../model/select.php");
?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<h3 align="center"  > Etat des ventes du <?php echo $_POST['dd']; ?> a <?php echo $_POST['df']; ?>
</h3>

<?php  
function design_ar($design,$design_ar){
  if ($design==''&& $design_ar!='') {
    echo $design_ar;
  }
  if ($design!=''&& $design_ar!='') {
    echo "/ ".$design_ar;
  }
}
$categorie=new categorie();
if(isset($_POST['id_categorie']) && $_POST['id_categorie']>0 ){$data_categorie=$categorie->selectById2($_POST['id_categorie']);}
else{$data_categorie=$categorie->selectAll();}
$total=0;
foreach($data_categorie as $rep_categorie){

?>
<fieldset>
<legend> <strong><?php echo $rep_categorie->nom;  ?> ...</strong></legend>
<table class="datatables"  border=1  >
	<tr class="row">
		<th width="50%"> Designation  </th> 
		<th width="25%"> Qte Vendu  </th> 
		<th width="25%"> Montant Total  </th>
   </tr> 

<?php 
$result=$categorie->selectVenteByCategorie($rep_categorie->id_categorie,$_POST['dd'],$_POST['df']);
$montant=0;
//var_dump($result);
foreach($result as $rep){ 
$montant+=$rep->total;
?>
 <tr>
        <th><?php echo $rep->designation;?>
        	<?php design_ar($rep->designation,$rep->designation_ar);?>
        </th>
		<td  class="montant"><?php echo $rep->qte;?></td>
		<td  class="montant"><?php echo number_format($rep->total,2,'.',' ');?></td>
 </tr>
<?php } ?>
<tr class="row">
		<th  colspan="2">Total </th> 
		<th class="montant"> <?php echo number_format($montant,2,'.',' ');?> </th>
</tr>

</table>
</fieldset>
 
<?php $total+=$montant;} ?>
<br /><br />
<fieldset>
<table class="datatables"  border=1  >
 <tr class="row">
		<th  colspan="2" width="75%">Total Generale  </th> 
		<th class="montant"> <?php echo number_format($total,2,'.',' ');?> </th>
</tr>

</table>

</fieldset> 
<?php }else{ include("form_date_categorie.php"); } ?>

</body>

</html>