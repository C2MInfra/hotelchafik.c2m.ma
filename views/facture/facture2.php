<?php
#hide errors
ob_start();
include('../../evr.php');
include('../../newpdf/tcpdf_include.php');
include('../../newpdf/tcpdf.php');
include("../../model/convert.php");
?>

<?php
$result = connexion::getConnexion()->query("select c.*,v.date_vente,v.id_user, v.remarque as obj
 ,GROUP_CONCAT(DISTINCT concat(v.numbon,'-',year(datebon)) SEPARATOR ' , ') as numbon from vente v  left join client c on c.id_client=v.id_client where id_vente in (" . trim($_GET["id"], ',') . ")");

$data1 = $result->fetch(PDO::FETCH_ASSOC);
$rem = $data1['obj'];

$data2 = connexion::getConnexion()->query("SELECT dv.valunit,dv.unit,p.code_bar,p.tva,p.unite,p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_vendu,dv.id_vente,dv.remise, v.id_vente 
from detail_vente dv 
left join produit p on p.id_produit=dv.id_produit  
left join vente v on dv.id_vente=v.id_vente  
where dv.id_vente in (" . trim($_GET["id"], ',') . ")");

$rep=$data2->fetchAll(PDO::FETCH_ASSOC);

$facture = new facture();

$fact = $facture->selectById($_GET["idf"]);

$mode = '';
$reg = '';$reg_vente = new reg_vente();
$Mynewid=explode(',',$_GET["id"]);
foreach($Mynewid as $myid){
  if($myid!=''){
    $mode = $reg_vente->selectByIdVente($myid);
    $reg = $reg_vente->selectAll2($myid);
    
  }
 
}
$societe= connexion::getConnexion()->query("SELECT * FROM societe")->fetch(PDO::FETCH_OBJ);

$totalreg= 0;

foreach ($reg  as $key) 
{

  $totalreg += $key->montant;

}
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

$pdf->setXY(144, 6);
$pdf->write1DBarcode($code, 'C128B', '', '', '', 22, 3, $style1d, 'T');


$societe= connexion::getConnexion()->query("SELECT * FROM societe")->fetch(PDO::FETCH_OBJ);

$id_vente = str_replace(',', '', $fact['id_vente']);
$vnt = connexion::getConnexion()->query('SELECT * FROM vente WHERE id_vente = ' . $id_vente)->fetch(PDO::FETCH_ASSOC);

$regs = connexion::getConnexion()->query('SELECT * FROM reg_vente WHERE id_vente = ' . $id_vente . ' ')->fetchAll(PDO::FETCH_OBJ);





if($vnt["date_vente"]!='0000-00-00')
{
   $str = date("Y");
}
else
  $str=date('Y');

$pdf->SetFont('dejavusans', '', 8, '', true);
$str="";
if($vnt["date_vente"]!='0000-00-00')
{
   $str=date('Y',strtotime($vnt["date_vente"]));
}

$id_vente = str_replace(',', '', $fact['id_vente']);
$vnt = connexion::getConnexion()->query('SELECT * FROM vente WHERE id_vente = ' . $id_vente)->fetch(PDO::FETCH_ASSOC);


$pdf->SetTextColor(15,15,15);
$pdf->SetFillColor(139, 130, 125);
$pdf->SetXY(142,22);
$pdf->setCellPadding(2);
$pdf->SetFont('dejavusans', '', 10, 'C', true);
$pdf->Cell(50, 5, 'Facture N°: ' . $code, 0, 0, 'C', 0, 0);

$pdf->SetFont('dejavusans', '', 10, 'C', true);


$pdf->setXY(10, 42);
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
      <td width="71%">' . $data1['nom'] . '</td>
   </tr>
   <tr>
      <td width="24%"></td>
      <td></td>
      <td></td>
   </tr>
   <tr>
      <td width="24%" style="height:10; width:10%;">ICE</td>
      <td width="4%"> : </td>
      <td>' . $data1['ice'] . '</td>
   </tr>
   <tr>
      <td width="24%"></td>
      <td></td>
      <td></td>
   </tr>
   <tr>
      <td width="24%" style="height:10; width:10%;">Télèphone</td>
      <td width="4%"> : </td>
      <td>' . $data1['telephone'] . '</td>
   </tr>
   <tr>
      <td width="24%"></td>
      <td></td>
      <td></td>
   </tr>
   <tr>
      <td width="24%" style="height:10; width:10%;">Adresse</td>
      <td width="4%"> : </td>
      <td>' . trim($data1['adresse']) . '</td>
   </tr>
</table>
</div>';
	   
$rightInfo = "
<style>
  h3{color: rgb(20,20,20); text-align:center;}
  h5,p{font-size:8pt; font-weight:700;color: #000;text-align:left;}
</style>
<div>
   <h5>CASABLANCA Le: " . date('d/m/Y',strtotime($fact["date_facture"])). "</h5>
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

$pdf->Ln(6);






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

      <th  align="center" height="10" width="6%" style="color:white; font-weight:800; font-size:8.5pt;">N°</th>
        <th  align="center" height="10" width="49%" style="color:white; font-weight:800; font-size:8.5pt;">Désignation</th>
        <th  align="center" height="10" width="10%" style="color:white; font-weight:800; font-size:8.5pt;">Unite</th>
        <th  align="center" height="10" width="10%" style="color:white; font-weight:800; font-size:8.5pt;">Qte</th>
        <th  align="center" height="10" width="12%" style="color:white; font-weight:800; font-size:8.5pt;">PU</th>
        <th  align="center" height="10" width="12%" style="color:white; font-weight:800; font-size:8.5pt;">TOTAL </th>  
    </tr>

  ';


$totalremise = 0;

foreach($rep as $key => $ligne)
{
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
$tot_ht = $ligne['qte_vendu'] * ($ligne["prix_produit"] / (1 + $ligne["tva"]));
$tbl .= '
   <tr >
        <td  align="center">' . ($key + 1) . '</td>
        <td  >'.$ligne['designation'].'</td>
        <td align="center">U</td>
        <td  align="right">'.number_format($ligne["qte_vendu"],2,"."," ") . ' ' . $unit .'</td>
        <td  align="right">'.number_format($ligne["prix_produit"]/ (1 + $ligne["tva"]),2,"."," ").'</td>
        <td  align="right">'. number_format($tot_ht,2,"."," ")  .'</td>
    </tr>
   ';

$total+=($prixut)*$ligne["qte_vendu"]* $vtva;

$tottva += $vtva;

$totalht+=$b;

$totaltva+=$c;
	
}

$tot = $totalht +$total ;

$total=round($total, 2);
$timber=$basHt;
	   
if($modReg=='Espece')
{
  $timber=$basHt * 0.0025 + ($basHt) ;
}
	   
$pdf->Ln(2);

$ttc= $basHt + $tottvaSee;

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
$ttc = $totalht;
$str='' .chifre_en_lettre(intval(($ttc)));

if(intval(round(($ttc)-intval(($ttc)), 2)*100)>0 )
{

$str .=' et ';

if (round(($ttc),2) - intval(($ttc ))<0.1 )

$str.= 'Zero ' ;


$str.= trim(chifre_en_lettre(substr(strstr(number_format(($ttc),2,"."," "),"."),1) ,' centimes'));

}

$str.="  ";
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
         Arréter la présente facture a la somme de :
	  </td>
       <th border="1" align="left" height="10" width="20%" style="background-color:#2597d5; color:white;">Total </th>
	  <td align="right" border="1" width="20%">' . number_format($totalht, 2, ".", " ") . '</td>
    </tr>
     <tr>
	   <td width="60%">
	   ' . $str . '
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
$pdf->Output($data1['nom'] . '_facture.pdf', 'I');

?>
