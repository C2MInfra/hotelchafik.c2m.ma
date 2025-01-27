<?php
ob_start();
include('../../evr.php');



include('../../newpdf/tcpdf_include.php');

include('../../newpdf/tcpdf.php');



include("../../model/convert.php");

 
if (!isset($_GET["id"])) {
  header("Location:index.php");
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$result = connexion::getConnexion()->query("select c.*,v.date_achat,v.id_user,v.remarque as obj
  from achat v  left join fournisseur c on c.id_fournisseur=v.id_fournisseur where id_achat in (" . trim($_GET["id"], ',') . ")");
$data1 = $result->fetch(PDO::FETCH_ASSOC);

$data2 = connexion::getConnexion()->query("select p.code_bar,p.tva,p.unite,p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_achete,dv.id_achat,dv.remise from detail_achat dv left join produit p on p.id_produit=dv.id_produit  where dv.id_achat in (" . trim($_GET["id"], ',') . ")");
$rep=$data2->fetchAll(PDO::FETCH_ASSOC);


/*$pdf = new tfPDF();
$pdf->AliasNbPages();
$pdf->AddPage();
*/ 
// $pdf = new pdf_barcode('P','mm','A4');
$pdf->AddPage();




$pdf->ln(30);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(20,2, 'Capital: 1.700.000 Dhs' ,0,1,'L',0);



$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(20,2, '108, Rue Rahal Ben Ahmed Belvédère, Casablanca.' ,0,1,'L',0);



$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(20,2, '05 22 24 55 13/19' ,0,1,'L',0);


$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(20,2, '06 66 65 31 95' ,0,1,'L',0);

$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(20,2, 'ami.caoutchouc@gmail.com' ,0,1,'L',0);

$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(20,2, 'www.amicaoutchouc.com' ,0,1,'L',0);





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

$pdf->Ln(2);

$pdf->Ln(12);
$pdf->SetXY(103,40);

$pdf->SetFont('dejavusans', '', 9, '', true);


$pdf->Cell(70,5,"",0,1,'L',0);
// $pdf->Cell(70,5,$data1["raison_sociale"] ,0,1,'L',0);
$pdf->SetFont('dejavusans', '', 11, '', true);
$pdf->Cell(92);
$pdf->Cell(70,5,"".$data1["raison_sociale"] ,0,1,'L',0);

$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(92);
$pdf->Cell(70,5,"ICE : ".$data1["ice"] ,0,1,'L',0);

$pdf->SetFont('dejavusans', '', 7, '', true);
$pdf->Cell(92);
$pdf->Cell(70,5,"Adresse : ".$data1["adresse"] ,0,1,'L',0);



/*$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(92);
$pdf->Cell(70,5,"Télè : ".$data1["telephone"] ,0,1,'L',0);*/
// $pdf->MultiCell(50,5,$data1['adresse'] );

$pdf->SetFont('dejavusans', '', 8, '', true);

$pdf->Ln(6);

$total=$totalht=0;

$cont=0;

$pdf->SetFont('dejavusans', '', 11, '', true);

$pdf->Cell(80,10, 'Bon de commande N° : BC.' . $_GET["id"] . ' - ' . date("Y", strtotime($data1["date_achat"])),0,0,'L',0);

$pdf->SetFont('dejavusans', '', 11, '', true);

$pdf->Cell(30,10,'Date: ' . date('d/m/Y',strtotime($data1["date_achat"])),0,1,'L',0);

$unite="Qte";

if ($data1["obj"]=="" or strlen($data1["obj"]) < 120) {

}
$pdf->Ln(5);
if(count($data2))

$pdf->Cell(10,10,'N°',1,0,'C',0);
$pdf->Cell(90,10,'Désignation','TB',0,'C',0);
$pdf->Cell(10,10,'Unite',1,0,'C',0);
$pdf->Cell(25,10,$unite,1,0,'C',0);
$pdf->Cell(25,10,'P.U','TB',0,'C',0);
$pdf->Cell(30,10,'Total',1,1,'C',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$total=$totalht=0;
$cont=0;
$pdf->SetFont('dejavusans', '', 9, '', true);
foreach($rep as $ligne){
$x = 10;
$y = $pdf->getY();
$pdf->setX($x+10);
//$pdf->MultiCell(90,5,$ligne["designation"],'BR');
$pdf->Cell(90,4,$ligne["designation"],'BR');
$cont++;
$height = $pdf->getY()-$y;
$pdf->setY($y);
$pdf->Cell(10,$height,$cont,'LRB',0,'C',0);
$pdf->setX($x+100);
$pdf->Cell(10,$height,$ligne["unite"],'RB',0,'C',0);
$pdf->Cell(25,$height,$ligne["qte_achete"],'RB',0,'C',0);
$vtva=1+($ligne["tva"]);
$prix=round($ligne["prix_produit"],2);
$pdf->Cell(25,$height,number_format($prix,2,"."," "),'BR',0,'R',0);
$pdf->Cell(30,$height,number_format($prix*$ligne["qte_achete"],2,"."," "),'RB',1,'R',0);
$total+=$prix*$vtva*$ligne["qte_achete"];
$totalht+=$prix*$ligne["qte_achete"];
if($pdf->getY()>250)
$pdf->AddPage();
}
$total=round($total,2);
$pdf->Ln(2);
$pdf->Cell(100);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(35,5,'  Total',1,0,'C',0);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->Cell(55,5,number_format($totalht,2,"."," ") . ' DH',1,1,'R',0);
$pdf->Cell(100);
$pdf->Ln(5);
$pdf->Cell(10);
// $pdf->Cell(22,5, 'Remarque :' ,0,0,'L',0);
// $pdf->SetFont('dejavusans',' ',9);
// $pdf->Cell(60,5, $data1["obj"],0 );
// $pdf->Ln(20);
// $str=' Arrêté le présent bon de commande à la somme de : ' .trim(chifre_en_lettre(intval($total)));
// if(intval(round($total-intval($total), 2)*100)>0 )
// {
// $str .=' et ';

// if (round($total,2) - intval($total)<0.1 )
// $str.= ' Zéro ' ;
// $str.= trim(chifre_en_lettre(substr(strstr(number_format($total,2,"."," "),"."),1) ,' Centimes'));
// }
// $pdf->SetFont('dejavusans', '', 8, '', true);
// $pdf->Cell(190,5,$str,0,1,'C' );
// $pdf->SetFont('dejavusans', '', 9, '', true);
/*$pdf->Cell(40, 5, 'Mode de paiement :', 0, 0, 'L', 0);

if (!empty($mode)) {
  $mod = $mode->mode_reg;
  if (!empty($mode->num_cheque)) $mod.= "(" . $mode->num_cheque . ')';
  $pdf->SetFont('deja', ' ', 11);
  $pdf->Cell(40, 5, ' : ' . $mod, 0, 1, 'L', 0);

} */
ob_end_clean();
$pdf->Output('etat.pdf', 'I');

?>