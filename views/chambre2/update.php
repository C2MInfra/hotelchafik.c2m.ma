<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$id_chambre = explode('?id=',$_SERVER["REQUEST_URI"])[1];
$chambre_obj = new chambre();
$chambre = $chambre_obj->selectById($id_chambre);
$caracteristiques = $chambre_obj->get_cracteristiques();
$chambre_caracteristiques = explode(',',$chambre['caracteristique']);
?>
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
<style>
  #caracteristiques{
    height: 300px;
    overflow-y: auto;
    padding: 10px;
  }
</style>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Chambre </h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-success  url notlink" data-url="chambre/index.php"> <i class="glyph-icon simple-icon-arrow-left"></i></button>
        </div>
      </div>
      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">
    <div class="col align-self-start">
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="mb-4">Modifier une chambre</h5>
          <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
            <input type="hidden" name="act" value="update">
            <input type="hidden" name="id_chambre" value="<?php echo $id_chambre; ?>">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="numero_chambre">Numero :</label>
                <input type="text" class="form-control" id="numero_chambre" name="numero_chambre" value="<?php echo $chambre['numero_chambre'] ?>">
              </div>
              <div class="form-group col-md-4">
                <label for="metrage">Métrage :</label>
                <input type="text" class="form-control" id="metrage" name="metrage" value="<?php echo $chambre['metrage'] ?>">
              </div>
              <div class="form-group col-md-4">
                <label for="lit">Lit :</label>
                <select class="form-control" id="lit" name="lit">
                  <option value="1 large double bed" <?php echo ($chambre['lit'] == "1 large double bed") ? 'selected=selected' : '' ?>>1 large double bed</option>
                  <option value="1 extra-large double bed" <?php echo ($chambre['lit'] == "1 extra-large double bed") ? 'selected=selected' : '' ?>>1 extra-large double bed</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="prix_1">Prix 1 :</label>
                <input type="text" class="form-control" id="prix_1" name="prix_1" value="<?php echo $chambre['prix_1'] ?>">
              </div>
              <div class="form-group col-md-4">
                <label for="prix_2">Prix 2 :</label>
                <input type="text" class="form-control" id="prix_2" name="prix_2" value="<?php echo $chambre['prix_2'] ?>">
              </div>
              <div class="form-group col-md-4">
                <label for="prix_3">Prix 3 :</label>
                <input type="text" class="form-control" id="prix_3" name="prix_3" value="<?php echo $chambre['prix_3'] ?>">
              </div>
            </div>
            <div class="form-group flex-row d-flex flex-wrap" id="caracteristiques">
              <?php
                foreach ($caracteristiques as $key => $value) {
                  $checked = '';
                  if($t = in_array($value->label, $chambre_caracteristiques)){
                    $checked = 'checked';
                  }
                  echo "<div class=\"form-check p-3\">
                  <input type=\"checkbox\" class=\"form-check-input\" $checked name=\"caracteristique[]\" id=\"$value->label\" value=\"$value->label\">
                  <label for=\"$value->label\" class=\"form-check-label\">$value->label</label>
                  </div>";
                }
              ?>
            </div>
            <div class="float-sm-right text-zero saveb">
              <button type="button" id="senddata" class="btn btn-primary btn-lg  mr-1 ">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include('js\update.js.php');
?>