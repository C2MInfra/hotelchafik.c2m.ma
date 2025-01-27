<?php
include("../../evr.php");
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
 .montant{text-align:center;}
 </style>
 
</head>

<body style="width:950px;margin:auto;">

<?php if(isset($_POST['dd'])){ ?>
<?php $res=connexion::getConnexion()->query("
SELECT p.code_bar,p.designation,dt.qte_vendu,c.nom,v.date_vente FROM produit p,detail_vente dt,client c,vente v
WHERE p.id_produit=dt.id_produit 
AND dt.id_vente=v.id_vente
AND v.id_client=c.id_client
AND date_vente BETWEEN '". $_POST['dd'] ."' and '".$_POST['df']."'"
)->fetchAll(PDO::FETCH_ASSOC); ?>
    <br> <br> <br>
    <table class="datatables"  border=1  >
    <thead>
        <tr class="row">		
            <th width="20%"> Référence </th> 
            <th width="20%"> Destination  </th> 
            <th width="20%"> Quantité  </th>
            <th width="20%"> Client  </th>
            <th width="20%"> Date </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($res as $rep ){ ?>
            <tr>
                <th width="20%" ><?php echo $rep['code_bar']?></th>
                <td width="20%" align="center"><?php echo $rep['designation'];?></td>
                <td width="20%" class="montant"><?php echo number_format($rep['qte_vendu'],2,'.',' ');?></td>
                <td width="20%" class="montant"><?php echo $rep['nom'];?></td>
                <td width="20%" class="montant"><?php echo $rep['date_vente'];?></td>
            </tr>
        <?php } ?>
    </tbody>
        
    </table>

<?php }else{ include("form_date.php"); } ?>

</body>

</html>