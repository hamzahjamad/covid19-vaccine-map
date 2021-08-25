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
        #mapid {
            height: 95%;
            padding-top: 56px;
        }
        .legend-card {
            margin-bottom: 40px !important;
        }
    </style>

    @if(env('GOOGLE_ANALYTICS_ID'))
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{env('GOOGLE_ANALYTICS_ID')}}"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag("js", new Date());

        gtag("config", "{{env('GOOGLE_ANALYTICS_ID')}}");
        </script> 
    @endif 

  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="/">Map</a>
                <a class="nav-link" href="/tabular">Tabular</a>
            </div>
            </div>
        </div>
    </nav>

    <div id="mapid"></div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
                    var geojsonLayer = L.geoJSON(data, {
                        style: function(feature) {

                            const urlParams = new URLSearchParams(window.location.search);
                            const showCount = urlParams.get('show_count');

                            var weight = "";
                            
                            if (showCount == null || showCount=="dose1_cumul") {
                                weight = feature.properties.Weight1;
                            }

                            if (showCount=="dose2_cumul") {
                                weight = feature.properties.Weight2;
                            }

                            var weight = feature.properties.Weight1;
                            return {
                                "fillOpacity": weight / 10,
                                "fillColor": "#3CB64C",
                                "color": "#3CB64C",
                            }
                        },
                        onEachFeature: function (feature, layer) {

                            layer.on('click', function(){
                    
                                const urlParams = new URLSearchParams(window.location.search);
                                const showCount = urlParams.get('show_count');

                                var description = "";
                                
                                if (showCount == null || showCount=="dose1_cumul") {
                                    description = feature.properties.Description1;
                                }

                                if (showCount=="dose2_cumul") {
                                    description = feature.properties.Description2;
                                }
                                

                                var template = `
                                    <div class="card-body">
                                        <h5 class="card-title">${feature.properties.Name}</h5>
                                        <p class="card-text">${description}</p>
                                    </div>
                                `;
                                layer.bindPopup(template).openPopup();

                            });
                           
                        }
                    });
                    
                    geojsonLayer.addTo(mymap);


                    var customControl =  L.Control.extend({
                                                    options: {
                                                    position: 'bottomleft'
                                                    },
                                                    onAdd: function (map) {
                                                        var container = L.DomUtil.create('div');
                                                        container.className = "card legend-card"
                                                        var buttonGroup = `
                                                        <div class="card-header">
                                                            Legend
                                                        </div>
                                                        <div class="card-body">
                                                            <h6 class="card-title">Display Doses Administered</h6>
                                                            <div class="form-check card-text">
                                                                <input class="form-check-input" type="radio" name="show_count" value="dose1_cumul" id="first_doses" checked>
                                                                <label class="form-check-label" for="first_doses">
                                                                    First Doses
                                                                </label>
                                                                </div>
                                                                <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="show_count" value="dose2_cumul" id="second_doses">
                                                                <label class="form-check-label" for="second_doses">
                                                                    Second Doses
                                                                </label>
                                                            </div>
                                                
                                                        </div>
                                                        `;
                                                
                                                        container.innerHTML = buttonGroup;
                                                        var firstDose = container.children[1].children[1].children[0];
                                                        var secondDose = container.children[1].children[2].children[0];
      
                                                        var handleShowCount = function($event) {
                                                            window.history.replaceState(null, null, '/?show_count=' + $event.target.value);
                                                        };

                                                        firstDose.onclick = handleShowCount
                                                        secondDose.onclick = handleShowCount

                                                        return container;
                                                    }
                                                });
                    mymap.addControl(new customControl());

                });

            
       </script>
    

  </body>
</html>