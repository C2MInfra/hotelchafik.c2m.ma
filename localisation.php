<html>
    <head>
        <title>Localisation</title>
        <style>
            html,
            body,
            .main {
            width: 100%;
            height: 100%;
            margin: 0;
            }

            .main {
            display: flex;
            flex-direction: row;
            }

            .address-line {
                padding: 10px;
                display: flex;
                flex-direction: row;
                min-width: 400px;
            }

            #address {
            padding: 5px 10px;
            flex: 1;
            margin-right: 5px;
            }

            .results {
                padding: 10px;
                display: flex;
                flex-direction: column;
                min-width: 400px;
            }

            .status {
                padding: 10px;
            }

            #my-map {
            flex: 1;
            }
        </style>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
    </head>
    <body>
        <h3>Your Location is: <span class="geolocation" style="color: #29bd25;"></span></h3>
        <div class="main">
            <div class="controls">
                <div class="address-line">
                <input id="address" type="text" placeholder="Enter address to search"><button onclick="geocodeAddress()">Geocode</button>
                </div>
                <div class="results">
                   
                </div>
                <div class="status">
                <span id="status">entrer addresse puis cliquer sur "Rechercher"</span>
                </div>
            </div>
            <div id="my-map"></div>
            </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            navigator.geolocation.getCurrentPosition(onSuccess, onError);

            // handle success case
            function onSuccess(position) {
                const {
                    latitude,
                    longitude
                } = position.coords;

                $('.geolocation').text(`(${latitude},${longitude})`);
                getMap(latitude, longitude);
            }

            // handle error case
            function onError(){
                console.log(`Failed to get your location!`);
            }
        
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
                map.panTo(new L.LatLng(lat, lon));
            }
        </script>
    </body>
</html>
