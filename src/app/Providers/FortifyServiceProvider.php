<?php

namespace App\Providers;

use App\Actions\Fortify\LogoutResponse;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\TwoFactorLoginResponse;
use App\Actions\Fortify\UpdateUserPassword;
use App\Facades\CacheData;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Deliver a logout response when logging out.
        $this->app->instance(LogoutResponseContract::class, new LogoutResponse);

        // Custom Login Response
        $this->app->singleton(TwoFactorLoginResponseContract::class, TwoFactorLoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Allow login via either Email Address, or username
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->username)
                ->orWhere('username', $request->username)
                ->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        // Login Route
        Fortify::loginView(function () {
            return Inertia::render('Auth/TechLogin', [
                'welcome-message' => config('app.welcome_message'),
                'home-links' => config('app.home_links'),
                'allow-oath' => config('services.azure.allow_login'),
                'public-link' => fn () => config('tech-tips.allow_public')
                    ? [
                        'url' => route('publicTips.index'),
                        'text' => config('tech-tips.public_link_text'),
                    ] : false,
            ]);
        });

        // Reset Password Request Route
        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('Auth/ForgotPassword');
        });

        // Reset Password Route
        Fortify::resetPasswordView(function (Request $request) {
            return Inertia::render('Auth/ResetPassword', [
                'email' => $request->get('email'),
                'token' => $request->route('token'),
                'rules' => CacheData::passwordRules(),
            ]);
        });

        // Two Factor Authentication Route
        Fortify::twoFactorChallengeView(function ($request) {
            $user = User::find($request->session()->get('login.id'));

            $via = $this->getTwoFaViaParam($user);

            if ($via === 'email') {
                $user->generateVerificationCode();
            }

            return Inertia::render('Auth/TwoFactorAuth', [
                'allow-remember' => fn () => config('auth.twoFa.allow_save_device'),
                'via' => fn () => $via,
            ]);
        });

        // Update and Reset Routes
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())).'|'.$request->ip()
            );

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
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())).'|'.$request->ip()
            );

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
    }

    /**
     * Determine if the two factor auth code should be sent via email or
     * the authenticator app.
     */
    protected function getTwoFaViaParam(User $user): ?string
    {
        $app = config('auth.twoFa.allow_via_authenticator');
        $email = config('auth.twoFa.allow_via_email');

        if ($app && $email) {
            return $user->two_factor_via;
        }

        if ($app && ! $email) {
            return 'authenticator';
        }

        if ($email && ! $app) {
            return 'email';
        }

        // @codeCoverageIgnoreStart
        return null;
        // @codeCoverageIgnoreEnd
    }
}
