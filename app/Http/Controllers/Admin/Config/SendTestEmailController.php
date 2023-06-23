<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Mail\TestEmail;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class SendTestEmailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        // try {
        //     Notification::send(Auth::user(), new SendTestEmail);

        //     return back()->with('success', __('admin.test_email_success'));
        // }
        // @codeCoverageIgnoreStart
        // catch (TransportException $e) {
        //     return back()->withErrors(['failed' => __('admin.test_email_failure'), 'message' => $e->getMessage()]);
        // }
        // @codeCoverageIgnoreEnd


        try {
            Mail::to($request->user())->send(new TestEmail);
        } catch (TransportException $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }

    }
}
