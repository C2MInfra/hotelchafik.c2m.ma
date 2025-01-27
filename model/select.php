<?php
//Option 1 ****************************************
function getOptions1($query) {
   $result = connexion::getConnexion()->query($query);
   $rep = $result->fetchAll();
   /* echo "<option value='0'>Choix</option>"; */
   for($i = 0; $i <= count($rep) - 1; $i++) {
      echo "<option value='" . $rep[$i][0] . "'>" . $rep[$i][1] . "</option>";
   }
}
function getCompte($query) {
   $result = connexion::getConnexion()->query($query);
   $rep = $result->fetchAll();
   /* echo "<option value='0'>Choix</option>"; */
   for($i = 0; $i <= count($rep) - 1; $i++) {
      echo "<option value='" . $rep[$i][0] . "'>" . $rep[$i][1] . "</option>";
   }
}


function getOptionsEr($query) {
   $result = connexion::getConnexion()->query($query);
   $rep = $result->fetchAll();
   /* echo "<option value='0'>Choix</option>"; */
   for($i = 0; $i <= count($rep) - 1; $i++) {
      if($rep[$i][1] === " " && $rep[$i][2] != "") {
         echo "<option value='" . $rep[$i][0] . "'>" . $rep[$i][2] . "</option>";
      }
      if($rep[$i][1] != " " && $rep[$i][2] != "") {
         echo "<option value='" . $rep[$i][0] . "'>" . $rep[$i][1] . "/" . $rep[$i][2] . "</option>";
      }
      if($rep[$i][1] != " " && $rep[$i][2] === "") {
         echo "<option value='" . $rep[$i][0] . "'>" . $rep[$i][1] . "</option>";
      }
//echo "<option value='".$rep[$i][0]."'>".$rep[$i][1]."</option>";
   }
}


function getOptionsP() {
   $result1 = connexion::getConnexion()->query("SELECT * FROM `produit` WHERE `emplacement`='frigo jouamiya' or emplacement='frigo sbit' or emplacement='frigo'");
   $rep1 = $result1->fetchAll();
   $result2 = connexion::getConnexion()->query("SELECT * FROM `produit` WHERE `emplacement`='depot'");
   $rep2 = $result2->fetchAll();

   echo "<option>  Produit </option><optgroup label='Frigo'>";
   for($i = 0; $i <= count($rep1) - 1; $i++) {
      echo "<option value='" . $rep1[$i][0] . "'>" . $rep1[$i][1] . "(" . $rep1[$i][2] . " g)" . "</option>";
   }
   echo "</optgroup>";
   echo "<optgroup label='Depot'>";
   for($i = 0; $i <= count($rep2) - 1; $i++) {
      echo "<option value='" . $rep2[$i][0] . "'>" . $rep2[$i][1] . "(" . $rep2[$i][2] . " g)" . "</option>";
   }
   echo "</optgroup>";
}

//Option 2 ****************************************
function getOptions2($query) {
   $result = connexion::getConnexion()->query($query);
   $rep = $result->fetchAll();
   echo "<option></option>";
   for($i = 0; $i <= count($rep) - 1; $i++) {
      echo "<option value='" . $rep[$i][0] . "'>" . $rep[$i][1] . "</option>";
   }
}


//Option 3 ****************************************
function getOptions3($query) {
   $result = connexion::getConnexion()->query($query);
   $rep = $result->fetchAll();
   echo "<option></option>";
   for($i = 0; $i <= count($rep) - 1; $i++) {
      echo "<option value='" . $rep[$i][0] . "'>" . $rep[$i][1] . " " . $rep[$i][2] . "</option>";
   }
}

//Option 4 ****************************************
function getOptions4($query) {
   $result = connexion::getConnexion()->query($query);
   $rep = $result->fetchAll();
   echo "<option></option>";
   for($i = 0; $i <= count($rep) - 1; $i++) {
      echo "<option value='" . $rep[$i][0] . "'>" . $rep[$i][1] . " " . $rep[$i][2] . "</option>";
   }
}

//Option 5 ****************************************
function getOptions5($query, $value) {
   $result = connexion::getConnexion()->query($query);
   $rep = $result->fetchAll();
   echo "<option></option>";
   for($i = 0; $i <= count($rep) - 1; $i++) {
      if($rep[$i][0] == $value) {
         echo "<option selected='selected' value='" . $rep[$i][0] . "'>" . $rep[$i][1] . "</option>";
      } else {
         echo "<option value='" . $rep[$i][0] . "'>" . $rep[$i][1] . "</option>";
      }

   }
}

//Option 6 ****************************************
function getOptions6($query, $value) {
   $result = connexion::getConnexion()->query($query);
   $rep = $result->fetchAll();
   echo "<option></option>";
   for($i = 0; $i <= count($rep) - 1; $i++) {
      if($rep[$i][0] == $value) {
         echo "<option  value='" . $rep[$i][0] . "' selected='selected'>" . $rep[$i][1] . " " . $rep[$i][2] . "</option>";
      } else {
         echo "<option value='" . $rep[$i][0] . "'>" . $rep[$i][1] . " " . $rep[$i][2] . "</option>";
      }
   }
}

?>