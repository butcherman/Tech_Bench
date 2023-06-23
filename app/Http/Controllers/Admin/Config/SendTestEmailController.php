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

        try {
            Mail::to($request->user())->send(new TestEmail);

            return back()->with('success', __('admin.email.test'));
        } catch (TransportException $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }

    }
}
