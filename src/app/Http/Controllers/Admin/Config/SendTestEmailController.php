<?php

// TODO - Refactor

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Notifications\Admin\SendTestEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mailer\Exception\TransportException;

class SendTestEmailController extends Controller
{
    /**
     * Send a test email to the currently logged in user
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $this->authorize('viewAny', AppSettings::class);

        // TODO - Move logic to its own class
        try {
            Notification::send($request->user(), new SendTestEmail);

            Log::info('Sending test email to '.$request->user()->email);

            return back()->with('success', __('admin.email.test'));
            // @codeCoverageIgnoreStart
        } catch (TransportException $e) {
            Log::error('Test email failed - '.$e->getMessage());

            return back()->withErrors(['email' => $e->getMessage()]);
        }
        // @codeCoverageIgnoreEnd
    }
}
