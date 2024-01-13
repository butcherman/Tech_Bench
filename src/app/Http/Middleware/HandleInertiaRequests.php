<?php

namespace App\Http\Middleware;

use App\Actions\BuildNavbar;
use Illuminate\Http\Request;
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
     * Put all flash data into an array to be gone over by front end
     */
    protected function getFlashData()
    {
        $flashArr = [];
        $flash = session('_flash');
        $allowedTypes = [
            'primary',
            'secondary',
            'success',
            'danger',
            'warning',
            'info',
            'light',
            'dark',
            'status',
        ];

        if ($flash) {
            foreach ($flash['new'] as $f) {
                if (in_array($f, $allowedTypes)) {
                    $flashArr[] = [
                        $f,
                    ];
                }
            }
            foreach ($flash['old'] as $f) {
                if (in_array($f, $allowedTypes)) {
                    $flashArr[] = [
                        'type' => $f,
                        'message' => session()->get($f),
                    ];
                }
            }
        }

        return $flashArr;
    }

    /**
     * Defines the props that are shared by default
     */
    public function share(Request $request): array
    {
        $userShare = [];
        $primaryShare = array_merge(parent::share($request), [
            //  Flash messages are used for success/failure messages on next page load
            'flash' => $this->getFlashData(),
            // App information that is shared and used on all pages
            'app' => [
                'name' => fn () => config('app.name'),
                'logo' => fn () => config('app.logo'),
            ],
        ]);

        /**
         * If a user is logged in, we need additional data
         */
        if ($request->user()) {
            $userShare = [
                //  Current logged in user
                'current_user' => fn () => $request->user(),
                'user_notifications' => [
                    'list' => fn () => $request->user()->notifications,
                    'new' => fn () => $request->user()->unreadNotifications->count(),
                ],
                //  Dynamically built navigation menu
                'navbar' => fn () => BuildNavbar::build($request->user()),
            ];
        }

        return array_merge($primaryShare, $userShare);
    }
}
