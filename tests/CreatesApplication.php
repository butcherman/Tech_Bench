<?php

namespace Tests;

use App\User;
use App\Role;
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
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'installer')->first());
        
        return $user;
    }
    
    //  Act as a registered Admin user
    public function getAdmin()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'admin')->first());
        
        return $user;
    }
    
    //  Act as a registered Reports user
    public function getReport()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'report')->first());
        
        return $user;
    }
    
    //  Act as a registered Tech user
    public function getTech()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'tech')->first());
        
        return $user;
    }
}
