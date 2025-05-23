<?php

$bon = explode('?bon=',$_SERVER["REQUEST_URI"])[1];
if (isset($_POST['ajax'])) 
{
   echo '<script>window.location.reload()</script>';
   exit;
include('../../evr.php');

}

$vente = new vente();

$data = $vente->selectAll3(date('Y') . '-' . date('m'));
$result = connexion::getConnexion()->query("SELECT t1.*,t2.avance from 
(select v.localisation,  v.id_vente,v.numbon,DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,v.client
	,c.id_client,c.nom_prenom_ar,v.remarque   ,sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)) as montantv ,
   sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100) )as motunitv from vente v 
left join client c on c.id_client=v.id_client 
inner join detail_vente dv on dv.id_vente=v.id_vente 
inner join produit p on dv.id_produit=p.id_produit
where v.id_bon = " . $bon . "
group by  v.id_vente  order by id_vente desc  ) as t1 
left join (select id_vente,ifnull(sum(montant),0) as avance 
from reg_vente group by id_vente ) as t2 
on t2.id_vente=t1.id_vente ");
	   
   $data = $result->fetchAll(PDO::FETCH_OBJ);

?>

<div class="container-fluid disable-text-selection">

  <div class="row">

    <div class="col-12">

      <div class="mb-2">

        <h1>Liste Des Ventes</h1>

        <div class="float-sm-right text-zero">
        <button type="button" class="btn btn-success  url notlink" data-url="commande-vendeurs/index.php" > <i class="glyph-icon simple-icon-arrow-left"></i></button>
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="vente_bon/add.php?bon=<?php echo $bon?>" >AJOUTER</button>

        </div>

      </div>

      <div class="separator mb-5"></div>

    </div>

  </div>

  <div class="row">

    <div class="col-xl-12 col-lg-12 mb-4">

      <div class="card h-100 ">

        <div class="card-body =">

          <form method="get" name="frm" id="formfilter" class="  d-none">

            <input type="hidden" name="act" value="filter">

            <div class="form-row">

              <div class="form-group col-md-2">

                <h4 class="mt-5 ">Mois de  recherche : </h4>

              </div>

              <div class="form-group col-md-2">
                <label for="id_client">A : </label>
                <select name='anne' class="form-control" id="anne">
                  <option value="0">Tous</option>
                  <?php for($d=Date("Y");$d>= 2009;$d--)
                  echo "<option value='$d'> $d</option>";?>
                </select>

              </div>

              <div class="form-group col-md-2">

                <label> M: </label>

                <select name='mois' class="form-control" id="mois">

                   <option value="0">Tous</option>
                  <?php for($m= 1;$m<=9;$m++)

                  echo "<option value='0$m' >0$m</option>";?>

                  <?php for($m= 10;$m<=12;$m++)

                  echo "<option value='$m' >$m</option>";?>

                </select>

              </div>

              

              <div class="form-group col-md-2">

                <button  type="submit" class="btn btn-success default btn-lg btn-block  mr-1 " style="margin-top: 30px;">Afficher</button>

              </div>

            </div>

          </form>

          <div id="results">

            

            <table class="table  responsive table-striped table-bordered table-hover" id="datatables" >

              <thead>

                <tr>
                  <th scope="col">Id</th>

                  <th scope="col">Client</th>

                  <th class="nowrap"> Date</th>

                  <th scope="col"> Montant</th>

                  <th scope="col"> Remarque</th>

                  <th scope="col"> Localisation</th>
                  
                  <th scope="col">Actions</th>

                </tr>

              </thead>

              <tbody>

                <?php

                foreach($data as $ligne) 
                {

                $query = $result = connexion::getConnexion()->query("SELECT count(*) as nbr,id_facture FROM facture where id_vente like '%" . $ligne->id_vente . "%'");

                $result = $query->fetch(PDO::FETCH_OBJ);

                $id_facture = $result->id_facture;

                $nbr = $result->nbr;
				
                ?>

                <tr>

                  <td> <?php echo $ligne->id_vente; ?></td>

                  <td class="nowrap">

                    <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->client;

                      if($ligne->nom_prenom_ar != "" && $ligne->client == " ") {

                      echo $ligne->nom_prenom_ar;

                      }

                      if($ligne->nom_prenom_ar != "" && $ligne->client != " ") {

                      echo "/" . $ligne->nom_prenom_ar;

                      }

                    ?> </a> </td>

                    <td class="nowrap">
                    <?php if($ligne->numbon!=0){?>
                    <a target="_blank" href="<?php echo BASE_URL."views/vente/facturebon.php?id=".$ligne->id_vente; ?>&h=15" class="badge badge-primary"><?php echo $ligne->date_vente; ?></a>  
                    <?php }else{?>
                      <span   class="badge badge-success"><?php echo $ligne->date_vente; ?></span> 
                    <?php }?>
                    </td>

                    <td style="text-align: right;" class="nowrap" data-href="#"> 
                       <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>">
                      <?php 
                      // var_dump($ligne->motunitv);
                      if($ligne->motunitv!=0 || !empty($ligne->motunitv)){
                        echo number_format($ligne->motunitv, 2, '.', ' ');
                      }else{
                        echo number_format($ligne->montantv, 2, '.', ' ');
                      }
                       ?>
                        </a>
                      &nbsp;&nbsp;
                    </td>

                    <td> <?php echo strlen($ligne->remarque) >50 ?substr($ligne->remarque, 0,50)."..." : $ligne->remarque; ?> </td>
                    
                    <td>
                    	<?php echo $ligne->localisation ?>
                    </td>
                    
                    <td class="nowrap">
                      <a class="badge badge-secondary mb-2 url notlink" data-url="detail_vente/index.php?id=<?php echo $ligne->id_vente; ?>" style="color: white;cursor: pointer;" title="voir Detail" href="javascript:void(0)">
                          <i class="glyph-icon simple-icon-list" style="font-size: 15px;"></i>
                      </a>
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
					
                     <a class="badge badge-info mb-2  " style="color: white;cursor: pointer;" title="Pro-format" href="<?php echo BASE_URL."views/devis/facture_pro_format.php?id=".$ligne->id_vente; ?>&h=15"  target="_black" >

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

                      <a class="badge badge-primary mb-2 notlink" style="background-color: #d322e8!important;color: white;cursor: pointer;" title="Ticket" href='<?php echo BASE_URL.'/views/vente/ticket.php?id='.$ligne->id_vente; ?>'  target="_black" >

                        <i class=" iconsmind-Billing" style="font-size: 15px;"></i>

                      </a>

                      <?php } ?>

                      

                      
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalMap" class="badge badge-success btn-localisation" data-localisation="<?php echo $ligne->localisation ?>" style="font-size:10pt;">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </a>
                      

                    </td>

                  </tr>

                  <?php } ?>

                </tbody>

              </table>

            </div>

          </div>

        </div>

      </div>

      <div class="modal fade modal-right" id="exampleModalRight" tabindex="-1" role="dialog" aria-labelledby="exampleModalRight" aria-hidden="true">

        <div class="modal-dialog" role="document">

          <div class="modal-content">

            <div class="modal-header">

              <h5 class="modal-title">Etat client</h5>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">&times;</span>

              </button>

            </div>

            <form id="Staticform" method="post" name="form_client" enctype="multipart/form-data">

              <div class="modal-body">

                

                

                <input type="hidden" name="act" value="getetat">

                <input type="hidden" name="id" id="idstatic">

                <div class="form-group">

                  

                  <h5 class="mb-4">Date début</h5>

                  

                  <input class="form-control datepicker" autocomplete="off" placeholder="Date" name="dd">

                </div>

                <div class="form-group">

                  

                  <h5 class="mb-4">Date fin</h5>

                  

                  <input class="form-control datepicker" autocomplete="off" placeholder="Date" name="df">

                </div>

                <h5 class="mb-4">Type</h5>

                <div class="mb-4">

                  <div class="custom-control custom-radio">

                    

                    <input type="radio" id="customRadio1" value="vente" checked="" name="etatProduit" class="custom-control-input">

                    <label class="custom-control-label"  for="customRadio1">Vente </label>
                    
                  </div>

                  <div class="custom-control custom-radio">

                    

                    <input type="radio" id="customRadio2" value="achat" name="etatProduit" class="custom-control-input">

                    <label class="custom-control-label"  for="customRadio2">Achat  </label>
                  </div>

                </div>

                

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>

                <input type="submit" name="submit" value="Afficher"  class="btn btn-primary"  data-toggle="modal" data-target=".bd-example-modal-lg">

                

              </div>

            </form>

          </div>

        </div>

      </div>

      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">

        <div class="modal-dialog modal-lg">

          <div class="modal-content">

            <div class="modal-header">

              <h5 class="modal-title">Liste des Ventes des clients : kit des implants</h5>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">&times;</span>

              </button>

            </div>

            <div class="modal-body" id="etatstatic">

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

  <div class="modal fade" id="modalMap" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-0">
        <div id="my-map" style="height:400px;"></div>
      </div>
    </div>
  </div>
