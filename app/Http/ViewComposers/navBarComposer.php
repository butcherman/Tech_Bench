<?php

namespace App\Http\ViewComposers;

use App\Navbar;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;

class NavBarComposer
{
    public $navBar;
    
    public function __construct()
    {
        //
        $this->navBar = Navbar::getNavLinks();
    }
    
    public function compose(View $view)
    {
        $view->with('navArray', $this->navBar);
    }
}
