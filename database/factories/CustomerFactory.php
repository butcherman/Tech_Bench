<?php

use Faker\Generator as Faker;

$factory->define(App\Customers::class, function(Faker $faker) {
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
