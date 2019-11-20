<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SystemDataFieldTypes;
use Faker\Generator as Faker;

$factory->define(SystemDataFieldTypes::class, function (Faker $faker) {
    return [
        //
        'name'   => $faker->words(),
        'hidden' => 0
    ];
});
