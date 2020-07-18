<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Doctrine\Inflector\Rules\Word;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'nazwa' => $faker->bothify('Product ##??')
        
    ];
});
