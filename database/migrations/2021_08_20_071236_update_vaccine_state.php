<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVaccineState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('vaccine_states');
        Schema::create('vaccine_states', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('state');
            $table->integer('daily_partial')->nullable();
            $table->integer('daily_full')->nullable();
            $table->integer('daily')->nullable();
            $table->integer('cumul_partial')->nullable();
            $table->integer('cumul_full')->nullable();
            $table->integer('cumul')->nullable();
            $table->integer('pfizer1')->nullable();
            $table->integer('pfizer2')->nullable();
            $table->integer('sinovac1')->nullable();
            $table->integer('sinovac2')->nullable();
            $table->integer('astra1')->nullable();
            $table->integer('astra2')->nullable();
            $table->integer('pending')->nullable();
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
}
