<?php
include("../../evr.php");
include("../../model/select.php");
?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inventaire Stock Par Categorie</title> 

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

<body style="width:1000px;margin:auto;">



<?php if(isset($_POST['id_categorie'])){ ?>

  <a href="<?php echo BASE_URL?>views/excel/stock.php?id_categorie=<?php echo $_POST['id_categorie'] ?>" target="_nlanck">Exporter Excel</a>
<h3 align="center"  >Inventaire Stock Par Categorie</h3>
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

if(isset($_POST['id_categorie']) && $_POST['id_categorie']>0 ){$resuly_cat=$categorie->selectById2($_POST['id_categorie']);}
else{$resuly_cat=$categorie->selectAll();}

$tqte_act=0;$tqte_stock=0;
$tpr_vente=0;$tpr_achat=0;
$tpr_achat_qte=0;$tpr_vente_qte=0;
foreach($resuly_cat as $rep_cat){


$qte_act=0;$qte_stock=0;
$pr_achat=0;$pr_vente=0;
$pr_achat_qte=0;$pr_vente_qte=0;
$data=$categorie->selectProduitByCategory($rep_cat->id_categorie,$_POST['dd']);
?>
<fieldset><legend><?php echo $rep_cat->nom; ?></legend>
<table class="datatables"  border=1  >
	<tr class="row">
    <th width="5%">Ref</th>
		<th width="29%">D&eacute;signation  </th> 
		<th width="12%"> QStock</th>
		<th width="7%"> P.Achat</th>
      <th width="7%"> Total</th>
   </tr>
             <?php
             $totalqte_actuel = 0;
$totalprix_achat = 0;
$totalprix_vente = 0;
			 $totaltal = 0;
			   foreach($data as $ligne){ 
			$totalqte_actuel += $ligne->qte_actuel;
      $totalprix_achat += $ligne->prix_achat;

        $totaltal += $ligne->prix_achat*$ligne->qte_actuel;
			 ?>
   	 <tr>
	          <td style="text-align: center;" > <?php echo $ligne->code_bar ; ?> </td>
            <td style="text-align: center;" > <?php echo $ligne->designation ; ?> 
            <?php
            design_ar($ligne->designation,$ligne->designation_ar)
            ?>
            </td>

            <td style="text-align: center;" > <?php echo $ligne->qte_actuel ; ?> </td>
            <td style="text-align: right;" > <?php echo $ligne->prix_achat ; ?> </td>
             <td style="text-align: right;" > <?php echo $ligne->prix_achat*$ligne->qte_actuel ; ?> </td>
            
   </tr>
   <?php } 
    ?>
    <tr bgcolor="green" style="font-weight: bold;font-size: 17px;">
            <td style="text-align: center;" colspan="2" >  Total :
            </td>

            <td style="text-align: center;" > <?php echo $totalqte_actuel ; ?> </td>
            <td style="text-align: right;" > <?php echo $totalprix_achat ; ?> </td>
             <td style="text-align: right;" > <?php echo  $totaltal ; ?> </td>
            
   </tr>
  
</table>  
</fieldset>

<?php }?>

 
<?php } else{ ?>

<fieldset class="tableform" >
<form method="post" name="form_date" >
<table width="542" border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td width="30%"><strong>Categorie : </strong></td>
    <td width="70%"> 
   <select name="id_categorie">
   <option>Tout</option>
   <?php getOptions1("select id_categorie,nom from categorie order by nom asc");?>
   </select>

   </td>
  </tr>

 
  <tr>
    <td colspan="2" align="center" >
   <input type="submit" class="button" onclick="document.form_facture.submit();" value="Afficher" >
  </td>
  </tr>
</table>
</form>
</fieldset>


<?php } ?>
</body>

</html>

