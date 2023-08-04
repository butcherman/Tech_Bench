<?php

namespace App\Http\Controllers\Admin\User;

use App\Events\User\ResendWelcomeEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * When a new user is created, a welcome email is generated
 * This class will create a new initialization link and re-send the welcome email
 */
class SendWelcomeEmailController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $this->authorize('manage', $user);

        event(new ResendWelcomeEvent($user));
        Log::stack(['daily', 'user', 'auth'])->info('Resending Welcome email to '.$user->full_name.'.  Triggered by '.$request->user()->username);

        return back()->with('success', __('admin.user.welcome_sent'));
    }
}
