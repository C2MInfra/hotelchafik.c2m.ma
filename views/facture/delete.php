<?php 
if(isset($_GET["id"])){
include("../model/header.php");
$facture=new facture();
$facture->delete($_GET["id"]);
header("location:index.php");
}

?>