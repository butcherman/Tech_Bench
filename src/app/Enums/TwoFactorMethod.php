<?php

namespace App\Enums;

enum TwoFactorMethod: string
{
    case Email = 'email';
    case Authenticator = 'authenticator';

    /**
     * Get a list of MFA Methods and their setup routes
     */
    public function getSetupRoute(): string
    {
        return match ($this) {
            self::Email => '#',
            self::Authenticator => route('two-factor.setup.authenticator'),
        };
    }

    /**
     * Get a list of MFA methods and their setup routes.
     */
    public static function getMethods(): array
    {
        return array_map(fn (self $method) => [
            'name' => $method->value,
            'setup-route' => $method->getSetupRoute(),
        ], array_values(
            array_filter(self::cases(),
                fn (self $method) => config("auth.twoFa.methods.{$method->value}",
                    false
                )
            )));
    }
}
