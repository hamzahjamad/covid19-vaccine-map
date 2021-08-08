<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>

    <title>Malaysia Vaccine Map</title>

    <style>
        body {
            padding: 0;
            margin: 0;
        }
        html, body, #mapid {
            height: 100%;
            width: 100%;
        }
    </style>

  </head>
  <body>
    <div id="mapid"></div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

   <script>
       var mymap = L.map('mapid').setView([3.9409, 109.5775], 7);

           L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                    attribution: '<a href="https://github.com/hamzahjamad/covid19-vaccine-map">Source Code </a>, Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                    maxZoom: 18,
                    id: 'mapbox/light-v10',
                    tileSize: 512,
                    zoomOffset: -1,
                    accessToken: 'pk.eyJ1IjoiaGFtemFoamFtYWQiLCJhIjoiY2tzMTY1NXh6MGJyaTJucnlmdnE0aXJydyJ9.XdD2KlvHwKuV6l8JQSm4MQ'
            }).addTo(mymap);


            fetch('/data/geojson')
                .then(response => response.json())
                .then(data =>{
                    L.geoJSON(data, {
                        style: function(feature) {
                            return {
                                "fillOpacity": feature.properties.Weight1 / 10
                            }
                        },
                        onEachFeature: function (feature, layer) {
                            var template = `
                                <div class="card-body">
                                    <h5 class="card-title">${feature.properties.Name}</h5>
                                    <p class="card-text">${feature.properties.Description1}</p>
                                </div>
                            `;
                            layer.bindPopup(template);
                        }
                    }).addTo(mymap);
                });

            
       </script>
    

  </body>
</html>