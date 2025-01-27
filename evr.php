<?php


 include('eve.php');
 $u = auth::user();


if (auth::user() == null){

die("<script language=javascript >document.location.href='".BASE_URL."login.php"."';</script>");
 // header("Location:".BASE_URL."login.php");

  }

?>