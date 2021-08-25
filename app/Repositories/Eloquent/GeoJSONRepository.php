<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IGeoJSONRepository;
use Illuminate\Support\Facades\Http;
use App\Models\VaccineState;
use App\Models\Population;

class GeoJSONRepository implements IGeoJSONRepository
{
    protected function getBaseShape()
    {
        $response = Http::get(env('APP_URL').'/data/malaysia.geojson');
        return $response->json();
    }

    public function getDosesAdministeredCountByState($state, $column_name)
    {
        //$vaccine_state = VaccineState::whereIn('date', VaccineState::selectRaw("max(date)")->get())
        $vaccine_state = VaccineState::whereIn('date', VaccineState::selectRaw("max(date)")->get())
                                     ->where("state", $state)
                                     ->first();
        
        if ($vaccine_state) {
            return $vaccine_state->$column_name;
        }

        return 0;
    }

    public function getPopulationCountByState($state, $column_name)
    {
        $population = Population::where("state", $state)
                                     ->first();
        
        if ($population) {
            return $population->$column_name;
        }

        return 0;
    }

    public function getDosesAdministeredCountRawData()
    {
        $response = Http::get('https://raw.githubusercontent.com/CITF-Malaysia/citf-public/main/vaccination/vax_state.csv');
        $result = \explode("\n", $response->body());
        $result = \array_filter($result);
        $result = \array_map(function($item){
             return explode(",", $item);
        }, $result);

        return $result;
    }


    public function getPopulationCountRawData()
    {
        $response = Http::get('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/static/population.csv');
        $result = \explode("\n", $response->body());
        $result = \array_filter($result);
        $result = \array_map(function($item){
             return explode(",", $item);
        }, $result);

        return $result;
    }


    public function getGeoJSON() 
    {
        $geojson = $this->getBaseShape();  
        $geojson_repository = $this;

        $geojson = array_map(function($item) use ($geojson_repository){
            $state = $item["properties"]["Name"];
            $population = $geojson_repository->getPopulationCountByState($state, "pop");
            $date = $geojson_repository->getDosesAdministeredCountByState($state, "date");

            $doses_administered_1st = $geojson_repository->getDosesAdministeredCountByState($state, "cumul_partial");
            $percapita_count_1st = round($doses_administered_1st/$population, 2) * 100;
            $pfizer_1st = $geojson_repository->getDosesAdministeredCountByState($state, "pfizer1");
            $sinovac_1st = $geojson_repository->getDosesAdministeredCountByState($state, "sinovac1");
            $astra_1st = $geojson_repository->getDosesAdministeredCountByState($state, "astra1");

            $doses_administered_2nd = $geojson_repository->getDosesAdministeredCountByState($state, "cumul_full");
            $percapita_count_2nd = round($doses_administered_2nd/$population, 2) * 100;
            $pfizer_2nd = $geojson_repository->getDosesAdministeredCountByState($state, "pfizer2");
            $sinovac_2nd = $geojson_repository->getDosesAdministeredCountByState($state, "sinovac2");
            $astra_2nd = $geojson_repository->getDosesAdministeredCountByState($state, "astra2");

            $item["properties"]["Weight1"] = round($doses_administered_1st/$population, 2) * 10;
            $item["properties"]["Description1"] =  "<b>".number_format($doses_administered_1st, 0, ".", ",")."</b> first doses have been administered, <b>";
            $item["properties"]["Description1"] .= $percapita_count_1st."</b> per 100 people.";
            $item["properties"]["Description1"] .= "<br>";
            $item["properties"]["Description1"] .= "<br>Administered (" .$date .")";
            $item["properties"]["Description1"] .= "<br>&nbsp;Pfizer: " .$pfizer_1st;
            $item["properties"]["Description1"] .= "<br>&nbsp;Sinovac: " .$sinovac_1st;
            $item["properties"]["Description1"] .= "<br>&nbsp;Astrazeneca: " .$astra_1st;
            $item["properties"]["Description1"] .= "<br>";
            $item["properties"]["Description1"] .= "<br>";
            $item["properties"]["Description1"] .= "Population: " .number_format($population, 0, ".", ",");

            $item["properties"]["Weight2"] = round($doses_administered_2nd/$population, 2) * 10;
            $item["properties"]["Description2"] =  "<b>".number_format($doses_administered_2nd, 0, ".", ",")."</b> second doses have been administered, <b>";
            $item["properties"]["Description2"] .= $percapita_count_2nd."</b> per 100 people.";
            $item["properties"]["Description2"] .= "<br>";
            $item["properties"]["Description2"] .= "<br>Administered (" .$date .")";
            $item["properties"]["Description2"] .= "<br>&nbsp;Pfizer: " .$pfizer_2nd;
            $item["properties"]["Description2"] .= "<br>&nbsp;Sinovac: " .$sinovac_2nd;
            $item["properties"]["Description2"] .= "<br>&nbsp;Astrazeneca: " .$astra_2nd;
            $item["properties"]["Description2"] .= "<br>";
            $item["properties"]["Description2"] .= "<br>";
            $item["properties"]["Description2"] .= "Population: " .number_format($population, 0, ".", ",");
            
            return $item;
        }, $geojson["features"]);


        return $geojson;
    }

    public function getAllDosesCount()
    {
        $population = Population::all("state","pop");

        $vaccine_state = VaccineState::whereIn('date', VaccineState::selectRaw("max(date)")->get())
                                     ->orderBy("state")
                                     ->get()
                                     ->transform(function($vaccine_state_item) use ($population) {
                                        
                                        $population_model = $population->filter(function($pop_item) use ($vaccine_state_item) {
                                            return $pop_item->state == $vaccine_state_item->state;
                                            })->first();

                                        $vaccine_state_item["cumul_partial_percentage"] = round( $vaccine_state_item["cumul_partial"] / $population_model->pop , 2 ) * 100;
                                        $vaccine_state_item["cumul_full_percentage"] = round( $vaccine_state_item["cumul_full"] / $population_model->pop , 2 ) * 100;

                                        $vaccine_state_item["pfizer1"] = number_format($vaccine_state_item["pfizer1"], 0, ".", ",");
                                        $vaccine_state_item["pfizer2"] = number_format($vaccine_state_item["pfizer2"], 0, ".", ",");

                                        $vaccine_state_item["sinovac1"] = number_format($vaccine_state_item["sinovac1"], 0, ".", ",");
                                        $vaccine_state_item["sinovac2"] = number_format($vaccine_state_item["sinovac2"], 0, ".", ",");

                                        $vaccine_state_item["astra1"] = number_format($vaccine_state_item["astra1"], 0, ".", ",");
                                        $vaccine_state_item["astra2"] = number_format($vaccine_state_item["astra2"], 0, ".", ",");

                                        $vaccine_state_item["pop"] = number_format($population_model->pop, 0, ".", ",");

                                        return $vaccine_state_item;
                                     });
        
        return $vaccine_state;   
    }
}