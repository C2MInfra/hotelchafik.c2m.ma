<?php
#hide errors
ob_start();
include('../../evr.php');
include('../../newpdf/tcpdf_include.php');
include('../../newpdf/tcpdf.php');
include("../../model/convert.php");
?>

<?php
$id = $_GET['id'];
$detail_reservation = new detail_reservation();
$detail_reservation = $detail_reservation->getReservationDetailsValid($id);
$reservation = new reservation();
$client = $reservation->get_client_byid_reservation($id);
$reserv = $reservation->selectById($id);
$facture = new facture();
$fact = $facture->selectById($_GET["idf"]);
ob_start();
// set default header data
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', '', array(255,255,255), array(0,255,255));
$pdf->setFooterData(array(0,64,0), array(0,64,128));
	   
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	   
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
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
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
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
$pdf->AddPage();


$code ='FV.' . $fact["num_fact"] .' - ' . date("Y");

$style1d = array('position' => 'S', 'align' => 'C', 'stretch' => false, 'fitwidth' => true, 'cellfitalign' => '', 'border' => false, 'hpadding' => '50', 'vpadding' => '30', 'fgcolor' => array(0, 0, 0), 'bgcolor' => false, 'text' => false, 'font' => 'helvetica', 'fontsize' => 10, 'stretchtext' => 4);

$pdf->setXY(144, 34);
$pdf->write1DBarcode($code, 'C128B', '', '', '', 22, 3, $style1d, 'T');


$societe= connexion::getConnexion()->query("SELECT * FROM societe")->fetch(PDO::FETCH_OBJ);

$id_vente = str_replace(',', '', $fact['id_vente']);


if($reserv["date_reservation"]!='0000-00-00')
{
   $str = date("Y");
}
else
  $str=date('Y');

$pdf->SetFont('dejavusans', '', 8, '', true);
$str="";
if($reserv["date_reservation"]!='0000-00-00')
{
   $str=date('Y',strtotime($reserv["date_reservation"]));
}

$id_vente = str_replace(',', '', $fact['id_vente']);
$pdf->SetFont('dejavusans', '', 10, 'C', true);
$pdf->setCellPadding(2);
$pdf->SetXY(10,10);
$pdf->Cell(50, 5, 'Café * Hotel * Chafik', 0, 0, 'L', 0, 0);
$pdf->SetXY(10,15);
$pdf->Cell(50, 5, '12 Bd Bir Anzarane', 0, 0, 'L', 0, 0);
$pdf->SetXY(10,20);
$pdf->Cell(50, 5, 'OUJDA', 0, 0, 'l', 0, 0);
$pdf->SetXY(10,25);
$pdf->Cell(50, 5, 'Tel: 05 36 68 29 50', 0, 0, 'l', 0, 0);
$pdf->SetXY(10,30);
$pdf->Cell(50, 5, 'Fax: 05 36 68 84 34', 0, 0, 'l', 0, 0);

$pdf->Image('icon.png',80,10,50,25);

$pdf->SetFont('dejavusans', '', 10, 'C', true);
$pdf->setCellPadding(2);
$pdf->SetXY(146,10);
$pdf->Cell(50, 5, 'نزل * مقهى * شفيق', 0, 0, 'R', 0, 0);
$pdf->SetXY(146,15);
$pdf->Cell(50, 5, 'شارع بئر إنزران 12', 0, 0, 'R', 0, 0);
$pdf->SetXY(146,20);
$pdf->Cell(50, 5, 'وجدة', 0, 0, 'R', 0, 0);
$pdf->SetXY(146,25);
$pdf->Cell(50, 5, 'Tel: 05 36 68 29 50', 0, 0, 'R', 0, 0);
$pdf->SetXY(146,30);
$pdf->Cell(50, 5, 'Fax: 05 36 68 84 34', 0, 0, 'R', 0, 0);

$pdf->SetTextColor(15,15,15);
$pdf->SetFillColor(139, 130, 125);
$pdf->SetXY(142,50);
$pdf->setCellPadding(2);
$pdf->SetFont('dejavusans', '', 10, 'C', true);
$pdf->Cell(50, 5, 'Facture N°: ' . $code, 0, 0, 'C', 0, 0);

