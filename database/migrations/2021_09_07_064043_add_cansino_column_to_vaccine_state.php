<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCansinoColumnToVaccineState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaccine_states', function (Blueprint $table) {
            $table->integer('cansino')->after('astra2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vaccine_states', function (Blueprint $table) {
            $table->dropColumn(['cansino']);
        });
    }
}
