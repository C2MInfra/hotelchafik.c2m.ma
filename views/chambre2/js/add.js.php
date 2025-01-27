<!-- helper functions -->
<script type="text/javascript">
  function display_caracteristiques(data) {
    caracteristiques = JSON.parse(data);
    html_caracteristiques = "";
    caracteristiques.forEach(element => {
      html_caracteristiques += `<div class="form-check p-3">
          <input type="checkbox" class="form-check-input" name="caracteristique[]" id="${element.label}" value="${element.label}">
          <label for="${element.label}" class="form-check-label">${element.label}</label>
          </div>`;
    });
    $("#caracteristiques").append(html_caracteristiques);
  }

  function get_caracteristiques() {
    $.ajax({
      type: "GET",
      url: "<?php echo BASE_URL; ?>views/chambre/controler.php",
      data: {
        act: "get_caracteristiques",
      },
      dataType: 'text',
      success: function(data) {
        display_caracteristiques(data)
      }
    });
  }
</script>
<script type="text/javascript">
  $("document").ready(function() {
    //load les caracteristiques du chambre
    get_caracteristiques();
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
            'Ajouter',
            'Chambre a ete bien Ajouter',
            'success'
          ).then((result) => {
            $.ajax({
              method: 'POST',
              data: {
                ajax: true
              },
              url: `<?php echo BASE_URL . "views/chambre/index.php" ?>`,
              context: document.body,
              success: function(data) {
                history.pushState({}, "", `<?php echo BASE_URL . "chambre/index.php"; ?>`);
                $("#main").html(data);
              }
            });
          });
        }
      });
    });
  })
</script>