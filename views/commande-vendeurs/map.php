<?php

if (isset($_POST['ajax'])) 
{
    include('../../evr.php');
}
$id = explode('?id=',$_SERVER["REQUEST_URI"])[1];

$data = connexion::getConnexion()->query("SELECT localisation, created_at, client FROM vente WHERE localisation IS NOT NULL AND id_bon = $id")->fetchAll(PDO::FETCH_ASSOC);	

$coords = $data;

?>



<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Commande vendeur</h1>
                <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-success  url notlink" data-url="commande-vendeurs/index.php" > <i class="glyph-icon simple-icon-arrow-left"></i></button>
                </div>
            </div>
            <div class="separator mb-5"></div>

            <div id="my-map" style="height:420px; margin-bottom:60px;"></div>
        </div>
    </div>
</div>

<script type="text/javascript">


  $( document ).ready(function(){
        let coords = <?php echo json_encode($coords) ?>;
        getMap(coords);
        
  });

  function getMap(coords)
  {
                coord = coords[0].localisation.split(',');
                // Create a Leaflet map
                const map = L.map('my-map').setView([coord[0], coord[1]], 10);
                
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

                //marker = L.marker(new L.LatLng(lat, lon)).addTo(map);
                //map.panTo(new L.LatLng(lat, lon));

                for(i=0; i< coords.length; i++)
                {
                    cor = coords[i].localisation.split(',');
                    L.marker([cor[0], cor[1]]).addTo(map).bindPopup('<strong>' + coords[i].client + '</strong>' + '<br>' + coords[i].created_at + '.')
                    .openPopup();
                }
                
            }
</script>

