<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
$id_chambre = explode('?id=', $_SERVER["REQUEST_URI"])[1];
$chambre_obj = new chambre();
$chambre = $chambre_obj->selectById($id_chambre);
$caracteristiques = $chambre_obj->get_cracteristiques();
$chambre_caracteristiques = explode(',', $chambre['caracteristique']);
?>
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
<style>
  #caracteristiques {
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
                  <option value="lit simple" <?php echo ($chambre['lit'] == "lit simple") ? 'selected=selected' : '' ?>>lit simple</option>
                  <option value="lit double" <?php echo ($chambre['lit'] == "lit double") ? 'selected=selected' : '' ?>>lit double </option>
                  <option value="lit double separe" <?php echo ($chambre['lit'] == "lit double separe") ? 'selected=selected' : '' ?>>lit double separe </option>
                  <option value="lit triple separe" <?php echo ($chambre['lit'] == "lit triple separe") ? 'selected=selected' : '' ?>>lit triple separe </option>
                  <option value="Supplement" <?php echo ($chambre['lit'] == "Supplement") ? 'selected=selected' : '' ?>>Supplement </option>
                </select>
              </div>
            </div>
            <fieldset>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <legend>Haute saison</legend>
                  <label for="prix_1">Une personne :</label>
                  <input type="text" class="form-control" id="haute_une_personne" name="haute_une_personne" value="<?php echo $chambre["haute_une_personne"] ?>">
                </div>
                <div class="form-group col-md-4">
                  <legend>Basse saison</legend>
                  <label for="prix_2">Une personne :</label>
                  <input type="text" class="form-control" id="basse_une_personne" name="basse_une_personne" value="<?php echo $chambre["basse_une_personne"] ?>">
                </div>
                <div class="form-group col-md-4">
                  <legend>Saison normale</legend>
                  <label for="prix_3">Une personne :</label>
                  <input type="text" class="form-control" id="normale_une_personne" name="normale_une_personne" value="<?php echo $chambre["normale_une_personne"] ?>">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="prix_1">Deux personnes :</label>
                  <input type="text" class="form-control" id="haute_deux_personnes" name="haute_deux_personnes" value="<?php echo $chambre["haute_deux_personnes"] ?>">
                </div>
                <div class="form-group col-md-4">
                  <label for="prix_2">Deux personnes :</label>
                  <input type="text" class="form-control" id="basse_deux_personnes" name="basse_deux_personnes" value="<?php echo $chambre["basse_deux_personnes"] ?>">
                </div>
                <div class="form-group col-md-4">
                  <label for="prix_3">Deux personnes :</label>
                  <input type="text" class="form-control" id="normale_deux_personnes" name="normale_deux_personnes" value="<?php echo $chambre["normale_deux_personnes"] ?>">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="prix_1">Trois personnes :</label>
                  <input type="text" class="form-control" id="haute_trois_personnes" name="haute_trois_personnes" value="<?php echo $chambre["haute_trois_personnes"] ?>">
                </div>
                <div class="form-group col-md-4">
                  <label for="prix_2">Trois personnes :</label>
                  <input type="text" class="form-control" id="basse_trois_personnes" name="basse_trois_personnes" value="<?php echo $chambre["basse_trois_personnes"] ?>">
                </div>
                <div class="form-group col-md-4">
                  <label for="prix_3">Trois personnes :</label>
                  <input type="text" class="form-control" id="normale_trois_personnes" name="normale_trois_personnes" value="<?php echo $chambre["normale_trois_personnes"] ?>">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="prix_1">Supplément :</label>
                  <input type="text" class="form-control" id="haute_supplement" name="haute_supplement" value="<?php echo $chambre["haute_supplement"] ?>">
                </div>
                <div class="form-group col-md-4">
                  <label for="prix_2">Supplément :</label>
                  <input type="text" class="form-control" id="basse_supplement" name="basse_supplement" value="<?php echo $chambre["basse_supplement"] ?>">
                </div>
                <div class="form-group col-md-4">
                  <label for="prix_3">Supplément :</label>
                  <input type="text" class="form-control" id="normale_supplement" name="normale_supplement" value="<?php echo $chambre["normale_supplement"] ?>">
                </div>
              </div>
            </fieldset>
            <div class="form-group flex-row d-flex flex-wrap" id="caracteristiques">
              <?php
              foreach ($caracteristiques as $key => $value) {
                $checked = '';
                if ($t = in_array($value->label, $chambre_caracteristiques)) {
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
include('js/update.js.php');
?>