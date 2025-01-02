<?php

namespace App\Providers;

use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Facades\CacheFacade;
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
            return Inertia::render('Auth/TechLogin', [
                'welcome-message' => config('app.welcome_message'),
                'home-links' => config('app.home_links'),
                'allow-oath' => config('services.azure.allow_login'),
                'public-link' => fn() => config('tech-tips.allow_public')
                    ? [
                        'url' => route('publicTips.index'),
                        'text' => config('tech-tips.public_link_text'),
                    ] : false,
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('Auth/ForgotPassword');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return Inertia::render('Auth/ResetPassword', [
                'email' => $request->get('email'),
                'token' => $request->route('token'),
                'rules' => CacheFacade::passwordRules(),
            ]);
        });

        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
            );

            // return Limit::perMinute(5)->by($throttleKey);
            if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
                $availableIn = ceil(RateLimiter::availableIn($throttleKey) / 60);

                event(new Lockout($request));

                return back()
                    ->withErrors([
                        'throttle' => 'Too many failed login attempts, try again in ' .
                            $availableIn . ' minutes',
                    ]);
            }

            RateLimiter::hit($throttleKey, 600);
        });
    }
}
