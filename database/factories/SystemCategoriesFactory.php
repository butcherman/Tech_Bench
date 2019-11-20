<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SystemCategories;
use Faker\Generator as Faker;

$factory->define(SystemCategories::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->unique()->text(15)
    ];
});
