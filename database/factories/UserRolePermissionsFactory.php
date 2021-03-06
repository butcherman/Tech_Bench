<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserRolePermissions;

$factory->define(UserRolePermissions::class, function() {
    return [
        'role_id'      => factory(App\UserRoleType::class)->create()->role_id,
        'perm_type_id' => factory(App\UserRolePermissionTypes::class)->create()->perm_type_id,
    ];
});