</div>

  <script type="text/javascript">
    function getMap(lat, lon)
    {
                // Create a Leaflet map
                const map = L.map('my-map').setView([33.5922, -7.6184], 17);
                
                // Marker to save the position of found address
                let marker;

                // The API Key provided is restricted to JSFiddle website
                // Get your own API Key on https://myprojects.geoapify.com
                const myAPIKey = "bf67e03cde8540789febc4ff158b7d0e";

                // Retina displays require different mat tiles quality
                const isRetina = L.Browser.retina;
                const baseUrl = "https://maps.geoapify.com/v1/tile/osm-liberty/{z}/{x}/{y}.png?apiKey={apiKey}";
                const retinaUrl = "https://maps.geoapify.com/v1/tile/osm-liberty/{z}/{x}/{y}@2x.png?apiKey={apiKey}";

                // add Geoapify attribution
                map.attributionControl.setPrefix('Powered by <a href="https://www.c2m.ma/" target="_blank">C2M</a>')

                // Add map tiles layer. Set 20 as the maximal zoom and provide map data attribution.
                L.tileLayer(isRetina ? retinaUrl : baseUrl, {
                attribution: '',
                apiKey: myAPIKey,
                maxZoom: 20,
                id: 'osm-bright',
                }).addTo(map);

                // move zoom controls to bottom right
                map.zoomControl.remove();
                L.control.zoom({
                position: 'bottomright'
                }).addTo(map);

                marker = L.marker(new L.LatLng(lat, lon)).addTo(map);
    }

   $('.btn-localisation').click(function(){
        let coord = $(this).data('localisation');
        let coords = coord.split(',');
        
        if(coords.length == 2)
        {
            getMap(coords[0], coords[1]);
        }
   });

   function searchFilter(page_num) 
   {
            page_num = page_num ? page_num : 0;
            var keywords = $('#keywords').val();
            var depot = $('#depot').val();
            var categorie = $('#categorie').val();
            var stock = $('#stock').val();
            var prix = $('#prix').val();
            var operateur = $('#operateur').val();
            var sortBy = $('#sortBy').val();
		    var fournisseur = $('#fournisseur').val();
		
            $.ajax({
                type: 'POST',
                  url: "<?php echo BASE_URL.'views/produit/' ;?>controle.php",
                data: 'act=pagination&keywords=' + keywords + '&page=' + page_num + '&depot=' + depot + '&categorie=' + categorie + '&stock=' + stock + '&sortBy=' + sortBy +'&prix='+prix+'&operateur='+ operateur + '&fournisseur=' + fournisseur,
                beforeSend: function () {
                    $('.loading-overlay').show();
                },
                success: function (html) {
                    $('#posts_content').html(html);
                   $('#myTable').DataTable( {
                   responsive: true,
                     columnDefs: [
                                  {
                            "targets": [ 0 ],
                            "visible": false,
                                  },
                                  { responsivePriority: 1, targets: -4 },
                                  { responsivePriority: 2, targets: -2 }
                        ],
                    bPaginate: false, 
                    bFilter: false, 
                    bInfo: false,
                } );
                }
            });
        }

  $(document).ready(function() {

  $("input.datepicker").datepicker({

  format: 'yyyy-mm-dd',

  templates: {

  leftArrow: '<i class="simple-icon-arrow-left"></i>',

  rightArrow: '<i class="simple-icon-arrow-right"></i>'

  }

  })

  $('#datatables').dataTable({
  responsive: true,
  order: [

  [0, "desc"]

  ],

  dom: 'Bfrtip',

  buttons: [{

  extend: 'excelHtml5',

  title: "liste des ventes",

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  },

  {

  extend: 'pdfHtml5',

  title: "liste des ventes",

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  },

  {

  extend: 'csvHtml5',

  title: "liste des ventes",

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  }

  ],

  pageLength: 20,

  language: {

  paginate: {

  previous: "<i class='simple-icon-arrow-left'></i>",

  next: "<i class='simple-icon-arrow-right'></i>"

  }

  },

  drawCallback: function(){

  $($(".dataTables_wrapper .pagination li:first-of-type")).find("a").addClass("prev"),

  $($(".dataTables_wrapper .pagination li:last-of-type")).find("a").addClass("next"),

  $(".dataTables_wrapper .pagination").addClass("pagination-sm")

  }

  });

  $('body').on("click", ".delete", function(event) {

  event.preventDefault();

  var btn = $(this);

  swal({

  title: 'Êtes-vous sûr?',

  text: "Voulez vous vraiment Supprimer ce Client !",

  type: 'warning',

  showCancelButton: true,

  confirmButtonColor: '#d33',

  cancelButtonColor: '#3085d6',

  confirmButtonText: 'Oui, Supprimer !'

  }).then((result) => {

  if (result.value) {

  $.ajax({

  type: "POST",

  url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",

  data: {

  act: "delete",

  id: btn.data('id')

  },

  success: function(data) {

  swal(

  'Supprimer',

  'Client a ete bien Supprimer',

  'success'

  ).then((result) => {

  btn.parents("td").parents("tr").remove();

  });

  }

  });

  }

  });

  });

  $('body').on("click", ".archive", function(event) {

  event.preventDefault();

  var btn = $(this);

  swal({

  title: 'Êtes-vous sûr?',

  text: "Voulez vous vraiment Archiver ce client !!",

  type: 'warning',

  showCancelButton: true,

  confirmButtonColor: '#145388',

  cancelButtonColor: '#3085d6',

  confirmButtonText: 'Oui, Archiver!'

  }).then((result) => {

  if (result.value) {

  $.ajax({

  type: "POST",

  url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",

  data: {

  act: "archive",

  id: btn.data('id'),

  val: btn.data('arc')

  },

  success: function(data) {

  swal(

  "Archived",

  'Your Product has been archived.',

  'success'

  ).then((result) => {

  btn.parents("td").parents("tr").remove();

  });

  }

  });

  }

  });

  });

  $('body').on("click", ".static", function(event) {

  event.preventDefault();

  var btn = $(this);

  $.ajax({

  type: "POST",

  url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",

  data: {

  act: "getName",

  id: btn.data('id')

  },

  success: function(datas) {

  var data = datas.split(';;;');

  $('#exampleModalRight .modal-title').html("Etat client " + data[1]);

  $('#idstatic').val(data[0]);

  }

  });

  });

  $("#Staticform").on("submit", function(event) {

  event.preventDefault();

  var form = $(this);

  $.ajax({

  type: "POST",

  url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",

  data: new FormData(this),

  dataType: 'text', // what to expect back from the PHP script, if anything

  cache: false,

  contentType: false,

  processData: false,

  success: function(data) {

  $('#etatstatic').html(data);

  }

  });

  });
//--------------------------------------------------
  $("#formfilter").on("submit", function(event) {

  event.preventDefault();

  $("#results").html('<div class="col-md-12"><br><br><br><br><br><br><div class="loading"></div></div>');

  var form = $(this);

  $.ajax({

  type: "POST",

  url: "<?php echo BASE_URL.'views/vente/' ;?>controle.php",

  data: new FormData(this),

  dataType: 'text',

  cache: false,

  contentType: false,

  processData: false,

  success: function(data) {

  $("#results").html(data);

  $('#datatables').dataTable({
    responsive: true,
  order: [

  [0, "desc"]

  ],

  dom: 'Bfrtip',

  buttons: [{

  extend: 'excelHtml5',

  title: "liste des ventes "+ $("#mois").val() +"-"+ $("#anne").val(),

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  },

  {

  extend: 'pdfHtml5',

  title: "liste des ventes "+ $("#mois").val() +"-"+ $("#anne").val(),

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  },

  {

  extend: 'csvHtml5',

  title: "liste des ventes "+ $("#mois").val() +"-"+ $("#anne").val(),

  exportOptions: {

  columns: [0, 1, 2, 3, 4, 5,]

  }

  }

  ],

  pageLength: 20,

  language: {

  paginate: {

  previous: "<i class='simple-icon-arrow-left'></i>",

  next: "<i class='simple-icon-arrow-right'></i>"

  }

  },

  drawCallback: function() {

  $($(".dataTables_wrapper .pagination li:first-of-type")).find("a").addClass("prev"),

  $($(".dataTables_wrapper .pagination li:last-of-type")).find("a").addClass("next"),

  $(".dataTables_wrapper .pagination").addClass("pagination-sm")

  }

  });

  }

  });

  });

  });

  </script>
  <script>
     document.addEventListener('DOMContentLoaded', () => {
         const cols = document.querySelectorAll('td[data-href]');
         console.log(cols);
     });
  </script>