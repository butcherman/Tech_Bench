<?php

namespace App\Http\Middleware;

use App\Actions\General\BuildNavbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'type'    => fn() => $request->session()->get('type'),
            ],
            'app' => [
                'name' => config('app.name'),
                'logo' => config('app.logo'),
            ],
            'user'   => fn() => $request->user() ? $request->user() : null,
            'navBar' => fn() => $request->user() ? (new BuildNavbar)->build($request->user()) : [],
        ]);
    }
}