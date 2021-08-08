<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccineStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccine_states', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('state');
            $table->integer('votes')->nullable();
            $table->integer('dose1_daily')->nullable();
            $table->integer('dose2_daily')->nullable();
            $table->integer('total_daily')->nullable();
            $table->integer('dose1_cumul')->nullable();
            $table->integer('dose2_cumul')->nullable();
            $table->integer('total_cumul')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaccine_states');
    }
}
