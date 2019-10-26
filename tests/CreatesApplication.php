<?php

namespace Tests;

use App\User;
use App\Role;
use App\UserPermissions;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    //  Act as a registered Installer user
    public function getInstaller()
    {
        $user = factory(User::class)->create(
        [
            'is_installer' => 1
        ]);
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
        ]);

        return $user;
    }

    //  Act as a standard registered Tech user
    public function getTech()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
        [
            'user_id' => $user->user_id
        ]);

        return $user;
    }
}
