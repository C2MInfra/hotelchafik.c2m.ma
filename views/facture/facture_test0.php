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

$data2 = connexion::getConnexion()->query("SELECT r.mode_reg,dv.valunit,dv.unit,p.code_bar,p.tva,p.unite,p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_vendu,dv.id_vente,dv.remise 
from detail_vente dv 
left join produit p on p.id_produit=dv.id_produit  
left join vente v on dv.id_vente=v.id_vente  
left join reg_vente r on r.id_vente=v.id_vente 
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

$totalreg= 0;

foreach ($reg  as $key) 
{

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

$code = ' FV.' . $fact["num_fact"] .' - ' . date("Y");



// $pdf->SetFont('dejavusans', '', 8, '', true);
// $pdf->Cell(20,2, 'Capital: 1.700.000 Dhs' ,0,1,'L',0);



// $pdf->SetFont('dejavusans', '', 8, '', true);
// $pdf->Cell(20,2, '108, Rue Rahal Ben Ahmed Belvédère, Casablanca.' ,0,1,'L',0);



// $pdf->SetFont('dejavusans', '', 8, '', true);
// $pdf->Cell(20,2, '05 22 24 55 13/19' ,0,1,'L',0);


// $pdf->SetFont('dejavusans', '', 8, '', true);
// $pdf->Cell(20,2, '06 66 65 31 95' ,0,1,'L',0);

// $pdf->SetFont('dejavusans', '', 8, '', true);
// $pdf->Cell(20,2, 'ami.caoutchouc@gmail.com' ,0,1,'L',0);

// $pdf->SetFont('dejavusans', '', 8, '', true);
// $pdf->Cell(20,2, 'www.amicaoutchouc.com' ,0,1,'L',0);



//$code = $data1["id_vente"]. '-' . date("Y");


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

//$pdf->Cell(70,5, ' : '.$pdf->getAliasNbPages(),0,1,'L',0);
//$pdf->Cell(70,5,  $vente1['bon_commande'],0,1,'L',0);
//$pdf->Cell(24,5, 'Bon Livraison : ' ,0,0);

$pdf->Ln(12);
$pdf->SetXY(11,40);

$pdf->SetFont('dejavusans', '', 12, '', true);







$pdf->Cell(5,5,$data1["nom"] ,0,1,'L',0);







$pdf->SetFont('dejavusans', '', 8, '', true);















// $pdf->Cell(10);



$pdf->Cell(5,5,"ICE : ".$data1["ice"] ,0,1,'L',0);

$pdf->SetFont('dejavusans', '', 9, '', true);
// $pdf->Cell(10);
$pdf->Cell(10,5,$data1['adresse'] );

$pdf->SetFont('dejavusans', '', 8, '', true);

$pdf->Ln(6);

$total=$totalht=0;

$cont=0;
$pdf->SetFont('dejavusans', '', 9, '', true);

$pdf->Cell(80,10,'Facture N° : ' . $code,0,0,'L',0);

$pdf->SetFont('dejavusans', '', 9, '', true);

$pdf->Cell(30,10,'Date: ' . date('d/m/Y',strtotime($fact["date_facture"])),0,1,'L',0);

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

        <th  align="center" height="10" width="10%">Référence</th>

        <th  align="center" height="10" width="20%">Désignation</th>

        <th  align="center" height="10" width="9%">Quantité</th>

        <th  align="center" height="10" width="10%">Unité</th>

        <th  align="center" height="10" width="10%">P.U</th>

        <th  align="center" height="10" width="6%">Remise %</th>

        <th  align="center" height="10" width="10%">Montant</th>
        <th  align="center" height="10" width="10%">Mode De Payement</th>
        <th  align="center" height="10" width="5%">Total TVA</th>
       

        <th  align="center" height="10" width="10%">Montant TVA</th>

    </tr>

  ';


$totalremise = 0;

foreach($rep as $ligne){

/*$prixut = ($ligne['prix_produit'] + ( $ligne['prix_produit']  *  $ligne['tva'] ) )  -  ( ($ligne['prix_produit'] + ( $ligne['prix_produit']  *  $ligne['tva'] ) )  * ($ligne["remise"]/100)  ) ;*/
$unit="";
if(!empty($ligne["unit"])){
  $unit=$ligne["unit"];
}
if(!empty($ligne["valunit"]) || $ligne["valunit"]!=0){
 $qte=($ligne["valunit"]);  
//$mtnht=($ligne["valunit"]*$ligne["qte_vendu"]);
}else{
  $qte=$ligne["qte_vendu"];
}

$PRIXht=$ligne["prix_produit"]/(1+$ligne["tva"]);

$qteprix=$PRIXht*$qte;

$montantHT=$qteprix-($qteprix *($ligne["remise"]/100));
$modepaym="";
if(!empty($ligne['mode_reg'])){
  $modepaym=$ligne['mode_reg'];
}else{
  $modepaym="";
}
$prixut =  (($ligne["qte_vendu"]*$ligne["prix_produit"] ) - ( ($ligne["qte_vendu"]*$ligne["prix_produit"] ) * ($ligne["remise"]/100) ) ) + (  (($ligne["qte_vendu"]*$ligne["prix_produit"] ) - (($ligne["qte_vendu"]*$ligne["prix_produit"] ) * ($ligne["remise"]/100)) ) * $ligne["tva"] ) ;
$tbl .= '
   <tr>
        <td  >'.$ligne['code_bar'].'</td>
        <td  style="font-size:11px;">'.$ligne['designation'].'</td>
        <td  align="right">'.number_format($ligne["qte_vendu"],2,"."," ").'</td>
        <td  align="right">'.number_format($ligne["valunit"],2,"."," ").' '.$unit.'</td>
        <td  align="center">'.number_format($PRIXht,2,"."," ").'</td>
        <td  align="right">'.$ligne['remise'].'</td>
        <td  align="center">'.number_format($montantHT,2,"."," ") .'</td>
        <td  align="center" >'.$ligne['mode_reg'].'</td>
        <td  align="center">'.($ligne['tva'] * 100).'%</td>
        <td  align="center">'. number_format((($ligne["prix_produit"]-($ligne["prix_produit"] *($ligne["remise"]/100)))*$qte)-($qteprix-($qteprix *($ligne["remise"]/100))),2,"."," ") .'</td>
    </tr>
   ';

$n=$ligne["prix_produit"]*$qte;
$n1=$n-($n*($ligne["remise"]/100));
$totalTva+=number_format((($ligne["prix_produit"]-($ligne["prix_produit"] *($ligne["remise"]/100)))*$qte)-($qteprix-($qteprix *($ligne["remise"]/100))),2,"."," ");
$totTva+=number_format((($ligne["prix_produit"]-($ligne["prix_produit"] *($ligne["remise"]/100)))*$qte)-($qteprix-($qteprix *($ligne["remise"]/100))),2,"."," ");

$basHt+=$montantHT;
if($ligne['mode_reg']=='Espece'){
 $modReg=$ligne['mode_reg']; 
}



}$timber=$basHt;
if($modReg=='Espece'){
  $tim=$basHt*0.0025 ;
  $timber=$basHt+$tim;
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
$ttc=(($modReg=='Espece')? $basHt+$totTva+(($basHt+$totalTva)*0.0025)  :$timber+$totTva  );

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
    <th align="center" height="10"  width="30%">BASES HT</th>
    <th align="center" height="10"  width="20%">TOTAL TVA %</th>
    <th align="center" height="10" width="20%">TIMBRE</th>
    <th align="center" height="10" width="30%">TOTAL T.T.C</th>
    </tr>
     <tr>
     <td align="center" height="10" >'.number_format($basHt,2,"."," ").'</td>
     <td align="center" height="10" >'.number_format($totTva,2,"."," ").'</td>
     <td align="center" height="10" >'. (($modReg=='Espece')? number_format((($basHt+$totalTva )*0.0025) ,2) : number_format(0 ,2) ).'</td>
     <td align="center" height="10" >'. number_format($ttc,2).'</td>
    
    </tr>
 </table>

</td>


  ';

$pdf->SetX(10);

$pdf->writeHTML($printtotal, true, false, false, false, '');

$pdf->Ln(5);

$pdf->writeHTML("<p>Arréter la présente facture a la somme de :</p>", true, false, false, false, '');

$str='<p>' .chifre_en_lettre(intval(($ttc)));

if(intval(round(($ttc)-intval(($ttc)), 2)*100)>0 )

{

$str .=' et ';

if (round(($ttc),2) - intval(($ttc))<0.1 )

$str.= 'Zero ' ;


$str.= trim(chifre_en_lettre(substr(strstr(number_format(($ttc),2,"."," "),"."),1) ,' centimes'));

}

$str.="</p>";

$pdf->Ln(2);

$pdf->writeHTML($str, true, false, false, false, '');
$pdf->Ln(7);
$pdf->writeHTML("<p>Remarque: " . $data1['obj']. "</p>", true, false, false, false, '');





ob_end_clean();
$pdf->Output('etat.pdf', 'I');

?>
