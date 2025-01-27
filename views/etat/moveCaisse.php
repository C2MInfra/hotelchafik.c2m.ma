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
<?php 
	$boncommande = new boncommande();
	$totalAutre = 0; 
	if(isset($_POST['dd']))
	{ 

		$charge=connexion::getConnexion()->query("
		select * from charge
		where date_charge BETWEEN '". $_POST['dd'] ."' and '".$_POST['df']."'"
		)->fetchAll(PDO::FETCH_ASSOC); 
		
		$caisse=connexion::getConnexion()->query("
		select * from caisse
		where date_caisse BETWEEN '". $_POST['dd'] ."' and '".$_POST['df']."'"
		)->fetchAll(PDO::FETCH_ASSOC); 
		
		$regcli=connexion::getConnexion()->query("
		SELECT r.*,c.*,v.*, SUM(r.montant) As montantr  FROM client c,reg_vente r,vente v
		WHERE
		r.id_vente = v.id_vente
		AND
		v.id_client = c.id_client
		and
		date_reg BETWEEN '". $_POST['dd'] ."' and '".$_POST['df']."'
		and numbon <> 0
		GROUP BY numbon, date_reg, mode_reg
		order by numbon Desc
		"
		)->fetchAll(PDO::FETCH_ASSOC);
		
		$regfor=connexion::getConnexion()->query("
		SELECT r.*,f.*,a.*  FROM fournisseur f,reg_achat r,achat a
		WHERE
		r.id_achat=a.id_achat
		AND
		a.id_fournisseur=f.id_fournisseur
		AND
		date_reg BETWEEN '". $_POST['dd'] ."' and '".$_POST['df']."'
		order by date_reg asc
		")->fetchAll(PDO::FETCH_ASSOC); 
		
		$regbons = $boncommande->regelements($_POST['dd'], $_POST['df']);
?>

<center><h1 style="border-radius: 6px;	box-shadow: 5px 20px 25px -5px rgba(0, 0, 0, 0.1), 5px 10px 10px -5px rgba(0, 0, 0, 0.04); padding: 25px;"><span style="text-decoration: underline;">Mouvement Caisse</span></h1></center>
    <br> <br>
    <center>
    <div style="  flex: 1;" >
    <h3>
    <span style="margin-right: 10px; text-decoration: none;"><b>Du</b> <?php echo  $_POST['dd']?></span>
    <span style="margin-right: 10px; text-decoration: none;"><b>Au</b> <?php echo  $_POST['df']?></span>
    </h3>
  
    </div>
    </center>
    
    <br> 
    <div >
        <center>
        <h2 style="margin: 25px; box-shadow: 0 4px 6px -1px rgba(205, 24, 121, 1), 0px 10px 10px -5px rgba(205, 24, 121, 1); color: rgba(205, 24, 121, 1);">ENTREES CAISSE</h2>
        </center>
    </div>
    <br> 
    <h2>Alimentation Caisse</h2>
    <table class="datatables"  border=1  >
    <thead>
        <tr class="row">		
            <th width="25%"> Date </th> 
            <th width="20%"> N° Chèque  </th> 
            <th width="25%"> Banque Société  </th>
            <th width="20%"> Montant  </th>
            
        </tr>
    </thead>
    <tbody>
    <?php 
    $tot2=0;
    foreach($caisse as $caiss){
        if($caiss['type_reg']=='crédit'){
        ?>
            <tr>
                <td align="center"><?php echo $caiss['date_caisse']?></td>
                <td align="center"><?php echo $caiss['remarque']?></td>
                <td class="montant"><?php echo $caiss['designation']?></td>
                <td class="montant"><?php echo number_format($caiss['montant'],2,'.',' ');?></td>
            </tr>
    <?php 
$tot2+=$caiss['montant'];
        }
    }?>
        <tr >
            <td style="border: none;" align=""></td>
            <td style="border: none;"  align=""></td>
            <td style="border: none;"  class=""></td>
            <td style="border-left: solid 1px ;"class="montant"><?php echo number_format($tot2,2,'.',' ');?></td>
        </tr>
    </tbody>
        
    </table>
     
    <h2>Règlements Clients</h2>
    <table class="datatables"  border=1  >
        <thead>
            <tr class="row">		
                <th width="20%"> Date </th> 
                <th width="25%"> Client  </th>
                <th width="10%"> N° BL  </th>
                <th width="20%"> Type Règlement  </th>
                <th width="20%"> Montant  </th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $regclitot=0;
            $cliecp=0;
            $clinoecp=0;
            foreach($regcli as $regclis){?>
            <tr>
                <td align="center"  ><?php echo $regclis['date_reg'] ?></td>
                <td  align="center"><?php echo $regclis['nom'] ?></td>
                <td  class="montant"><?php echo $regclis['numbon'] ?></td>
                <td  class="montant"><?php echo $regclis['mode_reg'] ?></td>
                <td  class="montant"><?php echo number_format($regclis['montantr'],2,'.',' '); ?></td>
            </tr>
            <?php 
                if($regclis['mode_reg']=='Espece' ){
                    $cliecp+=$regclis['montantr'];
                }else{
                    $clinoecp+=$regclis['montantr'];
                }
            $regclitot+=$regclis['montantr'];
            }$totentrer=$regclitot+$tot2; ?>
            <tr> 
                <td style="border: none;" align=""></td>
                <td style="border: none;"  class=""></td>
                <td style="border: none;"  class=""></td>
                <td style="border: none;"  class=""></td>
                <td style="border-left: solid 1px ;"class="montant"><?php echo number_format( $regclitot,2,'.',' ');?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <h2>Règlements bon Commandes</h2>
    <table class="datatables"  border=1  >
        <thead>
            <tr class="row">		
                <th width="20%"> Date </th> 
                <th width="25%"> Client  </th>
                <th width="10%"> N° Bon  </th>
                <th width="20%"> Type Règlement  </th>
                <th width="20%"> Montant  </th>
            </tr>
        </thead>
        <tbody>
            <?php 
		    $bon_total = 0;
            $bon_esp = 0;
            $bon_autre = 0;
            foreach($regbons as $regbon){?>
            <tr>
                <td align="center"  ><?php echo $regbon['date_reg'] ?></td>
                <td  align="center"><?php echo $regbon['nom'] ?></td>
                <td  class="montant"><?php echo $regbon['id_bon'] ?></td>
                <td  class="montant"><?php echo $regbon['mode_reg'] ?></td>
                <td  class="montant"><?php echo number_format($regbon['montant'],2,'.',' '); ?></td>
            </tr>
            <?php 
                if($regbon['mode_reg']=='Espece' )
				{
                     $bon_esp += $regbon['montant'];
                }
			    else
				{
                    $bon_autre += $regbon['montant'];
                }
										 
              $bon_total += $regbon['montant'];
            }
		
		     $totentrer = $regclitot + $tot2 + $bon_total; 
			?>
            <tr> 
                <td style="border: none;" align=""></td>
                <td style="border: none;"  class=""></td>
                <td style="border: none;"  class=""></td>
                <td style="border: none;"  class=""></td>
                <td style="border-left: solid 1px ;"class="montant"><?php echo number_format( $bon_total,2,'.',' ');?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table width='100%' style="border-collapse: collapse;"  border=1  >
    <tr>
        <th style="background-color:#bdbdbd ;">Total Espece</th>
        <td align="center"><?php echo  number_format( $cliecp + $tot2 + $bon_esp,2,'.',' ');?></td>
    </tr>
    <tr>
        <th style="background-color:#bdbdbd ;">Total Autre (Cheque, Virement, Effet)</th>
        <td align="center"><?php echo number_format( $clinoecp + $bon_autre,2,'.',' '); ?></td>
    </tr>
    <?php
	  $totalAutre += $fournonesp;
	?>
    </table > 
    <br>
    <div >
        <center>
        <h2 style="margin: 25px; box-shadow: 0 4px 6px -1px rgba(255, 105, 36, 1), 0px 10px 10px -5px rgba(255, 105, 36, 1); color: rgba(205, 24, 121, 1);">SORTIES CAISSE</h2>
        </center>
    </div>
    <br>
<!--
    <h2>Débitassions Caisse</h2>
     
    <table class="datatables"  border=1  >
    <thead>
        <tr class="row">		
            <th width="25%"> Date </th> 
            <th width="20%"> N° Chèque  </th> 
            <th width="25%"> Banque Société  </th>
            <th width="20%"> Montant  </th>
            
        </tr>
    </thead>
    <tbody>
    <?php 
    $totdb=0;
    foreach($caisse as $caiss){
        if($caiss['type_reg']=='débit'){
        ?>
            <tr>
                <td align="center"><?php echo $caiss['date_caisse']?></td>
                <td align="center"><?php echo $caiss['remarque']?></td>
                <td class="montant"><?php echo $caiss['designation']?></td>
                <td class="montant"><?php echo number_format($caiss['montant'],2,'.',' ');?></td>
            </tr>
    <?php 
$totdb+=$caiss['montant'];
        }
    }?>
        <tr >
            <td style="border: none;" align=""></td>
            <td style="border: none;"  align=""></td>
            <td style="border: none;"  class=""></td>
            <td style="border-left: solid 1px ;"class="montant"><?php echo number_format($totdb,2,'.',' ');?></td>
        </tr>
    </tbody>
        
    </table>
-->
    <br>
    <h2>Charges</h2>
    <table class="datatables"  border=1  >
    <thead>
        <tr class="row">		
            <th width="25%"> Date </th> 
            <th width="25%"> Descriptions </th> 
            <th width="25%"> Type Règlement </th> 
            <th width="22%"> Montant  </th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $tot1=0;
		$ttfor=0;
		$fournesp=0;
		$fournonesp=0;
        foreach($charge as $rep ){ ?>
            <tr>
                <td align="center" ><b><?php echo $rep['date_charge'] ?></b></td>
                <td  align="center"><b><?php echo $rep['mode_reg'] ?></b></td>
                <td  align="center"><b><?php echo $rep['designation'] ?></b></td>
                <td  class="montant"><?php echo  number_format( $rep['montant'],2,'.',' '); ?></td>
            </tr>
        <?php 
       $tot1+= $rep['montant'];
			if($rep['mode_reg']=='Espece'){
				$fournesp+=$rep['montant'];
			}else{
				$fournonesp+=$rep['montant'];
			}
        } ?>
        <tr >
           
            <td style="border: none;"  align=""></td>
            <td style="border: none;"  class=""></td>
            <td style="border: none;"  class=""></td>
            <td style="border-left: solid 1px ;"class="montant"><?php echo  number_format($tot1,2,'.',' ');?></td>
        </tr>
    </tbody>
        
    </table>
    <br>
    <h2>Règlements Fournisseurs</h2>
    <table class="datatables"  border=1  >
    <thead>
        <tr class="row">		
            <th width="20%"> Date </th> 
            <th width="20%"> Fournisseur</th>
            <th width="20%"> N° BC</th>
            <th width="20%"> Type Règlement  </th>
            <th width="20%"> Montant  </th>
            
        </tr>
    </thead>
    <tbody>
    <?php 
    
	
    foreach($regfor as $regfors){?>
        <tr>
            <td  align="center"><?php echo $regfors['date_reg']?></td>
            <td  align="center"><?php echo $regfors['raison_sociale']?></td>
            <td  align="center"><?php echo $regfors['id_reg']?></td>
            <td  align="center"><?php echo $regfors['mode_reg']?></td>
            <td  class="montant"><?php echo number_format($regfors['montant'],2,'.',' ');?></td>
        </tr>
    <?php $ttfor+=$regfors['montant'];
    if($regfors['mode_reg']=='Espece'){
        $fournesp+=$regfors['montant'];
    }else{
        $fournonesp+=$regfors['montant'];
    }
    
    }

    $ttsorti=$ttfor+$tot1;
    
    ?>
        <tr>
            <td style="border: none;"  align=""></td>
            <td style="border: none;"  class=""></td>
            <td style="border: none;"  class=""></td>
            <td style="border: none;"  class=""></td>
            <td style="border-left: solid 1px ;"class="montant"><?php echo number_format($ttfor,2,'.',' ');?></td>
        </tr>
    </tbody>
        
    </table>
    <br> 
    <table width='100%' style="border-collapse: collapse;"  border=1  >
    <tr>
        <th style="background-color:#bdbdbd ;">Total Espece</th>
        <td align="center"><?php echo  number_format( $fournesp,2,'.',' ');?></td>
    </tr>
    <tr>
        <th style="background-color:#bdbdbd ;">Total Autre (Cheque, Virement, Effet)</th>
        <td align="center"><?php echo number_format(  $fournonesp,2,'.',' '); ?></td>
    </tr>
    <?php
	  $totalAutre += $fournonesp;
	?>
    </table > 
    <br>
    
    <div >
        <center>
        <h2 style="margin: 25px; box-shadow: 0 4px 6px -1px rgba(0, 255, 12, 1), 0px 10px 10px -5px rgba(0, 255, 12, 1); color: rgba(0, 142, 7, 1);">TOTAL</h2>
        </center>
    </div>
    <br>
    
    <table class="datatables"  border=1  >
    <thead>
        <tr class="row">		
            <th width="25%"> Total Entrer  </th> 
            <th width="45%"> Total Sortie </th> 
            <th width="22%"> Solde Caisse  </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td align="center"><?php echo number_format($totentrer,2,'.',' '); ?></td>
            <td align="center"><?php echo number_format($ttsorti,2,'.',' '); ?></td>
            <td align="center"><?php echo number_format($totentrer-$ttsorti,2,'.',' '); ?></td>
        </tr>
    </tbody>
    </table>
    
    <br><br>


<!--
    <table width='100%' style="border-collapse: collapse;"  border=1  >
    <tr>
        <th style="background-color:#bdbdbd ;">Total Espece  Entree</th>
        <td align="center"><?php echo  number_format( $cliecp+$tot2,2,'.',' ');?></td>
        
    </tr>
    <tr>
        <th style="background-color:#bdbdbd ;">Total Espece Sortie</th>
        <td align="center"><?php echo number_format(  $fournesp+$totdb,2,'.',' '); ?></td>
    </tr>
    </table > 
-->

    
   
    <table width='100%' style="border-collapse: collapse;"  border=1  >
    <tr>
        <th style="background-color:#bdbdbd ;">Total Espece</th>
        <td align="center"><?php echo  number_format( ($cliecp + $tot2 + $bon_esp) - $ttsorti,2,'.',' ');?></td>
        
    </tr>
    <tr>
        <th style="background-color:#bdbdbd ;">Total Autre (Cheque, Virement, Effet)</th>
        <td align="center"><?php echo number_format(($totentrer-$ttsorti) - (( $cliecp + $tot2 + $bon_esp) - $ttsorti),2,'.',' '); ?></td>
    </tr>
    </table > 

    <br><br>
<?php }else{ include("form_date.php"); } ?>

</body>

</html>