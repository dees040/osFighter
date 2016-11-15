<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'group_id' => 1,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Time::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(\App\Models\User::class)->create()->id;
        },
        'jail' => null,
        'crime' => null,
    ];
});

$factory->define(App\Models\Crime::class, function (Faker\Generator $faker) {
    $payout = mt_rand(100, 100000);

    return [
        'title' => $faker->sentence,
        'min_payout' => mt_rand(10, $payout),
        'max_payout' => mt_rand($payout, 200000),
        'chance' => $faker->numberBetween(10, 2000),
        'max_chance' => $faker->numberBetween(50, 100),
    ];
});

$factory->define(App\Models\Page::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'route_name' => $faker->name,
        'route_action' => $faker->name,
        'route_method' => $faker->name,
        'url' => $faker->url,
        'menu_id' => 1,
        'group_id' => 1,
        'weight' => 999,
        'jail' => 1,
        'menuable' => 0,
    ];
});
