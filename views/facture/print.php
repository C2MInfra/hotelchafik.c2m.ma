<?php 
include("../model/header.php");
if(!isset($_GET["id"])){
header("Location:index.php");
}
$poids=0;$qte=0;
$societe=new societe();
$info=$societe->selectById(1);
$result=connexion::getConnexion()->query("select c.nom,c.prenom,c.telephone,v.date_vente,v.id_user,f.date_facture,u.login
 from vente v  left join client c on c.id_client=v.id_client left join utilisateur u on u.id=v.id_user left join facture f on f.id_vente=v.id_vente where v.id_vente in(".$_GET["id"]. ")");
 $data1=$result->fetch(PDO::FETCH_ASSOC);
 $data2=connexion::getConnexion()->query("select p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_vendu,dv.id_vente from detail_vente dv
 left join produit p on p.id_produit=dv.id_produit  where dv.id_vente in (".$_GET["id"].")");
 
?>

<script type="text/javascript" src="../js/script_money.js" ></script>
<style type="text/css">
<!--
.Style2 {font-size: 24px}
-->
</style>
<body style="top:0px" >
<center><table   width="900" height="343" style="margin-bottom:200"   cellpadding="0" cellspacing="0"  >
  <tr>
  <td height="155" >
  <center>
    <table width="99%"    border="1" cellpadding="0" cellspacing="0" style="border-radius:20px" >
    <tr  >
    <td height="72" style="border-radius:20 20  0  0;padding-left:50px" ><p><b><span style="color:#000000;font-size:28px;font:  Arial, Helvetica, sans-serif;text-transform:uppercase"><?php echo $info['raisonsocial']; ?></span></b> <b></b> </p>
       <?php echo $info['adresse']; ?> <br />
      <?php if(!empty($info['telephone'])){ ?>
       T&eacute;l .: <?php  echo $info['telephone']; ?>
	   <?php } ?>
	    
		<?php if(!empty($info['fax'])){ ?>
       - Fax .: <?php  echo $info['fax']; ?>
	   <?php } ?> <br />
      <span style="font-size:16px" > <span class="Style2">Facture N&deg;  : <?php echo $_GET["idf"]; ?></span> </span> &nbsp;&nbsp; <br />
&nbsp;&nbsp;</td>
	 </tr>
  
  <tr>
  <td  style="border-radius:  0  0 20 20;padding-top:10px;padding-left:10px;padding-bottom:-15px"  >
    <span >Date : <?php echo $data1["date_facture"]; ?> &nbsp;&nbsp;&nbsp;|| &nbsp;&nbsp;&nbsp; Agent : <?php echo $data1["login"]; ?> &nbsp;&nbsp;&nbsp; || &nbsp;&nbsp;&nbsp; </span>  <span style="0vertical-align:text-top">Client : &nbsp; <b> <?php echo $data1["nom"]."  ".$data1["prenom"]."<b> <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$data1["telephone"] ; ?> </span></td>
  </tr>
    </table>
	</center>
	  </td>
  </tr>  
  <tr>
    <td height="147" colspan="2" >
	<table width="99%" border="1" cellspacing="0" cellpadding="0" style="border-radius:20px;border:0;">
      <tr >
        <th height="38" bgcolor="#CCCCCC" style="border-radius:10px 0 0 0 ;" scope="col">N&deg; </th>
        <th bgcolor="#CCCCCC" scope="col">DESIGNATION</th>
        <th bgcolor="#CCCCCC" scope="col">QUANTIT&Egrave;</th>
		<th bgcolor="#CCCCCC" scope="col">Poid</th>
        <th bgcolor="#CCCCCC" scope="col">Prix Unitaire</th>
        <th bgcolor="#CCCCCC" style="border-radius: 0    10px 0  0;" scope="col"> Montant </th>
      </tr>
      <tr height="5px" >
        <td style="border:0;" height="5px" >&nbsp;</td>
        <td style="border:0;">&nbsp;</td>
        <td style="border:0;" >&nbsp;</td>
        <td style="border:0;" >&nbsp;</td>
        <td style="border:0;" >&nbsp;</td>
      </tr>
	  <?php $total=0; $i=0; foreach($data2 as $ligne){ $i++; ?>
	    <tr >
        <td  > <?php echo $i; ?> </td>
        <td style="text-align: center;" ><?php echo $ligne["designation"]."  (". $ligne["poid"] ." g )  "; ?></td>
        <td style="text-align: center;" > <?php echo $ligne["qte_vendu"];?> </td>
		 <td style="text-align: center;" > <?php echo $ligne["poid"]*$ligne["qte_vendu"];?> &nbsp; g &nbsp;</td>
        <td style="text-align: right;" ><?php echo number_format($ligne["prix_produit"],2,"."," ");?>&nbsp;&nbsp;</td>
        <td style="text-align: right;"  > <?php echo number_format($ligne["prix_produit"]*$ligne["qte_vendu"],2,"."," ");
         $total+=$ligne["prix_produit"]*$ligne["qte_vendu"]; 
		 $poids+=$ligne["poid"]*$ligne["qte_vendu"] ;
		  $qte+=$ligne["qte_vendu"] ;
		 ?> &nbsp;&nbsp;</td>
      </tr>
	  <?php } ?>
	   <tr style="" >
        <td style="border:0;" >&nbsp;</td>
        <td style="border:0;">&nbsp;</td>
        <td style="border:0;" >&nbsp;</td>
        <td style="border:0;" >&nbsp;</td>
        <td style="border:0;" >&nbsp;</td>
      </tr>
	  
	  
	   <tr >
        <td height="36" colspan="2" bgcolor="#CCCCCC" style="border-radius: 0 0 0 10px ;text-align:center" > <b><span style="font-size: 16px;" >  Total </span></b></td>
        
        <td bgcolor="#CCCCCC"   ><center><span style="font-size: 16px;" ><b> 
		<?php echo $qte; ?></span></strong></b>&nbsp;&nbsp;</td>
		
        <td bgcolor="#CCCCCC"   ><center><span style="font-size: 16px;" ><b> 
		<?php echo $poids ; ?></span></b>&nbsp;&nbsp;</center></td>
        
		<td bgcolor="#CCCCCC"   ><span style="font-size: 16px;" ><b> 
 </span></b>&nbsp;&nbsp;</td>
		
		
		<td bgcolor="#CCCCCC" style="border-radius:0  0 10 0  ;text-align: right;" ><span style="font-size: 16px;" ><b> <?php echo number_format($total,2,"."," "); ?></span></b>&nbsp;&nbsp;</td>
      </tr>
	  
    </table></td>
  </tr>
  <tr>
    <td height="10" colspan="2" >
	<p>Arr&eacute;ter la pr&eacute;sente facture en toute taxes comprise a la somme de :&nbsp;
<strong>
	<?php
	 
	 echo "<script>document.write(ConvNumberLetter_fr(". $total ."));</script>";  ?></strong>
	<br />
    </p>
   
	&nbsp; </td>
  </tr>
</table>
</center> 
</body>
