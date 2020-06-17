<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerFiles;
use Faker\Generator as Faker;

$factory->define(CustomerFiles::class, function(Faker $faker) {
    return [
        'file_id'      => factory(App\Files::class),
        'file_type_id' => factory(App\CustomerFileTypes::class),
        'cust_id'      => factory(App\Customers::class),
        'user_id'      => factory(App\User::class),
        'name'         => $faker->name()
    ];
});
