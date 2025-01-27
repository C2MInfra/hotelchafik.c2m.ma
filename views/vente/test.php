<?php
include('../../evr.php');

 include('../../newpdf/tcpdf_include.php');
include('../../newpdf/tcpdf.php');

include("../../model/convert.php");

if(!isset($_GET["id"])){ header("Location:index.php"); }
$result=connexion::getConnexion()->query("select v.remarque as obj,c.*,v.date_vente,v.id_user
from vente v  left join client c on c.id_client=v.id_client where id_vente=".$_GET["id"]);
$data1=$result->fetch(PDO::FETCH_ASSOC);
$data2=connexion::getConnexion()->query("select p.code_bar,p.tva,p.unite,p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_vendu,dv.remise,dv.id_vente from detail_vente dv left join produit p on p.id_produit=dv.id_produit left join vente v on dv.id_vente=v.id_vente  where dv.id_vente =".$_GET["id"]);
$rep=$data2->fetchAll(PDO::FETCH_ASSOC);
$vente=new vente();
$vente1=$vente->selectById($_GET["id"]);






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



$pdf->SetFont('dejavusans', '', 14, '', true);

if($vente1["date_vente"]!='0000-00-00')
{
$str=date("Y");
}
else
$str=date('Y');
$pdf->Cell(63,10,'Devis N° : D.'.$vente1["id_vente"] .' - ' . $str,0,1,'C',0);
$pdf->SetFont('dejavusans', '', 8, '', true);

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
$pdf->Cell(70,5, ' : ' ,0,1,'L',0);



$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(20,5, 'Echéance' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(70,5, ' : '.$data1["id_client"] ,0,1,'L',0);


$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(20,5, 'Références' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(70,5, ' : BC W201.802.945 DU 03/1012018' ,0,1,'L',0);


$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(20,5, 'Page' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(70,5, ' : '.$pdf->getAliasNbPages(),0,1,'L',0);
$pdf->Ln(12);



$pdf->SetXY(103,40);

$pdf->SetFont('dejavusans', '', 12, '', true);
$pdf->Cell(70,5,$data1["nom"] ,0,1,'L',0);
$pdf->SetFont('dejavusans', '', 8, '', true);

$pdf->Cell(90);
$pdf->Cell(70,5,$data1["ice"] ,0,1,'L',0);


$pdf->Cell(90);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->MultiCell(50,5,$data1['adresse'] );
$pdf->SetFont('dejavusans', '', 8, '', true);


/*$pdf->SetXY(10, 80);
$pdf->Cell(100);
$pdf->Cell(15,5, 'Remarque :' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->MultiCell(60,5, $data1["obj"],0 );
$pdf->SetFont('dejavusans', '', 8, '', true);

// $pdf->SetDash(0,0); //5 mm noir, 5 mm blanc
$unite="Qte";
if ($data1["obj"]=="" or strlen($data1["obj"]) < 120) {
$pdf->Ln(25);
}*/
$pdf->Ln(6);
//if(count($data2))

//$pdf->SetX(10);
$total=$totalht=0;
$cont=0;
$pdf->SetFont('dejavusans', '', 7, '', true);

$tbl = '
<style>
    .mytable  td {
        border-left : 1px solid #000;
    }
    .mytable  th {
       padding-butom:0;
    }
   
</style>
<table cellspacing="0" cellpadding="3" border="1" class="mytable">
    <tr >
        <th  align="center" height="10" width="12%">Référence</th>
        <th  align="center" height="10" width="35%">Désignation</th>
        <th  align="center" height="10" width="10%">Quantité</th>
        <th  align="center" height="10" width="12%">P.U. HT</th>
        <th  align="center" height="10" width="7%">% Rem</th>
        <th  align="center" height="10" width="12%">Remise HT</th>
        <th  align="center" height="10" width="13%">Montant HT</th>
        <th  align="center" height="10" width="5%">TVA</th>

    </tr>
  ';


  



foreach($rep as $ligne){

$vtva=$ligne["tva"];
$prix=round(($ligne["prix_produit"]*(1-$ligne["remise"]/100)) ,2);


  $tbl .= '
   <tr>
        <td>'.$ligne['code_bar'].'</td>
        <td>'.$ligne['designation'].'</td>
        <td align="right">'.$ligne['qte_vendu'].'</td>
        <td align="right">'.$ligne['prix_produit'].'</td>
        <td align="right">'.$ligne['remise'].'</td>
        <td align="right">'.$prix.'</td>
        <td align="right">'.( $prix)*$ligne["qte_vendu"] .'</td>
        <td align="center">1</td>
    </tr>
   ';
 
$total+=($prix)*$ligne["qte_vendu"]* $vtva;
$totalht+=($prix)*$ligne["qte_vendu"];
}



for ($i=0; $i < 25  - count($rep)  ; $i++) { 


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




$pdf->writeHTML($tbl, true, false, false, false, '');

$tot = $totalht +$total ;

$total=round($total,2);
$pdf->Ln(2);
$printtotal = '
<table width="100%">
<tr>
<td  width="70%">
<table cellspacing="0" cellpadding="6" border="1" width="100%">
    <tr>
        <th align="center" height="10"  width="15%">BASES HT</th>
		<th align="center" height="10"  width="15%">REMISE</th>
	     <th align="center" height="10"  width="15%">MT TVA</th>
	     <th align="center" height="10"  width="15%">% TVA</th>
	  	  <th align="center" height="10"  width="15%">PORT</th>
	 	  <th align="center" height="10" width="25%">TOTAUX</th>

    </tr>
     <tr>
        <th align="center" height="10" >0</th>
		<th align="center" height="10" >0</th>
        <th align="center" height="10" >0</th>
        <th align="center" height="10" >0</th>
        <th align="center" height="10" >0</th>
        <th align="right" height="10" >H.T : '.$totalht.'</th>
    </tr>
     

     <tr>
     <th align="center" height="10" >0</th>
		<th align="center" height="10" >0</th>
        <th align="center" height="10" >0</th>
        <th align="center" height="10" >0</th>
        <th align="center" height="10" >0</th>
        <th align="right" height="10" >T.V.A : '.$total.'</th>
    </tr>
    </table>

</td>
<td  width="35%" >
    <table cellspacing="0" cellpadding="6" border="1" width="100%">
   
 <tr>
        <th  height="10">T.T.C</th>
        <th align="right" height="10">'.number_format($tot,2,"."," ").'</th>
    </tr>

     <tr>
        <th  height="10">ACOMPTE</th>
        <th align="right" height="10">0</th>
        

    </tr>
     <tr>
        <th  height="10">NET A PAYER</th>
        <th align="right" height="10">'.number_format($tot,2,"."," ").'</th>
        

    </tr>
    </table>

    </td>
    </tr>
     </table>
  ';



$pdf->writeHTML($printtotal, true, false, false, false, '');



$pdf->Ln(5);
$pdf->writeHTML("<p>ARRETEE LA PRESENTE FACTURE A LA SOMME DE :</p>", true, false, false, false, '');
$str='<p>' .chifre_en_lettre(intval($tot));

if(intval(round($tot-intval($tot), 2)*100)>0 )
{
$str .=' et ';

if (round($tot,2) - intval($tot)<0.1 )
$str.= 'Zero ' ;
$str.= trim(chifre_en_lettre(substr(strstr(number_format($tot,2,"."," "),"."),1) ,' Centimes'));
}
 
$str.="</p>";


$pdf->Ln(2);
$pdf->writeHTML($str, true, false, false, false, '');



$pdf->Ln(15);


$pdf->RoundedRect(10, $pdf->getY(), 55, 25, 3.50, '1111', 'DF');
$pdf->SetFont('dejavusans', '', 12, '', true);

$pdf->Cell(30,10, 'Service commercial' ,0,0,'L',0);
$pdf->SetFont('dejavusans', '', 10, '', true);
$pdf->Ln(7);
$pdf->Cell(80,10, ' '.auth::user()["nom"] ,0,0,'L',0);
$pdf->Ln(7);
$pdf->Cell(80,10, ' '.auth::user()["tele"] ,0,0,'L',0);
/*
*/
$pdf->Output();
?>