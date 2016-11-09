<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique();
            $table->string('value');
        });

        DB::table('configurations')->insert([
            ['key' => 'user_start_group', 'value' => '1'],
            ['key' => 'app_slogan', 'value' => ''],
            ['key' => 'admin_group', 'value' => '4'],
            ['key' => 'currency_symbol', 'value' => '$'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configurations');
    }
}
