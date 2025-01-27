<?php
  include('../../evr.php');
  include('../../pdf/tfpdf.php');
  include("../../model/convert.php");
$getarrey =explode('&',explode('?',$_SERVER["REQUEST_URI"])[1]);
$GET = array();
foreach ($getarrey as $key) {

  $row = explode('=',$key);
 

  $GET[$row[0]]=$row[1];
}
$result=connexion::getConnexion()->query("select c.nom,c.prenom,c.adresse,c.email,v.date_vente,v.id_user
   from vente v  left join client c on c.id_client=v.id_client where id_vente=".$GET["id"]);
$data1=$result->fetch(PDO::FETCH_ASSOC);
$data2=connexion::getConnexion()->query("select p.code_bar,p.tva,p.designation,p.poid,dv.prix_produit,p.poid,dv.qte_vendu,dv.id_vente ,dv.remise from detail_vente dv left join produit p on p.id_produit=dv.id_produit left join vente v on dv.id_vente=v.id_vente  where dv.id_vente =".$GET["id"]);
$vente=new vente();
$vente1=$vente->selectById($GET["id"]);
$pdf = new tfPDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->AddFont('deja','','dejavusans.TTF',true);
$pdf->AddFont('dejavu','B','dejavusans-bold.TTF',true);
  $pdf->SetFont('dejavu','B',12);
  $pdf->Cell(65);
  /*
 $pdf->Line(0,24.5,100,24);
   $pdf->Line(0,24.5,100,25);
  $pdf->Line(100,24,110,30);
    $pdf->Line(100,25,110,31);
    $pdf->Line(110,30,210,30);
     $pdf->Line(110,31,210,31);
  $pdf->SetLineWidth(0.1);*/
  
  $pdf->SetLineWidth(0.1);
 $pdf->SetXY(120, 20);
    $pdf->Cell(70,10,'Bon de livraison N° : BL.'.$vente1["numbon"] .' - ' . date("Y",strtotime($vente1["datebon"])),0,1,'C',0);
         $pdf->Line(0,24.5,100,24);
   $pdf->Line(0,24.5,100,25);
  $pdf->Line(100,24,110,30);
    $pdf->Line(100,25,110,31);
    $pdf->Line(110,30,210,30);
     $pdf->Line(110,31,210,31);
    
        $pdf->SetFont('deja',' ',11);
          $pdf->Ln(10);
            $pdf->Cell(115);
         //   $pdf->SetDash(1,1);
           $pdf->Cell(35,5,'CASABLANCA Le ',0,0,'L',0);
     $pdf->Cell(40,5, ' : ' .date('d/m/Y',strtotime($vente1["datebon"]))  ,0,1,'L',0);
       $pdf->Cell(115);
       $utilisateur=new utilisateur();
   $reponse=$utilisateur->selectById($data1["id_user"]);
           $pdf->Cell(35,5,'Agent',0,0,'L',0);
     $pdf->Cell(20,5, ' : ' .$reponse['login'],0,1,'L',0);
 $pdf->Ln(5);
  $pdf->SetFont('dejavu','B',11);
  $pdf->Rect(10,50,100,22);
        $pdf->Cell(20,5, 'Client' ,0,0,'L',0);
           $pdf->Cell(40,5, ' : '.$data1["nom"] ,0,1,'L',0);
               $pdf->Cell(20,5, 'Adresse' ,0,0,'L',0);
                $pdf->SetFont('deja',' ',11);          
         $pdf->SetFont('deja',' ',11);
           $pdf->Cell(40,5, ' : ' ,0,1,'L',0);
       if (isset($GET["h"]))  
        $pdf->Ln($GET["h"]); 
        else 
 $pdf->Ln(15);
  $pdf->SetFont('dejavu','B',10);
  //$pdf->SetDash(0,0); //5 mm noir, 5 mm blanc
 $unite="Qte";
 if($pdf->societe['etat_unite']==1){$unite.= '/'. $pdf->societe['unite_util'];} 
$pdf->Cell(30,10,'Referance',1,0,'C',0); 
$pdf->Cell(60,10,'Designation','TB',0,'C',0); 

$pdf->Cell(12,10,"QTE",1,0,'C',0); 
$pdf->Cell(30,10,'P.U','TB',0,'C',0); 
$pdf->Cell(16,10,'Rem %',1,0,'C',0); 
$pdf->Cell(20,10,'TVA %',1,0,'C',0); 
$pdf->Cell(30,10,'Total','TRB',1,'C',0); 
$pdf->SetFont('deja',' ',11);
 $total=$totalht=0;
 foreach($data2 as $ligne){

$x = 10;
 $y = $pdf->getY();
  $pdf->setX($x+30);
 $pdf->MultiCell(60,5,$ligne["designation"],'B'); 
  $height = $pdf->getY()-$y;
   $pdf->setY($y);
 $pdf->Cell(30,$height,$ligne["code_bar"],'LRB',0,'C',0); 
  $pdf->setX($x+90);
$pdf->Cell(12,$height,$ligne["qte_vendu"],'LRB',0,'R',0); 
$pdf->Cell(30,$height,number_format($ligne["prix_produit"],2,"."," "),'B',0,'C',0); 
$pdf->Cell(16,$height,($ligne["remise"]>0)?($ligne["remise"]*100):'',1,0,'C',0);
$pdf->Cell(20,$height,$ligne["tva"]*100,'LRB',0,'C',0); 
$prixr=($ligne["prix_produit"]*$ligne["qte_vendu"]);
$prixr = $prixr - ($prixr * $ligne["remise"]);
$pdf->Cell(30,$height,number_format($prixr,2,"."," "),1,1,'R',0);  
$total+=$prixr;

$totalht+=$prixr-($prixr / (1+ $ligne["tva"]));
if($pdf->getY()>250)
$pdf->AddPage(); 
 }
 $pdf->Ln(2);
    $pdf->Cell(90);
       $pdf->SetFont('dejavu','B',12);
 $pdf->Cell(55,5,'  Total T.V.A',1,0,'C',0); 
  $pdf->SetFont('deja',' ',11);
 $pdf->Cell(50,5,number_format($totalht,2,"."," ") . ' DH',1,1,'C',0); 
   $pdf->Cell(90);
     $pdf->SetFont('dejavu','B',12);
  $pdf->Cell(55,5,'T.T.C',1,0,'C',0); 
    $pdf->SetFont('deja',' ',11);
 $pdf->Cell(50,5,number_format($total,2,"."," ") . ' DH',1,1,'C',0); 
 $pdf->Ln(20);


  $str='Arréter le présent bon de livraison a la somme de :'.chifre_en_lettre(intval($total));
 if(intval(round($total-intval($total), 2)*100)>0 )
 {
  $str .=' et ';  
 if (round($total,2) - intval($total)<0.1 )
$str.= 'Zero ' ;
$str.= trim(chifre_en_lettre(substr(strstr(number_format($total,2,"."," "),"."),1) ,' Centimes'));
}
 $pdf->MultiCell(180,5,$str ); 

$pdf->Output();
?>