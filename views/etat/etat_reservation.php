<?php
#hide errors
ob_start();
include('../../evr.php');
include('../../newpdf/tcpdf_include.php');
include('../../newpdf/tcpdf.php');
include("../../model/convert.php");

if (isset($_POST['dd'])) {
  $reservation = new reservation();
  $data = $reservation->get_reservations_etat($_POST['dd'], $_POST['df']);
}
//if(!isset($_GET["id"]))
//{ 
//	header("Location:index.php"); 
//}
ob_start();
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', '', array(255, 255, 255), array(0, 255, 255));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
  require_once(dirname(__FILE__) . '/lang/eng.php');
  $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
$pdf->AddPage();

$pdf->SetFont('dejavusans', '', 12, '', true);
$pdf->SetLineStyle(array('width' => 0.05, 'cap' => 'butt', 'join' => 'miter', 'solid' => 1, 'color' => array(110, 110, 110)));
$pdf->SetFillColor(180, 180, 180);
$pdf->SetXY(150, 6);
$pdf->setCellPadding(2);
$pdf->Cell(50, 5, 'Reservations', 1, 1, 'C', 1, 0);

$pdf->setCellPadding(0);
$pdf->Ln(2);
$pdf->Ln(12);

$pdf->Ln(6);

$pdf->Cell(30, 10, 'Date: ' . date('d/m/Y', strtotime($_POST["dd"])) . ' - '  . date('d/m/Y', strtotime($_POST["df"])), 0, 1, 'L', 0);

$pdf->SetFont('dejavusans', '', 7, '', true);
$tbl = '
<style>
   .mytable  td 
   {
        border-left : 1px solid #000;
        border-right : 1px solid #000;
   }
   .mytable  th 
   {
       padding-butom:0;
   }
</style>
<table cellspacing="0" cellpadding="3" border="1" class="mytable">
 <tr>
       <th  align="center" height="10" width="10%">Id</th>
        <th  align="center" height="10" width="20%">Client</th>
        <th  align="center" height="10" width="10%">Date reservation</th>
        <th  align="center" height="10" width="12.5%">Montant</th>
        <th  align="center" height="10" width="12.5%">Regelement</th>
        <th  align="center" height="10" width="10%">Reste</th>
        <th  align="center" height="10" width="25%">Remarque</th>
</tr>
  ';

foreach ($data as $ligne) {
  $tbl .= '
   <tr>
        <td align="center">' . $ligne['id_reservation'] . '</td>
        <td style="font-size:11px;" align="left">' . $ligne['nom'] . '</td>
        <td align="center">' . $ligne['date_reservation'] . '</td>
        <td align="rigth">' . number_format($ligne['montant_total'],2) . '</td>
        <td  align="rigth">' . number_format($ligne['regelement'],2) . '</td>
        <td align="rigth">' . number_format($ligne['rest'] ,2) . '</td>
        <td align="left">' . $ligne['remarque']  . '</td>
    </tr>
   ';

  $total_montant += $ligne['montant_total'];
  $total_regelement += $ligne['regelement'];
  $total_reste += $ligne['rest'];
}

for ($i = 0; $i < 22  - count($rep); $i++) {

  $tbl .= '
   <tr>
        <td border="0" ></td>
        <td border="0" ></td>
        <td border="0"  align="right"></td>
        <td border="0"  align="right"></td>
        <td border="0"  align="right"></td>
        <td border="0"  align="right"></td>
        <td border="0"  align="right"></td>
    </tr>
   ';
}

$tbl  .= "</table> ";

$pdf->SetX(10);

$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->Ln(2);
$printtotal = '

<style>
    .mytable  td 
	{
        border-left : 1px solid #000;
    }
    .mytable  th 
	{
       padding-butom:0;
    }
</style>
<table>
<tr>
<td>
<table cellspacing="0" cellpadding="6"   class="" width="100%">
    <tr>
	  <td width="60%"></td>
      <th border="1" align="left" height="10" width="20%">Montant Total</th>
	  <td align="right" border="1" width="20%">' . number_format($total_montant, 2, ".", " ") . '</td>
    </tr>
    <tr>
	  <td width="60%"></td>
      <th border="1" align="left" height="10" width="20%">Regelement Total</th>
	  <td align="right" border="1" width="20%">' . number_format($total_regelement, 2, ".", " ") . '</td>
    </tr>
    <tr>
	  <td width="60%"></td>
      <th border="1" align="left" height="10" width="20%">Reste Total</th>
	  <td align="right" border="1" width="20%">' . number_format($total_reste, 2, ".", " ") . '</td>
    </tr>
  </table>
</td>
  ';

$pdf->SetX(10);

$pdf->writeHTML($printtotal, true, false, false, false, '');
ob_end_clean();
$pdf->Output('etat.pdf', 'I');

?>
