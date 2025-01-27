<?php
include("../../evr.php");
function dateFormat($dat){
$date = new DateTime($dat);

return $date->format('d-m-Y');
}
?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
 body{text-align: center;}
 </style>
 
</head>

<body style="width:950px;margin:auto;">

<?php if(isset($_POST['dd'])){ ?>
<h3 align="center"  > Etat des ventes Achats du <?php echo dateFormat( $_POST['dd']); ?> a <?php echo dateFormat($_POST['df']); ?> .</h3>

<table class="datatables"  border=1  >
	<tr class="row">
		<th width="40">  Produit </th> 
		<th width="10%"> Qte vendu</th> 
		<th width="15%"> Montant vendu  </th>
		<th width="15%"> Montant achete  </th>
	    <th width="15%"> Bénifice </th>
   </tr>

<?php  
$qv=$qa=$v=$a=0;

 $query=$result=connexion::getConnexion()->query("select prod.designation,prod.designation_ar, ifnull(d.prix_produit,0)  as achat, ifnull(vente,0)as vente ,ifnull(qte_vendu,0)as qte_vendu   from 
(select  id_produit, designation,designation_ar  from produit)as prod 
left join (SELECT p.id_produit, sum(`prix_produit`*`qte_vendu`)as 'vente',sum(qte_vendu) as 'qte_vendu' FROM `vente` v inner join detail_vente d on v.`id_vente`=d.`id_vente` inner join produit p on p.`id_produit`=d.`id_produit` where  (date_vente between '".$_POST['dd']."' and '".$_POST['df']."')  group by id_produit ) as vente on vente.id_produit=prod.id_produit
left join ( select d.id_produit,d.prix_produit from (SELECT `id_produit`,max(`id_detail`) as id_detail FROM `detail_achat` group by `id_produit`)as lastd inner join `detail_achat` d on d.id_detail=lastd.id_detail)
	  d on  d.id_produit=prod.id_produit where vente.id_produit IS NOT NULL order by vente -(qte_vendu*ifnull(d.prix_produit,0)) desc");
 $result=$query->fetchAll(PDO::FETCH_OBJ);
 foreach ($result as  $value) {
$design_ar="";
if ($value->designation==''&& $value->designation_ar!='') {
    $design_ar= $value->designation_ar;
}
if ($value->designation!=''&& $value->designation_ar!='') {
    $design_ar= "/ ".$value->designation_ar;
}

echo "<tr ";

if ($value->vente<$value->achat) echo "style='background-color:#FCB2B2'"; else 
	if($value->vente==$value->achat) echo "style='background-color:##DEECFF'";
  
echo "><td>".$value->designation.$design_ar."</td><td>" .$value->qte_vendu."</td><td>" .number_format( $value->vente,2,'.',' ') ."</td><td>".number_format( $value->achat*$value->qte_vendu,2,'.',' ') ."</td><td>" .  number_format( ($value->vente-($value->achat*$value->qte_vendu)) ,2,'.',' ')."</td></tr>";
 
 $qv +=$value->qte_vendu;
$v +=$value->vente;
$a +=$value->achat*$value->qte_vendu;
  }
?>
	<tr >
		<td colspan="6" style="padding: 10px;"></td>
   </tr>
	<tr class="row">
		<th width="40">Total </th> 
		<th width="10%"> <?php echo $qv;?> </th> 
		<th width="15%"> <?php echo number_format( $v,2,'.',' '); ?> </th>
		<th width="15%"> <?php echo number_format( $a,2,'.',' '); ?> </th>
			<th width="15%"><?php echo number_format( $v-$a,2,'.',' '); ?></th>
   </tr>
</table>  

 
<?php }else{ include("form_date.php"); } ?>
</body>

</html>