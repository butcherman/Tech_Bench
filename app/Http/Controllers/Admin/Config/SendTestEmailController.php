<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Notifications\SendTestEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mailer\Exception\TransportException;

class SendTestEmailController extends Controller
{
    /**
     * Send a test email using the currently saved config
     */
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        try {
            Notification::send(Auth::user(), new SendTestEmail);

            return back()->with('success', __('admin.test_email_success'));
        }
        // @codeCoverageIgnoreStart
        catch (TransportException $e) {
            return back()->withErrors(['failed' => __('admin.test_email_failure'), 'message' => $e->getMessage()]);
        }
        // @codeCoverageIgnoreEnd
    }
}
