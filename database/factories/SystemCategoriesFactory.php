<?php

use Faker\Generator as Faker;

$factory->define(App\SystemCategories::class, function (Faker $faker) {
    return [
        'cat_id' => 1,
        'name'   => $faker->jobTitle()
    ];
});
