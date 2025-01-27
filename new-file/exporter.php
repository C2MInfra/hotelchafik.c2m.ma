<?php
include 'eve.php';

function dump_MySQLi($serveur, $login, $password, $base, $mode) {

   $connexion = mysqli_connect($serveur, $login, $password);

   mysqli_select_db($connexion,$base);



   $entete = "-- ----------------------\n";

   $entete .= "-- dump de la base " . $base . " au " . date("d-M-Y") . "\n";

   $entete .= "-- ----------------------\n\n\n";

   $creations = "";

   $insertions = "\n\n";



   $listeTables = mysqli_query($connexion,"show tables");

   while($table = mysqli_fetch_array($listeTables)) {

      // structure ou la totalit de la BDD

      if($mode == 1 || $mode == 2) {

         $creations .= "-- -----------------------------\n";

         $creations .= "-- Structure de la table " . $table[0] . "\n";

         $creations .= "-- -----------------------------\n";

         $listeCreationsTables = mysqli_query( $connexion,"show create table " . $table[0]);

         while($creationTable = mysqli_fetch_array($listeCreationsTables)) {

            $creations .= $creationTable[1] . ";\n\n";

         }

      }

      // donnes ou la totalit

      if($mode > 1) {

         $donnees = mysqli_query( $connexion,"SELECT * FROM " . $table[0]);

         $insertions .= "-- -----------------------------\n";

         $insertions .= "-- Contenu de la table " . $table[0] . "\n";

         $insertions .= "-- -----------------------------\n";

         while($nuplet = mysqli_fetch_array($donnees)) {

            $insertions .= "INSERT INTO " . $table[0] . " VALUES(";

            for($i = 0; $i < mysqli_num_fields($donnees); $i++) {

               if($i != 0)

                  $insertions .= ", ";

               if($nuplet[$i] == '0000-00-00') $nuplet[$i] = "1900-01-01";
          
            if($nuplet[$i] == NULL)
            {
            $insertions .= "''";
            }
            else{
               if(gettype($nuplet[$i]) == "string" || gettype($nuplet[$i]) == "date" || isDate($nuplet[$i]) || gettype($nuplet[$i]) == "blob")

                  $insertions .= "'";

               $insertions .= addslashes($nuplet[$i]);

               if(gettype($nuplet[$i]) == "string" || gettype($nuplet[$i]) == "date" || isDate($nuplet[$i]) || gettype($nuplet[$i]) == "blob")

                  $insertions .= "'";
              }
                 

            }

            $insertions .= ");\n";

         }

         $insertions .= "\n";

      }

   }



   mysqli_close($connexion);
   $date = str_replace(' ', '___', date("d-m-Y H-i-s"));
   $Fnm = "db/" . $date . ".txt";
   $fichierDump = fopen($Fnm, "wb");

   fwrite($fichierDump, $entete);

   fwrite($fichierDump, $creations);

   fwrite($fichierDump, $insertions);

   fclose($fichierDump);



}

function isDate($value) 
{
    if (!$value) {
        return false;
    }

    try {
        new \DateTime($value);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}


dump_MySQLi(SERVER, USER, PASSWORD,DATABASE, 2);

 $response = array(
        'data' => "Date d'exportation : ".date("d-m-Y H-i-s")
        
    );

    

echo json_encode($response);


?>