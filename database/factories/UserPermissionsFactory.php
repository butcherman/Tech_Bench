<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserPermissions;
use Faker\Generator as Faker;

$factory->define(App\UserPermissions::class, function(Faker $faker) {
    return [
        'manage_users'        => rand(0, 1),
        'run_reports'         => rand(0, 1),
        'add_customer'        => rand(0, 1),
        'deactivate_customer' => rand(0, 1),
        'use_file_links'      => rand(0, 1),
        'create_tech_tip'     => rand(0, 1),
        'edit_tech_tip'       => rand(0, 1),
        'delete_tech_tip'     => rand(0, 1),
        'create_category'     => rand(0, 1),
        'modify_category'     => rand(0, 1)
    ];
});
