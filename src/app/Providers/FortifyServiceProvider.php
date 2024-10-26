<?php

namespace App\Providers;

use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView(function () {
            return Inertia::render('Auth/Login', [
                'welcome-message' => config('app.welcome_message'),
                'home-links' => config('app.home_links'),
                'public-link' => false,
                'allow-oath' => config('services.azure.allow_login'),
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('Auth/ForgotPassword');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return Inertia::render('Auth/ResetPassword', [
                'email' => $request->get('email'),
                'token' => $request->route('token'),
                'rules' => [],
            ]);
        });

        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())).'|'.$request->ip()
            );

            // return Limit::perMinute(5)->by($throttleKey);
            if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
                $availableIn = ceil(RateLimiter::availableIn($throttleKey) / 60);

                event(new Lockout($request));

                return back()
                    ->withErrors([
                        'throttle' => 'Too many failed login attempts, try again in '.
                            $availableIn.' minutes',
                    ]);
            }

            RateLimiter::hit($throttleKey, 600);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
