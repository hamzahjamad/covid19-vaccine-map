<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" 
          rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">      
    <link href="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/sticky-header/bootstrap-table-sticky-header.css" rel="stylesheet">


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
        .tabular-data {
            padding-top: 20px;
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
                <a class="nav-link" aria-current="page" href="/">Map</a>
                <a class="nav-link active" href="/tabular">Tabular</a>
            </div>
            </div>
        </div>
    </nav>
  

    <div class="container">


    <div class="row">
        <div class="col tabular-data">
            <div class="table-responsive">
                    <table class="table" data-toggle="table"  data-sticky-header="true">
                        <thead>
                            <tr>
                            <th scope="col">State</th>
                            <th scope="col">Population</th>
                            <th scope="col">First Dose (%)</th>
                            <th scope="col">Second Dose (%)</th>
                            <th scope="col">Pfizer ({{ $date }})</th>
                            <th scope="col">Sinovac ({{ $date }})</th>
                            <th scope="col">Astrazeneca ({{ $date }})</th>
                            <th scope="col">Cansino ({{ $date }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $tabular_data as $item )
                                <tr>
                                    <td> {{ $item->state }} </td>
                                    <td> {{ $item->pop }} </td>
                                    <td> {{ $item->cumul_partial_percentage }} </td>
                                    <td> {{ $item->cumul_full_percentage }} </td>
                                    <td> 
                                        {{ $item->pfizer1 }} (1st) <br>
                                        <br> 
                                        {{ $item->pfizer2 }} (2nd)
                                        <br> 
                                    </td>
                                    <td> 
                                        {{ $item->sinovac1 }} (1st) <br> 
                                        <br>
                                        {{ $item->sinovac2 }} (2nd) 
                                        <br> 
                                    </td>
                                    <td> 
                                        {{ $item->astra1 }} (1st) <br> 
                                        <br>
                                        {{ $item->astra2 }} (2nd)
                                        <br> 
                                    </td>
                                    <td> 
                                        {{ $item->cansino }}  
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    Data Source: <br>
                    <ul>
                        <li> <a href="https://github.com/MoH-Malaysia/covid19-public/blob/main/static/population.csv">Population Data - MoH-Malaysia/covid19-public</a> </li>
                        <li> <a href="https://github.com/CITF-Malaysia/citf-public/blob/main/vaccination/vax_state.csv">Vaccine Count - CITF-Malaysia/citf-public</a> </li>
                    
            </div>
        </div>
    </div>
    </div>
    
   


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" 
            crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" 
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" 
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>    
   <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js"></script>    
    

  </body>
</html>