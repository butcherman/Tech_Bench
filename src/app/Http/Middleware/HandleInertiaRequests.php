<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        return array_merge(
            parent::share($request),
            $this->getPrimaryShare(),
            $this->getUserShare($request),
        );
    }

    /**
     * Get the primary share information (Application Data)
     */
    protected function getPrimaryShare(): array
    {
        return [
            //  Flash messages are used for success/failure messages on next page load
            'flash' => $this->getFlashData(),
            // App information that is shared and used on all pages
            'app' => [
                'name' => fn () => config('app.name'),
                'company_name' => fn () => config('app.company_name'),
                'logo' => fn () => config('app.logo'),
                'version' => fn () => 'test', //  Cache::version(),
                'copyright' => fn () => 'test', //  Cache::copyright(),
                //  File information
                'fileData' => [
                    'maxSize' => fn () => config('filesystems.max_filesize'),
                    'chunkSize' => fn () => config('filesystems.chunk_size'),
                    'token' => fn () => csrf_token(),
                ],
            ],
        ];
    }

    /**
     * Get the user share information if it exists
     */
    protected function getUserShare(Request $request): array
    {
        if (! $request->user()) {
            return [];
        }

        return [
            //  Current logged in user
            'current_user' => fn () => $request->user()->makeVisible('user_id'),
            'idle_timeout' => fn () => intval(config('auth.auto_logout_timer')),
            //  Dynamically built navigation menu
            'navbar' => fn () => [], // (new BuildNavbar)->getNavbar($request->user()),
        ];
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
