<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->tinyInteger('rank_id')->default(1)->unsigned();
            $table->bigInteger('cash')->default(0);
            $table->bigInteger('bank')->default(0);
            $table->bigInteger('power')->default(0)->unsigned();
            $table->tinyInteger('health')->default(100)->unsigned();
            $table->integer('crime_progress')->default(1)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_info');
    }
}