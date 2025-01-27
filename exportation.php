<?php
   include 'eve.php';
   include('PHPExcel/PHPExcel.php');


  #prepare query
  $query = "SELECT p.code_bar AS `Code Produit`, p.designation AS `Désignation`, c.nom AS `Famille`, p.prix_achat AS `Prix Achat`, p.prix_vente AS `Prix Vente`, p.image AS `Image`, p.qte_actuel AS `Qte`, p.tva AS `T.V.A`, p.unite AS `Unité`, p.fournisseur AS `Fournisseur`
  FROM produit p
  LEFT JOIN categorie c ON p.id_categorie = c.id_categorie";

  #get data from db
  try
  {
	  $connexion = new PDO('mysql:host=localhost;dbname=' . DATABASE, USER, PASSWORD);
	  $connexion->exec("SET NAMES 'utf8'");
	  $result = $connexion->query($query);
  }
  catch(PDOException $e)
  {
	  echo $e->getMessage();
  }

  $data = $result->fetchAll(PDO::FETCH_ASSOC);

  #set Excel Class
  $objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);
#header
$isPrintHeader = false;
$tot = 1;
$deleted = [];
foreach($data as $index=>$value)
{
		 if(!$isPrintHeader)
		 {
			 foreach(array_keys($value) as $i=>$key)
			 {
				 $objPHPExcel->getActiveSheet()->SetCellValue(chr(65 + $i) . '1', $key);
			 }
			 $isPrintHeader = true;
		 }
         $in = 0;
         foreach($value as $colIndex => $col)
         {
             $objPHPExcel->getActiveSheet()->SetCellValue(chr(65 + $in) . ($index + 2), mb_strtoupper($col,'UTF-8'));
			 
			 $in++;
         }
	
	$tot++;
}

//Style

$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('#5eba7d');
$objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);
foreach (range('A', 'J') as $letra) {            
        $objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setAutoSize(true);
}
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$objPHPExcel->getActiveSheet()->getStyle('A1:J' . $tot)->applyFromArray($styleArray);
//End style


$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

    ob_end_clean();
    header( "Content-type: application/vnd.ms-excel" );
    header('Content-Disposition: attachment;filename=exportation_' . date('d_m_y') . '.xls');
    header("Pragma: no-cache");
    header("Expires: 0");
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    ob_end_clean();
?>

