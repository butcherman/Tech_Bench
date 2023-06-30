<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Notifications\Admin\SendTestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mailer\Exception\TransportException;

class SendTestEmailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        try {
            Notification::send($request->user(), new SendTestEmail);

            return back()->with('success', __('admin.email.test'));
            // @codeCoverageIgnoreStart
        } catch (TransportException $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }
        // @codeCoverageIgnoreEnd

    }
}
