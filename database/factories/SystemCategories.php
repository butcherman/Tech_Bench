<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SystemCategories;
use Faker\Generator as Faker;

$factory->define(SystemCategories::class, function(Faker $faker) {

    do
    {
        $name = $faker->unique()->word;
        $res = SystemCategories::where('name', $name)->first();
    } while($res);

    return [
        'name' => $name,
    ];
});
