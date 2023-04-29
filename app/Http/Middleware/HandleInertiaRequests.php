<?php

namespace App\Http\Middleware;

use App\Actions\BuildNavbar;
use Illuminate\Http\Request;
use Inertia\Middleware;
use PragmaRX\Version\Package\Version;

class HandleInertiaRequests extends Middleware
{
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
                'success' => fn () => $request->session()->get('success'),
                'warning' => fn () => $request->session()->get('warning'),
                'danger' => fn () => $request->session()->get('danger'),
                'info' => fn () => $request->session()->get('info'),
            ],
            //  App information that is shared and used on all pages
            'app' => [
                'name' => config('app.name'),
                'logo' => config('app.logo'),
                'version' => (new Version)->full(),
                'copyright' => (new Version)->copyright(),
                //  Current logged in user
                'user' => fn () => $request->user() ? $request->user() : null,
                //  File information
                'fileData' => [
                    'maxSize' => config('filesystems.max_filesize'),
                    'chunkSize' => config('filesystems.chunk_size'),
                    'token' => csrf_token(),
                ],
            ],
            'notifications' => [
                'list' => fn () => $request->user() ? $request->user()->notifications : null,
                'new' => fn () => $request->user() ? $request->user()->unreadNotifications->count() : null,
            ],
            //  Dynamically built navigation menu
            'navbar' => fn () => $request->user() ? (new BuildNavbar)->build($request->user()) : [],
        ]);
    }
}
