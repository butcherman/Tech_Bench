<?php

use Faker\Generator as Faker;

$factory->define(App\SystemTypes::class, function(Faker $faker) {
    return [
        'sys_id'          => 1,
        'cat_id'          => 1,
        'name'            => $faker->jobTitle(),
        'parent_id'       => null,
        'folder_location' => 'nec'
    ];
});
