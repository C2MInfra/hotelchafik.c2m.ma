<script type="text/javascript">
  $("document").ready(function() {
    //ajouter une chambre
    $("body").on("click", "#senddata", function(event) {
      event.preventDefault();
      var form_data = new FormData(document.getElementById('addform'));
      $.ajax({
        type: "POST",
        url: "<?php echo BASE_URL; ?>views/chambre/controler.php",
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          swal(
            'Modifier',
            'Chambre a ete bien Modifier',
            'success'
          ).then((result) => {
            window.location = "<?php echo BASE_URL . "chambre/index.php"; ?>";
          });
        }
      });
    });
  })
</script>