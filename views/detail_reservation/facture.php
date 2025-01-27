<?php

#hide errors

ob_start();

include('../../evr.php');

include('../../newpdf/tcpdf_include.php');

include('../../newpdf/tcpdf.php');

include("../../model/convert.php");

?>



<?php

//if(!isset($_GET["id"]))

//{ 

//	header("Location:index.php"); 

//}

$id = $_GET['id'];

$detail_reservation = new detail_reservation();

$detail_reservation = $detail_reservation->getReservationDetails($id);

$reservation = new reservation();

$client = $reservation->get_client_byid_reservation($id);

$reserv = $reservation->selectById($id);

ob_start();

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



// set default header data

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



$pdf->SetFont('dejavusans', '', 12, '', true);

$pdf->SetLineStyle(array('width' => 0.05, 'cap' => 'butt', 'join' => 'miter', 'solid' => 1, 'color' => array(110, 110, 110)));

     $pdf->SetFillColor(180, 180, 180);

$pdf->SetXY(150,6);

$pdf->setCellPadding(2);

// $pdf->Cell(50,5,'Reservations' ,1,1,'C',1,0);

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

$pdf->SetFont('dejavusans', '', 10, 'C', true);


$pdf->setXY(10, 60);
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

   <h5>Oujda Le: " . date('d/m/Y'). "</h5>

	 <h5>Objet: " . $reserv['remarque'] . "</h5>

	 

	 

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

       <th  align="center" height="10" width="15%">Numero chambre</th>

        <th  align="center" height="10" width="15%">Prix</th>

        <th  align="center" height="10" width="10%">Nbr nuits</th>

        <th  align="center" height="10" width="10%">Nbr personnes</th>

        <th  align="center" height="10" width="12.5%">date d\'arrivé</th>

        <th  align="center" height="10" width="12.5%">date depart</th>

        <th  align="center" height="10" width="10%">Check-In</th>

        <th  align="center" height="10" width="15%">Check-Out</th>

</tr>

  ';



foreach($detail_reservation as $ligne){

  $tbl .= '

   <tr>

        <td>'.$ligne['numero_chambre'].'</td>

        <td style="font-size:11px;" align="right">'.number_format($ligne['montant'],2).' DH</td>

        <td align="center">'.$ligne['nombre_nuits'].'</td>

        <td align="center">'.$ligne['nb_personnes'].'</td>

        <td align="center">'.$ligne['date_arriver'].'</td>

        <td  align="center">'.$ligne['date_depart'].'</td>

        <td align="center">' . ($ligne['checkin'] == 1 ? 'Oui' : 'Non') . '</td>

        <td  align="center">'.($ligne['checkout'] == 1 ? 'Oui' : 'Non') .'</td>

    </tr>

   ';



$total+=$ligne['montant']*$ligne['nombre_nuits'];

}



for ($i=0; $i < 22  - count($rep)  ; $i++) 

{ 



  $tbl .= '

   <tr>

        <td border="0" ></td>

        <td border="0" ></td>

        <td border="0"  align="right"></td>

        <td border="0"  align="right"></td>

        <td border="0"  align="right"></td>

        <td border="0"  align="right"></td>

        <td border="0"  align="right"></td>

        <td border="0"  align="center"></td>

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

      <th border="1" align="left" height="10" width="20%">Montant</th>

	  <td align="right" border="1" width="20%">' . number_format($total, 2, ".", " ") . '</td>

    </tr>

  </table>

</td>

  ';



$ttc = $totalht;

$pdf->SetX(10);



$pdf->writeHTML($printtotal, true, false, false, false, '');



$pdf->writeHTML($str, true, false, false, false, '');

$pdf->Ln(7);



$pdf->SetFont('dejavusans', 'B', 7, '', true);

ob_end_clean();

$pdf->Output('etat.pdf', 'I');



?>

