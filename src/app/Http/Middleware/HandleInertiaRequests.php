<?php

namespace App\Http\Middleware;

use App\Actions\User\BuildUserNavbar;
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
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user() ? $request->user() : null;
        $navbar = $request->user() ?
            new BuildUserNavbar($request->user())() :
            [];

        return [
            ...parent::share($request),
            'csrf_token' => fn () => csrf_token(),
            'current_user' => fn () => $user,
            'navbar' => fn () => $navbar,
        ];
    }
}
