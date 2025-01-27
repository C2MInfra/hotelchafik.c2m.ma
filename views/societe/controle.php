<?php
include('../../evr.php');
if ($_POST['act']=='update') {
	try {
	
  
	$societe=new societe();
		 $societe->update($_POST['id']);

			die('success');


		} catch (Exception $e) {
				die($e);
		
	}
}
?>