<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crimes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('min_payout')->unsigned();
            $table->integer('max_payout')->unsigned();
            $table->integer('chance')->unsigned();
            $table->tinyInteger('max_chance')->unsigned()->default(100);
        });

        DB::table('crimes')->insert([
            ['title' => 'Steal from child', 'min_payout' => 10,  'max_payout' => 100, 'chance' => 10, 'max_chance' => 100],
            ['title' => 'Rob a jewelry store', 'min_payout' => 15000,  'max_payout' => 30000, 'chance' => 109, 'max_chance' => 90],
            ['title' => 'Rob the bank', 'min_payout' => 150000,  'max_payout' => 300000, 'chance' => 200, 'max_chance' => 50],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crimes');
    }
}
