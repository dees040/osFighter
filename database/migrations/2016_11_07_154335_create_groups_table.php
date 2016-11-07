<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('child_id')->unsigned();
        });

        DB::table('groups')->insert([
            ['name' => 'Member', 'child_id' => 0],
            ['name' => 'Helpdesk', 'child_id' => 1],
            ['name' => 'Moderator', 'child_id' => 2],
            ['name' => 'Admin', 'child_id' => 3],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
