<?php
error_reporting(E_ALL);ini_set('display_errors', 1);
include("../../evr.php");
function dateFormat($dat){
$date = new DateTime($dat);
return $date->format('d-m-Y');
}
?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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

<?php if(isset($_POST['dd']) && isset($_POST['df'])){
    $result=connexion::getConnexion()->query("SELECT u.login, u.id,SUM((prix_produit * qte_vendu) * (1-remise) / 1) 'total' FROM utilisateur u inner join vente v on u.id = v.id_user inner join detail_vente d on v.id_vente = d.id_vente where DATE_FORMAT(v.date_vente,'%Y-%m-%d') between DATE_FORMAT('".$_POST['dd']."','%Y-%m-%d') and DATE_FORMAT('".$_POST['df']."','%Y-%m-%d') and u.id = ".$_POST['comm']." group by u.id asc");
    $data=$result->fetchAll(PDO::FETCH_OBJ);
    
    $result2=connexion::getConnexion()->query("select c.nom, v.date_vente, p.designation, d.prix_produit, d.qte_vendu, d.remise,((prix_produit * qte_vendu) * (1-remise) / 1) as 'total' from client c inner join vente v on c.id_client = v.id_client inner join detail_vente d on v.id_vente = d.id_vente inner join produit p on p.id_produit = d.id_produit where DATE_FORMAT(v.date_vente,'%Y-%m-%d') between DATE_FORMAT('".$_POST['dd']."','%Y-%m-%d') and DATE_FORMAT('".$_POST['df']."','%Y-%m-%d') and v.id_user = ".$_POST['comm']." order by v.date_vente asc");
    $data2=$result2->fetchAll(PDO::FETCH_OBJ);
?>
<h3 align="center"  > Chiffre d'affaires du <?php echo dateFormat( $_POST['dd']); ?> à <?php echo dateFormat($_POST['df']); ?>.</h3>
 <fieldset><legend></legend>
<table class="datatables"  border=1  >
	<tr class="row">
		<th >Nom Commercial</th>
		<th> Chiffre d'affaires </th>
   </tr>
   <?php foreach($data as $rep ){?>
   <tr>
        <th ><?php echo $rep->login;?></th>
		<td style="text-align: center;"><?php echo number_format($rep->total,2,'.',' ');?></td>
   </tr>
   <?php } ?>
</table>  
</fieldset>

<br />
<fieldset><legend></legend>
<table class="datatables"  border=1  >
	<tr class="row">
		<th >Nom Client</th>
		<th> Date Vente </th>
		<th> Produit </th>
		<th> Prix </th>
		<th> Qte </th>
		<th> Remise </th>
		<th> Total </th>
   </tr>
   <?php foreach($data2 as $rep ){?>
   <tr>
        <th ><?php echo $rep->nom;?></th>
        <th ><?php echo $rep->date_vente;?></th>
        <th ><?php echo $rep->designation;?></th>
		<td style="text-align: center;"><?php echo number_format($rep->prix_produit,2,'.',' ');?></td>
        <th ><?php echo $rep->qte_vendu;?></th>
        <th ><?php echo $rep->remise;?></th>
		<td style="text-align: center;"><?php echo number_format($rep->total,2,'.',' ');?></td>
   </tr>
   <?php } ?>
</table>  
</fieldset>

<?php }else{ include("form_commerciaux.php"); } ?>
</body>

</html>