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
        Fortify::createUsersUsing(CreateNewUser::class);
        // Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        //  Deliver a logout message to the user
        $this->app->instance(LogoutResponseContract::class, new LogoutResponse);

        RateLimiter::for('login', function (Request $request) {
            $username = (string) $request->username;
            $throttleKey = $username.'|'.$request->ip();

            if (RateLimiter::tooManyAttempts($throttleKey, 10)) {

                //  TODO - Proper Error Page
                //  TODO - Fix Rate Limiter
                event(new Lockout($request));
                abort(429, 'Too Many Attempts.  Locked out for '.RateLimiter::availableIn($throttleKey));
            }

            RateLimiter::hit($throttleKey, 600);

        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
