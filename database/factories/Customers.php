<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customers;
use Faker\Generator as Faker;

$factory->define(Customers::class, function (Faker $faker) {
    return [
        //
        'cust_id'  => rand(50, 5000),
        'name'     => $faker->company(),
        'dba_name' => null,
        'address'  => $faker->streetAddress(),
        'city'     => $faker->city(),
        'state'    => $faker->stateAbbr(),
        'zip'      => rand(20000, 99999),
        'active'   => 1
    ];
});