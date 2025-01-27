<?php
include('../../evr.php');
if ($_POST['act']=='insert') {
	try {
		$_POST["idu"] = auth::user()["id"] ;
		$utilisateur=new utilisateur(); 

		$res = $utilisateur->insert();
		
			die('success');
		
		
		} catch (Exception $e) {
				die($e);
		
	}
}
elseif ($_POST['act']=='update') {
	try {

		$_POST["idu"] = auth::user()["id"] ;
		$utilisateur=new utilisateur();
 		$utilisateur->update($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
		
	}
}
elseif ($_POST['act']=='delete') {
	try {
		
	
		$utilisateur=new utilisateur();
		$utilisateur->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}

?>