<!DOCTYPE html>
<html lang="en">
<head>
	<title>Table V01</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>


	<h2>Import Fournisseurs</h2>
	    
	    <div class="outer-container">
	        <form action="#" method="post"
	            name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
	            <div>
	                <label>Choose Excel
	                    File</label> <input type="file" name="file"
	                    id="file" accept=".xls,.xlsx">
	                <button type="submit" id="submit" name="import"
	                    class="btn-submit">Import</button>
	        
	            </div>
	        
	        </form>
	        
	    </div>
	    <div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>

	<?php
	$conn = mysqli_connect("localhost","root","","import_excel");
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
	        {   $count = 0;
	            
	            $Reader->ChangeSheet($i);

	           
	            
	            foreach ($Reader as $Row)
	            {
	                $count++;
	                if($count>1){

	                    $raison_sociale = "";
	                    if(isset($Row[0])) {
	                        $raison_sociale = mysqli_real_escape_string($conn,$Row[0]);
	                    }

	                    $adresse = "";
	                    if(isset($Row[1])) {
	                        $adresse = mysqli_real_escape_string($conn,$Row[1]);
	                    }

	                    $email = "";
	                    if(isset($Row[2])) {
	                        $email = mysqli_real_escape_string($conn,$Row[2]);
	                    }

	                    $telephone = "";
	                    if(isset($Row[3])) {
	                        $telephone = mysqli_real_escape_string($conn,$Row[3]);
	                    }

	                    $ice = "";
	                    if(isset($Row[4])) {
	                        $ice = mysqli_real_escape_string($conn,$Row[4]);
	                    }

	                    $rc = "";
	                    if(isset($Row[4])) {
	                        $rc = mysqli_real_escape_string($conn,$Row[4]);
	                    }

	                    $iff = "";
	                    if(isset($Row[5])) {
	                        $iff = mysqli_real_escape_string($conn,$Row[5]);
	                    }
	                    
	                    
	                    
	                    if (!empty($raison_sociale) || !empty($adresse) || !empty($email) || !empty($telephone) || !empty($ice) || !empty($rc) || !empty($iff)) {


	                        $queryselect = "select * from fournisseur where raison_sociale = '".$raison_sociale."' ";
	                        $resultselect = $conn->query($queryselect);

	                        if ($resultselect->num_rows > 0) {

	                            $queryupdate = "update fournisseur set adresse ='".$adresse."',adresse ='".$adresse."',email ='".$email."',telephone ='".$telephone."',ice ='".$ice."',rc ='".$rc."',iff ='".$iff."' where raison_sociale = '".$raison_sociale."' ";
	                            $resultupdate = mysqli_query($conn, $queryupdate);

	                        }else{

	                            $queryinsert = "insert into fournisseur (raison_sociale,adresse,email,telephone,ice,rc,iff,archive,id_user) values('".$raison_sociale."','".$adresse."','".$email."','".$telephone."','".$ice."','".$rc."','".$iff."',0,35)";
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
	    


	<div class="limiter">
		<div class="container-table100">
			<div class="wrap-table100">
				<div class="table100">

					<?php
					    $sqlSelect = "SELECT * FROM fournisseur ";
					    $result = mysqli_query($conn, $sqlSelect);

					if (mysqli_num_rows($result) > 0)
					{
					?>

					<table>
						<thead>
							<tr class="table100-head">
								<th class="column1">raison_sociale</th>
								<th class="column2">adresse</th>
								<th class="column3">email</th>
								<th class="column4">telephone</th>
								<th class="column5">ice</th>
								<th class="column6">rc</th>
								<th class="column7">iff</th>
							</tr>
						</thead>
						

					<?php
					    while ($row = mysqli_fetch_array($result)) {
					?>                  
					    <tbody>
					        <tr>
					            <td class="column1"><?php  echo $row['raison_sociale']; ?></td>
					            <td class="column2"><?php  echo $row['adresse']; ?></td>
					            <td class="column3"><?php  echo $row['email']; ?></td>
					            <td class="column4"><?php  echo $row['telephone']; ?></td>
					            <td class="column5"><?php  echo $row['ice']; ?></td>
					            <td class="column6"><?php  echo $row['rc']; ?></td>
					            <td class="column7"><?php  echo $row['iff']; ?></td>
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
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>