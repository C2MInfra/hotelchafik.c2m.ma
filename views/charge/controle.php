<?php
include('../../evr.php');


if ($_POST['act']=='filter') 
{
  
	$charge = new charge();
	
    if($_POST['anne'] != 0){
      $data = $charge->selectAll3($_POST['anne'] . "-" . $_POST['mois'], 1);
    }else
	{
   
		$data = $charge->selectAll3($_POST['mois'], 0);
	}
	
?>
	  <table  class="table  responsive table-striped table-bordered table-hover" id="datatables" >
              <thead>
                <tr>
                  
                  <th>NR°</th>
                  <th>Mode</th>
                  <th>Num&egrave;ro</th>
                  <th>Designation</th>
                  <th>Date charge</th>
                  <th>Montant</th>
                  <th>Remarque</th>
                  <th style="width: 85px;">Action</th>
                </tr>
              </thead>
              <tbody>
                
                
                <?php
                foreach ($data as $charge) {
                
                
                ?>
                <tr>
                  <td>
                    <?php echo $charge->id ?>
                  </td>
                   <td>
                    <?php echo $charge->mode_reg ?>
                  </td>
                   <td>
                    <?php echo $charge->num_cheque ?>
                  </td>
                  <td>
                    <?php echo $charge->designation ?>
                  </td>
                  
                  <td>
                    <?php echo $charge->date_charge ?>
                  </td>
                  <td>
                    <?php echo $charge->montant ?>
                  </td>
                   <td>
                    <?php echo $charge->remarque ?>
                  </td>
                  <td>
                    <?php if(auth::user()['privilege'] == 'Admin') { ?>
                    <a class="badge badge-danger mb-2 delete" data-id="<?php echo $charge->id; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                      <i class="simple-icon-trash" style="font-size: 15px;"></i>
                    </a>
                   
                    <?php } ?>
                    
                  <a class="badge badge-warning mb-2  url notlink" data-url="charge/update.php?id=<?php echo $charge->id; ?>" style="color: white;cursor: pointer;" title="Modifier"
                    href="javascript:void(0)">
                    <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                  </a>
                    
                                        <?php if($charge->image !='' || $charge->deux_image !='' ) { $img =  $charge->image != ''? $charge->image : $charge->deux_image ; ?>
                                                <a class="badge badge-success " data-fancybox data-caption="<?php echo $charge->designation; ?> <br> Prix : <?php echo $charge->montant?> DH" style="color: white;cursor: pointer;" title="<?php echo $charge->designation; ?>" href="<?php echo BASE_URL.'upload/charge/'.$img; ?>">
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


        $dossier = '../../upload/charge/';

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
        $charge=new charge(); 
        $charge->insert();

			die("success");
}
elseif ($_POST['act']=='update') {
	try {
		
		 $dossier = '../../upload/charge/';

        if($_FILES['image']['name']!="")
        {
            $fichier = md5(uniqid());
            $extension = strrchr($_FILES['image']['name'], '.'); 
            if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier .$fichier.$extension)) 
            {
              $_POST["image"]=$fichier.$extension;
            }
        }
		$charge=new charge();
 		$charge->update($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
		
	}
}
elseif ($_POST['act']=='delete') {
	try {
		
		$charge=new charge();
		$charge->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}
?>