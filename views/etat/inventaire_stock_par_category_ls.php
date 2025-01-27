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
 .row
 {
	background-color: #008acc;
    color: white;
 }
 td, th 
 {
    padding: 6px 10px;
 }
  .montant
  {
	 text-align:right;
  }
  h3
  {
		border: 2px solid black;
		padding: 6px;
		color: black;
		text-decoration: none;
   }
  .footer
  {
		 background: #dbeef7;
  }
  .header
  {
        margin-bottom: 6px;
		color: #666;
		font-size: 11pt;	  
  }
 </style>
 
</head>

<body style="width:1000px;margin:auto;">



<?php if(isset($_POST['dd'])){ ?>
<h3 align="center"  >Inventaire Stock Par Categorie DU <?php echo $_POST['dd'];?><br>
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

if(isset($_POST['id_categorie']) && $_POST['id_categorie']>0 )
{
	$resuly_cat=$categorie->selectById2($_POST['id_categorie']);
}
else
{
	$resuly_cat=$categorie->selectAll();
}
							  
$tqte_act=0;$tqte_stock=0;
$tpr_vente=0;$tpr_achat=0;
$tpr_achat_qte=0;$tpr_vente_qte=0;
							  
foreach($resuly_cat as $rep_cat)
{


$qte_act=0;$qte_stock=0;
$pr_achat=0;$pr_vente=0;
$pr_achat_qte=0;$pr_vente_qte=0;
$data=$categorie->selectProduitBtCategory($rep_cat->id_categorie,$_POST['dd']);
?>
<h4 class="header"><?php echo $rep_cat->nom; ?></h4>
<table class="datatables"  border=1  >
	<tr class="row">
    <th width="5%">Ref</th>
		<th width="29%">D&eacute;signation</th> 
		<th width="16%"> Poid (kg)</th> 
		<th width="12%"> QStock(U)</th>
		<th width="11%"> QStock(kg)</th>
		<th width="7%"> P.Achat</th>
		<th width="7%"> P.Vente</th>
    <th width="7%"> P.A*U</th>
    <th width="7%"> P.V*U</th>
		<th width="13%"> Emplacement </th>
   </tr>
             <?php
			 $produit=new produit();
			 foreach($data as $ligne){ 
			 $res_vente=$produit->selectQteVenteParProduit($ligne->id_produit,$_POST['dd']);
			 $res_achat=$produit->selectQteAchatParProduit($ligne->id_produit,$_POST['dd']);
			 
			 if($_POST['dd']<date("Y-m-d")){
			 $ligne->qte_actuel=($ligne->qte_actuel+$res_vente->vente)-($res_achat->achat);
			 }	 
			 $qte_act+=$ligne->qte_actuel;
			 $qte_stock+=$ligne->qte_actuel*$ligne->poid;
       $pr_achat+=$ligne->prix_achat;
       $pr_vente+=$ligne->prix_vente;
       $pr_achat_qte+=$ligne->prix_achat*$ligne->qte_actuel ;
       $pr_vente_qte+=$ligne->prix_vente*$ligne->qte_actuel ;
			 
			 ?>
   	 <tr>
	          <td style="text-align: center;" > <?php echo $ligne->code_bar ; ?> </td>
            <td style="text-align: center;" > <?php echo $ligne->designation ; ?> 
            <?php
            design_ar($ligne->designation,$ligne->designation_ar)
            ?>
            </td>
            <td style="text-align: center;" > <?php echo $ligne->poid ; ?> </td>
            <td style="text-align: center;" > <?php echo $ligne->qte_actuel ; ?> </td>
		      	<td style="text-align: center;" > <?php echo number_format($ligne->qte_actuel*$ligne->poid,2,'.',' ') ; ?> </td>
            <td style="text-align: right;" > <?php echo $ligne->prix_achat ; ?> </td>
            <td style="text-align: right;" > <?php echo $ligne->prix_vente ; ?> </td>
            <td style="text-align: right;" > <?php echo $ligne->prix_achat*$ligne->qte_actuel ; ?> </td>
            <td style="text-align: right;" > <?php echo $ligne->prix_vente*$ligne->qte_actuel ; ?> </td>
            <td style="text-align: center;" > <?php echo $ligne->emplacement ; ?> </td>
            
   </tr>
   <?php } 
   $tpr_vente+=$pr_vente;
   $tpr_achat+=$pr_achat; 
   $tqte_act+=$qte_act;
   $tqte_stock+=$qte_stock;
   $tpr_achat_qte+=$pr_achat_qte;
   $tpr_vente_qte+=$pr_vente_qte;
    ?>
   
    <tr class="footer">
            <td style="text-align: center;" colspan="3" > Total</td>
            <td style="text-align: center;" > <strong><?php echo number_format($qte_act,2,'.',' ') ; ?></strong> </td>
			<td style="text-align: center;" > <strong><?php echo number_format($qte_stock,2,'.',' ') ; ?></strong> 
      </td>
            <td style="text-align: center;" > <strong><?php echo number_format($pr_achat,2,'.',' ') ; ?></strong></td>
            <td style="text-align: center;" > <strong><?php echo number_format($pr_vente,2,'.',' ') ; ?></strong></td>
            <td style="text-align: center;" ><?php echo number_format($pr_achat_qte,2,'.',' ') ; ?></td>
            <td style="text-align: center;" ><?php echo number_format($pr_vente_qte,2,'.',' ') ; ?></td>
            <td style="text-align: center;" > </td>          
   </tr>
</table>  

<?php }?>
<fieldset><legend>Total</legend>
<table class="datatables"  border=1  >
    <tr bgcolor="#666666">
            <td width="42.5%" style="text-align: center;" colspan="3" > Total</td>
            <td width="10.5%" style="text-align: center;" > <strong><?php echo number_format($tqte_act,2,'.',' ') ; ?></strong> </td>
			<td width="10%" style="text-align: center;" > <strong><?php echo number_format($tqte_stock,2,'.',' ') ; ?></strong> </td>
            <td width="7%" style="text-align: center;" > <strong><?php echo number_format($tpr_achat,2,'.',' ') ; ?></strong></td>
            <td width="7%" style="text-align: center;" > <strong><?php echo number_format($tpr_vente,2,'.',' ') ; ?></strong></td>
            <td width="6.5%" style="text-align: center;" > <strong><?php echo number_format($tpr_achat_qte,2,'.',' ') ; ?></strong></td>
            <td width="7%" style="text-align: center;" > <strong><?php echo number_format($tpr_vente_qte,2,'.',' ') ; ?></strong></td>
            <td width="13%" style="text-align: center;" >&nbsp; </td>
 
   </tr>
</table> 
 </fieldset>
 
<?php } else{  include("form_inventaire.php"); } ?>
</body>

</html>