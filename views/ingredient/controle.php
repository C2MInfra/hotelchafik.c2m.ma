<?php
include('../../evr.php');



if($_POST['act']=='getcat' && isset($_POST['id'])) {
   $id = $_POST['id'];

    $ingredient = new ingredient();
    $data = $ingredient->selectChamps("code_bar","id =  $id",'',"id","desc","1");
     die ($data[0]->code_bar);
}
elseif ($_POST['act']=='insert') {
  try {
    $_POST["id_user"] = auth::user()["id"] ;
    $ingredient=new ingredient(); 
    $res = $ingredient->insert();
    if ($res) {
      die('success');
    }
    die("Erreur");
    } catch (Exception $e) {
        die($e);
    
  }
}
elseif ($_POST['act']=='update') {
  try {
    $_POST["id_user"] = auth::user()["id"] ;
      $ingredient=new ingredient(); 
    $ingredient->update($_POST["id"]);
    
      die('success');
    
    } catch (Exception $e) {
        die($e);
    
  }
}
elseif ($_POST['act']=='delete') {
  try {
    
  
    $ingredient=new ingredient();
    $ingredient->delete($_POST["id"]);
    die('success');
    } catch (Exception $e) {
        die($e);
  }
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
  $detail_produit=new detail_produit();




    if(isset($_POST['id'])){
    $detail_produit->delete($_POST['id']);
    }
    
    
  $data=$detail_produit->selectAllNonValide();
  $total=0;
  foreach($data as $ligne){
?>
<tr>
  
  <td><?php echo $ligne->designation ; ?></td>
  <td><?php echo $ligne->qte; ?></td>
  

  </td>
  <td><a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
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

elseif ($_POST['act']=='addIngredient') 
{

  if (!isset($_SESSION['rand_v_er']) || $_SESSION['rand_v_er']==="" ){
  $_SESSION['rand_v_er']=rand(10,1000);
  }
  $_POST["id_user"] = auth::user()["id"] ;
  $somme_poid=0;
  $_POST["id_produit"]= "-1".$_SESSION['rand_v_er'];

  $detail_produit=new detail_produit();
  $detail_produit->insert();
  $data = $detail_produit->selectAllNonValide();
  $total=0;
  foreach($data as $ligne){
?>
<tr>
  <td><?php echo $ligne->designation ; ?></td>
  <td><?php echo $ligne->qte ; ?></td>

  </td>
  <td>    <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)' >
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








?>