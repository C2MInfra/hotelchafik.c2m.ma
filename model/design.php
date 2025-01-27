<?php
class design extends table{

   protected $id;
   protected $libele;



   public function selectById2($id){
      $result=connexion::getConnexion()->query("select * from ".$this->className." where id=$id ");
      return $result->fetchAll(PDO::FETCH_OBJ);
   }

}
?>