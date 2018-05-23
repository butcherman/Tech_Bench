<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserComposer
{
    public $current_user;
    
    public function __construct()
    {
        $this->current_user = Auth::user();
    }
    
    public function compose(View $view)
    {
        $view->with('current_user', $this->current_user);
    }
}
