<?php

// TODO - Refactor

namespace App\Http\Controllers\Admin\User;

use App\Events\User\ResendWelcomeEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user)
    {
        $this->authorize('update', $user);

        event(new ResendWelcomeEvent($user));
        Log::stack(['daily', 'auth'])->info('Resending Welcome Email to '.
            $user->full_name.'.  Triggered by '.$request->user()->username);

        return back()->with('success', __('admin.user.welcome_sent'));
    }
}
