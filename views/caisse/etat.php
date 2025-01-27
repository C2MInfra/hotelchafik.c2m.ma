<?php
include('../../evr.php');

if(!isset($_GET["id"]))
{
	header("Location:index.php"); 
}

$id = $_GET['id'];
$caisse = connexion::getConnexion()->query("SELECT * FROM caisse WHERE id = " . $id)->fetch(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html>
	<head>
		 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
         <title>Caisse etat</title>
  
    <style type="text/css">

		.row{background-color: #d4edda !important;
		color: #155724;}
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
	<br><br>
		<table class="datatables"  border=1  style="border-style:none;">
			  <tr class="row">
					<th scope="col" colspan="2">DATE: <?php echo $caisse->date_caisse ?></th>
			   </tr>
			   <tr class="row">
			   	   <th scope="col"  colspan="2"><?php echo $caisse->designation ?> </th>
			   </tr>
			   <tr class="row">
			   	   <th scope="col" width="50%">Mode Reglement</th>
			   	   <th scope="col">Montant</th>
			   </tr>
			   <tr>
			   	  <td align="center"><?php echo $caisse->type_reg ?> </td>
			   	  <td align="center"><?php echo number_format($caisse->montant, 2, '.', '') ?></td>
			   </tr>
		</table>
		<br><br>
	</body>
</html>