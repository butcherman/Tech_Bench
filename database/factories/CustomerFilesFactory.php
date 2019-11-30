<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerFiles;
use Faker\Generator as Faker;

$factory->define(CustomerFiles::class, function (Faker $faker) {
    return [
        'file_id'      => factory(App\Files::class)->create()->file_id,
        'file_type_id' => factory(App\CustomerFileTypes::class)->create()->file_type_id,
        'cust_id'      => factory(App\Customers::class)->create()->cust_id,
        'user_id'      => factory(App\User::class)->create()->user_id,
        'name'         => $faker->name()
    ];
});
