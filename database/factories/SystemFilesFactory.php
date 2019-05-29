<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\SystemFiles::class, function(Faker $faker) {
    return [
        'sys_id'      => 1,
        'type_id'     => 1,
        'file_id'     => factory(App\Files::class)->create()->file_id,
        'name'        => 'This is a name',
        'description' => 'This is a description',
        'user_id'     => 1
    ];
});
