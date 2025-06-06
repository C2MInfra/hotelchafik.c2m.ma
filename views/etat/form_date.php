<link rel="stylesheet" href="<?php echo BASE_URL; ?>asset/css/vendor/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>asset/css/vendor/bootstrap.min.css">
<script src="<?php echo BASE_URL; ?>asset/js/vendor/jquery-3.3.1.min.js"></script>
<script src="<?php echo BASE_URL; ?>asset/js/vendor/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>asset/css/vendor/select2.min.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>asset/css/vendor/select2-bootstrap.min.css">
<script src="<?php echo BASE_URL; ?>asset/js/vendor/select2.full.js"></script>

<!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" /> -->
<script>

   $(function () {
      $('#date_achat,#date_vente,#dd,#df').datepicker({
         format: 'yyyy-mm-dd',
      })
   });
</script>

<img class="logo d-none d-xs-block" src="<?php echo BASE_URL ?>/asset/img/logo.png" alt="" style="
    width: 80px;
    /* margin-top: 40px; */
    margin: auto;
    margin-top: 40px;
">

<fieldset class="tableform"
   style="padding: 30px; background-color:#ddd; border-radius:6px; margin:20px auto !important;">
   <form method="post" name="form_date">

      <div class="form-group">
         <label>Date début</label>
         <input type="text" name="dd" class="form-control" id="dd" autocomplete="off" />
      </div>
      <div class="form-group">
         <label>Date Fin</label>
         <input type="text" name="df" class="form-control" id="df" autocomplete="off" />
      </div>
      <div class="form-group">
      <label>Pays</label>

         <select name="id_pays[]"  class="form-control select2-single" id="pays" multiple>
            <?php
            foreach ($all_countries as $key => $value) {
               echo '<option value="' . $value['id_pays'] . '">' . $value['nom'] . '</option>';
            }
            ?>
         </select>
      </div>
      <div class="form-group">
         <label>Nb nuits</label>
         <input type="text" name="nombre_nuits" class="form-control"  autocomplete="off" />
      </div>
      <input type="submit" name="submit" value="Afficher" class="btn btn-success"
         style="width:100%; background-color:#008acc; border-color:#008acc;">
   </form>
</fieldset>
<script>
$("#pays").select2();
</script>