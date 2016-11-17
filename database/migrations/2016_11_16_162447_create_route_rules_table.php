<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('route_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->boolean('menuable')->default(1);
            $table->boolean('jail_viewable')->default(1);
            $table->boolean('fly_viewable')->default(0);
            $table->boolean('family_viewable')->default(0);
            $table->string('bindings')->nullable();
        });

        $routes = require app_path('Library/pages.php');

        foreach ($routes as $route) {
            $dynamic = \App\Models\Route::create($route);

            $dynamic->rules()->create($route['rules']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_rules');
    }
}
