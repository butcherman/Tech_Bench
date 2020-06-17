<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SystemCategories;
use App\SystemTypes;
use Faker\Generator as Faker;

$factory->define(SystemTypes::class, function(Faker $faker) {
    return [
        'cat_id'          => factory(SystemCategories::class),
        'name'            => $name = $faker->unique()->name(),
        'folder_location' => $name
    ];
});
