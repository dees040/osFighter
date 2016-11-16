<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        DB::table('locations')->insert([
           ['name' => 'Amsterdam'],
           ['name' => 'Berlin'],
           ['name' => 'Paris'],
           ['name' => 'London'],
           ['name' => 'Milan'],
           ['name' => 'New York'],
           ['name' => 'Las Vegas'],
           ['name' => 'Tokyo'],
           ['name' => 'Los Angles'],
           ['name' => 'Dubai'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
