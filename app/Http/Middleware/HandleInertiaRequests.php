<?php

namespace App\Http\Middleware;

use App\Actions\BuildNavbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;
use PragmaRX\Version\Package\Version;

/**
 * Middleware to process all InertiaJS responses
 */
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
                'status' => fn () => $request->session()->get('status'),
                'success' => fn () => $request->session()->get('success'),
                'warning' => fn () => $request->session()->get('warning'),
                'danger' => fn () => $request->session()->get('danger'),
                'info' => fn () => $request->session()->get('info'),
            ],
            //  Alert messages that will stick to top of request
            'alerts' => fn () => $request->session()->get('alert'),
            //  App information that is shared and used on all pages
            'app' => [
                'name' => fn () => config('app.name'),
                'logo' => fn () => config('app.logo'),
                'version' => fn () => Cache::get('version.full', function () {
                    $version = (new Version)->full();
                    Cache::put('version.full', $version);

                    return $version;
                }),
                'copyright' => fn () => Cache::get('version.copyright', function () {
                    $copyright = (new Version)->copyright();
                    Cache::put('version.copyright', $copyright);

                    return $copyright;
                }),
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
            'navbar' => fn () => $request->user() ? (new BuildNavbar)->build($request->user()) : [],
        ]);
    }
}
