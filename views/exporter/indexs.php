<?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}


error_reporting(0);

function dump_MySQL($serveur, $login, $password, $base, $mode) {

   $connexion = mysql_connect($serveur, $login, $password);

   mysql_select_db($base, $connexion);



   $entete = "-- ----------------------\n";

   $entete .= "-- dump de la base " . $base . " au " . date("d-M-Y") . "\n";

   $entete .= "-- ----------------------\n\n\n";

   $creations = "";

   $insertions = "\n\n";



   $listeTables = mysql_query("show tables", $connexion);

   while($table = mysql_fetch_array($listeTables)) {

      // structure ou la totalit de la BDD

      if($mode == 1 || $mode == 2) {

         $creations .= "-- -----------------------------\n";

         $creations .= "-- Structure de la table " . $table[0] . "\n";

         $creations .= "-- -----------------------------\n";

         $listeCreationsTables = mysql_query("show create table " . $table[0],

            $connexion);

         while($creationTable = mysql_fetch_array($listeCreationsTables)) {

            $creations .= $creationTable[1] . ";\n\n";

         }

      }

      // donnes ou la totalit

      if($mode > 1) {

         $donnees = mysql_query("SELECT * FROM " . $table[0]);

         $insertions .= "-- -----------------------------\n";

         $insertions .= "-- Contenu de la table " . $table[0] . "\n";

         $insertions .= "-- -----------------------------\n";

         while($nuplet = mysql_fetch_array($donnees)) {

            $insertions .= "INSERT INTO " . $table[0] . " VALUES(";

            for($i = 0; $i < mysql_num_fields($donnees); $i++) {

               if($i != 0)

            $insertions .= ", ";
            if($nuplet[$i] == '0000-00-00') $nuplet[$i] = "'1900-01-01'";
            if($nuplet[$i] == NULL) $nuplet[$i] = 0;



               if(mysql_field_type($donnees, $i) == "string" || mysql_field_type($donnees, $i) == "date" ||

                  mysql_field_type($donnees, $i) == "blob")

                  $insertions .= "'";

               $insertions .= addslashes($nuplet[$i]);

               if(mysql_field_type($donnees, $i) == "string" || mysql_field_type($donnees, $i) == "date" ||

                  mysql_field_type($donnees, $i) == "blob")

                  $insertions .= "'";

            }

            $insertions .= ");\n";

         }

         $insertions .= "\n";

      }

   }



   mysql_close($connexion);
   $date = date("d-m-Y");
   $Fnm = BASE_URL."db/" . $date . ".txt";
   $fichierDump = fopen($Fnm, "wb");

   fwrite($fichierDump, $entete);

   fwrite($fichierDump, $creations);

   fwrite($fichierDump, $insertions);

   fclose($fichierDump);



}



$date = date("d-m-Y");


dump_MySQL(SERVER, USER, PASSWORD,DATABASE, 2);
header('location:index.php');


?>