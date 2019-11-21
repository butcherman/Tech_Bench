<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerSystemData;
use Faker\Generator as Faker;

$factory->define(CustomerSystemData::class, function (Faker $faker) {
    return [
        //
        'cust_sys_id' => factory(App\CustomerSystems::class)->create()->cust_sys_id,
        'field_id'    => factory(App\SystemDataFields::class)->create()->field_id,
        'value'       => $faker->name(),
    ];
});
