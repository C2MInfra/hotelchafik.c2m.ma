<?php

include('../../evr.php');



include('../../newpdf/tcpdf_include.php');

include('../../newpdf/tcpdf.php');



include("../../model/convert.php");

?>
<html>
    <head>
        <title>Alkhadem C2M</title>
    </head>
    <body>
<?php
// var_dump(explode('',explode(',',$_GET['id'])));die();
// var_dump($_GET['id_client']);die();
$client=connexion::getConnexion()->query('SELECT * FROM client where id_client ='.$_GET['id_client'])->fetch(PDO::FETCH_ASSOC);
// $rep=connexion::getConnexion()->query("SELECT 
// v.id_vente,
// SUM(dt.prix_produit * dt.valunit *(1-(dt.remise/100))) as unitprix,
// r.montant as montreg,
// SUM(dt.prix_produit * dt.valunit *(1-(dt.remise/100)))-sum(r.montant) as balunit,
// v.datebon,p.code_bar,p.designation 
// FROM vente v,detail_vente dt ,reg_vente r,produit p
// WHERE v.id_vente =dt.id_vente
// AND r.id_vente=v.id_vente
// AND p.id_produit=dt.id_produit
// AND v.numbon<>0
// AND v.id_client=".$_GET['id_client']." 
// GROUP BY v.datebon
// ")->fetchAll(PDO::FETCH_ASSOC);

