<?php
#hide errors
ob_start();
include('../../evr.php');
include('../../newpdf/tcpdf_include.php');
include('../../newpdf/tcpdf.php');
include("../../model/convert.php");

?>
<?php
if(!isset($_GET["id"]))
{ 
	header("Location:index.php"); 
}

$result=connexion::getConnexion()->query("select v.remarque as obj,c.*,v.date_vente,v.id_user
from vente v  left join client c on c.id_client=v.id_client where id_vente=".$_GET["id"]);

$data1=$result->fetch(PDO::FETCH_ASSOC);

$data2=connexion::getConnexion()->query("select dv.valunit,dv.unit,p.code_bar,p.tva,p.unite,p.designation,p.poid,dv.prix_produit,p.poid,dv.remise,dv.qte_vendu,dv.id_vente from detail_vente dv left join produit p on p.id_produit=dv.id_produit left join vente v on dv.id_vente=v.id_vente  where dv.id_vente =".$_GET["id"]);

$rep=$data2->fetchAll(PDO::FETCH_ASSOC);

$vente=new vente();

$vente1=$vente->selectById($_GET["id"]);
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
if($vente1["date_vente"]!='0000-00-00')
{
   $str = date("Y");
}
else
  $str=date('Y');
$str="";
if($vente1["date_vente"]!='0000-00-00')
{
  $str=date('Y',strtotime($vente1["date_vente"]));
}
$style1d = array('position' => 'S', 'align' => 'C', 'stretch' => false, 'fitwidth' => true, 'cellfitalign' => '', 'border' => false, 'hpadding' => '50', 'vpadding' => '30', 'fgcolor' => array(0, 0, 0), 'bgcolor' => false, 'text' => false, 'font' => 'helvetica', 'fontsize' => 10, 'stretchtext' => 4);

$pdf->setXY(150, 10);
//$pdf->write1DBarcode('BL00' . $vente1["numbon"] .' - '.  $str, 'C128B', '', '', '', 22, 3, $style1d, 'T');

$pdf->SetTextColor(15,15,15);
$pdf->SetFillColor(139, 130, 125);
$pdf->SetXY(128,34);
$pdf->setCellPadding(2);
$pdf->Cell(50,5,'Bon Livraison' ,0,0,'C',0,0);

$pdf->setCellPadding(0);
$pdf->Ln(4);
$pdf->Ln(12);
$pdf->SetXY(10,38);
$pdf->SetTextColor(20,20,20);

$code = $vente1["id_vente"]. '-' . date("Y");

$societe= connexion::getConnexion()->query("SELECT * FROM societe")->fetch(PDO::FETCH_OBJ);
$code = $vente1["id_vente"]. '-' . date("Y");

$pdf->SetFont('dejavusans', '', 8, '', true);


#first Row
$pdf->SetFont('dejavusans', '', 10, '', true);
$pdf->Cell(70,0,'' ,0,1,'L',0);

#ini Row
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(32,8,'Client     :' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(90,8,$data1['nom'] ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(40,8,'Date                    : ' . date('d/m/Y',strtotime($vente1["date_vente"])),0,1,'L',0);
#second Row
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(32,8,'Adresse      :' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(90,8, $data1['adresse'] ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(40,8,'BL N°                   : BL00' . $vente1["numbon"] .' - '.  $str,0,1,'L',0);

#third Row
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(32,8,'Téléphone  :' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(90,8,$data1['telephone'] ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(40,8,'',0,1,'L',0);


$pdf->Ln(6);

$total=$totalht=0;

$cont=0;

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
 <tr style="background-color:#a5a2a2; color:white !important;">
        <th  align="center" height="10" width="10%" style="color:white;">Référence</th>
        <th  align="center" height="10" width="40%" style="color:white;">Désignation</th>
        <th  align="center" height="10" width="10%" style="color:white;">Quantité</th>
        <th  align="center" height="10" width="10%" style="color:white;">Unité</th>
        <th  align="center" height="10" width="10%" style="color:white;">PU TTC</th>
        <th  align="center" height="10" width="10%" style="color:white;">Remise %</th>
        <th  align="center" height="10" width="10%" style="color:white;">TOTAL TTC </th>
</tr>
  ';

$totalremise = 0;

foreach($rep as $ligne){

$vtva=$ligne["tva"];

$prix=round(($ligne["prix_produit"]*(1-$ligne["remise"]/100)) ,2);
$totalremise += $ligne["qte_vendu"]*$ligne["prix_produit"]*$ligne["remise"]/100 ; 

if(!empty($ligne["unit"]))
{
    $unit=$ligne["unit"];
    
}
if(!empty($ligne["valunit"]) || $ligne["valunit"]!=0)
{
    $mtnht=($ligne["valunit"]);
}
else
{
    $mtnht=$ligne["qte_vendu"];
}
$a = ($mtnht*$ligne["prix_produit"] );

$b = $a - ($a * ($ligne["remise"]/100));

$c = $b * $ligne["tva"];

$prixut =  (($ligne["qte_vendu"]*$ligne["prix_produit"] ) - ( ($ligne["qte_vendu"]*$ligne["prix_produit"] ) * ($ligne["remise"]/100) ) ) + (  (($ligne["qte_vendu"]*$ligne["prix_produit"] ) - (($ligne["qte_vendu"]*$ligne["prix_produit"] ) * ($ligne["remise"]/100)) ) * $ligne["tva"] ) ;

  $tbl .= '
   <tr>
       <td>'.$ligne['code_bar'].'</td>
        <td>'.$ligne['designation'].'</td>
        <td align="right">'.number_format($ligne['qte_vendu'],2,"."," ").'</td>
        <td align="right">'.number_format($ligne['valunit'],2,"."," ").' '. $unit .'</td>
        <td  align="right">'.number_format($ligne["prix_produit"],2,"."," ").'</td>
        <td align="right">'.$ligne['remise'].'</td>
        <td  align="right">'.number_format($b,2,"."," ") .'</td>
    </tr>
   ';

$total+=($prixut)*$ligne["qte_vendu"]* $vtva;

$tottva += $vtva;

$totalht+=$b;

$totaltva+=$c;

}

for ($i=0; $i < 12  - count($rep)  ; $i++) 
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

$tot = $totalht +$total ;

$total=round($total,2);

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
      <th border="1" align="left" height="10" width="20%" style="background-color:#a5a2a2; color:white;">THT</th>
	  <td align="right" border="1" width="20%">' . number_format($totalht/1.2, 2, ".", " ") . '</td>
    </tr>
     <tr>
	   <td width="60%"></td>
       <th border="1" align="left" height="10" width="20%"  style="background-color:#a5a2a2; color:white;">T.V.A</th>
	   <td align="right" border="1" width="20%">' . number_format($totalht - $totalht/1.2, 2, ".", " ") . '</td>
    </tr>
	<tr>
	   <td width="60%"></td>
       <th border="1" align="left" height="10" width="20%"  style="background-color:#a5a2a2; color:white;">TTC</th>
	   <td align="right" border="1" width="20%">' . number_format($totalht, 2, ".", " ") . '</td>
    </tr>
  </table>
</td>
  ';

$ttc = $totalht;
$pdf->SetX(10);

$pdf->writeHTML($printtotal, true, false, false, false, '');

$pdf->ln(4);
$pdf->SetFont('dejavusans', 'B', 7, '', true);

$pdf->writeHTML("<p>" . $data1['obj'] . "</p>", true, false, false, false, '');
ob_end_clean();
$pdf->Output('etat.pdf', 'I');

?>
