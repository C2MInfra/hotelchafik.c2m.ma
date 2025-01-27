<?php

include('../../evr.php');

include("../../model/convert.php");



$result=connexion::getConnexion()->query("select v.remarque as obj,c.*,v.date_vente,v.id_user



from vente v  left join client c on c.id_client=v.id_client where id_vente=".$_GET["id"]);



$data1=$result->fetch(PDO::FETCH_ASSOC);

$data2 = connexion::getConnexion()->query("select p.code_bar,p.tva,p.unite,p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_vendu,dv.id_vente,dv.remise from detail_vente dv left join produit p on p.id_produit=dv.id_produit  where dv.id_vente in (" . trim($_GET["id"], ',') . ")");

$rep=$data2->fetchAll(PDO::FETCH_ASSOC);

$vente=new vente();

$vente1=$vente->selectById($_GET["id"]);


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



<body style="width:500px;margin:auto;" onload="window.print()" onclick="window.print()">





<h3 align="center">Bravo Service</h3>

<h3  align="center"  style="text-decoration: none;">Rue 36 N°1-3 Hay Al Qods Sidi Bernoussi - Casablanca</h3>
<h3  align="center"  style="text-decoration: none;">Tél : 0661120103 - 0661073480 - 0661059650 - 0522735017</h3>
<h3  align="center"  style="text-decoration: none;">Client : <?php echo $data1["nom"]; ?></h3>
<h3  align="center" style="text-decoration: none;">Date : <?php echo $vente1["date_vente"]; ?></h3>

<h3  align="center" dir="rtl">Ticket de Bon de livraison N° : BL00 <?php echo $vente1["numbon"] ?></h3>


<table class="datatables"  border=1  >

  <tr class="row">

    <th width="70%"> Produit </th> 
    <th width="15%"> Qte </th> 
    <th width="15%"> Prix </th>


   </tr>

   <?php 

   foreach($rep as $ligne){

   $tot+= $ligne['prix_produit'];
   $a = ($ligne["qte_vendu"]*$ligne["prix_produit"] );
    $b = $a - ($a * ($ligne["remise"]/100));
    $c = $b * $ligne["tva"];

    $totalht+=$b;
    $totaltva+=$c;

    ?>

  <tr>

    <td  align="center"><?php echo $ligne["designation"]; ?></td>
    <td  align="center"><?php echo number_format($ligne['qte_vendu'],0,"."," "); ?></td>
    <td  align="center"><?php echo number_format($ligne['prix_produit'],2,"."," "); ?></td>

  </tr>




  <?php } ?>

  

</table>  
<table class="datatables"  border=1  >

  <tr class="row">

    <th width="85%"> Total </th> 
    <th width="15%"> <?php echo number_format($totalht + $totaltva,2,"."," "); ?> </th>


   </tr>

</table>

<br>
<br>
<br>

<h3 align="center">Merci pour votre visite</h3>

</body>
</html>

