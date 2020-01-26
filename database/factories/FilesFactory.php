<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Files::class, function(Faker $faker) {
    return [
        'file_name' => Str::random(5).$faker->fileExtension,
        'file_link' => 'random/path'
    ];
});
