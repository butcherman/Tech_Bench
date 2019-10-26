<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserPermissions;
use Faker\Generator as Faker;

$factory->define(App\UserPermissions::class, function (Faker $faker) {
    return [
        'user_id'             => 0,
        'manage_users'        => 0,
        'run_reports'         => 0,
        'add_customer'        => 1,
        'deactivate_customer' => 0,
        'use_file_links'      => 1,
        'create_tech_tip'     => 1,
        'edit_tech_tip'       => 0,
        'delete_tech_tip'     => 0,
        'create_category'     => 0,
        'modify_category'     => 0
    ];
});
