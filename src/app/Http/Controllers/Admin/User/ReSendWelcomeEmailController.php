<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Http\Request;

class ReSendWelcomeEmailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(User $user)
    {
        $this->authorize('update', $user);

        dispatch(new SendWelcomeEmailJob($user));

        return back()->with('success', __('admin.user.welcome_sent'));
    }
}
