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
                "dose1_daily" => $row[2],
                "dose2_daily" => $row[3],
                "total_daily" => $row[4],
                "dose1_cumul" => $row[5],
                "dose2_cumul" => $row[6],
                "total_cumul" => $row[7]
            ]);
        }

        return 0;
    }
}
