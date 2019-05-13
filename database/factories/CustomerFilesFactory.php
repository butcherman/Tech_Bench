<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Files::class, function(Faker $faker) {
    return [
        'file_name' => Str::random(5).$faker->fileExtension,
        'file_link' => 'random/path'
    ];
});

$factory->define(App\CustomerFiles::class, function (Faker $faker) {
    return [
        'file_id' => factory(App\Files::class)->create()->file_id,
        'file_type_id' => 1,
        'cust_id' => factory(App\Customers::class)->create()->cust_id,
        'user_id' => 1,
        'name' => 'This is a test file'
    ];
});
