<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Population;
use App\Repositories\Contracts\IGeoJSONRepository;

class LoadPopulationData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'population:load-data';

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
        $population_raw_data = $this->geojson_repository->getPopulationCountRawData();
        unset($population_raw_data[0]);

        foreach ($population_raw_data as $row) {
            var_dump($row);
            $population = Population::updateOrCreate(['state' => $row[0], 'idxs' => $row[1]], [ 
                "state" => $row[0],
                "idxs" => $row[1],
                "pop" => $row[2],
                "pop_18" => $row[3],
                "pop_60" => $row[4],
            ]);
        }

        return 0;
    }
}
