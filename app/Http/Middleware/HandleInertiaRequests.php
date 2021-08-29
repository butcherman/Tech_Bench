<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use PragmaRX\Version\Package\Version;

use App\Actions\BuildNavbar;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            //  Flash messages are used for success/failure messages on next page load
            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'type'    => fn() => $request->session()->get('type'),
            ],
            //  App information that is shared and used on all pages
            'app' => [
                'name'     => config('app.name'),
                'logo'     => config('app.logo'),
                'version'  => (new Version)->full(),
                //  Current logged in user
                'user'     => fn() => $request->user() ? $request->user() : null,
                //  File information
                'fileData' => [
                    'maxSize'   => config('filesystems.max_filesize'),
                    'chunkSize' => config('filesystems.chunk_size'),
                    'token'     => csrf_token(),
                ],
            ],
            //  Dynamically built navigation menu
            'navBar' => fn() => $request->user() ? (new BuildNavbar)->build($request->user()) : [],
        ]);
    }
}
