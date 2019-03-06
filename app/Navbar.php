<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Navbar extends Model
{
    protected $table = 'navbar_view';
    
    public static function getNavLinks()
    {
        $navItems = Self::all();
        
        $navBarCat = [];
        $navBarSys = [];
        $navBarSub = [];
        
        foreach($navItems as $item)
        {
            if(!in_array($item->category, $navBarCat))
            {
                $navBarCat[] = $item->category;
            }
            if(empty($item->parent_id))
            {
                $navBarSys[$item->sys_id]['category'] = $item->category;
                $navBarSys[$item->sys_id]['name'] = $item->sys_name;
                $navBarSys[$item->sys_id]['url'] = '/system/'.$item->category.'/'.urlencode($item->sys_name);
            }
            else
            {
                $navBarSub[$item->sys_id]['category'] = $item->category;
                $navBarSub[$item->sys_id]['parent'] = $item->parent_id;
                $navBarSub[$item->sys_id]['name'] = $item->sys_name;
                $navBarSub[$item->sys_id]['url'] = '/system/'.$item->category.'/'.urlencode($item->sys_name);
                $navBarSys[$item->parent_id]['parent'] = true;
            }
        }
        
        return ['navbarCategories' => $navBarCat, 'navbarParents' => $navBarSys, 'navbarChildren' => $navBarSub];
    }
}
