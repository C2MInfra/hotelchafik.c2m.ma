<?php 
include('../../evr.php'); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Import des produits</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->  
    <link rel="icon" type="image/png" href="main/images/icons/favicon.ico"/>
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="main/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="main/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="main/vendor/animate/animate.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="main/vendor/select2/select2.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="main/vendor/perfect-scrollbar/perfect-scrollbar.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="main/css/util.css">
    <link rel="stylesheet" type="text/css" href="main/css/main.css">
<!--===============================================================================================-->
</head>
<body>

    <div class="limiter">
        <div class="container-table100">


            <div class="row" style="width: 600px;">
                <div class="col align-self-start">
                    <div class="card mb-12">
                        <div class="card-body">
                                <form action="#" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="image"><span style="font-weight: bold;">Attention:</span> Avant d'importer, téléchargez <a href="<?php echo BASE_URL."views/produit/produits.xlsx" ?>">ce ficher</a> et inserez vos produits pour importer correctement vos données. </label>
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
                                        <button type="submit" id="submit" name="import" class="btn btn-success btn-lg  mr-1 ">Import</button>
                                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary btn-lg  mr-1 " style="color: #fff">Retour</a>
                                    </div>
                                    
                                </form>

                        </div>
                    </div>
                </div>
            </div>

        <?php
        $conn = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
        require_once('vendor/php-excel-reader/excel_reader2.php');
        require_once('vendor/SpreadsheetReader.php');

        if (isset($_POST["import"]))
        {
               
          $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
          
          if(in_array($_FILES["file"]["type"],$allowedFileType)){

                $targetPath = 'uploads/'.$_FILES['file']['name'];
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
                        if($count>1){

                            $code = "";
                            if(isset($Row[0])) {
                                $code = mysqli_real_escape_string($conn,$Row[0]);
                            }
                            
                            $designation = "";
                            if(isset($Row[1])) {
                                $designation = mysqli_real_escape_string($conn,$Row[1]);
                            }

                            $nom_categorie = "";
                            if(isset($Row[2])) {
                                $nom_categorie = mysqli_real_escape_string($conn,$Row[2]);



                                $sql = "select * from categorie where nom LIKE '%$nom_categorie%' ";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    // output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        $id_c = $row["id_categorie"];
                                    }
                                }else {
                                    $query = "insert into categorie(nom,id_user) values('".$nom_categorie."','".$admin."')";
                                    $result = mysqli_query($conn, $query);

                                    $sql = "select * from categorie where nom LIKE '%$nom_categorie%' ";
                                    $resultt = $conn->query($sql);

                                    if ($resultt->num_rows > 0) {
                                        // output data of each row
                                        while($row = $resultt->fetch_assoc()) {
                                            $id_c = $row["id_categorie"];
                                        }
                                    }
                                }
                            }
                            

                            $prix_achat = "";
                            if(isset($Row[3])) {
                                $prix_achat = mysqli_real_escape_string($conn,$Row[3]);
                            }

                            $prix_vente = "";
                            if(isset($Row[4])) {
                                $prix_vente = mysqli_real_escape_string($conn,$Row[4]);
                            }


                            $image = "";
                            if(isset($Row[5])) {
                                $image = mysqli_real_escape_string($conn,$Row[5]);
                            }

                            $qte = "";
                            if(isset($Row[6])) {
                                $qte = mysqli_real_escape_string($conn,$Row[6]);
                            }

                            $tva = "";
                            if(isset($Row[7])) {
                                $tva = mysqli_real_escape_string($conn,$Row[7]);
                            }
                            
                            if (!empty($code) || !empty($designation) || !empty($nom_categorie) || !empty($prix_achat) || !empty($prix_vente) || !empty($image) || !empty($qte) || !empty($tva) ) {

                                $queryselect = "select * from produit where code_bar = '".$code."' ";
                                $resultselect = $conn->query($queryselect);

                                if ($resultselect->num_rows > 0) {

                                    $queryupdate = "update produit set prix_achat ='".$prix_achat."',prix_vente ='".$prix_vente."',designation ='".$designation."',id_categorie ='".$id_c."',image ='".$image."',qte_actuel ='".$qte.",tva ='".$tva."',id_user = '".$admin."' where code_bar = '".$code."' ";


                                    $resultupdate = mysqli_query($conn, $queryupdate);

                                }else{

                                    $queryinsert = "insert into produit(code_bar,designation,id_categorie,prix_achat,prix_vente,image,qte_actuel,tva,emplacement,archive,id_user) values('".$code."','".$designation."','".$id_c."','".$prix_achat."','".$prix_vente."','".$image."','".$qte."','".$tva."',5,0,'".$admin."')";


                                    $resultinsert = mysqli_query($conn, $queryinsert);
                                
                                    if (! empty($resultinsert)) {
                                        $type = "success";
                                        $message = "Excel Data Imported into the Database";
                                    } else {
                                        $type = "error";
                                        $message = "Problem in Importing Excel Data";
                                    }

                                }


                            }
                        }
                    }
                }
          }
          else
          { 
                $type = "error";
                $message = "Invalid File Type. Upload Excel File.";
          }
        }
        ?>    


        <div class="wrap-table100">
                <div class="table100">

                    <?php
                        $sqlSelect = "SELECT *,c.nom as 'nom_categorie' FROM produit p inner join categorie c on (c.id_categorie = p.id_categorie) group BY id_produit desc";
                        $result = mysqli_query($conn, $sqlSelect);

                    if (mysqli_num_rows($result) > 0)
                    {
                    ?>

                    <table>
                        <thead>
                            <tr class="table100-head">
                                <th style="width: 100px;text-align: center;">Code</th>
                                <th style="width: 100px;text-align: center;">Designation</th>
                                <th style="width: 100px;text-align: center;">Categorie</th>
                                <th style="width: 100px;text-align: center;">Prix d'achat</th>
                                <th style="width: 100px;text-align: center;">Prix de vente</th>
                                <th style="width: 100px;text-align: center;">Image</th>
                                <th style="width: 100px;text-align: center;">Qte</th>
                            </tr>
                        </thead>
                        

                    <?php
                        while ($row = mysqli_fetch_array($result)) {
                    ?>                  
                        <tbody>
                            <tr>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['code_bar']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['designation']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['nom_categorie']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['prix_achat']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['prix_vente']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['image']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['qte_actuel']; ?></td>
                            </tr>
                    <?php
                        }
                    ?>
                        </tbody>
                    </table>
                    <?php 
                    } 
                    ?>
                </div>
            </div>
        </div>
    </div>


    

<!--===============================================================================================-->  
    <script src="main/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
    <script src="main/vendor/bootstrap/js/popper.js"></script>
    <script src="main/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
    <script src="main/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
    <script src="main/js/main.js"></script>

</body>
</html>