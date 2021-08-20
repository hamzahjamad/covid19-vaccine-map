<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VaccineState;
use App\Repositories\Contracts\IGeoJSONRepository;

class LoadVaccineStateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vaccine-state:load-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $geojson_repository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(IGeoJSONRepository $geojson_repository)
    {
        $this->geojson_repository = $geojson_repository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $vaccine_state_raw_data = $this->geojson_repository->getDosesAdministeredCountRawData();
        unset($vaccine_state_raw_data[0]);

        foreach ($vaccine_state_raw_data as $row) {
            var_dump($row);
            $vaccine_state = VaccineState::updateOrCreate(['date' => $row[0], 'state' => $row[1]], [ 
                "date" => $row[0],
                "state" => $row[1],
                "daily_partial" => $row[2],
                "daily_full" => $row[3],
                "daily" => $row[4],
                "cumul_partial" => $row[5],
                "cumul_full" => $row[6],
                "cumul" => $row[7],
                "pfizer1" => $row[8],
                "pfizer2" => $row[9],
                "sinovac1" => $row[10],
                "sinovac2" => $row[11],
                "astra1" => $row[12],
                "astra2" => $row[13],
                "pending" => $row[14],
            ]);
        }

        return 0;
    }
}
