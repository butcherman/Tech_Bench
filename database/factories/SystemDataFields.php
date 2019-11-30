<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SystemDataFields;

$factory->define(SystemDataFields::class, function() {
    return [
        'sys_id'       => factory(App\SystemTypes::class)->create()->sys_id,
        'data_type_id' => factory(App\SystemDataFieldTypes::class)->create()->data_type_id,
        'order'        => rand(0, 14)
    ];
});
