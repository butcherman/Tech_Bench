<?php

namespace App\Providers;

use App\Actions\Fortify\AuthenticateUser;
use App\Actions\Fortify\RedirectIfTwoFactorAuthenticatable;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;
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
        Fortify::authenticateUsing(
            fn (Request $request) => app(AuthenticateUser::class)
                ->authenticate($request)
        );
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(
            RedirectIfTwoFactorAuthenticatable::class
        );

        /*
        |-----------------------------------------------------------------------
        | Fortify Views
        |-----------------------------------------------------------------------
        */
        Fortify::loginView(fn () => app(LoginController::class)->__invoke());
        Fortify::requestPasswordResetLinkView(
            fn () => Inertia::render('Auth/ForgotPassword')
        );
        Fortify::resetPasswordView(
            fn (Request $request) => app(ResetPasswordController::class)
                ->__invoke($request)
        );
        Fortify::twoFactorChallengeView(
            fn (Request $request) => app(TwoFactorChallengeController::class)
                ->__invoke($request)
        );

        /*
        |-----------------------------------------------------------------------
        | Rate Limiters
        |-----------------------------------------------------------------------
        */
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower(
                $request->input(Fortify::username())
            ).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip()
            );
        });
    }
}
