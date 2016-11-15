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
            $table->string('route_method', 6)->default('get');
            $table->string('url');
            $table->integer('group_id')->unsigned()->default(1);
            $table->integer('menu_id')->unsigned();
            $table->integer('weight')->unsigned();
            $table->boolean('jail')->default(0);
            $table->boolean('menuable');
        });

        DB::table('pages')->insert(require app_path('Library/pages.php'));
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
