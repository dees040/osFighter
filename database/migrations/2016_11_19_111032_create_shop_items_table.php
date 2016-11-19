<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('price')->unsigned();
            $table->integer('power')->unsigned();
            $table->integer('min_strength_points')->default(0)->unsigned();
            $table->integer('max_amount')->default(0)->unsigned();
        });

        DB::table('shop_items')->insert([
            ['name' => 'Bat', 'price' => 500, 'power' => 25, 'min_strength_points' => 1],
            ['name' => 'Pepperspray', 'price' => 950, 'power' => 40, 'min_strength_points' => 10],
            ['name' => 'Switch Blade', 'price' => 1000, 'power' => 45, 'min_strength_points' => 25],
            ['name' => 'Sig P228', 'price' => 1400, 'power' => 55, 'min_strength_points' => 50],
            ['name' => 'Desert Eagle', 'price' => 3000, 'power' => 75, 'min_strength_points' => 75],
            ['name' => 'Machine Gun', 'price' => 5000, 'power' => 150, 'min_strength_points' => 100],
            ['name' => 'Corner Shot', 'price' => 5000, 'power' => 150, 'min_strength_points' => 150],
            ['name' => 'C4 Bomb', 'price' => 9500, 'power' => 180, 'min_strength_points' => 200],
            ['name' => 'Pitbull', 'price' => 12500, 'power' => 250, 'min_strength_points' => 300],
            ['name' => 'Sniper', 'price' => 20000, 'power' => 450, 'min_strength_points' => 400],
            ['name' => 'S.W.A.T. Gun', 'price' => 37500, 'power' => 625, 'min_strength_points' => 500],
            ['name' => 'Bodyguard', 'price' => 50000, 'power' => 950, 'min_strength_points' => 600],
            ['name' => 'RPG', 'price' => 50000, 'power' => 950, 'min_strength_points' => 700],
            ['name' => 'Tank', 'price' => 150000, 'power' => 10000, 'min_strength_points' => 1000],
            ['name' => 'Scud Rocket', 'price' => 200000, 'power' => 15000, 'min_strength_points' => 1200],
            ['name' => 'War Boat', 'price' => 240000, 'power' => 15500, 'min_strength_points' => 1500],
            ['name' => 'Nuke', 'price' => 450000, 'power' => 25000, 'min_strength_points' => 2000],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_items');
    }
}
