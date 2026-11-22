<?php

namespace App\Actions\Admin;

use Illuminate\Support\Facades\Mail;

class SendTestEmail
{
    public function send(array $settings, string $recipient): void
    {
        $mailer = Mail::build(
            $this->mailerConfig($settings)
        );

        $mailer->raw(
            'This is a test email from Tech Bench. '
            .'Your email settings are working.',
            function ($message) use ($recipient, $settings) {
                // @codeCoverageIgnoreStart
                $message
                    ->to($recipient)
                    ->from(
                        $settings['from_address'],
                        $settings['from_address'],
                    )
                    ->subject('Tech Bench - Test Email');
                // @codeCoverageIgnoreEnd
            }
        );
    }

    protected function mailerConfig(array $settings): array
    {
        return [
            'transport' => 'smtp',
            'host' => $settings['host'],
            'port' => (int) $settings['port'],
            'encryption' => $settings['encryption'] ?: null,
            'username' => $settings['username'] ?: null,
            'password' => $settings['password'] ?: null,
            'timeout' => 10,
            'local_domain' => parse_url(
                config('app.url'),
                PHP_URL_HOST
            ),
        ];
    }
}
