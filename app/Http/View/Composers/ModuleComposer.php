<?php

namespace App\Http\View\Composers;

use Module;
use Illuminate\View\View;

class ModuleComposer
{
    protected $modules; 

    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        $this->modules = Module::all();
    }
 
    public function compose(View $view)
    {
        $view->with('tb_modules', $this->modules);
    }
}
