<?php

include('../../evr.php');
  include('../../pdf/tfpdf.php');
  include('../../pdf/pdf_barcode.php');
  include("../../model/convert.php");
 
if (!isset($_GET["id"])) {
  header("Location:index.php");
}

$result = connexion::getConnexion()->query("select c.*,v.date_achat,v.id_user,v.remarque as obj
  from achat v  left join fournisseur c on c.id_fournisseur=v.id_fournisseur where id_achat in (" . trim($_GET["id"], ',') . ")");
$data1 = $result->fetch(PDO::FETCH_ASSOC);

$data2 = connexion::getConnexion()->query("select p.code_bar,p.tva,p.unite,p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_achete,dv.id_achat,dv.remise from detail_achat dv left join produit p on p.id_produit=dv.id_produit  where dv.id_achat in (" . trim($_GET["id"], ',') . ")");
$rep=$data2->fetchAll(PDO::FETCH_ASSOC);


/*$pdf = new tfPDF();
$pdf->AliasNbPages();
$pdf->AddPage();
*/ 
$pdf = new pdf_barcode('P','mm','A4');
//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$code = $_GET["id"] . '-' . date("Y", strtotime($data1["date_achat"]));

//$pdf->Code128(130,5,$code,50,15);
$pdf->SetXY(135,12);



$pdf->AddFont('deja', '', 'dejavusans.TTF', true);
$pdf->AddFont('dejavu', 'B', 'dejavusans-bold.TTF', true);
$pdf->SetFont('dejavu', 'B', 12);
$pdf->Cell(65);
$pdf->SetLineWidth(0.1);
$pdf->SetXY(120, 20);
$pdf->Cell(70, 10, 'Bon de commande N° : BC.' . $_GET["id"] . ' - ' . date("Y", strtotime($data1["date_achat"])) , 0, 1, 'C', 0);
$pdf->Line(0, 24.5, 100, 24);
$pdf->Line(0, 24.5, 100, 25);
$pdf->Line(100, 24, 110, 30);
$pdf->Line(100, 25, 110, 31);
$pdf->Line(110, 30, 210, 30);
$pdf->Line(110, 31, 210, 31);
$pdf->SetFont('deja', ' ', 11);
$pdf->Ln(10);
$pdf->Cell(115);
$pdf->Ln(5);
$pdf->Cell(115);

// $pdf->SetDash(1,1); //5 mm noir, 5 mm blanc
$pdf->SetFont('dejavu','B',10);
$pdf->Cell(35, 5, 'CASABLANCA Le ', 0, 0, 'L', 0);
$pdf->Cell(40, 5, ' : ' . date('d/m/Y', strtotime($data1["date_achat"])) , 0, 1, 'L', 0);

//$pdf->SetFont('dejavu','B',9);
$pdf->Rect(4, 45, 100, 40);
$pdf->SetFont('dejavu','B',9);
$pdf->Cell(20,5, 'fournisseur' ,0,0,'L',0);
$pdf->SetFont('deja',' ',9);
$pdf->Cell(70,5, ' : '.$data1["raison_sociale"] ,0,1,'L',0);
$pdf->SetFont('dejavu','B',9);
$pdf->Cell(20,5, 'ICE' ,0,0,'L',0);
$pdf->SetFont('deja',' ',9);
$pdf->Cell(70,5, ' : '.$data1["ice"] ,0,1,'L',0);
$pdf->SetFont('dejavu','B',9);
$pdf->Cell(20,5, 'Télè' ,0,0,'L',0);
$pdf->SetFont('deja',' ',9);
$pdf->Cell(70,5, ' :'.$data1["telephone"] ,0,1,'L',0);
$pdf->SetFont('dejavu','B',9);
$pdf->Cell(20,5, 'Adresse' ,0,0,'L',0);
$pdf->SetFont('deja',' ',9);
$pdf->MultiCell(70,5, ' : '.$data1['adresse'] );
$pdf->SetFont('dejavu','B',9);
$pdf->SetXY(10, 60);
$pdf->Cell(115);
$pdf->Cell(15,5, 'Remarque :' ,0,0,'L',0);
$pdf->SetFont('deja',' ',9);
$pdf->MultiCell(60,5, $data1["obj"],0 );
$pdf->SetFont('dejavu','B',9);
// $pdf->SetDash(0,0); //5 mm noir, 5 mm blanc
$unite="Qte";
if ($data1["obj"]=="" or strlen($data1["obj"]) < 120) {
$pdf->Ln(25);
}
$pdf->Ln(5);
//if(count($data2))

$pdf->Cell(10,10,'N°',1,0,'C',0);
$pdf->Cell(90,10,'Désignation','TB',0,'C',0);
$pdf->Cell(10,10,'Unite',1,0,'C',0);
$pdf->Cell(25,10,$unite,1,0,'C',0);
$pdf->Cell(25,10,'P.U (H.T)','TB',0,'C',0);
$pdf->Cell(30,10,'Total  (H.T)',1,1,'C',0);
$pdf->SetFont('dejavu','B',9);
$total=$totalht=0;
$cont=0;
$pdf->SetFont('deja',' ',9);
foreach($rep as $ligne){
$x = 10;
$y = $pdf->getY();
$pdf->setX($x+10);
$pdf->MultiCell(90,5,$ligne["designation"],'BR');
$cont++;
$height = $pdf->getY()-$y;
$pdf->setY($y);
$pdf->Cell(10,$height,$cont,'LRB',0,'C',0);
$pdf->setX($x+100);
$pdf->Cell(10,$height,$ligne["unite"],'RB',0,'C',0);
$pdf->Cell(25,$height,$ligne["qte_achete"],'RB',0,'C',0);
$vtva=1+($ligne["tva"]);
$prix=round($ligne["prix_produit"]/$vtva,2);
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
$pdf->SetFont('dejavu','B',9);
$pdf->Cell(35,5,'  Total H.T',1,0,'C',0);
$pdf->SetFont('dejavu','B',9);
$pdf->Cell(55,5,number_format($totalht,2,"."," ") . ' DH',1,1,'R',0);
$pdf->Cell(100);
$pdf->SetFont('dejavu','B',9);
$pdf->Cell(35,5,'  Total T.V.A (20%)',1,0,'C',0);
$pdf->SetFont('dejavu','B',9);
$pdf->Cell(55,5,number_format($total-$totalht,2,"."," ") . ' DH',1,1,'R',0);
$pdf->Cell(100);
$pdf->SetFont('dejavu','B',9);
$pdf->Cell(35,5,'T.T.C',1,0,'C',0);
$pdf->SetFont('dejavu','B',9);
$pdf->Cell(55,5,number_format($total,2,"."," ") . ' DH',1,1,'R',0);
$pdf->Ln(20);
$str='Arrêter bon de commande a la somme de :' .chifre_en_lettre(intval($total));
if(intval(round($total-intval($total), 2)*100)>0 )
{
$str .=' et ';

if (round($total,2) - intval($total)<0.1 )
$str.= 'Zero ' ;
$str.= trim(chifre_en_lettre(substr(strstr(number_format($total,2,"."," "),"."),1) ,' Centimes'));
}
$pdf->MultiCell(180,5,$str );
$pdf->SetFont('dejavu', 'B', 11);
/*$pdf->Cell(40, 5, 'Mode de paiement :', 0, 0, 'L', 0);

if (!empty($mode)) {
  $mod = $mode->mode_reg;
  if (!empty($mode->num_cheque)) $mod.= "(" . $mode->num_cheque . ')';
  $pdf->SetFont('deja', ' ', 11);
  $pdf->Cell(40, 5, ' : ' . $mod, 0, 1, 'L', 0);

} */
$pdf->Output();
?>