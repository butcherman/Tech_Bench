<?php

namespace App\Http\Controllers\Home;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use App\Http\Controllers\Controller;

use App\Models\UserTechTipRecent;
use App\Models\UserCustomerRecent;
use App\Models\UserTechTipBookmark;
use App\Models\UserCustomerBookmark;

class DashboardController extends Controller
{
    /**
     *  Landing page for authorized users
     */
    public function __invoke(Request $request)
    {
        //  Determine if there are any Dashboard Tools that need to be loaded
        // @codeCoverageIgnoreStart
        $modules = Module::allEnabled();
        $tools   = [];
        foreach($modules as $module)
        {
            $name     = $module->getLowerName();
            $toolData = config($name.'.dashboard_tool');

            if($toolData)
            {
                foreach($toolData as $data)
                {
                    $tools[] = $data;
                }
            }
        }
        // @codeCoverageIgnoreEnd

        return Inertia::render('Home/Dashboard', [
            // 'notifications' => $request->user()->notifications,
            'bookmarks'     => [
                'customers' => UserCustomerBookmark::where('user_id', $request->user()->user_id)->get(),
                'tips'      => UserTechTipBookmark::where('user_id', $request->user()->user_id)->get(),
            ],
            'recents'       => [
                'customers' => UserCustomerRecent::where('user_id', $request->user()->user_id)->orderBy('updated_at', 'DESC')->get()->take(10),
                'tips'      => UserTechTipRecent::where('user_id', $request->user()->user_id)->orderBy('updated_at', 'DESC')->get()->take(10),
            ],
            'tools'         => $tools,
        ]);
    }
}
