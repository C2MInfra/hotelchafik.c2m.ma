<?php
include('../../evr.php');


if ($_POST['act']=='filterVentes') {




		$reg_vente=new reg_vente();
        if($_POST['anne'] != 0)
        $data = $reg_vente->selectAll3_er($_POST['anne'] . "-" . $_POST['mois']);
        else
        $data = $reg_vente->selectAll3all_er();



        ?>

        <table class="table responsive table-striped " id="datatablesVentes">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Mode</th>
                      <th>Num&egrave;ro</th>
                      <th> Date </th>
                      <th> Remarque </th>
                      <th> Montant </th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $avance=0;
                    foreach($data as $ligne){
                    ?>
                    <tr>
                      <td> <?php echo $ligne->id_reg ; ?></td>
                      <td> <?php echo $ligne->mode_reg ; ?> </td>
                      <td> <?php echo $ligne->num_cheque ; ?> </td>
                      <td> <?php echo $ligne->date_reg ; ?> </td>
                      <td> <?php echo $ligne->remarque ; ?> </td>
                      <td style="float:right" > <?php echo number_format($ligne->montant,2,'.',' ') ;
                        $avance+=$ligne->montant;
                      ?> </td>
                      
                      <td>
                        <?php if(auth::user()['privilege'] == 'Admin') { ?>
                        <a class="badge badge-danger mb-2 deleteVentes" data-id="<?php echo $ligne->id_reg; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                          <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
                        </a>
                        <?php } ?>
                      </td>
                      
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
    <?php
	
}

elseif ($_POST['act']=='deleteVentes') {
	try {
		
	
		$reg_vente=new reg_vente();
		$reg_vente->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}
elseif ($_POST['act']=='filterAchats') {




		$reg_achat=new reg_achat();
        if($_POST['anne'] != 0)
        $data = $reg_achat->selectAll3_er($_POST['anne'] . "-" . $_POST['mois']);
        else
        $data = $reg_achat->selectAll3all_er();

        ?>

        <table class="table responsive table-striped " id="datatablesAchats">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Mode</th>
                      <th>Num&egrave;ro</th>
                      <th> Date </th>
                      <th> Remarque </th>
                      <th> Montant </th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $avance=0;
                    foreach($data as $ligne){
                    ?>
                    <tr>
                      <td> <?php echo $ligne->id_reg ; ?></td>
                      <td> <?php echo $ligne->mode_reg ; ?> </td>
                      <td> <?php echo $ligne->num_cheque ; ?> </td>
                      <td> <?php echo $ligne->date_reg ; ?> </td>
                      <td> <?php echo $ligne->remarque ; ?> </td>
                      <td style="float:right" > <?php echo number_format($ligne->montant,2,'.',' ') ;
                        $avance+=$ligne->montant;
                      ?> </td>
                      
                      <td>
                        <?php if(auth::user()['privilege'] == 'Admin') { ?>
                        <a class="badge badge-danger mb-2 deleteAchats" data-id="<?php echo $ligne->id_reg; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                          <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
                        </a>
                        <?php } ?>
                      </td>
                      
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
    <?php
	
}

elseif ($_POST['act']=='deleteAchats') {
	try {
		
	
		$reg_achat=new reg_achat();
		$reg_achat->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}
elseif ($_POST['act']=='filterClient') {




		$reg_client=new reg_client();
        if($_POST['anne'] != 0)
        $data = $reg_client->selectAll3_er($_POST['anne'] . "-" . $_POST['mois']);
        else
        $data = $reg_client->selectAll3all_er();



        ?>

        <table class="table responsive table-striped " id="datatablesClient">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Mode</th>
                      <th>Num&egrave;ro</th>
                      <th> Date </th>
                      <th> Remarque </th>
                      <th> Montant </th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $avance=0;
                    foreach($data as $ligne){
                    ?>
                    <tr>
                      <td> <?php echo $ligne->id_reg ; ?></td>
                      <td> <?php echo $ligne->mode_reg ; ?> </td>
                      <td> <?php echo $ligne->num_cheque ; ?> </td>
                      <td> <?php echo $ligne->date_reg ; ?> </td>
                      <td> <?php echo $ligne->remarque ; ?> </td>
                      <td style="float:right" > <?php echo number_format($ligne->montant,2,'.',' ') ;
                        $avance+=$ligne->montant;
                      ?> </td>
                      
                      <td>
                        <?php if(auth::user()['privilege'] == 'Admin') { ?>
                        <a class="badge badge-danger mb-2 deleteClient" data-id="<?php echo $ligne->id_reg; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                          <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
                        </a>
                        <?php } ?>
                      </td>
                      
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
    <?php
	
}

elseif ($_POST['act']=='deleteClient') {
	try {
		
	
		$reg_client=new reg_client();
		$reg_client->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}
elseif ($_POST['act']=='filterFournisseurs') {




		$reg_fournisseur=new reg_fournisseur();
        if($_POST['anne'] != 0)
        $data = $reg_fournisseur->selectAll3_er($_POST['anne'] . "-" . $_POST['mois']);
        else
        $data = $reg_fournisseur->selectAll3all_er();



        ?>

        <table class="table responsive table-striped " id="datatablesFournisseurs">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Mode</th>
                      <th>Num&egrave;ro</th>
                      <th> Date </th>
                      <th> Remarque </th>
                      <th> Montant </th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $avance=0;
                    foreach($data as $ligne){
                    ?>
                    <tr>
                      <td> <?php echo $ligne->id_reg ; ?></td>
                      <td> <?php echo $ligne->mode_reg ; ?> </td>
                      <td> <?php echo $ligne->num_cheque ; ?> </td>
                      <td> <?php echo $ligne->date_reg ; ?> </td>
                      <td> <?php echo $ligne->remarque ; ?> </td>
                      <td style="float:right" > <?php echo number_format($ligne->montant,2,'.',' ') ;
                        $avance+=$ligne->montant;
                      ?> </td>
                      
                      <td>
                        <?php if(auth::user()['privilege'] == 'Admin') { ?>
                        <a class="badge badge-danger mb-2 deleteFournisseurs" data-id="<?php echo $ligne->id_reg; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                          <i class="glyph-icon simple-icon-trash" style="font-size: 15px;"></i>
                        </a>
                        <?php } ?>
                      </td>
                      
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
    <?php
	
}

elseif ($_POST['act']=='deleteFournisseurs') {
	try {
		
	
		$reg_fournisseur=new reg_fournisseur();
		$reg_fournisseur->delete($_POST["id"]);
		die('success');
		} catch (Exception $e) {
				die($e);
	}
}
?>