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
$societe= connexion::getConnexion()->query("SELECT * FROM societe")->fetch(PDO::FETCH_OBJ);

$result=connexion::getConnexion()->query("select v.bon_commande, v.remarque as obj,c.*,v.date_vente,v.id_user
from vente v  left join client c on c.id_client=v.id_client where id_vente=".$_GET["id"]);

$data1=$result->fetch(PDO::FETCH_ASSOC);
$data2=connexion::getConnexion()->query("SELECT dv.unit, dv.valunit,p.code_bar,p.tva,p.unite,p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_vendu,dv.remise,dv.id_vente 
from detail_vente dv 
left join produit p on p.id_produit=dv.id_produit 
left join vente v on dv.id_vente=v.id_vente 
 where dv.id_vente =".$_GET["id"]);
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

$code = $vente1["id_vente"]. '-' . date("Y");
if($vente1["date_vente"]!='0000-00-00')
{
   $str = date("Y");
}
else
  $str=date('Y');
$pdf->SetFont('dejavusans', '', 8, '', true);
$str="";
if($vente1["date_vente"]!='0000-00-00')
{
  $str=date('d/m/Y',strtotime($vente1["date_vente"]));
}
$pdf->SetFont('dejavusans', '', 12, '', true);
$pdf->SetLineStyle(array('width' => 0.05, 'cap' => 'butt', 'join' => 'miter', 'solid' => 1, 'color' => array(110, 110, 110)));
     $pdf->SetFillColor(180, 180, 180);
$pdf->SetXY(150,6);
$pdf->setCellPadding(2);
$pdf->Cell(50,5,'DEVIS' ,1,1,'C',1,0);

$pdf->setCellPadding(0);
$pdf->Ln(2);
$pdf->Ln(12);

$pdf->SetXY(10,30);

$pdf->SetFont('dejavusans', '', 10, '', true);
$pdf->Cell(80,5, $societe->raisonsocial ,0,1,'C',0);

$pdf->SetFont('dejavusans', '', 8, '', false);
$pdf->Cell(80,5, str_replace('\\', '', $societe->adresse) ,0,1,'C',0);

$pdf->SetFont('dejavusans', '', 8, '', false);
$pdf->Cell(80,5,'ICE: 00036217000095 - RC: ' . $societe->rc ,0,1,'C',0);

$pdf->SetFont('dejavusans', '', 8, '', false);
$pdf->Cell(80,5,'Télephone: ' . $societe->telephone . ' - Fax: ' . $societe->fax ,0,1,'C',0);

$pdf->SetXY(103,40);
$pdf->SetFont('dejavusans', '', 12, '', true);
$pdf->Cell(70,5,$data1["nom"] ,0,1,'L',0);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(90);
$pdf->Cell(70,5,"ICE : ".$data1["ice"] ,0,1,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(90);
$pdf->MultiCell(50,5,$data1['adresse'] );
$pdf->SetFont('dejavusans', '', 8, '', true);

$pdf->Ln(6);
$cont=0;
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(80,10,'Devis N° : D.'.$vente1["id_vente"] .' - ' . $str,0,1,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(30,10,'Date: ' . date('d/m/Y',strtotime($vente1["date_vente"])),0,1,'L',0);

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
        <th  align="center" height="10" width="15%">Montant </th>
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
	
$a = ($mtnht*$ligne["prix_produit"]);

$b = $a - ($a * ($ligne["remise"]/100));

$c = $b * $ligne["tva"];

$prixut =  (($ligne["qte_vendu"]*$ligne["prix_produit"] ) - ( ($ligne["qte_vendu"]*$ligne["prix_produit"] ) * ($ligne["remise"]/100) ) ) + (  (($ligne["qte_vendu"]*$ligne["prix_produit"] ) - (($ligne["qte_vendu"]*$ligne["prix_produit"] ) * ($ligne["remise"]/100)) ) * $ligne["tva"] ) ;

  $tbl .= '
   <tr>
        <td>'.$ligne['code_bar'].'</td>
        <td style="font-size:11px;">'.$ligne['designation'].'</td>
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
      <th border="1" align="left" height="10" width="20%">HT</th>
	  <td align="right" border="1" width="20%">' . number_format($totalht/1.2, 2, ".", " ") . '</td>
    </tr>
     <tr>
	   <td width="60%"></td>
       <th border="1" align="left" height="10" width="20%">TVA</th>
	   <td align="right" border="1" width="20%">' . number_format($totalht - $totalht/1.2, 2, ".", " ") . '</td>
    </tr>
	<tr>
	   <td width="60%"></td>
       <th border="1" align="left" height="10" width="20%">TTC</th>
	   <td align="right" border="1" width="20%">' . number_format($totalht, 2, ".", " ") . '</td>
    </tr>
	<tr>
	   <td width="60%"></td>
       <th border="1" align="left" height="10" width="20%">ACCOMPTE</th>
	   <td align="right" border="1" width="20%" ></td>
    </tr>
	<tr>
	   <td width="60%"></td>
       <th border="1" align="left" height="10" width="20%">NET A PAYER</th>
	   <td align="right" border="1" width="20%">' . number_format($totalht, 2, ".", " ") . '</td>
    </tr>
  </table>
</td>
  ';

$ttc = $totalht;
$pdf->SetX(10);

$pdf->writeHTML($printtotal, true, false, false, false, '');

$pdf->Ln(5);

$pdf->writeHTML("<p>Arréter le présent devis a la somme de :</p>", true, false, false, false, '');

$str='<p>' .chifre_en_lettre(intval(($ttc)));

//$pdf->Image('../../pdf/signe.png',160,240,35);

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

$pdf->SetFont('dejavusans', 'B', 7, '', true);
ob_end_clean();
$pdf->Output('etat.pdf', 'I');

?>
