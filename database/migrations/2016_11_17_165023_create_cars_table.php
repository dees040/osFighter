<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('price')->unsigned();
        });

        DB::table('cars')->insert([
            ['name' => 'Virgo 3 Luxe', 'price' => '2000'],
            ['name' => 'Peugeot 106 GTi', 'price' => '14000'],
            ['name' => 'Toyota Prius', 'price' => '24000'],
            ['name' => 'Fiat Doblo', 'price' => '29000'],
            ['name' => 'Volkswagen Golf GTi', 'price' => '36000'],
            ['name' => 'Volvo XC90', 'price' => '47000'],
            ['name' => 'Audi A6', 'price' => '55000'],
            ['name' => 'Tesla Model S', 'price' => '65000'],
            ['name' => 'Maserati Levante', 'price' => '73000'],
            ['name' => 'Mercedes-AMG CLS 63 s', 'price' => '109000'],
            ['name' => 'Porsche 911 GT3 RS', 'price' => '131000'],
            ['name' => 'Lamborghini Aventador SV Coupé', 'price' => '368000'],
            ['name' => 'McLaren P1', 'price' => '1030000'],
            ['name' => 'Ferrari LaFerrari', 'price' => '1420000'],
            ['name' => 'Koenigsegg Regera', 'price' => '1930000'],
            ['name' => 'Bugatti Chiron', 'price' => '2660000'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
