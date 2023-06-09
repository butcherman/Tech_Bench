<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use PragmaRX\Version\Package\Version;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default
     */
    public function share(Request $request): array
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
                'name' => fn () => config('app.name'),
                'logo' => fn () => config('app.logo'),
                'version' => fn () => (new Version)->full(),             //  TODO - Cache this
                'copyright' => fn () => (new Version)->copyright(),      //  TODO - Cache this
                //  Current logged in user
                'user' => fn () => $request->user() ? $request->user() : null,
                //  File information
                'fileData' => [
                    'maxSize' => fn () => config('filesystems.max_filesize'),
                    'chunkSize' => fn () => config('filesystems.chunk_size'),
                    'token' => fn () => csrf_token(),
                ],
            ],
            'notifications' => [
                'list' => fn () => $request->user() ? $request->user()->notifications : null,
                'new' => fn () => $request->user() ? $request->user()->unreadNotifications->count() : null,
            ],
            //  Dynamically built navigation menu
            'navbar' => [], /// fn () => $request->user() ? (new BuildNavbar)->build($request->user()) : [],
        ]);
    }
}