$pdf->SetFont('dejavusans', '', 10, 'C', true);


$pdf->setXY(10, 60);
#Custom

$leftInfo = '
<style>
  h5,p{font-size:7pt; font-weight:400;color: rgb(20,20,20);}
   h3, h5{color: rgb(20,20,20);}
   h5{
     font-size:12pt !important; margin-bottom:20px;
   }
   tr
   {
    padding:30px;
   }
  
</style>
<div>
<table>
   <tr>
      <td width="24%" style="height:10; width:10%;">Client</td>
      <td width="4%"> : </td>
      <td width="71%">' . $client['nom'] . '</td>
   </tr>
   <!--<tr>
      <td width="24%"></td>
      <td></td>
      <td></td>
   </tr>
   <tr>
      <td width="24%" style="height:10; width:10%;">ICE</td>
      <td width="4%"> : </td>
      <td>' . $client['ice'] . '</td>
   </tr>-->
   <tr>
      <td width="24%"></td>
      <td></td>
      <td></td>
   </tr>
   <tr>
      <td width="24%" style="height:10; width:10%;">Télèphone</td>
      <td width="4%"> : </td>
      <td>' . $client['telephone'] . '</td>
   </tr>
   <tr>
      <td width="24%"></td>
      <td></td>
      <td></td>
   </tr>
   <tr>
      <td width="24%" style="height:10; width:10%;">Pays</td>
      <td width="4%"> : </td>
      <td>' . $client['pays'] . '</td>
   </tr>
   <tr>
      <td width="24%"></td>
      <td></td>
      <td></td>
   </tr>
   <tr>
      <td width="24%" style="height:10; width:10%;">Nationalité</td>
      <td width="4%"> : </td>
      <td>' . $client['nationalite'] . '</td>
   </tr>
   <tr>
      <td width="24%"></td>
      <td></td>
      <td></td>
   </tr>
   <tr>
      <td width="24%" style="height:10; width:10%;">Adresse</td>
      <td width="4%"> : </td>
      <td>' . trim($client['adresse']) . '</td>
   </tr>
</table>
</div>';
	   
$rightInfo = "
<style>
  h3{color: rgb(20,20,20); text-align:center;}
  h5,p{font-size:8pt; font-weight:700;color: #000;text-align:left;}
</style>
<div>
   <h5>Oujda Le: " . date('d/m/Y',strtotime($fact["date_facture"])). "</h5>
	 <h5>Objet: " . $fact['remarque'] . "</h5>
	 
	 
</div>";
	   
$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(37,151,213)));	   
$pdf->SetFillColor(255,255,255);
$pdf->setCellPadding(3,0,3,0 );
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->writeHTMLCell(86, 36,'','', $leftInfo, 1, 0 , 'L', 1); 
$pdf->Cell(8);
	  
$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150)));
     
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('dejavusans', '', 7, '', true);
$pdf->setCellPadding(3,0,3,0 );
$pdf->SetFont('dejavusans', '', 7, '', true);
$pdf->writeHTMLCell(86, 36,'','', $rightInfo, 0, 1 , 'C',0);   

$pdf->Ln(20); 





$pdf->SetFont('dejavusans', '', 6, '', true);


$tbl = '

<style>

.mytable  td {


        border-left : 1px solid #000;
        border-right : 1px solid #000;
 }

    .mytable  th {

       padding-butom:0;

    }

    .mytable td
   {
      border-bottom : 1px solid #000;
      border-right : 1px solid #000;
      font-size:8.5pt;
   }
</style>

<table cellspacing="0" cellpadding="3" border="1" class="mytable">

    <tr style="background-color:#2597d5;">

      <th  align="center" height="10" width="15%" style="color:white; font-weight:800; font-size:8.5pt;">Numero chambre</th>
      <th  align="center" height="10" width="15%" style="color:white; font-weight:800; font-size:8.5pt;">Prix</th>
      <th  align="center" height="10" width="10%" style="color:white; font-weight:800; font-size:8.5pt;">Nbr nuits</th>
      <th  align="center" height="10" width="10%" style="color:white; font-weight:800; font-size:8.5pt;">Nbr personnes</th>
      <th  align="center" height="10" width="15%" style="color:white; font-weight:800; font-size:8.5pt;">date d\'arrivé</th>
      <th  align="center" height="10" width="15%" style="color:white; font-weight:800; font-size:8.5pt;">date depart</th>
      <th  align="center" height="10" width="20%" style="color:white; font-weight:800; font-size:8.5pt;">Montant</th>
    </tr>

  ';

