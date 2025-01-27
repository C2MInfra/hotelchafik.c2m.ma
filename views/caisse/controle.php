<?php
include('../../evr.php');


if ($_POST['act']=='filter') {
	$caisse = new caisse();
    if($_POST['anne'] != 0)
      $data = $caisse->selectAll3($_POST['anne'] . "-" . $_POST['mois'], 1);
    else
      $data = $caisse->selectAll3($_POST['mois'], 0);



?>
	  <table  class="table  responsive table-striped table-bordered table-hover" id="datatables" >
              <thead>
                <tr>
                  
                  <th>NR°</th>
                  <th>Mode</th>
                  <th>Designation</th>
                  <th>Date caisse</th>
                  <th>Montant</th>
                  <th>Remarque</th>
                  <th style="width: 85px;">Action</th>
                </tr>
              </thead>
              <tbody>
                
                
                <?php
                foreach ($data as $caisse) {
                
                
                ?>
                <tr>
                  <td>
                    <?php echo $caisse->id ?>
                  </td>
                   <td>
                    <?php echo $caisse->type_reg ?>
                  </td>
                  
                  <td>
                    <?php echo $caisse->designation ?>
                  </td>
                  
                  <td>
                    <?php echo $caisse->date_caisse ?>
                  </td>
                  <td>
                    <?php echo $caisse->montant ?>
                  </td>
                   <td>
                    <?php echo $caisse->remarque ?>
                  </td>
                  <td>
                    <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL."views/caisse/etat.php?id=".$caisse->id; ?>"  target="_black" >

                        <i class=" simple-icon-printer" style="font-size: 15px;"></i>

                      </a>
                    <?php if(auth::user()['privilege'] == 'Admin') { ?>
                    <a class="badge badge-danger mb-2 delete" data-id="<?php echo $caisse->id; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                      <i class="simple-icon-trash" style="font-size: 15px;"></i>
                    </a>
                   
                    <?php } ?>
                    
                  <a class="badge badge-warning mb-2  url notlink" data-url="caisse/update.php?id=<?php echo $caisse->id; ?>" style="color: white;cursor: pointer;" title="Modifier"
                    href="javascript:void(0)">
                    <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                  </a>
                    
                                        <?php if($caisse->image !='' || $caisse->deux_image !='' ) { $img =  $caisse->image != ''? $caisse->image : $caisse->deux_image ; ?>
                                                <a class="badge badge-success " data-fancybox data-caption="<?php echo $caisse->designation; ?> <br> Prix : <?php echo $caisse->montant?> DH" style="color: white;cursor: pointer;" title="<?php echo $caisse->designation; ?>" href="<?php echo BASE_URL.'upload/caisse/'.$img; ?>">
                                                    <i class="simple-icon-picture" style="font-size: 15px;"> </i>
                                                </a>
                                                <?php } ?>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
<?php
}

elseif ($_POST['act']=='insert') {


        $dossier = '../../upload/caisse/';

        if($_FILES['image']['name']!="")
        {
            $fichier = md5(uniqid());
            $extension = strrchr($_FILES['image']['name'], '.'); 
            if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier .$fichier.$extension)) 
            {
              $_POST["image"]=$fichier.$extension;
            }
        }
        
  $_POST["id_user"] = auth::user()["id"] ;

        $caisse=new caisse(); 
        $caisse->insert();

			die("success");
}
elseif ($_POST['act']=='update') {
	try {
		
		 $dossier = '../../upload/caisse/';

        if($_FILES['image']['name']!="")
        {
            $fichier = md5(uniqid());
            $extension = strrchr($_FILES['image']['name'], '.'); 
            if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier .$fichier.$extension)) 
            {
              $_POST["image"]=$fichier.$extension;
            }
        }
		$caisse=new caisse();
 		$caisse->update($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
		
	}
}
elseif ($_POST['act']=='delete') {
	try {
		
		$caisse=new caisse();
		$caisse->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}
?>