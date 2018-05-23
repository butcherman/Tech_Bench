<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use App\Navbar;

class NavBarComposer
{
    public $current_user;
    
    public function __construct()
    {
        //
    }
    
    public function compose(View $view)
    {
        $navItems = Navbar::getNavLinks();
        extract($navItems);
        
        $view->with('navCategories', $navbarCategories);
        $view->with('navSystems', $navbarParents);
        $view->with('navSubSystems', $navbarChildren);
    }
}
