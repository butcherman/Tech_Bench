<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SystemTypes;
use Faker\Generator as Faker;

$factory->define(SystemTypes::class, function(Faker $faker) {
    return [
        //
        'cat_id'          => factory(App\SystemCategories::class)->create()->cat_id,
        'name'            => $name = $faker->unique()->name(),
        'folder_location' => $name
    ];
});
