<?php
ob_start();
include('../../evr.php');
include('../../newpdf/tcpdf_include.php');
include('../../newpdf/tcpdf.php');
include("../../model/convert.php");

if(!isset($_GET["id"]))
{
	header("Location:index.php"); 
}
	   
$result=connexion::getConnexion()->query("select c.*, bon.date_bon, bon.id_user
from boncommandevendeur bon  left join utilisateur c on c.id = bon.id_vendeur where id_bon=".$_GET["id"]);
	   
$data1=$result->fetch(PDO::FETCH_ASSOC);

$data2=connexion::getConnexion()->query("SELECT r.mode_reg,dv.unit, dv.valunit,p.code_bar,p.tva,p.unite,p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_vendu,dv.remise,dv.id_bon
from detail_bon_vendeur dv 
left join produit p on p.id_produit=dv.id_produit 
left join boncommandevendeur v on dv.id_bon=v.id_bon
left join reg_commande r on r.id_bon=v.id_bon 
 where dv.id_bon = ".$_GET["id"]);
	   
$rep=$data2->fetchAll(PDO::FETCH_ASSOC);
	   
$boncommandevendeur =new boncommandevendeur();
	   
$boncommandevendeur1 = $boncommandevendeur->selectById($_GET["id"]);
	   
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
$pdf->SetFont('dejavusans', '', 14, '', true);
	   
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
$pdf->AddPage();
$code = $boncommandevendeur1["id_bon"]. '-' . date("Y");
if($boncommandevendeur1["date_bon"]!='0000-00-00')
{
   $str = date("Y");
}
else
  $str=date('Y');

$pdf->SetFont('dejavusans', '', 8, '', true);
$str="";
if($boncommandevendeur1["date_bon"]!='0000-00-00')
{
  $str=date('d/m/Y',strtotime($boncommandevendeur1["date_bon"]));
}

$pdf->setXY(140, 10);
//$pdf->write1DBarcode('BV.' . $boncommandevendeur1["id_bon"], 'C128B', '', '', '', 22, 3, $style1d, 'T');

$pdf->Ln(2);
$pdf->Ln(12);
$pdf->SetFont('dejavusans', '', 16, '', true);

$pdf->SetTextColor(15,15,15);
$pdf->SetFillColor(139, 130, 125);
$pdf->SetXY(120,34);
$pdf->setCellPadding(2);
$pdf->Cell(50, 5, '             Bon Vendeur', 0, 0, 'C', 0, 0);

$pdf->setCellPadding(0);
$pdf->Ln(4);
$pdf->Ln(12);
$pdf->SetXY(10,38);
$pdf->SetTextColor(20,20,20);

#first Row
$pdf->SetFont('dejavusans', '', 10, '', true);
$pdf->Cell(70,0,'' ,0,1,'L',0);

#ini Row
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(32,8,'Client                    : ' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(90,8,$data1['nom'] ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(40,8,'Date                    : ' . date('d/m/Y',strtotime($boncommandevendeur1["date_bon"])),0,1,'L',0);
#second Row
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(32,8,'A l\'attention de     :' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(90,8,$data1['responsable'] ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(40,8,'BV N°              : BV.' . $boncommandevendeur1["id_bon"],0,1,'L',0);

$style1d = array('position' => 'S', 'align' => 'C', 'stretch' => false, 'fitwidth' => true, 'cellfitalign' => '', 'border' => false, 'hpadding' => '50', 'vpadding' => '30', 'fgcolor' => array(0, 0, 0), 'bgcolor' => false, 'text' => false, 'font' => 'helvetica', 'fontsize' => 10, 'stretchtext' => 4);

#third Row
/*
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(32,8,'Adresse                :' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(90,8, $data1['adresse'] ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
*/

#fourth Row


#fifth Row
/*
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(32,8,'            ' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(90,8,'' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(40,8,'Préparé par         : ' . $data1['nom_user'],0,1,'L',0);
*/
#End Rows
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(10,8,$data1['obj'],0,1,'L',0);
$pdf->Ln(4);

$tbl = '
<style>
	 .mytable td 
   {
        border-left : 1px solid #000;
        border-right : 1px solid #000;
   }
   
   .mytable th 
   {
       padding: 8px;
	   
   }
</style>
<table cellspacing="0" cellpadding="3" border="1" class="mytable">
 <tr style="background-color:#a5a2a2; color:white !important;">
       <th  align="center" height="10" width="10%" style="color:white; font-weight:800; font-size:8.3pt;">Référence</th>
        <th  align="center" height="10" width="40%" style="color:white; font-weight:800; font-size:8.3pt;">DÉSIGNATION</th>
        <th  align="center" height="10" width="10%" style="color:white; font-weight:800; font-size:8.3pt;">QUANTITÉ</th>
        <th  align="center" height="10" width="10%" style="color:white; font-weight:800; font-size:8.3pt;">UNITÉ</th>
        <th  align="center" height="10" width="10%" style="color:white; font-weight:800; font-size:8.3pt;">PU TTC</th>
        <th  align="center" height="10" width="10%" style="color:white; font-weight:800; font-size:8.3pt;">REMISE %</th>
        <th  align="center" height="10" width="10%" style="color:white; font-weight:800; font-size:8.3pt;">TOTAL TTC </th>
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
        <td style="font-size:7pt;">'.$ligne['code_bar'].'</td>
        <td style="font-size:7pt;">'.$ligne['designation'].'</td>
        <td align="right" style="font-size:7pt;">'.number_format($ligne['qte_vendu'],2,"."," ").'</td>
        <td align="right" style="font-size:7pt;">'.number_format($ligne['valunit'],2,"."," ") .'</td>
        <td  align="center" style="font-size:7pt;"
>'.number_format($ligne["prix_produit"],2,"."," ").'</td>
        <td align="right" style="font-size:7pt;"
>'.$ligne['remise'].'</td>
        <td  align="center" style="font-size:7pt;"
>'.number_format($b,2,"."," ") .'</td>
    </tr>
   ';

$total+=($prixut)*$ligne["qte_vendu"]* $vtva;

$tottva += $vtva;

$totalht+=$b;

$totaltva+=$c;


}

for ($i=0; $i < 14  - count($rep)  ; $i++) 
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
$pdf->SetFont('dejavusans', '', 7, '', true);
$pdf->SetX(10);

$pdf->writeHTML($tbl, true, false, false, false, '');

$tot = $totalht +$total ;

$total=round($total, 2);
$timber=$basHt;
	   
if($modReg=='Espece')
{
  $timber=$basHt * 0.0025 + ($basHt) ;
}
	   
$pdf->Ln(2);
$ttc= $basHt + $tottvaSee;
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
       <th border="1" align="left" height="10" width="20%"  style="background-color:#a5a2a2; color:white;">Total</th>
	   <td align="right" border="1" width="20%">' . number_format($totalht, 2, ".", " ") . '</td>
    </tr>
  </table>
</td>
  ';

$ttc = $totalht;
$pdf->SetX(10);

$pdf->writeHTML($printtotal, true, false, false, false, '');

$pdf->Ln(5);

$pdf->writeHTML("Arréter le présent bon vendeur a la somme de :", true, false, false, false, '');

$str='<p>' .chifre_en_lettre(intval(($ttc)));



if(intval(round(($ttc )-intval(($ttc)), 2)*100)>0 )
{

	$str .=' et ';

	if (round(($ttc ),2) - intval(($ttc ))<0.1 )

	$str.= 'Zero ' ;

	$str.= trim(chifre_en_lettre(substr(strstr(number_format(($ttc),2,"."," "),"."),1) ,' Centimes'));

}
$str.="</p>";

$pdf->Ln(2);

$pdf->writeHTML($str, true, false, false, false, '');
$pdf->Ln(7);
$pdf->ln(4);
$pdf->SetFont('dejavusans', 'B', 7, '', true);

$pdf->writeHTML("<p>Remarque : " . $boncommandevendeur1['remarque'] . "</p>", true, false, false, false, '');
$pdf->SetFont('dejavusans', 'B', 7, '', true);

ob_end_clean();
$pdf->Output('etat.pdf', 'I');
?>
