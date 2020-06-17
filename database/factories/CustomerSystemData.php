<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerSystemData;
use Faker\Generator as Faker;

$factory->define(CustomerSystemData::class, function(Faker $faker) {
    return [
        //
        'cust_sys_id' => factory(App\CustomerSystems::class),
        'field_id'    => factory(App\SystemDataFields::class),
        'value'       => $faker->name(),
    ];
});
