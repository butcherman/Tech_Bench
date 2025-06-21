<?php

namespace App\Http\Controllers\Admin\Config;

use App\Actions\Admin\SendTestEmail;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;

class SendTestEmailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, SendTestEmail $svc)
    {
        $this->authorize('viewAny', AppSettings::class);

        [$success, $msg] = $svc($request->user());

        if (! $success) {
            return back()->withErrors(['email' => $msg]);
        }

        return back()->with('success', $msg);
    }
}
