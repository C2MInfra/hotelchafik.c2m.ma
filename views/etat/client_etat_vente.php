<?php
include("../../evr.php");
if(!isset($_GET["id"])) 
{
   header("Location:../client/index.php");
}

function dateFormat($dat)
{

    $date = new DateTime($dat);
    return $date->format('d-m-Y');
    
}
    
function dateFormat1($dat)
{
    
    $date = new DateTime($dat);
    return $date->format('Y-m-d');
    
}

$poids = 0;
$qte = 0;
$client = new client();
$res = $client->selectById($_GET["id"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
  
    <style type="text/css">

.row{background-color: #28a745 !important;
color: white;}
.tableform{background-color:#999999; width:400px; margin:150px auto; }

.inputText{height:22px; width:80%; border-radius:3px;margin-top:10px;}

.button{height:25px; width:30%; border-radius:3px;margin-top:10px; font-weight:bold;}

.button:hover{color:#666666; cursor:pointer;}

h3{text-transform:uppercase; color:#666;}

.datatables{border-collapse:collapse; width:100%;}

.row{background-color:#CCCCCC;}

.montant{text-align:right;}

.date
{
   color: #28a745; 
}
th,td{
    padding: 6px;
}
</style>
</head>

<body style="width:950px;margin:auto;">
<?php 
 $sum_ventes = 0;
 $sum_regs = 0;
 $reste = 0;
?>
<?php if(isset($_POST['dd'])){
$data = connexion::getConnexion()->query("select v.remarque, v.id_vente,v.date_vente, sum(dv.prix_produit*(1-dv.remise/100)*dv.qte_vendu)as
 montant from vente v left join  client c on  c.id_client=v.id_client left join detail_vente dv on dv.id_vente=v.id_vente where v.numbon>0 and c.id_client=" . $_GET["id"] . "
and  (v.date_vente  between '" . $_POST["dd"] . "' and '" . $_POST["df"] . "') group by  v.id_vente  order by v.date_vente ASC")->fetchAll(PDO::FETCH_ASSOC);
		
	
?>

    <h3 align="center" style="border: 2px solid black;
    padding: 6px; color:black;" > Etat des ventes du <span class="date"><?php echo dateFormat( $_POST['dd']); ?></span> A <span class="date"><?php echo dateFormat($_POST['df']); ?></span>
    </h3>

    <h3 align="center" style="
    padding: 6px; color:black;" ><span class="date"><?php echo $res["nom"] . " " . $res["prenom"] ?></span>
    </h3>
    <table class="datatables"  border=1  style="border-style:none;">
  <tr class="row">
        <th scope="col">DATE</th>
        <th scope="col">BN°</th>
        <th scope="col">DESIGNATION</th>
        <th width="10%" scope="col">QTE</th>
        <th width="10%" scope="col">PU</th>
        <th width="10%" scope="col">MT</th>
        <th  scope="col">OBSERVATION</th>
   </tr>

   <?php foreach($data as $ligne) { ?>

          <?php

          $data2 = connexion::getConnexion()->query("SELECT  v.numbon, dv.id_detail,p.designation,p.poid,p.designation_ar,dv.prix_produit,dv.qte_vendu, dv.valunit
          FROM  detail_vente dv 
          left join vente v ON v.id_vente = dv.id_vente
          left join produit p on (p.id_produit=dv.id_produit) 
          where dv.id_vente=" . $ligne['id_vente'] . " order by dv.id_detail desc");

          $query = $result = connexion::getConnexion()->query("select  sum(rv.montant) as paye from vente v left join reg_vente rv on rv.id_vente=v.id_vente where v.id_vente=" . $ligne["id_vente"]);
          $result = $query->fetch(PDO::FETCH_OBJ);
          $paye = $result->paye;

          ?>
          <?php
          $total = 0;
          foreach($data2 as $ligne2) {
             $nom_ar = "";
             if($ligne2["designation"] != "" && $ligne2["designation_ar"] != "") {
                $nom_ar = "/" . $ligne2["designation_ar"];
             }
             if($ligne2["designation"] == "" && $ligne2["designation_ar"] != "") {
                $nom_ar = $ligne2["designation_ar"];
             }
             ?>
              <tr>
                  <td><?php echo $ligne["date_vente"] ?></td>
                  <td><?php echo $ligne2["numbon"] ?></td>
                  <td style="text-align:left;padding-left:15px"
                      scope="col"><?php echo $ligne2["designation"] . $nom_ar ; ?></td>



                  <td scope="col" style="text-align:right"><?php echo number_format($ligne2["valunit"],2);
                     $qte += $ligne2["qte_vendu"]; ?>&nbsp;&nbsp;&nbsp;
                  </td>
                  <td scope="col" style="text-align:right"><?php echo $ligne2["prix_produit"]; ?>&nbsp;&nbsp;&nbsp;</td>
                  
                  <td style="text-align:right;padding-right:15px"
                      scope="col"><?php 
			         $sum_ventes += $ligne2["valunit"] * $ligne2["prix_produit"]*(1-$ligne2["remise"]/100);
			         echo number_format($ligne2["valunit"] * $ligne2["prix_produit"]*(1-$ligne2["remise"]/100), 2, '.', ' ');
                     ?></td>
                  <td><?php echo $ligne["remarque"] ?></td>
              </tr>
          <?php }
          //} ?>
   <?php } ?>
   </table>
   <br/>
   <br/>

   <!-- Réglements -->
   <?php 
     $query = "select rv.*, SUM(rv.montant) AS gmontant from vente v 
     left join reg_vente rv ON rv.id_vente = v.id_vente
     where v.numbon>0 and v.id_client=" . $_GET["id"] . " 
     AND  (rv.date_reg  between '" . $_POST["dd"] . "' 
     AND '" . $_POST["df"] . "') 
     AND rv.montant IS NOT NULL
	 GROUP BY rv.date_reg, rv.mode_reg, rv.num_cheque
     ORDER BY rv.date_reg asc";

     $regs = connexion::getConnexion()->query($query)->fetchAll(PDO::FETCH_OBJ);
   ?>

   <h3>Les réglements</h3>
   <table class="datatables"  border=1  style="border-style:none;">
        <tr class="row">
                <th scope="col">DATE</th>
                <th scope="col">MODE</th>
                <th scope="col">NUMERO</th>
                <th  scope="col">OBSERVATION</th>
                <th scope="col">MONTANT</th>
        </tr>
        
        <?php foreach($regs as $reg): ?>
               <tr>
                   <td><?php echo $reg->date_reg ?></td>
                   <td><?php echo $reg->mode_reg ?></td>
                   <td><?php echo ($reg->num_cheque != '')?$reg->num_cheque:'_' ?></td>
                   <td><?php echo $reg->remarque ?></td>
                   <td align="right">
                     <?php 
	    				$sum_regs += $reg->gmontant;
	                    echo $reg->gmontant ?>
                   </td>
                </tr>
        <?php endforeach; ?>
  </table>
   <!-- Fin Réglements -->
   <?php
	 //last reg
	 $query = "select SUM(rv.montant) from vente v left join reg_vente rv ON rv.id_vente = v.id_vente where v.numbon>0 and v.id_client=" . $_GET["id"] . " AND rv.date_reg < '" . $_POST["dd"] . "' AND rv.montant IS NOT NULL";

	 $last_totalr = connexion::getConnexion()->query($query)->fetch(PDO::FETCH_COLUMN);
	
	 $last_totalr = ($last_totalr == null)?0:$last_totalr;
	 //last reg
	 
	 $query = "select SUM(dv.prix_produit * dv.valunit * (1 - dv.remise / 100)) AS Total from vente v left join detail_vente dv ON dv.id_vente = v.id_vente left join produit p ON p.id_produit = dv.id_produit where v.numbon>0 and v.id_client=" . $_GET["id"] . " AND v.date_vente < '" . $_POST["dd"] . "'";

	 $last_totalv = connexion::getConnexion()->query($query)->fetch(PDO::FETCH_COLUMN);
	 $last_totalv = ($last_totalv == null)?0:$last_totalv;
	
	 $last_r = $last_totalv - $last_totalr;
	
	?>
   <br>
   <br>
   
   <!-- TEST -->
   <!--
      <h3>derniere reg : <?php echo $last_totalr ?></h3>
      <h3>derniere ven : <?php echo $last_totalr ?></h3>
   -->
   <!-- ENDTEST -->
   
   <table class="datatables"  border=1  style="border-style:none; width:30%;">
       <tr>
              <td width="50%" style="background-color:#28a745; color:white;">Reste précédent</td>
              <td align="right">
              	<?php echo number_format($last_r, 2) ?>
              </td>
        </tr>
        <tr>
              <td width="50%" style="background-color:#28a745; color:white;">Total ventes</td>
              <td align="right">
              	<?php echo number_format($sum_ventes, 2) ?>
              </td>
        </tr>
        <tr>
              <td style="background-color:#28a745; color:white;">Total Reg</td>
              <td align="right">
              	 <?php echo number_format($sum_regs, 2) ?>
              </td>
        </tr>
        <tr>
              <td style="background-color:#28a745; color:white;">Reste</td>
              <td align="right">
              	 <?php echo number_format($last_r + $sum_ventes - $sum_regs, 2) ?>
              </td>
        </tr>
  </table>
  <br><br><br>
   <?php
   } else {
      include("form_date.php");
   } ?>

</center>
</body>
</html>
