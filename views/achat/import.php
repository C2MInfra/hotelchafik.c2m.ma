<?php 

    include('../../evr.php'); 

    $conn = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
    $result = $conn->query('select * from vente limit 1');
    
    function achat_exists($id_fournisseur, $date)
    {
        $conn = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
        $sql = "select * from achat where id_fournisseur = $id_fournisseur AND date_achat = $date";
        
        $result = $conn->query($sql);

        return $result->fetch_assoc();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
        <title>Importation des produits</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" type="image/png" href="main/images/icons/import.png"/>
        <link rel="stylesheet" type="text/css" href="main/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="main/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="main/vendor/animate/animate.css">
        <link rel="stylesheet" type="text/css" href="main/vendor/select2/select2.min.css">
        <link rel="stylesheet" type="text/css" href="main/vendor/perfect-scrollbar/perfect-scrollbar.css">
        <link rel="stylesheet" type="text/css" href="main/css/util.css">
        <link rel="stylesheet" type="text/css" href="main/css/main.css">
        <?php include('includes/style.php') ;?>
        <?php include("includes/script.php") ;?>

        <style>
        th 
        {
            font-size: 11pt !important;
        }	
        </style>
</head>
<body>
    <div class="limiter">
        <div class="container-table100" style="background:#f2f2f2 !important;">
            <div class="row mb-4" style="width: 600px;">
                <div class="col align-self-start">
                    <div class="card mb-12">
                        <div class="card-body">
                                <form action="#" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="image"><span style="font-weight: bold;">Attention:</span> Avant d'importer, téléchargez <a href="<?php echo BASE_URL."views/achat/importation_achats_canvas.xlsx" ?>" style="color:#28a745;">ce ficher</a> et inserez vos achats pour importer correctement vos données. </label>
                                            <br><br>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="" name="file" id="file" accept=".xls,.xlsx">
                                                    <label class="custom-file-label" for="image">Choisir le fichier</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="float-sm-right text-zero" style="margin: auto;">
                                        <button type="submit" id="submit" name="import" style="font-size:9pt !important;" class="btn btn-success btn-lg  mr-1 ">Importer</button>
                                        <a style="font-size:9pt !important;" href="<?php echo BASE_URL; ?>" class="btn btn-secondary btn-lg  mr-1 " style="color: #fff">Retour</a>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php

        $conn = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
        
        $conn->set_charset("utf16");

        require_once('vendor/php-excel-reader/excel_reader2.php');
        require_once('vendor/SpreadsheetReader.php');

        if (isset($_POST["import"]))
        {
          $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

          if(in_array($_FILES["file"]["type"], $allowedFileType))
		  {
                $targetPath = 'uploads/' . $_FILES['file']['name'];

                move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

                $Reader = new SpreadsheetReader($targetPath);
                $sheetCount = count($Reader->sheets());
                
                for($i=0;$i<$sheetCount;$i++)
                {
                    $count = 0;
                    $Reader->ChangeSheet($i);
                    
                    $admin =  auth::user()["id"];

                    foreach ($Reader as $Row)
                    {
                        $count++;

                        if($count>1)
                        {
                            //0. Date achat
                            $date_achat = '';

                            if($Row[0])
                            {
                                $date_achat = ($Row[0] == '')?'NULL':"'" . $Row[0] . "'";
                            }

                            //1. Fournisseur
                            $id_fournisseur = -1;
							
							if(isset($Row[1]))
							{
								$fournisseur = ($Row[1] == '')?1:trim($Row[1]);
								if($fournisseur != '')
								{
									$fournisseur = mysqli_real_escape_string($conn, trim($Row[1]));

                                    $sql = "select * from fournisseur where raison_sociale = '$fournisseur' ";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0)
                                    {
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            $id_fournisseur = $row["id_fournisseur"];
                                        }
                                    }
                                    else 
                                    {
                                        $query = "insert into fournisseur(raison_sociale) values('".$fournisseur."')";
                                        $result = mysqli_query($conn, $query);
                                        $sql = "select * from fournisseur where raison_sociale LIKE '$fournisseur' ";
                                        $resultt = $conn->query($sql);

                                        if ($resultt->num_rows > 0) 
                                        {
                                            while($row = $resultt->fetch_assoc()) 
                                            {
                                                $id_fournisseur = $row["id_fournisseur"];
                                            }
                                        }
                                    }
							    }
							}

                            //2. Remarque
                            $remarque = '';

                            if($Row[2])
                            {
                                $remarque = ($Row[2] == '')?'NULL':"'" . $Row[2] . "'";
                            }

                            //3. Devise
                            $devise = '';

                            if($Row[3])
                            {
                                $devise = ($Row[3] == '')?'NULL':"'" . $Row[3] . "'";
                            }

                            //4. Cout Devise
                            $cout_devise = 1;

                            if($Row[4])
                            {
                                $cout_devise = ($Row[4] == '')?1:$Row[4];
                            }
                           
                            //5. Code produit
                            $id_produit = -1;

                            if(isset($Row[5]))
							{
								$code_produit = ($Row[5] == '')?1:trim($Row[5]);

								if($code_produit != '')
								{
									$code_produit = mysqli_real_escape_string($conn, trim($Row[5]));

                                    $sql = "select * from produit where code_bar = '$code_produit' ";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0)
                                    {
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            $id_produit = $row["id_produit"];
                                        }
                                    }
                                    else 
                                    {
                                        $query = "insert into produit(code_bar) values('" . $code_produit . "')";
                                        $result = mysqli_query($conn, $query);
                                        $sql = "select * from produit where code_bar LIKE '$code_produit' ";
                                        $resultt = $conn->query($sql);

                                        if ($resultt->num_rows > 0) 
                                        {
                                            while($row = $resultt->fetch_assoc()) 
                                            {
                                                $id_produit = $row["id_produit"];
                                                break;
                                            }
                                        }
                                    }
							    }
							}

                            //6. Depot
                            $id_deopt = -1;

                            if(isset($Row[6]))
							{
                                    $depot = mysqli_real_escape_string($conn, trim($Row[6]));

                                    $sql = "select * from depot where nom = '$depot' ";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0)
                                    {
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            $id_depot = $row["id"];
                                        }
                                    }
                                    else 
                                    {
                                        $query = "insert into depot(nom) values('" . $depot . "')";
                                        $result = mysqli_query($conn, $query);
                                        $sql = "select * from depot where nom LIKE '$depot' ";
                                        $resultt = $conn->query($sql);

                                        if ($resultt->num_rows > 0) 
                                        {
                                            while($row = $resultt->fetch_assoc()) 
                                            {
                                                $id_depot = $row["id"];
                                                break;
                                            }
                                        }
                                    }
                            }

                            //7. PU
                            $prix_unitaire = 1;

                            if($Row[7])
                            {
                                $prix_unitaire = ($Row[7] == '')?0:$Row[7];
                            }

                            //8. FApproch
                            $fapproch = 1;

                            if($Row[8])
                            {
                                $fapproch = ($Row[8] == '')?1:$Row[8];
                            }

                            //9. Qte Achete
                            $qte = 0;

                            if($Row[9])
                            {
                                $qte = ($Row[9] == '')?0:trim($Row[9]);
                            }

                            //Insert new achat
                            if(!empty($id_fournisseur)|| !empty($date_achat))
                            {
                                //verify if there is any achat before in the same day
                                $exists = achat_exists($id_fournisseur, $date_achat);
                                
                                //if not create new
                                if(!$exists)
                                {
                                    $query = "INSERT INTO achat(id_fournisseur, date_achat, remarque)
                                          VALUES ($id_fournisseur, $date_achat, $remarque)";
                                    $conn->query($query);
                               }
                                
                               //insert details achats
                               $old = achat_exists($id_fournisseur, $date_achat);

                               $id_achat = $old['id_achat'];
                               $prix_produit = $prix_unitaire * $fapproch;

                               $query = "INSERT INTO detail_achat(id_achat, id_produit, id_depot, prix_produit, qte_achete, devise_produit, cout_device, f_approch)
                                         VALUES ($id_achat, $id_produit, $id_depot, $prix_produit, $qte, $devise, $cout_devise, $fapproch)";

                               $result = $conn->query($query);

                               /*if($result)
                               {
                                  
                               }*/
                            }
                        }
                    }
                }
                echo "<br><div style='width:100%;' class='alert alert-success'>Félicitation! vos achat ont été bien importer.</div>";
          }
          else
          { 
                $type = "error";
                $message = "Invalid File Type. Upload Excel File.";
          }
        }
?>    

        </div>
    </div>
  
    <!-- JS Scripts -->
    <script src="main/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="main/vendor/bootstrap/js/popper.js"></script>
    <script src="main/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="main/vendor/select2/select2.min.js"></script>
    <script src="main/js/main.js"></script>
    <script>
        $('#produits').DataTable({
                        responsive: true,
                        columnDefs: [
                                    {
                                "targets": [ 0 ],
                                "visible": false,
                                    },
                                    { responsivePriority: 1, targets: -4 },
                                    { responsivePriority: 2, targets: -2 }
                            ],
                        bPaginate: false, 
                        bFilter: false, 
                        bInfo: false,
                    });
    </script>
</body>

</html>