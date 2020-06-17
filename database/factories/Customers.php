<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customers;
use Faker\Generator as Faker;

$factory->define(Customers::class, function(Faker $faker) {

    do
    {
        $custID = $faker->unique()->numberBetween(50, 10000);
        $res    = Customers::find($custID);
    } while($res);

    return [
        'cust_id'   => $custID,
        'parent_id' => null,
        'name'      => $faker->company(),
        'dba_name'  => null,
        'address'   => $faker->streetAddress(),
        'city'      => $faker->city(),
        'state'     => $faker->stateAbbr(),
        'zip'       => rand(20000, 99999),
    ];
});
