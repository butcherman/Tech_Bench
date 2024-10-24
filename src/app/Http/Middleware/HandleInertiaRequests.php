<?php

// TODO - Refactor

namespace App\Http\Middleware;

use App\Actions\BuildNavbar;
use App\Service\Cache;
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
                'company_name' => fn () => config('app.company_name'),
                'logo' => fn () => config('app.logo'),
                'version' => fn () => Cache::version(),
                'copyright' => fn () => Cache::copyright(),
                //  File information
                'fileData' => [
                    'maxSize' => fn () => config('filesystems.max_filesize'),
                    'chunkSize' => fn () => config('filesystems.chunk_size'),
                    'token' => fn () => csrf_token(),
                ],
            ],
        ]);

        /**
         * If a user is logged in, we need additional data
         */
        if ($request->user()) {
            $userShare = [
                //  Current logged in user
                'current_user' => fn () => $request->user()->makeVisible('user_id'),
                'idle_timeout' => fn () => intval(config('auth.auto_logout_timer')),
                //  Dynamically built navigation menu
                'navbar' => fn () => (new BuildNavbar)->getNavbar($request->user()),
            ];
        }

        return array_merge($primaryShare, $userShare);
    }

    /**
     * Put all flash data into an array to be gone over by front end
     */
    protected function getFlashData(): array
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
}