$rep=connexion::getConnexion()->query("SELECT v.id_vente,dt.id_detail,
SUM(dt.prix_produit * if(dt.valunit=0,dt.qte_vendu,dt.valunit) *(1-(dt.remise/100))) as unitprix,
SUM(dt.prix_produit * dt.qte_vendu *(1-(dt.remise/100)))as qteprix,
v.datebon,p.code_bar,p.designation,c.* ,v.numbon
FROM  vente v
INNER JOIN detail_vente dt ON dt.id_vente=v.id_vente
INNER JOIN produit p ON p.id_produit =dt.id_produit
INNER JOIN client c on v.id_client=c.id_client
WHERE 
v.id_client=".$_GET['id_client']."
AND
v.numbon<>0
GROUP BY v.id_vente 
order by id_vente asc
")->fetchAll(PDO::FETCH_ASSOC);






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

$code = ' FV.' . $fact["num_fact"] .' - ' . date("Y");


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

//$pdf->Cell(40,5,' : '. date('d/m/Y',strtotime($vente1["date_vente"])) ,0,1,'L',0);
//$pdf->Ln(12);
$pdf->Ln(2);


$pdf->Ln(12);
$pdf->SetXY(15,40);

$pdf->SetFont('dejavusans', '', 12, '', true);
$pdf->Cell(5,5,$client["nom"] ,0,1,'L',0);
$pdf->SetFont('dejavusans', '', 8, '', true);
// $pdf->Cell(10);
$pdf->Cell(5,5,"ICE : ".$client["ice"] ,0,1,'L',0);

$pdf->SetFont('dejavusans', '', 9, '', true);
// $pdf->Cell(10);

$pdf->Cell(10,5,$client['adresse'] );


$pdf->SetFont('dejavusans', '', 8, '', true);

$pdf->Ln(6);

$total=$totalht=0;

$cont=0;
// $pdf->SetFont('dejavusans', '', 9, '', true);

// $pdf->Cell(80,10,'Facture N° : ' . $code,0,0,'L',0);

$pdf->SetFont('dejavusans', '', 9, '', true);

$pdf->Cell(30,10,'Date: ' . date('d/m/Y',strtotime(date('m/d/Y'))),0,1,'L',0);

$pdf->SetFont('dejavusans', '', 7, '', true);




$tbl = '

<style>

.mytable  td {


        border-left : 1px solid #000;
        border-right : 1px solid #000;
 }

    .mytable  th {

       padding-butom:0;

    }

</style>

<table cellspacing="0" cellpadding="3" border="1" class="mytable">

    <tr >

        <th  align="center" height="10" width="20%">Référence</th>

        <th  align="center" height="10" width="20%">Désignation</th>

        <th  align="center" height="10" width="15%">Date</th>

        <th  align="center" height="10" width="10%">debit</th>

        <th  align="center" height="10" width="15%">credit</th>

        <th  align="center" height="10" width="20%">balance </th>

        
    </tr>

';
  $n=0;
  $ling='<td  align="right"></td>';
  $totdebit=0;
  $totcredit=0;
  $totbalance=0;
  $id=0;
  $credi=0;
  $prix=0;
  $detail=0;
foreach($rep as $ligne){


$tbl .= '
   <tr>
        <td  align="center">Bon Livraison</td>
        <td  style="font-size:11px;">BL N°: '.$ligne['numbon'].'</td>
        <td  align="center">'.$ligne['datebon'].'</td>
        <td  align="center">'.number_format($ligne['unitprix'],2,"."," ").'</td>
        <td align="center"></td>
        <td  align="center">'.number_format(($ligne['unitprix'] - $n),2,"."," ") .'</td>
    </tr>
   ';
   $regel=connexion::getConnexion()->query('SELECT reg_vente.*,montant ,id_reg
        FROM reg_vente 
        where id_vente='.$ligne['id_vente'].'
        
        order by id_vente asc
        '
        )->fetchAll(PDO::FETCH_ASSOC);
        
foreach($regel as $reg){
$n+=$reg['montant'];          
$tbl .= '<tr>
            <td  align="center">Règlements</td>
            <td  style="font-size:11px;">Reg N°: '.$reg['id_reg'].'</td>
            <td  align="center">'.$reg['date_reg'].'</td>
            <td  align="center"></td>
            <td align="center">'.number_format(($reg['montant'] ),2,"."," ") .'</td>
            <td  align="center">'.number_format(($ligne['unitprix'] - $n),2,"."," ") .'</td>
        </tr>';
       $totbalance+=$ligne['unitprix']- $n;
       $credi+=$reg['montant'];
       $totcredit+=$reg['montant'];
}
        
    $n=0;
    
    $totdebit+=$ligne['unitprix'] ;
    $prix=0;
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


$pdf->SetX(10);

$pdf->writeHTML($tbl, true, false, false, false, '');




$printtotal = '

<style>

    .mytable  td {

        border-left : 1px solid #000;

    }

    .mytable  th {


       padding-butom:0;

    }


</style>

<table width="100%">

<tr>

<td  width="100%">

<table cellspacing="0" cellpadding="6" border="1"  class="mytable" width="100%">
    <tr>
    <th align="center" height="10"  width="33%">Total debit</th>
    <th align="center" height="10"  width="33%">TOTAL credit</th>
    <th align="center" height="10" width="34%">TOTAL BALANCE</th>

    </tr>
     <tr>
     <td align="center" height="10" >'.number_format($totdebit,2,"."," ").'</td>
     <td align="center" height="10" >'.number_format($totcredit,2,"."," ").'</td>
     <td align="center" height="10" >'.number_format($totbalance,2,"."," ").'</td>
     
    </tr>
 </table>

</td>


  ';

$pdf->SetX(10);

$pdf->writeHTML($printtotal, true, false, false, false, '');

$pdf->Ln(5);

// $pdf->writeHTML("<p>Arréter la présente facture a la somme de :</p>", true, false, false, false, '');

$str='<p>' .chifre_en_lettre(intval((0)));

if(intval(round((0)-intval((0)), 2)*100)>0 )

{

$str .=' et ';

if (round((0),2) - intval((0))<0.1 )

$str.= 'Zero ' ;


$str.= trim(chifre_en_lettre(substr(strstr(number_format((0),2,"."," "),"."),1) ,' centimes'));

}

$str.="</p>";

// $pdf->Ln(2);

// $pdf->writeHTML($str, true, false, false, false, '');
// $pdf->Ln(7);
// $pdf->writeHTML("<p>Remarque: " . $data1['obj']. "</p>", true, false, false, false, '');






























/*$pdf->Ln(15);























$pdf->RoundedRect(10, $pdf->getY(), 55, 25, 3.50, '1111', 'DF');







$pdf->SetFont('dejavusans', '', 12, '', true);















$pdf->Cell(30,10, 'Service commercial' ,0,0,'L',0);







$pdf->SetFont('dejavusans', '', 10, '', true);







$pdf->Ln(7);







$pdf->Cell(80,10, ' '.auth::user()["nom"] ,0,0,'L',0);







$pdf->Ln(7);







$pdf->Cell(80,10, ' '.auth::user()["tele"] ,0,0,'L',0);*/







/*







*/







$pdf->SetFont('dejavusans', 'B', 7, '', true);







/*$pdf->Cell(5);







$pdf->SetXY(5,250);







$pdf->writeHTML("<p>RIB/ BMCE Agence MEDIOUNA N° de compte : 011.780.0000.66.210.00.10522.48 <br></p>", true, false, false, false, ''); 







$pdf->SetFont('dejavusans', '', 6, '', true);







$pdf->writeHTML("<p>La marchandise voyageant aux risques et périls du tmataire, sous la seule responsabilité du transporteur En cas de litige, les tribunaux de Casablanca sont seuls compétents. </p>", true, false, false, false, ''); 















$pdf->writeHTML("<p>Aucun retour de marchandise ne doit être fait sans tonsation de la direction.</p>", true, false, false, false, '');







$pdf->writeHTML("<p>Aucune réclamation ne sera prise en considératio près réception de la marchandise par l'acheteur







</p>", true, false, false, false, '');*/







$pdf->Output();







?>
</body>
</html>