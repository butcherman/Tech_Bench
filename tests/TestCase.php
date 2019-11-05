<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use App\User;
// use App\UserLogins;
use App\UserPermissions;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    // public function setUp():void
    // {
    //     parent::setUp();
    //     $this->seed('DatabaseSeeder');
    // }

    //  Act as a registered Installer user
    public function getInstaller()
    {
        $user = factory(User::class)->create(
            [
                'is_installer' => 1
            ]
        );
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 1,
                'run_reports'         => 1,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 1,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 1,
                'create_category'     => 1,
                'modify_category'     => 1
            ]
        );
        return $user;
    }
    //  Act as a standard registered Tech user
    public function getTech()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 1,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );
        return $user;
    }
}
