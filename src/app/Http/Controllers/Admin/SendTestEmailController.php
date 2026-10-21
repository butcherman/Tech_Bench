<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\SendTestEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailSettingsRequest;

class SendTestEmailController extends Controller
{
    public function __construct(protected SendTestEmail $svc) {}

    /**
     * Send a test email
     */
    public function __invoke(EmailSettingsRequest $request)
    {
        try {
            $this->svc->send(
                $request->safe()->toArray(),
                $request->input('recipient')
            );

            return back()->with(
                'success',
                'Test email accepted by the mail server.'
            );
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'mail_settings' => 'Unable to send the test email. '
                    .'Check your mail settings and try again.',
            ]);
        }
    }
}
