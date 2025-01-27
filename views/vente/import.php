<?php 

    include('../../evr.php'); 

    $conn = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
    $result = $conn->query('select * from vente limit 1');
    
    function vente_exists($id_client, $date)
    {
        $conn = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
        $sql = "select * from vente where id_client = $id_client AND date_vente = $date";
        
        $result = $conn->query($sql);

        return $result->fetch_assoc();
    }

    $query1 = $result1=connexion::getConnexion()->query("SELECT numbon as dernier_bon FROM vente where  numbon != 0 or numbon !='' ORDER BY id_vente DESC LIMIT 1");
    $result1 = $query1->fetch(PDO::FETCH_OBJ);
    $last_num = $result1->dernier_bon + 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
        <title>Importation des ventes</title>
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
                                            <label for="image"><span style="font-weight: bold;">Attention:</span> Avant d'importer, téléchargez <a href="<?php echo BASE_URL."views/vente/importation_ventes_canvas.xlsx" ?>" style="color:#28a745;">ce ficher</a> et inserez vos ventes pour importer correctement vos données. </label>
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

                    foreach ($Reader as $index => $Row)
                    {
                        $count++;

                        if($count>1)
                        {
                            //0. Date vente
                            $date_vente = '';

                            if($Row[0])
                            {
                                $date_vente = ($Row[0] == '')?'NULL':"'" . $Row[0] . "'";
                            }

                            //1. client
                            $id_client = -1;
							
							if(isset($Row[1]))
							{
								$client = ($Row[1] == '')?1:trim($Row[1]);
								if($client != '')
								{
									$client = mysqli_real_escape_string($conn, trim($Row[1]));

                                    $sql = "select * from client where nom = '$client' ";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0)
                                    {
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            $id_client = $row["id_client"];
                                        }
                                    }
                                    else 
                                    {
                                        $query = "insert into client(nom) values('".$client."')";
                                        $result = mysqli_query($conn, $query);
                                        $sql = "select * from client where nom LIKE '$client' ";
                                        $resultt = $conn->query($sql);

                                        if ($resultt->num_rows > 0) 
                                        {
                                            while($row = $resultt->fetch_assoc()) 
                                            {
                                                $id_client = $row["id_client"];
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

                            //3. Code produit
                            $id_produit = -1;

                            if(isset($Row[3]))
							{
								$code_produit = ($Row[3] == '')?1:trim($Row[3]);

								if($code_produit != '')
								{
									$code_produit = mysqli_real_escape_string($conn, trim($Row[3]));

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

                            //4. Depot
                            $id_deopt = -1;

                            if(isset($Row[4]))
							{
                                    $depot = mysqli_real_escape_string($conn, trim($Row[4]));

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

                            //5. PU
                            $prix_unitaire = 1;

                            if($Row[5])
                            {
                                $prix_unitaire = ($Row[5] == '')?0:$Row[5];
                            }

                            //6. Remise
                            $remise = 0;

                            if($Row[6])
                            {
                                $remise = ($Row[6] == '')?0:$Row[6];
                            }

                            //7. Qte Vendu
                            $qte = 0;

                            if($Row[7])
                            {
                                $qte = ($Row[7] == '')?0:trim($Row[7]);
                            }

                            //8. Unite
                            $valunit = '';

                            if($Row[8])
                            {
                                $valunit = ($Row[8] == '')?0:$Row[8];
                            }

                            //9. Unite
                            $unite = '';

                            if($Row[9])
                            {
                                $unite = ($Row[9] == '')?'NULL':"'" . $Row[9] . "'";
                            }

                            //Insert new vente
                            if(!empty($id_client)|| !empty($date_vente))
                            {
                                //verify if there is any vente before in the same day
                                $exists = vente_exists($id_client, $date_vente);
                                
                                //if not create new
                                if(!$exists)
                                {
                                    $query = "INSERT INTO vente(id_client, date_vente, remarque)
                                          VALUES ($id_client, $date_vente, $remarque)";
                                    $conn->query($query);

                               }
                                
                               //insert details ventes
                               $old = vente_exists($id_client, $date_vente);

                               $id_vente = $old['id_vente'];

                               $query = "INSERT INTO detail_vente(id_vente, id_produit, id_depot, prix_produit, qte_vendu, remise, unit, valunit)
                                         VALUES ($id_vente, $id_produit, $id_depot, $prix_unitaire, $qte, $remise, $unite, $valunit)";

                               $result = $conn->query($query);

                               //transfer au bon livraison
                               $data = vente::getdevis($id_vente);

                               foreach ($data as $value) 
                               {
                                    connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel - ".$value["qte_vendu"]." WHERE  id_produit =".$value["id_produit"] );
                               }
                               
                               $_POST['datebon'] = $Row[0];
                               $_POST['numbon'] = $last_num;
                               $vente = new vente();
                               $query = $vente->update($id_vente);

                            }
                        }
                    }
                }
                echo "<br><div style='width:100%;' class='alert alert-success'>Félicitation! vos ventes ont été bien importer.</div>";
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