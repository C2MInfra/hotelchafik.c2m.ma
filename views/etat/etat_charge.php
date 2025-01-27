<?php

include("../../evr.php");


function dateFormat($dat){

$date = new DateTime($dat);

return $date->format('d-m-Y');

}

function dateFormat1($dat){

$date = new DateTime($dat);

return $date->format('Y-m-d');

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

 

</head>



<body style="width:950px;margin:auto;">



<?php if(isset($_POST['dd'])){ ?>

<h3 align="center"  > Etat des charges du <?php echo dateFormat( $_POST['dd']); ?> الى <?php echo dateFormat($_POST['df']); ?>

<br>

	<b dir="rtl">لائحة الرسوم من <?php echo dateFormat( $_POST['dd']); ?> a <?php echo dateFormat($_POST['df']); ?></b>

</h3>

 <fieldset><legend> Etat des charges / لائحة الرسوم</legend>

<table class="datatables"  border=1  >

   <tr class="row">

		<th width="20%"> Num Charge / رقم الرسم</th> 

		<th width="20%"> D&eacute;signation / المنتوج  </th> 

		<th width="20%"> Date / التاريخ </th> 

		<th width="20%"> Montant / الثمن</th> 

		<th width="20%"> Type Reglement / نوع الدفع</th>

		<th width="20%"> Remarque / ملاحضة </th>	

   </tr>

   <?php  



	$charge=new charge();

	$data=$charge->selectEtat(dateFormat1($_POST['dd']),dateFormat1($_POST['df']));

	$total_montant=0;



	foreach($data as $rep ){

   ?>

   <tr>

        <th width="20%" ><?php echo $rep->id_charge;?></th>

        <th width="20%" ><?php echo $rep->designation;

          if ($rep->designation_ar!="") {

               echo "/".$rep->designation_ar;

             }

        ?></th>

		<td width="20%" align="center"><?php echo $rep->date_charge;?></td>

		<td width="20%" class="montant"><?php echo number_format($rep->montant,2,'.',' ');?></td>

		<td width="20%" align="center"><?php echo $rep->type_reg;?></td>

		<td width="20%" align="center"><?php echo $rep->remarque;?></td>

   </tr>

   <?php  

     $total_montant+=$rep->montant;

     } 

   ?>

   <tr class="row">

		<th  colspan="2">Total / المجموع</th> 

		<th class="montant"> </th>

		<th class="montant"><?php echo number_format($total_montant,2,'.',' ');?></th>

		<th class="montant">  </th>

		<th class="montant">  </th>

		

   </tr>

</table>  

</fieldset>





<?php }else{ include("form_date.php"); } ?>

</body>



</html>