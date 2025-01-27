<?php
include('../../evr.php');
if ($_POST['act']=='filter') :
		 $facture=new facture();
         if ($_POST['anne']!=0 )
		 {
			  $data = $facture->selectAll3($_POST['anne'] . "-" . $_POST['mois'], 1);
	     }
         else
		 {
			  $data = $facture->selectAll3($_POST['mois'], 0); 
		 }
        ?>
          <table class="table  responsive table-striped table-bordered table-hover" id="datatables" >
            <thead>
              <tr>
                <th>Id</th>
                <th>Client</th>
                <th>Date Facture</th>
                <th>N° FA</th>
                <th>Montant</th>
                <th>Id facture</th>
                <th>Remarque</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
             <?php      
		 foreach($data as $ligne){
		 ?>
		          <tr>
             <td> <?php echo $ligne->id_facture ; ?></td>
            <td> <?php echo $ligne->nom ; ?></td>
            <td> <?php echo $ligne->date_facture ; ?> </td>
            <td> <?php echo $ligne->num_fact ; ?> </td>
            <td> <?php echo number_format($ligne->montant,2,'.',' ') ; ?> </td> 
            <td> <?php echo trim($ligne->id_facture,',') ; ?> </td> 
            <td> <?php echo $ligne->remarque ; ?> </td>  
                <td>
                  <?php if(auth::user()['privilege'] == 'Admin') { ?>
                  <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_facture; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                    <i class="simple-icon-trash" style="font-size: 15px;"></i>
                  </a>
                  <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Régler" href="<?php echo BASE_URL."views/facture/facture.php?id=".$ligne->id_vente."&idf=".$ligne->id_facture; ?>&h=15"  target="_black" >
                    <i class=" simple-icon-printer" style="font-size: 15px;"></i>
                  </a>
                  <?php } ?>
                  <?php if(auth::user()['privilege'] == 'Admin' || auth::user()['privilege'] == 'User+'){ ?>
                <a class="badge badge-warning mb-2  url notlink" data-url="facture/update.php?id=<?php echo $ligne->id_facture; ?>" style="color: white;cursor: pointer;" title="Modifier"
                  href="javascript:void(0)">
                  <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                </a>
                <?php } ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
<?php 
elseif ($_POST['act']=='delete') :
		$facture=new facture();
		$facture->delete($_POST["id"]);
	die("success");
elseif ($_POST['act']=='insert') :
		// $_POST["id_user"]= auth::user()["id"] ;
		//     $list="";
		//     $_POST["idv"] = array_reverse($_POST["idv"] );
		//     foreach($_POST["idv"] as $fact){
		//     $list.=$fact.",";
		//     }
		//     $_POST["taux"]=0;
		//     $_POST["tva"]=0;
		//     $list=substr($list,0,(strlen($list))-1) ; 
		//       $_POST["id_vente"]=','. $list .',';
		//       $query=$result=connexion::getConnexion()->query("SELECT ifnull(max(num_fact),0) as dernier_facture FROM facture  where DATE_FORMAT(date_facture,'%Y')= ".strstr($_POST["date_facture"], '-', true));
		//       $result=$query->fetch(PDO::FETCH_OBJ);
		//       $facture=new facture(); 
		//       $facture->insert();
		//       $query=$result=connexion::getConnexion()->query("SELECT max(id_facture) as dernier_facture FROM facture ");
		//       $result=$query->fetch(PDO::FETCH_OBJ);
		//       $dernier_facture=$result->dernier_facture;
		// 	die($dernier_facture."//success");
    $_POST["id_user"]= auth::user()["id"] ;
		    $_POST["taux"]=0;
		    $_POST["tva"]=0;
        $_POST["id_vente"]=$_POST["id"];
        $facture=new facture(); 
        $facture->insert();
        $query=$result=connexion::getConnexion()->query("SELECT max(id_facture) as dernier_facture FROM facture ");
        $result=$query->fetch(PDO::FETCH_OBJ);
        $dernier_facture=$result->dernier_facture;
			die($dernier_facture."//success");
elseif ($_POST['act']=='update') :
 $facture=new facture();
 $facture->update($_POST["id"]);
die("success");
endif;
?>