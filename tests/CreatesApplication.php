<?php

namespace Tests;

use App\User;
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
    public function actAsInstaller()
    {
        $user = User::find(1);
        $this->be($user);
    }
    
    //  Act as a registered Admin user
    public function actAsAdmin()
    {
        $user = User::find(2);
        $this->be($user);
    }
    
    //  Act as a registered Reports user
    public function actAsReport()
    {
        $user = User::find(3);
        $this->be($user);
    }
    
    //  Act as a registered Tech user
    public function actAsTech()
    {
        $user = User::find(4);
        $this->be($user);
    }
}
