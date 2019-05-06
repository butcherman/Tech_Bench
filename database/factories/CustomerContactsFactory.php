<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
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

$factory->define(App\CustomerContacts::class, function (Faker $faker) use ($factory) {
    return [
        'cust_id' => factory(App\Customers::class)->create()->cust_id,
        'name'    => $faker->name,
        'email'   =>$faker->email
    ];
});
