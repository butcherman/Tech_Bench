<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerFileTypes;
use Faker\Generator as Faker;

$factory->define(CustomerFileTypes::class, function (Faker $faker) {
    return [
        //
        'description' => $faker->unique()->name(),
    ];
});
