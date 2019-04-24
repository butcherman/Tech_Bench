<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Files::class, function(Faker $faker) {
    return [
        'file_name' => Str::random(5).$faker->fileExtension,
        'file_link' => 'random/path'
    ];
});

$factory->define(App\SystemFiles::class, function(Faker $faker) use ($factory) {
    return [
        'sys_id'      => 1,
        'type_id'     => 1,
        'file_id'     => factory(App\Files::class)->create()->file_id,
        'name'        => 'This is a name',
        'description' => 'This is a description',
        'user_id'     => 1
    ];
});
