<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SystemDataFieldTypes;
use App\SystemDataFields;
use Faker\Generator as Faker;

$factory->define(SystemDataFields::class, function (Faker $faker) {
    return [
        //
        'sys_id' => factory(App\SystemTypes::class)->create()->sys_id,
        'data_type_id' => 1, // factory(App\SystemDataFieldTypes::class)->create()->data_type_id,
        'order' => rand(0, 14)
    ];
});
