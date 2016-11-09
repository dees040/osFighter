<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('route_name')->unique();
            $table->string('route_action');
            $table->string('url');
            $table->integer('group_id')->unsigned()->default(1);
            $table->integer('menu_id')->unsigned();
            $table->integer('weight')->unsigned();
        });

        DB::table('pages')->insert([
            ['name' => 'Home', 'route_name' => 'home', 'route_action' => 'HomeController@index', 'url' => 'home', 'menu_id' => 1, 'weight' => 1],
            ['name' => 'About', 'route_name' => 'about', 'route_action' => 'HomeController@index', 'url' => 'about', 'menu_id' => 1, 'weight' => 2],
            ['name' => 'Crime', 'route_name' => 'crimecrimes.store.create', 'route_action' => 'Crimes\CrimeController@create', 'url' => 'crimes/crime', 'menu_id' => 3, 'weight' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
