<?php
class depot extends table{
  
protected $id; 
protected $nom; 
 

 
public function selectById2($id)
{
	$result=connexion::getConnexion()->query("select * from ".$this->className." where id=$id ");
	return $result->fetchAll(PDO::FETCH_OBJ);
}

public function selectALl()
{
	$result=connexion::getConnexion()->query("select * from ".$this->className);
	return $result->fetchAll(PDO::FETCH_OBJ);
}

	
}
?>