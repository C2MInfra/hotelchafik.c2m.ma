<?php

include('../../evr.php');
include('../../newpdf/tcpdf_include.php');
include('../../newpdf/tcpdf.php');
include("../../model/convert.php");


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
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
{
     require_once(dirname(__FILE__).'/lang/eng.php');
	 $pdf->setLanguageArray($l);
}

// set default font subsetting mode
$pdf->setFontSubsetting(true);

$pdf->SetFont('dejavusans', '', 14, '', true);

$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

$pdf->AddPage();

$code = $vente1["id_vente"]. '-' . date("Y");

$pdf->SetFont('dejavusans', '', 14, '', true);

if($vente1["date_vente"]!='0000-00-00')
{

   $str=date("Y");

}
else
   $str=date('Y');

$pdf->Cell(82,15,'Bon de livraison N° : BL00'.$vente1["numbon"] .' - ' . $str,0,1,'C',0);

$str="";

if($vente1["date_vente"]!='0000-00-00')
{

    $str=date('d/m/Y',strtotime($vente1["date_vente"]));
	
}

$pdf->SetFont('dejavusans', '', 8, '', true);

$pdf->Cell(20,5, 'Date' ,0,0,'L',0);

$pdf->SetFont('dejavusans', '', 9, '', true);

$pdf->Cell(40,5,' : '. $str ,0,1,'L',0);

$pdf->SetFont('dejavusans', '', 8, '', true);

$pdf->Cell(20,5, 'Code Client' ,0,0,'L',0);

$pdf->SetFont('dejavusans', '', 9, '', true);

$pdf->Cell(70,5, ' : ' .$data1["id_client"],0,1,'L',0);

$pdf->SetFont('dejavusans', '', 8, '', true);

$pdf->Cell(20,5, 'Telephone' ,0,0,'L',0);

$pdf->SetFont('dejavusans', '', 9, '', true);

$pdf->Cell(70,5, ' : '.$data1['telephone'],0,1,'L',0);

$pdf->Ln(12);

$pdf->SetXY(103,40);

$pdf->SetFont('dejavusans', '', 12, '', true);

$pdf->Cell(70,5,$data1["nom"] ,0,1,'L',0);

$pdf->SetFont('dejavusans', '', 8, '', true);

$pdf->Cell(90);

$pdf->Cell(70,5,"ICE : ".$data1["ice"] ,0,1,'L',0);

$pdf->Cell(90);

$pdf->SetFont('dejavusans', '', 9, '', true);

$pdf->Cell(50,5,$data1['adresse'] );

$pdf->Ln(6);

$pdf->SetFont('dejavusans', '', 8, '', true);

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
 <tr>
       <th  align="center" height="10" width="20%">Référence</th>
        <th  align="center" height="10" width="20%">Désignation</th>
        <th  align="center" height="10" width="10%">Quantité</th>
        <th  align="center" height="10" width="10%">Unité</th>
        <th  align="center" height="10" width="15%">P.U</th>
        <th  align="center" height="10" width="10%">Remise %</th>
        <th  align="center" height="10" width="15%">Montant</th>
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
        <td style="font-size:11px;">'.$ligne['designation'].'</td>
        <td align="right">'.number_format($ligne['qte_vendu'],2,"."," ").'</td>
        <td align="right">'.number_format($ligne['valunit'],2,"."," ").' '. $unit .'</td>
        <td  align="center">'.number_format($ligne["prix_produit"],2,"."," ").'</td>
        <td align="right">'.$ligne['remise'].'</td>
        <td  align="center">'.number_format($b,2,"."," ") .'</td>
    </tr>
   ';

$total+=($prixut)*$ligne["qte_vendu"]* $vtva;

$tottva += $vtva;

$totalht+=$b;

$totaltva+=$c;

}

for ($i=0; $i < 25  - count($rep)  ; $i++) 
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
<table width="100%">
<tr>
<td  width="100%">
<table cellspacing="0" cellpadding="6"   class="" width="100%">
    <tr>
    <th align="center" height="10" width="40%"></th>
    <th align="center" height="10" width="40%"></th>
      <th border="1" align="center" height="10" width="20%">TOTAL</th>
    </tr>
     <tr>
     <td align="center"  ></td>
     <td align="center"  ></td>
     <td align="center" border="1" >' . number_format($totalht, 2, ".", " ") . '</td>
    </tr>
  </table>
</td>
  ';

$pdf->SetX(10);

$pdf->writeHTML($printtotal, true, false, false, false, '');

$pdf->Ln(5);

$pdf->writeHTML("<p>Arréter le présent bon de livraison a la somme de :</p>", true, false, false, false, '');

$str='<p>' .chifre_en_lettre(intval(($totalht)));

if(intval(round(($totalht * 1.2) - intval(($totalht * 1.2)), 2)*100)>0 )
{
	
$str .=' et ';
	
if (round(($totalht + $totaltva),2) - intval(($totalht))<0.1 )
$str.= 'Zero ' ;

$str.= trim(chifre_en_lettre(substr(strstr(number_format(($totalht),2,"."," "),"."),1) ,' Centimes'));

}

$str.="</p>";

$pdf->Ln(2);

$pdf->writeHTML($str, true, false, false, false, '');
$pdf->ln(4);
$pdf->SetFont('dejavusans', 'B', 7, '', true);

$pdf->writeHTML("<p>Remarque : " . $data1['obj'] . "</p>", true, false, false, false, '');


$pdf->Output();

?>