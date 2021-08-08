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
            $doses_administered_1st = $geojson_repository->getDosesAdministeredCountByState($state, "dose1_cumul");
            $percapita_count_1st = round($doses_administered_1st/$population, 2) * 100;
            $doses_administered_2nd = $geojson_repository->getDosesAdministeredCountByState($state, "dose2_cumul");
            $percapita_count_2nd = round($doses_administered_2nd/$population, 2) * 100;

            $item["properties"]["Weight1"] = round($doses_administered_1st/$population, 2) * 10;
            $item["properties"]["Description1"] =  "<b>".number_format($doses_administered_1st, 0, ".", ",")."</b> first doses have been administered, <b>".$percapita_count_1st."</b> per 100 people.";

            $item["properties"]["Weight2"] = round($doses_administered_2nd/$population, 2) * 10;
            $item["properties"]["Description2"] =  "<b>".number_format($doses_administered_2nd, 0, ".", ",")."</b> second doses have been administered, <b>".$percapita_count_1st."</b> per 100 people.";
            
            return $item;
        }, $geojson["features"]);


        return $geojson;
    }
}