$total = 0;
foreach($detail_reservation as $key => $ligne)
{
$tbl .= '
   <tr >
        <td  align="center">' . $ligne["numero_chambre"] . '</td>
        <td  align="right">'.number_format($ligne['montant'],2).'</td>
        <td align="center">'.$ligne["nombre_nuits"].'</td>
        <td align="center">'.$ligne["nombre_personnes"].'</td>
        <td  align="center">'.$ligne["date_arriver"] . '</td>
        <td  align="center">'.$ligne["date_depart"] . '</td>
        <td  align="right">'. number_format($ligne['montant']*$ligne['nombre_nuits'],2).'</td>
    </tr>
   ';
  $total += $ligne['montant']*$ligne['nombre_nuits'];
}


$pdf->Ln(2);

for ($i=0; $i < 12  - count($rep)  ; $i++) { 
/*
  $tbl .= '

   <tr>

        <td border="0" ></td>

        <td border="0" ></td>

        <td border="0"  align="right"></td>

        <td border="0"  align="right"></td>

        <td border="0"  align="right"></td>

        <td border="0"  align="right"></td>

        <td border="0"  align="center"></td>

  </tr>

   ';*/


}

$tbl  .= "</table> ";

$pdf->SetX(10);

$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->Ln(2);

$printtotal = '

<style>
    .tab-foot  td 
	{
        font-size:8.5pt;
    }
    .tab-foot  th 
	{
       font-size:8.5pt;
    }
</style>
<table>
<tr>
<td>
<table cellspacing="0" cellpadding="6"   class="tab-foot" width="100%">
    <tr>
	  <td width="60%">
	  </td>
       <th border="1" align="left" height="10" width="20%" style="background-color:#2597d5; color:white;">Total </th>
	  <td align="right" border="1" width="20%">' . number_format($total, 2, ".", " ") . '</td>
    </tr>
     <tr>
	   <td width="60%">
	   </td>
     
    </tr>
	<tr>
	   
    </tr>
	
	
  </table>
</td>
  ';


$pdf->SetX(10);
$pdf->writeHTML($printtotal, true, false, false, false, '');

//$pdf->Ln(5);
//$pdf->writeHTML("<p>Arréter la présente facture a la somme de :</p>", true, false, false, false, '');

//$ttc = $totalht;
//$str='<p>' .chifre_en_lettre(intval(($ttc)));
//
//if(intval(round(($ttc)-intval(($ttc)), 2)*100)>0 )
//{
//
//$str .=' et ';
//
//if (round(($ttc),2) - intval(($ttc ))<0.1 )
//
//$str.= 'Zero ' ;
//
//
//$str.= trim(chifre_en_lettre(substr(strstr(number_format(($ttc),2,"."," "),"."),1) ,' centimes'));
//
//}
//
//$str.="</p>";

//$pdf->Ln(2);
//
//$pdf->writeHTML($str, true, false, false, false, '');
$pdf->Ln(7);
$pdf->Ln(6);

if($regs)
{
	$pdf->SetFont('dejavusans', '', 7, '', true);
	$pdf->SetFont('dejavusans', '', 7, '', true);
	$pdf->Cell(32,5,'Référence réglements' ,0,1,'L',0);
	
	foreach($regs as $reg)
	{
		$num = ($reg->num_cheque != '' && $reg->num_cheque != '0')?$reg->num_cheque: '';
		$pdf->SetFont('dejavusans', '', 7, '', true);
		$pdf->SetFont('dejavusans', '', 7, '', true);
		$pdf->Cell(32,5,$reg->mode_reg . ': ' . $num . '(' . number_format($reg->montant, 2) . ' DH)' ,0,1,'L',0);
	}
}
$pdf->SetFont('dejavusans', 'B', 7, '', true);

ob_end_clean();
$pdf->Output($client['nom'] . '_facture.pdf', 'I');

?>
