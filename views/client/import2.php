<?php 
include('../../evr.php'); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Import des clients</title>
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
                                            <label for="image"><span style="font-weight: bold;">Attention:</span> Avant d'importer, téléchargez <a href="<?php echo BASE_URL."views/client/clients.xlsx" ?>">ce ficher</a> et inserez vos clients pour importer correctement vos données. </label>
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

                            $code  = "";
                            if(isset($Row[0])) {
                                $code = mysqli_real_escape_string($conn,$Row[0]);
                            }
                            
                            $nom = "";
                            if(isset($Row[1])) {
                                $nom = mysqli_real_escape_string($conn,$Row[1]);
                            }
                            

                            $prenom = "";
                            if(isset($Row[2])) {
                                $prenom = mysqli_real_escape_string($conn,$Row[2]);
                            }

                            $cin = "";
                            if(isset($Row[3])) {
                                $cin = mysqli_real_escape_string($conn,$Row[3]);
                            }

                            $ice = "";
                            if(isset($Row[4])) {
                                $ice = mysqli_real_escape_string($conn,$Row[4]);
                            }


                            $telephone = "";
                            if(isset($Row[5])) {
                                $telephone = mysqli_real_escape_string($conn,$Row[5]);
                            }

                            $email = "";
                            if(isset($Row[6])) {
                                $email = mysqli_real_escape_string($conn,$Row[6]);
                            }

                            $adresse = "";
                            if(isset($Row[7])) {
                                $adresse = mysqli_real_escape_string($conn,$Row[7]);
                            }

                            $image = "";
                            if(isset($Row[8])) {
                                $image = mysqli_real_escape_string($conn,$Row[8]);
                            }
                            
                            if (!empty($code) || !empty($nom) || !empty($prenom) || !empty($cin) || !empty($ice) || !empty($telephone) || !empty($email) || !empty($adresse) || !empty($image) ) {

                                $queryselect = "select * from client where code = '".$code."' ";
                                $resultselect = $conn->query($queryselect);

                                if ($resultselect->num_rows > 0) {

                                    $queryupdate = "update client set nom ='".$nom."', prenom ='".$prenom."', cin ='".$cin."', ice ='".$ice."', telephone='".$telephone."', email ='".$email."', adresse ='".$adresse."',id_user = '".$admin."' where code = '".$code."' ";


                                    $resultupdate = mysqli_query($conn, $queryupdate);

                                }else{

                                    $queryinsert = "insert into client(code,nom,prenom,cin,ice,telephone,email,adresse,image,archive,id_user) values('".$code."','".$nom."','".$prenom."','".$cin."','".$ice."','".$telephone."','".$email."','".$adresse."','".$image."',0,'".$admin."')";


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
                        $sqlSelect = "SELECT * from client group BY id_client desc";
                        $result = mysqli_query($conn, $sqlSelect);

                    if (mysqli_num_rows($result) > 0)
                    {
                    ?>

                    <table>
                        <thead>
                            <tr class="table100-head">
                                <th style="width: 100px;text-align: center;">code</th>
                                <th style="width: 100px;text-align: center;">nom</th>
                                <th style="width: 100px;text-align: center;">prenom</th>
                                <th style="width: 100px;text-align: center;">cin</th>
                                <th style="width: 100px;text-align: center;">ice</th>
                                <th style="width: 100px;text-align: center;">telephone</th>
                                <th style="width: 100px;text-align: center;">email</th>
                                <th style="width: 100px;text-align: center;">adresse</th>
                                <th style="width: 100px;text-align: center;">image</th>
                            </tr>
                        </thead>
                        

                    <?php
                        while ($row = mysqli_fetch_array($result)) {
                    ?>                  
                        <tbody>
                            <tr>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['code']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['nom']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['prenom']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['cin']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['ice']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['telephone']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['email']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['adresse']; ?></td>
                                <td style="width: 100px;text-align: center;"><?php  echo $row['image']; ?></td>
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