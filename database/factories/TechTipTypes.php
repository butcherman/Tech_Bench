<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TechTipTypes;
use Faker\Generator as Faker;

$factory->define(TechTipTypes::class, function (Faker $faker) {
    return [
        'description' => $faker->catchPhrase,
    ];
});
