<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Http\Resources\UserCollection;

class AdminController extends Controller
{
    public function __construct()
    {
        //  Only Authorized users with specific admin permissions are allowed
        $this->middleware(['auth', 'can:allow_admin']);
    }

    //  Admin landing page
    public function index()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.index');
    }

    //  Display all file links
    public function userLinks()
    {
        $userLinks = new UserCollection(
                        User::where('active', 1)
                            ->withCount([
                                'FileLinks',
                                'FileLinks as expired_file_links_count' => function($query)
                                {
                                    $query->where('expire', '<', Carbon::now());
                                }
                            ])
                            ->get()
                            ->makeVisible('user_id')
                    );

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.userLinks', [
            'links' => $userLinks,
        ]);
    }

    //  Show the links for the selected user
    public function showLinks($id)
    {
        $user     = User::find($id);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.linkDetails', [
            'user' => $user,
            // 'name'   => $userName
        ]);
    }
}
