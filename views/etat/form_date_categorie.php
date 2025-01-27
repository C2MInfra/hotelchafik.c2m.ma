
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>asset/css/vendor/bootstrap-datepicker3.min.css">
     <link rel="stylesheet" href="<?php echo BASE_URL; ?>asset/css/vendor/bootstrap.min.css">
     <script src="<?php echo BASE_URL; ?>asset/js/vendor/jquery-3.3.1.min.js"></script>
      <script src="<?php echo BASE_URL; ?>asset/js/vendor/bootstrap-datepicker.js"></script>

  <!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" /> -->
     <script>
    
 $(function() {
        
    
 $('#date_achat,#date_vente,#dd,#df').datepicker({
        format: 'yyyy-mm-dd',
       
    })
    });
  </script>
   

 <fieldset class="tableform" style="padding: 30px" >
<form method="post" name="form_date" target="_blanck" >
  <div class="form-group">
    <strong class="mb-4">Categorie : </strong>
	 <select name="id_categorie" class="form-control">
	 <?php getOptions1("select id_categorie,nom from categorie order by nom asc");?>
	 </select>
	
	</div>
  
    <div class="form-group">
      <strong class="mb-4">Date  D&eacute;but : </strong> <input type="text" name="dd" class="form-control" id="dd"   autocomplete="off" />  
  </div>
   <div class="form-group"><strong class="mb-4">Date Fin  : </strong><input type="text" name="df" class="form-control" id="df"    autocomplete="off" /> 
</div>
   
   <input type="submit" class="btn btn-primary" onclick="document.form_facture.submit();" value="Afficher" >
  
</form>
</fieldset>
