<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\LogoutResponse;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        //  Deliver a logout message to the user
        $this->app->instance(LogoutResponseContract::class, new LogoutResponse);

        //  Rate limiter for login attempts
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = $request->ip();

            if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
                $availableIn = ceil(RateLimiter::availableIn($throttleKey) / 60);

                event(new Lockout($request));
                return back()->withErrors(['throttle' => 'Too many failed login attempts, try again in '.$availableIn.' minutes']);
            }

            RateLimiter::hit($throttleKey, 600);
        });
    }
}
