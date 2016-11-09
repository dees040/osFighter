<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('level');
        });

        DB::table('ranks')->insert([
            ['name' => 'Newbie', 'level' => 1],
            ['name' => 'Office boy', 'level' => 2],
            ['name' => 'Pickpocket', 'level' => 3],
            ['name' => 'Shoplifter', 'level' => 4],
            ['name' => 'Thief', 'level' => 5],
            ['name' => 'Mobster', 'level' => 6],
            ['name' => 'Assassin', 'level' => 7],
            ['name' => 'Local leader', 'level' => 8],
            ['name' => 'Boss', 'level' => 9],
            ['name' => 'Godfather', 'level' => 10],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ranks');
    }
}
