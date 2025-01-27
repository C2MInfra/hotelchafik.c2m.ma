<?php 
$page='facture'; 
include("../model/header.php");
$facture=new facture(); 
if(isset($_POST["date_facture"])){
$_POST["id_user"]= $_SESSION["gs_id"] ;

$list="";
$_GET["idv"] =array_reverse($_GET["idv"] );
foreach($_GET["idv"] as $fact){
$list.=$fact.",";
}
$list=substr($list,0,(strlen($list))-1) ; 

$_POST["id_vente"]=','. $list .',';
$query=$result=connexion::getConnexion()->query("SELECT ifnull(max(num_fact),0) as dernier_facture FROM facture  where DATE_FORMAT(date_facture,'%Y')= ".strstr($_POST["date_facture"], '-', true));
$result=$query->fetch(PDO::FETCH_OBJ);
//$_POST["num_fact"]=$result->dernier_facture+1;
//$_POST["num_fact"]=$_POST["num_fact"];
$facture=new facture(); 
//$_POST["date_facture"]=date("d-m-Y");
$facture->insert();
$query=$result=connexion::getConnexion()->query("SELECT max(id_facture) as dernier_facture FROM facture ");
$result=$query->fetch(PDO::FETCH_OBJ);
$dernier_facture=$result->dernier_facture;
 ?>  
    
    <script type="text/javascript" >

	var idf=<?php echo $dernier_facture; ?>;
	window.open("facture.php?h=15&id="+<?php echo "'".$list."'"; ?>+"&idf="+idf) ;
	window.location.href="index.php";
    </script>


 <?php  }?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> facture </title>
<script language="javascript" >
document.getElementById('date_facture').focus();
</script>
<?php include('../view/fichier.php'); ?>

</head>

<body>
<!-- WRAPPER START -->
<div class="container_16" id="wrapper">	 
  	
	 <?php include('../view/menu.php'); ?>
<!-- CONTENT START -->
    <div class="grid_16" id="content">
   
    <div class="grid_9">
    <h1 class="dashboard">Nouveau facture</h1>
    </div>
    <div class="clear">
    </div>
    <div id="portlets">
      <div class="column" id="left" style="width:938px !important" >
       <!-- ****************************************************************************************************************-->
	  <div style="width: 98%;" >  
      <br />
      <br />
	    <center>
        <?php include("../forms/form_facture.php"); ?>
          </center>
	   </div> 
       <!-- ****************************************************************************************************************-->
        <div class="portlet"> 
		 
        </div>
      </div>
      <div class="column">      
      <div class="portlet">
	 
		 
       </div>     
      <div class="portlet">
		<div class="portlet-header">
       			</div>
	 
       </div>                         
    </div>
	<!--  SECOND SORTABLE COLUMN END -->
    <div class="clear"></div>
    <!--THIS IS A WIDE PORTLET-->
    <div class="portlet">
       
		 
      </div>
<!--  END #PORTLETS -->  
   </div>
    <div class="clear"> </div>
<!-- END CONTENT-->    
  </div>
<div class="clear"> </div>

		<!-- This contains the hidden content for modal box calls -->
		 
</div>
<?php include("../view/footer.php"); ?>
<!-- WRAPPER END --> 
</body>
</html>
