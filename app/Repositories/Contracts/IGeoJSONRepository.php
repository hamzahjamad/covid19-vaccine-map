<?php
namespace App\Repositories\Contracts;

interface IGeoJSONRepository
{
   public function getGeoJSON();

   public function getDosesAdministeredCountRawData();

   public function getPopulationCountRawData();

   public function getDosesAdministeredCountByState($state, $column_name);

   public function getPopulationCountByState($state, $column_name);
}