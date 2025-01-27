<?php
class reg_avoir extends table{
    
  
  
    
    
protected $id_reg; 
protected $id_avoir;
protected $mode_reg;
protected $num_cheque;
protected $date_reg;
protected $montant;
protected $remarque;
protected $id_user;
protected $etat;

  
    public function selectAll2($id){
	$result=connexion::getConnexion()->query("select * from reg_avoir where id_avoir=".$id."  order by id_reg  desc ");
	return $result->fetchAll(PDO::FETCH_OBJ);
	}
     public function selectByIdAvoir($id){
	$result=connexion::getConnexion()->query("select * from reg_avoir where id_avoir=".$id."  order by id_reg  desc limit 0,1");
	return $result->fetch(PDO::FETCH_OBJ);
	}

	  public function selectAll3_er($date) {

	  
      $result = connexion::getConnexion()->query("select rga.* from reg_avoir rga
		where (mode_reg = 'Cheque' or mode_reg = 'Effet') and DATE_FORMAT(rga.date_reg,'%Y-%m')='" . $date . "'  order by rga.date_reg desc  ");
	}
	
}
?>