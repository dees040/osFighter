<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->timestamp('jail')->nullable();
            $table->timestamp('crime')->nullable();
            $table->timestamp('flying')->nullable();
            $table->timestamp('pimped')->nullable();
            $table->timestamp('pimped_cash')->nullable();
        });

        \App\Models\User::create([
            'group_id' => 4,
            'username' => 'dees',
            'email' => 'd.oomens@hotmail.nl',
            'password' => bcrypt('test1234'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_times');
    }
}
