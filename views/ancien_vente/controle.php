<?php
include('../../evr.php');
if ($_POST['act']=='filter') {
    $vente=new vente();
if($_POST['anne'] != 0)
$data = $vente->selectAll3($_POST['anne'] . "-" . $_POST['mois']);
else
$data = $vente->selectAll3all();
?>
<table class="table  responsive table-striped table-bordered table-hover" id="datatables" >
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Client</th>
      <th> Date</th>
      <th scope="col"> Montant</th>
      <th scope="col"> Reste</th>
      <th scope="col"> remarque</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach($data as $ligne) {
    $query = $result = connexion::getConnexion()->query("SELECT count(*) as nbr,id_facture FROM facture where id_vente like '%" . $ligne->id_vente . "%'");
    $result = $query->fetch(PDO::FETCH_OBJ);
    $id_facture = $result->id_facture;
    $nbr = $result->nbr;
    ?>
    <tr>
      <td> <?php echo $ligne->id_vente; ?></td>
      <td class="nowrap"><a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->client;
        if($ligne->nom_prenom_ar != "" && $ligne->client == " ") {
        echo $ligne->nom_prenom_ar;
        }
        if($ligne->nom_prenom_ar != "" && $ligne->client != " ") {
        echo "/" . $ligne->nom_prenom_ar;
        } 
      ?> </a> </td>
      <td class="nowrap"><?php if($nbr > 0) {
        echo "<a target='_blank'  href='../facture/facture.php?id=" . $ligne->id_vente . "&idf=" . $id_facture . "' tilite='Facture'> " . $ligne->date_vente . "</a>";
        } else {
        echo $ligne->date_vente;
      } ?> </td>
      <td class="nowrap" style="text-align: right;"> <?php echo number_format($ligne->montantv, 2, '.', ' '); ?>
        &nbsp;&nbsp;
      </td>
      <td class="nowrap" style="text-align: right;"> <?php
        $query = $result = connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_vente where id_vente=" . $ligne->id_vente);
        $result = $query->fetch(PDO::FETCH_OBJ);
        $paye = $result->paye;
        echo number_format($ligne->montantv - $paye, 2, '.', ' ');
        ?> &nbsp;&nbsp;
      </td>
      <td> <?php echo strlen($ligne->remarque) >50 ? substr($ligne->remarque, 0,50)."..." : $ligne->remarque; ?> </td>
      <td class="nowrap">
                      <?php if(auth::user()['privilege'] == 'Admin') { ?>
                      <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                        <i class="simple-icon-trash" style="font-size: 15px;"></i>
                      </a>
                      <a class="badge badge-success mb-2  url notlink" data-url="reg_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)' >
                        <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>
                      </a>
                        <a class="badge badge-warning mb-2  url notlink" data-url="vente/update.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Modifier"
                    href="javascript:void(0)">
                    <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>
                  </a>
                     
                      <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL."views/vente/facture.php?id=".$ligne->id_vente; ?>&h=15"  target="_black" >
                        <i class=" simple-icon-printer" style="font-size: 15px;"></i>
                      </a>
                      <a  class="badge badge-secondary mb-2 url notlink" data-url="detail_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">
                        
                        <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>
                      </a>
                       <?php if($ligne->numbon == 0) { ?>
                      <a class="badge badge-warning mb-2  url notlink" data-url="vente/transfer.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Bon livraison" href='javascript:void(0)' >
                        <i class="iconsmind-Add-Cart" style="font-size: 15px;"></i>
                      </a>
                      <?php } ?>
                      <?php if($nbr == 0) { ?>
                      <a class="badge badge-primary mb-2 url notlink" style="color: white;cursor: pointer;" title="Facture" data-url="<?php echo "facture/add.php?idv[]=".$ligne->id_vente; ?>" href='javascript:void(0)' >
                        <i class=" iconsmind-Billing" style="font-size: 15px;"></i>
                      </a>
                      <?php } ?>
                      <?php } ?>
                      
                      
                      
                    </td>
                  </tr>
                  <?php } ?>
  </tbody>
</table>
<?php
  
}
if ($_POST['act']=='filterbon') {
    $vente=new vente();
if($_POST['anne'] != 0)
$data = $vente->selectallbond($_POST['anne'] . "-" . $_POST['mois']);
else
$data = $vente->selectallbon();
?>
<table class="table  responsive table-striped table-bordered table-hover" id="datatables" >
              <thead>
                <tr>
                  <th scope="col">Id</th>
                  <th scope="col">Cliet</th>
                  <th> Date</th>
                  <th scope="col"> Montant</th>
                  <th scope="col"> Reste</th>
                  <th scope="col"> remarque</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach($data as $ligne) {
                $query = $result = connexion::getConnexion()->query("SELECT count(*) as nbr,id_facture FROM facture where id_vente like '%" . $ligne->id_vente . "%'");
                $result = $query->fetch(PDO::FETCH_OBJ);
                $id_facture = $result->id_facture;
                $nbr = $result->nbr;
                ?>
                <tr>
                  <td> <?php echo $ligne->numbon; ?></td>
                  <td class="nowrap">
                    <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->client;
                      if($ligne->nom_prenom_ar != "" && $ligne->client == " ") {
                      echo $ligne->nom_prenom_ar;
                      }
                      if($ligne->nom_prenom_ar != "" && $ligne->client != " ") {
                      echo "/" . $ligne->nom_prenom_ar;
                      }
                    ?> </a> </td>
                    <td class="nowrap"><?php   echo $ligne->date_vente; ?> </td>
                    <td class="nowrap" style="text-align: right;"> <?php echo number_format($ligne->montantv, 2, '.', ' '); ?>
                      &nbsp;&nbsp;
                    </td>
                    <td class="nowrap" style="text-align: right;"> <?php
                      $query = $result = connexion::getConnexion()->query("SELECT sum(montant) as paye FROM reg_vente where id_vente=" . $ligne->id_vente);
                      $result = $query->fetch(PDO::FETCH_OBJ);
                      $paye = $result->paye!=null  ?  $result->paye : 0;
                      echo number_format($ligne->montantv - $paye, 2, '.', ' ');
                      ?> &nbsp;&nbsp;
                    </td>
                    <td> <?php  echo strlen($ligne->remarquebon) >50 ? substr($ligne->remarquebon, 0,50)."..." : $ligne->remarquebon;?> </td>
                    <td class="nowrap">
                      <?php if(auth::user()['privilege'] == 'Admin') { ?>
                      <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
                        <i class="simple-icon-trash" style="font-size: 15px;"></i>
                      </a>
                      <a class="badge badge-warning mb-2  url notlink" data-url="vente/updatebon.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Modifier" href='javascript:void(0)' >
                        <i class="iconsmind-Pen-5" style="font-size: 15px;"></i>
                      </a>
                      <a class="badge badge-success mb-2  url notlink" data-url="reg_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="Régler" href='javascript:void(0)' >
                        <i class=" iconsmind-Money-2" style="font-size: 15px;"></i>
                      </a>
                      <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Imprimmer" href="<?php echo BASE_URL."views/vente/facturebon.php?id=".$ligne->id_vente; ?>&h=15"  target="_black" >
                        <i class=" simple-icon-printer" style="font-size: 15px;"></i>
                      </a>
                      <a  class="badge badge-secondary mb-2 url notlink" data-url="detail_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">
                        
                        <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>
                      </a>
                      <?php if($nbr==0 && $ligne->numbon!=0 ){ ?>
                      <a class="badge badge-primary mb-2 url notlink" style="color: white;cursor: pointer;" title="Facture" data-url="<?php echo "facture/add.php?idv[]=".$ligne->id_vente; ?>" href='javascript:void(0)' >
                        <i class=" iconsmind-Billing" style="font-size: 15px;"></i>
                      </a>
                      <?php } ?>
                      <?php } ?>
                      
                      
                      
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
<?php
  
}
if ($_POST['act']=='getproduit') {
  $depot=new depot();
  $res_depot=$depot->selectAll();
  foreach($res_depot as $rep_depot){
?>
<optgroup label="<?php echo $rep_depot->nom; ?> ">
  <?php
  $produits=$depot->selectQuery("SELECT  id_produit,designation  FROM produit where   id_categorie=".$_POST['id_categorie']." and   emplacement='".$rep_depot->id."' order by designation asc");

  foreach ($produits as $row) {
  echo '<option value="'.$row->id_produit.'">'.$row->designation.'</option>';
  }?>
</optgroup>
<?php }
}
if ($_POST['act']=='rech') {
$depot=new depot();
$res_depot=$depot->selectAll();
foreach($res_depot as $rep_depot){
?>
<optgroup label="<?php echo $rep_depot->nom; ?> ">
  <?php
  $produits=$depot->selectQuery("SELECT  id_produit,designation as designation   FROM produit where  code_bar like '".$_POST['id']."%' and   emplacement='".$rep_depot->id."' order by designation asc");
  foreach ($produits as $row) {
  echo '<option value="'.$row->id_produit.'">'.$row->designation.'</option>';
  }?>
</optgroup>
<?php }
}
elseif ($_POST['act']=='deleterow') {
  $detail_vente=new detail_vente();




    if(isset($_POST['id_detail'])){
    $detail_vente->delete($_POST['id_detail']);
    }
    
    
  $data=$detail_vente->selectAllNonValide();
  $total=0;
  foreach($data as $ligne){
?>
<tr>
  
  <td><?php echo $ligne->designation ; ?></td>
  <td><?php echo $ligne->prix_produit ; ?></td>
  <td><?php echo $ligne->qte_vendu ; ?></td>
  
  <td><?php echo $ligne->poid*$ligne->qte_vendu ; ?> g </td>
  <td width="90" style="text-align: right;" >
    <?php  echo number_format($ligne->qte_vendu * $ligne->prix_produit ,2,'.',' ');
    $total+=$ligne->qte_vendu * $ligne->prix_produit;
    ?>
    
  </td>
  <td><a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
  <i class="simple-icon-trash" style="font-size: 15px;"></i></a> </td>
</tr>
<?php
}
?>
<tr>
  <td colspan="4" style="text-align: center;font-size: 15px;" > <b>Total</b>   </td>
  <td style="text-align: right;" colspan="3">  <b style="font-size: 15px;color: green;text-align: right;" ><?php echo number_format($total,2,'.',' '); ?></b></td>
  
</tr>
<?php
  }

elseif ($_POST['act']=='addProduct') {
if (!isset($_SESSION['rand_v_er']) || $_SESSION['rand_v_er']==="" ){
  $_SESSION['rand_v_er']=rand(10,1000);
  }
  $_POST["id_user"] = auth::user()["id"] ;
  $somme_poid=0;
  $_POST["id_vente"]= "-1".$_SESSION['rand_v_er'];
  $detail_vente=new detail_vente();
  $detail_vente->insert();
  $data=$detail_vente->selectAllNonValide();
  $total=0;
  foreach($data as $ligne){
?>
<tr>
  <td><?php echo $ligne->designation ; ?></td>
  <td><?php echo $ligne->prix_produit ; ?></td>
  <td><?php echo $ligne->qte_vendu ; ?></td>
  <td><?php echo $ligne->poid*$ligne->qte_vendu ;
                $somme_poid+=$ligne->poid*$ligne->qte_vendu ;
  ?> g </td>
  <td width="90" style="text-align: right;" >
    <?php  echo number_format($ligne->qte_vendu * $ligne->prix_produit,2,'.',' ');
    $total+=$ligne->qte_vendu * $ligne->prix_produit* (1 - $ligne->remise/100);
    ?>
  </td>
  <td>    <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
    <i class="simple-icon-trash" style="font-size: 15px;"></i>
  </a>
</td>
</tr>
<?php
}
?>
<tr>
<td colspan="4" style="text-align: center;font-size: 15px;" > <b>Total</b>   </td>
<td style="text-align: right;" colspan="3">  <b style="font-size: 15px;color: green;text-align: right;" ><?php echo number_format($total,2,'.',' '); ?></b></td>

</tr>
<?php
}
elseif ($_POST['act']=='insert') {

$_POST["id_user"] = auth::user()["id"] ;
if ($_POST['bonsi'] == 1) {

$_POST['datebon'] = date('Y-m-d');

}else
{
  $_POST['numbon'] = 0;
}

$vente=new vente();
$vente->insert();


connexion::getConnexion()->exec("UPDATE  detail_vente  SET detail_vente.id_vente =(SELECT max(vente.id_vente) FROM vente)   WHERE detail_vente.id_vente = -1".$_SESSION['rand_v_er']);
unset($_SESSION['rand_v_er']);
$query=$result=connexion::getConnexion()->query("SELECT max(id_vente) as dernier_vente FROM vente ");
$result=$query->fetch(PDO::FETCH_OBJ);
$dernier_vente=$result->dernier_vente;
$result2=connexion::getConnexion()->query("select da.id_produit,sum(da.qte_vendu)as qte_vendu from detail_vente da inner join vente a on a.id_vente=da.id_vente
where a.id_vente=$dernier_vente group by  da.id_produit");
$data=$result2->fetchAll(PDO::FETCH_OBJ);

/*foreach($data as $ligne)
  {
connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel+".$ligne->qte_vendu." WHERE  id_produit =".$ligne->id_produit);
  }*/
  die("success");
}
elseif ($_POST['act']=='update') {

  try {

    $_POST["idu"] = auth::user()["id"] ;
    $vente=new vente();
    $vente->update($_POST["id"]);
    die('success');
    } catch (Exception $e) {
        die($e);
    
  }
}
elseif ($_POST['act']=='delete') {
try {


$vente=new vente();


$data = vente::getdevis($_POST["id"]);
foreach (  $data as $value) {
connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel + ".$value["qte_vendu"]." WHERE  id_produit =".$value["id_produit"] );
}
$vente->delete($_POST["id"]);





die('success');
} catch (Exception $e) {
    die($e);
}
}

elseif ($_POST['act']=='getPrix') {
$produit=new produit();
$ligne=$produit->selectById($_POST['id_produit']);
echo  $ligne['prix_vente']."/".$ligne['qte_actuel']  ;
}
elseif ($_POST['act']=='insertbon') {
try {


$data = vente::getdevis($_POST["id"]);
foreach (  $data as $value) {
connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel - ".$value["qte_vendu"]." WHERE  id_produit =".$value["id_produit"] );
}
 

$vente=new vente();
$vente->update($_POST["id"]);
die('success');
} catch (Exception $e) {
die($e);
}

}
?>