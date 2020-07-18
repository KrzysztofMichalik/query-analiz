<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'id_produktu' => round($faker->numberBetween($min = 1, $max = 10000)),
        'nazwa'       => $faker->randomElement(['Sneakers', 'Nowość','Trampki', 'Outlet'])
    ];
});
