<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Navbar extends Model
{
    protected $table = 'navbar_view';
    
    public static function getNavLinks()
    {
        $navItems = Self::orderBy('category', 'ASC')->orderBy('sys_name', 'ASC')->get();

        $navArray = [];
        
        foreach($navItems as $item)
        {
            $navArray[$item->category][] = [
                'name'   => $item->sys_name,
                'sys_id' => $item->sys_id,
                'link'   => route('system.details', [urlencode($item->category), urlencode($item->sys_name)])
            ];            
        }

        return $navArray;
    }
}
