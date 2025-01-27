<?php
include("../../evr.php");
function dateFormat($dat){
$date = new DateTime($dat);
return $date->format('d-m-Y');
}
function design_ar($design,$design_ar){
  if ($design==''&& $design_ar!='') {
    echo $design_ar;
  }
  if ($design!=''&& $design_ar!='') {
    echo "/ ".$design_ar;
  }
}
?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <link rel = "icon" href ="<?php echo BASE_URL . 'asset/img/icon.png' ?>"
        type = "image/x-icon">
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
 </style>
 
<script language=javascript>
    function CallPrint(strid)
    {
         var prtContent = document.getElementById(strid);
         var entetee1 = document.getElementById("entetee1");
         var showwme = document.getElementById("showwme");
         var WinPrint = window.open('','','letf=0,top=0,width='+screen.width+',height='+screen.height+',toolbar=0,scrollbars=0,status=0,fullscreen=yes');
         WinPrint.document.write("<style type='text/css'>.tableform{background-color:#999999; width:400px; margin:150px auto; }.inputText{height:22px; width:80%; border-radius:3px;margin-top:10px;}.button{height:25px; width:30%; border-radius:3px;margin-top:10px; font-weight:bold;}.button:hover{color:#666666; cursor:pointer;}h3{text-decoration:underline;text-transform:uppercase;}.datatables{border-collapse:collapse; width:100%;}.row{background-color:#CCCCCC;}.montant{text-align:right;}</style><h3 align='center'>"+entetee1.innerHTML + "</h3><br /><table class='datatables' border=1  ><tr class='row'>"+ showwme.innerHTML +"</tr></table><br />"+prtContent.innerHTML);
         WinPrint.document.close();
         WinPrint.focus();
         WinPrint.print();
         WinPrint.close();
    }
</script>
</head>

<body style="width:950px;margin:auto;">

<?php if(isset($_POST['dd'])){ ?>
<h3 align="center" id="entetee1" > Etat des Reglements des achat <?php echo dateFormat( $_POST['dd']); ?> a <?php echo dateFormat($_POST['df']); ?> .</h3>
 <fieldset><legend></legend>
<table class="datatables"  border=1  >
  <tr class="row" id="showwme">
     <th width="20%">Num Vente </th> 
    <th width="20%">Num cheque </th> 
    <th width="20%">Type Reg </th> 
    <th width="20%">Date </th> 
    <th width="20%"> Montant </th>
   </tr>
</table>  
</fieldset>
<?php  
$fournisseur=new fournisseur();
if(isset($_GET['id_fournisseur'])){$data_fournisseur=$fournisseur->selectById2($_GET['id_fournisseur']);}
else{$data_fournisseur=$fournisseur->selectAll_1();}
$total_montant=0;$i=0;
foreach($data_fournisseur as $rep_fournisseur ){$i+=1;
$result=connexion::getConnexion()->query("SELECT a.id_achat,r.etat,r.montant,r.id_reg,date_reg,r.remarque ,mode_reg ,num_cheque FROM `reg_achat` r inner join achat a on a.id_achat=r.id_achat  WHERE  a.id_fournisseur= ".$rep_fournisseur->id_fournisseur."  and   (date_reg between '".$_POST['dd']."' and '".$_POST['df']."') ");
$data=$result->fetchAll(PDO::FETCH_OBJ);
if (count($data)>0){
 ?>
 <fieldset id="<?php echo "A".$i;?>"><legend> <strong><?php echo $rep_fournisseur->raison_sociale;  ?> ...</strong> 
 <a href="#" onClick="javascript:CallPrint('<?php echo "A".$i;?>');">Imprimer</a>
 </legend>

<table class="datatables"  border=1  >

 <?php 
 $montant=0; $avance=0; 
 foreach($data as $rep ){ 
 $montant+=$rep->montant;

 ?>
   <tr <?php if( $rep->etat==1) echo 'style="background:#D9FCCE"'; ?>>
     <th width="20%" ><?php echo $rep->id_achat;?></th>
     <th width="20%" ><?php echo $rep->num_cheque;?></th>
     <th width="20%" ><?php echo $rep->mode_reg;?></th>
     <th width="20%" ><?php echo $rep->date_reg;?></th>

    <td width="20%" class="montant"><?php echo number_format($rep->montant,2,'.',' ');?></td>
    
   </tr>
   <?php } ?>
   <tr class="row">
    <th  colspan="4">Total </th> 
    <th class="montant"> <?php echo number_format($montant,2,'.',' ');?></th>
  
    
   </tr>
</table>
</fieldset>
<br />
<?php   
$total_montant+=$montant;
 }} ?>
 <fieldset><legend> Total G&eacute;n&eacute;rale </legend>
<table class="datatables"  border=1  > 
    <tr class="row">
    <th  width="40%" colspan="3">Total G&eacute;n&eacute;rale </th> 
    <th  width="20%" class="montant"> <?php echo number_format($total_montant,2,'.',' ');?></th>
   </tr>
</table>
 </fieldset>
 
<?php }else{ include("form_date.php"); } ?>
</body>

</html>